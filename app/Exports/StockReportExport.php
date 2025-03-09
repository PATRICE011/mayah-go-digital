<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class StockReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents
{
    private $index = 1; // Start numbering from 1

    public function query()
    {
        return DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->select(
                'products.product_name',
                'stock_movements.type',
                'stock_movements.quantity',
                'stock_movements.created_at'
            )
            ->orderBy('stock_movements.created_at', 'desc'); // Fetch latest first
    }

    public function headings(): array
    {
        return ['#', 'Product Name', 'Movement Type', 'Quantity', 'Date'];
    }

    public function map($row): array
    {
        return [
            $this->index++, // Numbering
            $row->product_name,
            ucfirst($row->type), // Capitalize 'in' or 'out'
            $row->quantity,
            date('Y-m-d H:i:s', strtotime($row->created_at))
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => '0000FF']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Get the row count dynamically
                $rowCount = DB::table('stock_movements')->count() + 1;

                // Apply border styles to the entire table
                $sheet->getStyle("A1:E{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                ]);

                // Set column widths for better readability
                $columnWidths = [
                    'A' => 8,   // #
                    'B' => 25,  // Product Name
                    'C' => 15,  // Movement Type
                    'D' => 12,  // Quantity
                    'E' => 20   // Date
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Apply alternate row colors for readability
                for ($i = 2; $i <= $rowCount; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:E{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'F2F2F2']
                            ]
                        ]);
                    }
                }
            },
        ];
    }
}
