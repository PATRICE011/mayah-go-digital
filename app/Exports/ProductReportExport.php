<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class ProductReportExport implements FromQuery, WithHeadings, WithStyles, WithEvents
{
    protected $search;

    public function __construct($search = '')
    {
        $this->search = $search;
    }

    public function query()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.product_name',
                'order_items.quantity',
                'order_items.price',
                DB::raw('order_items.quantity * order_items.price as total_amount')
            )
            ->when($this->search, function ($query) {
                return $query->where('products.product_name', 'LIKE', "%{$this->search}%");
            })
            ->orderBy('products.product_name'); // Adding an order by clause
    }

    public function headings(): array
    {
        return ['Product Name', 'Quantity', 'Unit Price', 'Total Amount'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFFF00']]]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge cells for the title and set the title
                $event->sheet->mergeCells('A1:D1');
                $event->sheet->setCellValue('A1', 'PRODUCT REPORT');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFF00']  // Color for title background
                    ]
                ]);

                // Set the width for each column
                $event->sheet->getColumnDimension('A')->setWidth(20);  // Adjust the width as needed
                $event->sheet->getColumnDimension('B')->setWidth(15);
                $event->sheet->getColumnDimension('C')->setWidth(15);
                $event->sheet->getColumnDimension('D')->setWidth(20);

                // Apply styles for headings in row 2
                $event->sheet->getStyle('A2:D2')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FF0000']  // Red color for the headings
                    ]
                ]);

                // Ensure that heading texts are set explicitly if they are missing
                $headings = ['Product Name', 'Quantity', 'Unit Price', 'Total Amount'];
                foreach ($headings as $key => $heading) {
                    $cell = chr(65 + $key) . '2';  // 'A2', 'B2', 'C2', 'D2'
                    $event->sheet->setCellValue($cell, $heading);
                }
            },
        ];
    }
}
