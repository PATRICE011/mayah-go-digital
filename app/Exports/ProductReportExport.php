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

class ProductReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents
{
    private $index = 1; // Start numbering from 1

    public function query()
    {
        return DB::table('products')
            ->select(
                'product_name',
                'product_stocks_sold',
                'product_raw_price',
                'product_price',
                DB::raw('product_stocks_sold * product_price as total_amount_sold')
            )
            ->where('product_stocks_sold', '>', 0) // Only include sold products
            ->orderBy('product_name');
    }

    public function headings(): array
    {
        return ['#', 'Product Name', 'Total Stocks Sold', 'Raw Price', 'Unit Price', 'Total Amount Sold'];
    }

    public function map($row): array
    {
        return [
            $this->index++, // Numbering
            $row->product_name,
            $row->product_stocks_sold,
            number_format((float) $row->product_raw_price, 2),
            number_format((float) $row->product_price, 2),
            number_format((float) $row->total_amount_sold, 2)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF0000']
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

                // Set headers explicitly in Row 1
                $headings = ['#', 'Product Name', 'Total Stocks Sold', 'Raw Price', 'Unit Price', 'Total Amount Sold'];
                $columnIndex = 'A';

                foreach ($headings as $heading) {
                    $cell = $columnIndex . '1';
                    $sheet->setCellValue($cell, $heading);

                    // Apply header styles
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF'], 'size' => 12],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText' => true
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['argb' => 'FF0000']
                        ]
                    ]);

                    $columnIndex++;
                }

                // Ensure row height for headers
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Get the row count dynamically
                $rowCount = DB::table('products')->where('product_stocks_sold', '>', 0)->count() + 1;

                // Apply border styles to the entire table
                $sheet->getStyle("A1:F{$rowCount}")->applyFromArray([
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
                    'C' => 18,  // Total Stocks Sold
                    'D' => 15,  // Raw Price
                    'E' => 15,  // Unit Price
                    'F' => 20   // Total Amount Sold
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Apply alternate row colors for readability
                for ($i = 2; $i <= $rowCount; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:F{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'F2F2F2']
                            ]
                        ]);
                    }
                }

                // Format currency fields
                $sheet->getStyle("D2:F{$rowCount}")->getNumberFormat()->setFormatCode('#,##0.00');
            },
        ];
    }
}
