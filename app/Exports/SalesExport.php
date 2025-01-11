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
        $query = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('users_area', 'orders.user_id', '=', 'users_area.id')
            ->select(
                'products.product_name',
                'order_items.quantity',
                'order_items.price as unit_price',
                DB::raw('order_items.quantity * order_items.price as total_amount'),
                'orders.created_at as date',
                'users_area.name as customer_name'
            );

        if (!empty($this->search)) {
            $query->where(function ($subQuery) {
                $subQuery->where('products.product_name', 'LIKE', "%{$this->search}%")
                    ->orWhere('users_area.name', 'LIKE', "%{$this->search}%");
            });
        }

        if (!empty($this->fromDate) && !empty($this->toDate)) {
            $query->whereBetween('orders.created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
        } elseif (!empty($this->fromDate)) {
            $query->where('orders.created_at', '>=', $this->fromDate . ' 00:00:00');
        } elseif (!empty($this->toDate)) {
            $query->where('orders.created_at', '<=', $this->toDate . ' 23:59:59');
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Product Name', 'Quantity', 'Unit Price', 'Total Amount', 'Date', 'Customer'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFFF00'] // Yellow color for the title
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            2 => [
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF0000'] // Red color for the headings
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:F1');
                $event->sheet->setCellValue('A1', 'SALES REPORT');

                // Set headings explicitly
                $headings = ['Product Name', 'Quantity', 'Unit Price', 'Total Amount', 'Date', 'Customer'];
                $headingRow = 2; // Row where headings should start
                foreach ($headings as $key => $value) {
                    $cell = chr(65 + $key) . $headingRow; // 'A2', 'B2', etc.
                    $event->sheet->setCellValue($cell, $value);
                }

                $event->sheet->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getRowDimension('2')->setRowHeight(15);
            },
        ];
    }
}
