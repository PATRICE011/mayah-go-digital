<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SalesPosExport implements FromQuery, WithHeadings, WithEvents
{
    use Exportable;

    private $fromDate;
    private $toDate;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function query()
    {
        return DB::table('pos_order_items')
            ->join('products', 'pos_order_items.product_id', '=', 'products.id')
            ->join('pos_orders', 'pos_order_items.pos_order_id', '=', 'pos_orders.id')
            ->select(
                'products.product_name',
                'pos_order_items.quantity',
                'pos_order_items.price',
                'pos_order_items.total',
                'pos_order_items.created_at',
                DB::raw('IFNULL(pos_orders.user_id, "Guest") as customer')
            )
            ->when($this->fromDate && $this->toDate, function ($query) {
                $query->whereBetween('pos_order_items.created_at', [
                    $this->fromDate . ' 00:00:00',
                    $this->toDate . ' 23:59:59'
                ]);
            })
            ->orderBy('pos_order_items.created_at', 'desc');
    }

    public function headings(): array
    {
        return []; // manually handled in AfterSheet
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Export date in A1
                $sheet->setCellValue('A1', 'POS Sales Report: ' . Carbon::now()->format('Y-m-d H:i:s'));
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'CCCCCC']],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Set headers in row 2
                $headers = ['#', 'Product Name', 'Quantities Sold', 'Unit Price', 'Total Amount', 'Date', 'Customer'];
                $column = 'A';
                foreach ($headers as $header) {
                    $cell = $column . '2';
                    $sheet->setCellValue($cell, $header);
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                        'alignment' => ['horizontal' => 'center'],
                        'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF0000']],
                    ]);
                    $column++;
                }

                $sheet->getRowDimension(2)->setRowHeight(25);

                // Fetch data
                $data = $this->query()->get();
                $rowIndex = 3;
                $totalAmount = 0;

                foreach ($data as $index => $item) {
                    $sheet->setCellValue('A' . $rowIndex, $index + 1);
                    $sheet->setCellValue('B' . $rowIndex, $item->product_name);
                    $sheet->setCellValue('C' . $rowIndex, $item->quantity);
                    $sheet->setCellValue('D' . $rowIndex, number_format($item->price, 2));
                    $sheet->setCellValue('E' . $rowIndex, number_format($item->total, 2));
                    $sheet->setCellValue('F' . $rowIndex, Carbon::parse($item->created_at)->format('Y-m-d H:i'));
                    $sheet->setCellValue('G' . $rowIndex, $item->customer ?? 'Guest');

                    $totalAmount += $item->total;

                    $sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->applyFromArray([
                        'alignment' => ['vertical' => 'center'],
                    ]);

                    $rowIndex++;
                }

                // Summary row
                $sheet->setCellValue("A{$rowIndex}", 'TOTAL');
                $sheet->mergeCells("A{$rowIndex}:D{$rowIndex}");
                $sheet->setCellValue("E{$rowIndex}", number_format($totalAmount, 2));

                $sheet->getStyle("A{$rowIndex}:G{$rowIndex}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFF2CC']],
                    'alignment' => ['vertical' => 'center'],
                ]);

                // Column widths
                $widths = ['A' => 8, 'B' => 25, 'C' => 18, 'D' => 12, 'E' => 15, 'F' => 20, 'G' => 20];
                foreach ($widths as $col => $w) {
                    $sheet->getColumnDimension($col)->setWidth($w);
                }

                // Borders
                $finalRow = $rowIndex;
                $sheet->getStyle("A1:G{$finalRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Format currency columns
                $sheet->getStyle("D3:E{$finalRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            },
        ];
    }
}
