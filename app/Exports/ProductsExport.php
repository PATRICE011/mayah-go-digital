<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        // Eager load the category relationship
        $products = Product::with('category')->limit(200)->get();

        // Map the products to include the category name
        $products = $products->map(function ($product) {
            return [
                'product_name' => $product->product_name,
                'product_description' => $product->product_description,
                'product_price' => $product->product_price,
                'product_stocks' => $product->product_stocks,
                'category_name' => $product->category ? $product->category->category_name : 'No Category',
            ];
        });

        return $products;
    }

    public function headings(): array
    {
        return ['Name', 'Description', 'Selling Price', 'Stocks', 'Category'];
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
            AfterSheet::class => function(AfterSheet $event) {
                // Merge cells for the title and set the title
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->setCellValue('A1', 'LIST OF PRODUCTS');

                // Set headings explicitly
                $headings = ['Name', 'Description', 'Price', 'Stocks', 'Category'];
                $headingRow = 2; // Row where headings should start
                foreach ($headings as $key => $value) {
                    $cell = chr(65 + $key) . $headingRow; // 'A2', 'B2', etc.
                    $event->sheet->setCellValue($cell, $value);
                }

                // Optionally, set height for the title and heading rows
                $event->sheet->getRowDimension('1')->setRowHeight(20);
                $event->sheet->getRowDimension('2')->setRowHeight(15);
            },
        ];
    }
}
