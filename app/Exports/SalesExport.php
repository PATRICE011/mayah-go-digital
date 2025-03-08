<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class SalesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
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
        $salesData = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('users_area', 'orders.user_id', '=', 'users_area.id')
            ->select(
                'products.product_name',
                'order_items.quantity',
                'order_items.price as unit_price',
                'products.product_raw_price as raw_price',
                DB::raw('order_items.quantity * order_items.price as total_amount'),
                DB::raw('order_items.quantity * products.product_raw_price as total_raw_cost'), // NEW: Total Raw Cost
                'orders.created_at as date',
                'users_area.name as customer_name'
            )
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('products.product_name', 'LIKE', "%{$this->search}%")
                        ->orWhere('users_area.name', 'LIKE', "%{$this->search}%");
                });
            })
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereBetween('orders.created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
            })
            ->when($this->fromDate && !$this->toDate, function ($query) {
                $query->where('orders.created_at', '>=', $this->fromDate . ' 00:00:00');
            })
            ->when($this->toDate && !$this->fromDate, function ($query) {
                $query->where('orders.created_at', '<=', $this->toDate . ' 23:59:59');
            })
            ->get();

        // Reset index and calculate Gross Income
        $salesData = array_values($salesData->toArray());
        $totalRevenue = 0;
        $totalRawCost = 0;

        // Add numbering and format data
        $formattedData = collect($salesData)->map(function ($item, $index) use (&$totalRevenue, &$totalRawCost) {
            $totalRevenue += $item->total_amount;
            $totalRawCost += $item->total_raw_cost; // NEW: Sum up total raw cost

            return [
                'row_number' => $index + 1,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => number_format($item->unit_price, 2),
                'raw_price' => number_format($item->raw_price, 2),
                'total_amount' => number_format($item->total_amount, 2),
                'date' => date('Y-m-d H:i', strtotime($item->date)),
                'customer_name' => $item->customer_name ?? 'Guest',
            ];
        });

        // Calculate Gross Income
        $grossIncome = $totalRevenue - $totalRawCost;

        // Add breakdown rows
        $formattedData->push([
            'row_number' => '',
            'product_name' => '',
            'quantity' => '',
            'unit_price' => '',
            'raw_price' => '',
            'total_amount' => 'TOTAL REVENUE: ' . number_format($totalRevenue, 2),
            'date' => '',
            'customer_name' => '',
        ]);

        $formattedData->push([
            'row_number' => '',
            'product_name' => '',
            'quantity' => '',
            'unit_price' => '',
            'raw_price' => '',
            'total_amount' => 'TOTAL RAW COST: ' . number_format($totalRawCost, 2),
            'date' => '',
            'customer_name' => '',
        ]);

        $formattedData->push([
            'row_number' => '',
            'product_name' => '',
            'quantity' => '',
            'unit_price' => '',
            'raw_price' => '',
            'total_amount' => 'GROSS INCOME: ' . number_format($grossIncome, 2), // NEW: Gross Income
            'date' => '',
            'customer_name' => '',
        ]);

        return $formattedData;
    }


    public function headings(): array
    {
        return ['#', 'Product Name', 'Quantity', 'Unit Price', 'Raw Price', 'Total Amount', 'Date', 'Customer'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFFF00']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            2 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF0000']
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge title across all columns (A to H)
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'SALES REPORT');

                // Style title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 20, 'color' => ['argb' => '000000']],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFF00']
                    ]
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Set column headers manually
                $headings = ['#', 'Product Name', 'Quantity', 'Unit Price', 'Raw Price', 'Total Amount', 'Date', 'Customer'];
                $headingRow = 2;
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

                // Adjust column widths
                $columnWidths = [
                    'A' => 8,  // #
                    'B' => 25, // Product Name
                    'C' => 10, // Quantity
                    'D' => 12, // Unit Price
                    'E' => 12, // Raw Price
                    'F' => 15, // Total Amount
                    'G' => 18, // Date
                    'H' => 20  // Customer
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
