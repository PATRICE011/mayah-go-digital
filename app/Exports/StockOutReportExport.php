<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class StockOutReportExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $fromDate;
    protected $toDate;
    protected $search;

    public function __construct($fromDate = null, $toDate = null, $search = '')
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->search = $search;
    }

    public function collection()
    {
        // Query to fetch the data based on filters
        $stockData = DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.product_id',
                'products.product_name',
                'categories.category_name',
                DB::raw('SUM(CASE WHEN stock_movements.type = "out" THEN stock_movements.quantity ELSE 0 END) as out_quantity'),
                'products.product_raw_price',
                'stock_movements.created_at as stock_out_date'
            )
            ->where('stock_movements.type', 'out') // Only "stock-out" movements
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('products.product_id', 'LIKE', "%{$this->search}%")
                        ->orWhere('products.product_name', 'LIKE', "%{$this->search}%")
                        ->orWhere('categories.category_name', 'LIKE', "%{$this->search}%");
                });
            })
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereBetween('stock_movements.created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
            })
            ->when($this->fromDate && !$this->toDate, function ($query) {
                $query->where('stock_movements.created_at', '>=', $this->fromDate . ' 00:00:00');
            })
            ->when($this->toDate && !$this->fromDate, function ($query) {
                $query->where('stock_movements.created_at', '<=', $this->toDate . ' 23:59:59');
            })
            ->groupBy('products.product_id', 'products.product_name', 'categories.category_name', 'products.product_raw_price', 'stock_movements.created_at')
            ->orderBy('stock_movements.created_at', 'desc') // Sort by latest stock-out date
            ->get();

        // Format data for export
        $formattedData = collect($stockData)->map(function ($item, $index) {
            return [
                'row_number' => $index + 1,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'category_name' => $item->category_name,
                'out_quantity' => $item->out_quantity,
                'unit_price' => number_format($item->product_raw_price, 2),
                'stock_out_date' => date('Y-m-d H:i', strtotime($item->stock_out_date)),
            ];
        });

        return $formattedData;
    }

    public function headings(): array
    {
        return ['#', 'Product ID', 'Product Name', 'Category', 'Out Quantity', 'Unit Price', 'Stock Out Date'];
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

                // Set column headers in Row 1
                $headings = ['#', 'Product ID', 'Product Name', 'Category', 'Out Quantity', 'Unit Price', 'Stock Out Date'];
                $columnIndex = 'A';

                foreach ($headings as $heading) {
                    $cell = $columnIndex . '1';
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

                // Set row height for headers
                $sheet->getRowDimension(1)->setRowHeight(25);

                // Ensure Data Starts at Row 2
                $rowCount = count($sheet->toArray(null, true, true)) + 1;

                // Apply borders to the data
                $sheet->getStyle("A1:G{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                ]);

                // Adjust column widths
                $columnWidths = [
                    'A' => 8,   // #
                    'B' => 15,  // Product ID
                    'C' => 25,  // Product Name
                    'D' => 20,  // Category
                    'E' => 20,  // Out Quantity
                    'F' => 15,  // Unit Price
                    'G' => 18   // Stock Out Date
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
