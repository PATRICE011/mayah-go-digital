<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class StockInReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $fromDate;
    protected $toDate;
    protected $search;
    protected $exportData;

    public function __construct($fromDate = null, $toDate = null, $search = '')
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->search = $search;
    }

    public function collection()
    {
        $stockData = DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.product_id',
                'products.product_name',
                'categories.category_name',
                DB::raw('SUM(CASE WHEN stock_movements.type = "in" THEN stock_movements.quantity ELSE 0 END) as in_quantity'),
                'products.product_raw_price',
                'stock_movements.created_at as stock_in_date'
            )
            ->where('stock_movements.type', 'in')
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
            ->havingRaw('SUM(CASE WHEN stock_movements.type = "in" THEN stock_movements.quantity ELSE 0 END) > 0')
            ->orderBy('stock_movements.created_at', 'desc')
            ->get();

        // Format and store for rendering
        $this->exportData = collect($stockData)->map(function ($item, $index) {
            return [
                'row_number' => $index + 1,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'category_name' => $item->category_name,
                'in_quantity' => $item->in_quantity,
                'unit_price' => number_format($item->product_raw_price, 2),
                'stock_in_date' => date('Y-m-d H:i', strtotime($item->stock_in_date)),
            ];
        });

        return $this->exportData;
    }

    public function headings(): array
    {
        return []; // We will write manually in AfterSheet
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $data = $this->exportData ?? collect();
                $rowIndex = 1;

                // Export date in row 1
                $sheet->setCellValue('A1', 'Stock in: ' . Carbon::now()->format('Y-m-d H:i:s'));
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'CCCCCC']]
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Header row
                $headers = ['#', 'Product ID', 'Product Name', 'Category', 'In Quantity', 'Unit Price', 'Stock In Date'];
                $col = 'A';
                foreach ($headers as $heading) {
                    $cell = $col . '2';
                    $sheet->setCellValue($cell, $heading);
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                        'alignment' => ['horizontal' => 'center'],
                        'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF0000']],
                    ]);
                    $col++;
                }

                // Data rows start from row 3
                $rowIndex = 3;
                foreach ($data as $row) {
                    $sheet->setCellValue("A{$rowIndex}", $row['row_number']);
                    $sheet->setCellValue("B{$rowIndex}", $row['product_id']);
                    $sheet->setCellValue("C{$rowIndex}", $row['product_name']);
                    $sheet->setCellValue("D{$rowIndex}", $row['category_name']);
                    $sheet->setCellValue("E{$rowIndex}", $row['in_quantity']);
                    $sheet->setCellValue("F{$rowIndex}", $row['unit_price']);
                    $sheet->setCellValue("G{$rowIndex}", $row['stock_in_date']);

                    $sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->applyFromArray([
                        'alignment' => ['vertical' => 'center'],
                    ]);

                    $rowIndex++;
                }

                // Apply borders
                $lastRow = $rowIndex - 1;
                $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                ]);

                // Column widths
                $columnWidths = [
                    'A' => 8,
                    'B' => 15,
                    'C' => 25,
                    'D' => 20,
                    'E' => 15,
                    'F' => 15,
                    'G' => 20,
                ];

                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // Format unit price column
                $sheet->getStyle("F3:F{$lastRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            },
        ];
    }
}
