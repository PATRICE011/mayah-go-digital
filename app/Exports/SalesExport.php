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
                DB::raw('order_items.quantity * products.product_raw_price as total_raw_cost'),
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
            ->orderBy('orders.created_at', 'desc') // SORT BY LATEST PURCHASE
            ->get();

        // Compute Totals
        $totalRevenue = $salesData->sum('total_amount');
        $totalRawCost = $salesData->sum('total_raw_cost');
        $grossIncome = $totalRevenue - $totalRawCost;

        // Format Data for UI
        $formattedData = collect($salesData)->map(function ($item, $index) {
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

        // Append Total Rows
        $formattedData->push([
            'row_number' => 'TOTAL REVENUE',
            'product_name' => '',
            'quantity' => '',
            'unit_price' => '',
            'raw_price' => '',
            'total_amount' => number_format($totalRevenue, 2),
            'date' => '',
            'customer_name' => '',
        ]);

        $formattedData->push([
            'row_number' => 'TOTAL RAW COST',
            'product_name' => '',
            'quantity' => '',
            'unit_price' => '',
            'raw_price' => '',
            'total_amount' => number_format($totalRawCost, 2),
            'date' => '',
            'customer_name' => '',
        ]);

        $formattedData->push([
            'row_number' => 'GROSS INCOME',
            'product_name' => '',
            'quantity' => '',
            'unit_price' => '',
            'raw_price' => '',
            'total_amount' => number_format($grossIncome, 2),
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
                $headings = ['#', 'Product Name', 'Quantity', 'Unit Price', 'Raw Price', 'Total Amount', 'Date', 'Customer'];
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
                $rowCount = DB::table('order_items')->count() + 4; // Account for total rows

                $sheet->getStyle("A1:H{$rowCount}")->applyFromArray([
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
                    'B' => 25,  // Product Name
                    'C' => 10,  // Quantity
                    'D' => 12,  // Unit Price
                    'E' => 12,  // Raw Price
                    'F' => 15,  // Total Amount
                    'G' => 18,  // Date
                    'H' => 20   // Customer
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }
            },
        ];
    }
}
