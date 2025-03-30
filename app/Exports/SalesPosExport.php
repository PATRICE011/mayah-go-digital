<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesPosExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithEvents
{
    use Exportable;

    private $fromDate;
    private $toDate;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    // Query for fetching data
    public function query()
    {
        $query = DB::table('pos_order_items')
            ->join('products', 'pos_order_items.product_id', '=', 'products.id')
            ->join('pos_orders', 'pos_order_items.pos_order_id', '=', 'pos_orders.id')
            ->select('pos_order_items.id', 'products.product_name', 'pos_order_items.quantity', 'pos_order_items.price', 'pos_order_items.total', 'pos_order_items.created_at', 'pos_orders.order_number', DB::raw('IFNULL(pos_orders.user_id, "Guest") as customer'))
            ->orderBy('pos_order_items.created_at', 'desc'); // Fetch latest purchases first

        // Apply date filter if provided
        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('pos_order_items.created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
        }

        return $query;
    }

    // Define the headings of the export
    public function headings(): array
    {
        return ['#', 'Product Name', 'Quantities Sold', 'Unit Price', 'Total Amount', 'Date', 'Customer'];
    }

    // Map data for each row
    public function map($item): array
    {
        static $index = 1; // Ensure data numbering starts at 1
    
        return [
            $index++,
            $item->product_name ?? 'Unknown', // Product name
            $item->quantity, // Quantity sold
            number_format((float) $item->price, 2), // Unit price
            number_format((float) $item->total, 2), // Total amount
            Carbon::parse($item->created_at)->format('Y-m-d H:i'), // Format created_at date
            $item->customer ?? 'Guest', // Customer, default 'Guest' for POS
        ];
    }

    // Apply styles to the export sheet
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

    // Register custom events (headers, row styling, etc.)
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Explicitly set headers in Row 1
                $headings = ['#', 'Product Name', 'Quantities Sold', 'Unit Price', 'Total Amount', 'Date', 'Customer'];
                $headingRow = 1;
                $columnIndex = 'A';

                foreach ($headings as $heading) {
                    $cell = $columnIndex . $headingRow;
                    $sheet->setCellValue($cell, $heading);

                    // Apply styling for headers
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
                $rowCount = DB::table('pos_order_items')->count() + 1; // Adjust row count for correct data placement

                $sheet->getStyle("A1:G{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                ]);

                // Manually set column widths for better readability
                $columnWidths = [
                    'A' => 8,   // #
                    'B' => 25,  // Product Name
                    'C' => 20,  // Quantity
                    'D' => 12,  // Unit Price
                    'E' => 15,  // Total Amount
                    'F' => 18,  // Date
                    'G' => 20   // Customer
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Apply alternate row colors for readability
                for ($i = 2; $i <= $rowCount; $i++) {
                    if ($i % 2 == 0) {
                        $sheet->getStyle("A{$i}:G{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'F2F2F2']
                            ]
                        ]);
                    }
                }

                // Format currency fields
                $sheet->getStyle("D2:E{$rowCount}")->getNumberFormat()->setFormatCode('#,##0.00');
            },
        ];
    }
}
