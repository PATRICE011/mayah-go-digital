<?php

namespace App\Exports;

use App\Models\PosOrderItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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

    public function query()
    {
        $query = PosOrderItem::with(['product', 'order.user'])->latest();

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        }

        return $query;
    }

    public function headings(): array
    {
        return ['#', 'Product Name', 'Quantity', 'Unit Price', 'Total Amount', 'Date', 'Customer'];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->product->product_name ?? 'Unknown',
            $item->quantity,
            number_format($item->price, 2),
            number_format($item->total, 2),
            $item->created_at->format('Y-m-d H:i'),
            $item->order->user->name ?? 'Guest',
        ];
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
                    'color' => ['argb' => 'FFFF00']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            2 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF']
                ],
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
            AfterSheet::class => function(AfterSheet $event) {
                // Merge title cells for "SALES REPORT"
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->setCellValue('A1', 'SALES REPORT');

                // Style header title
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'color' => ['argb' => '000000']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFF00']
                    ]
                ]);

                // Set row height for title
                $event->sheet->getRowDimension(1)->setRowHeight(30);

                // Set column headers
                $headings = ['#', 'Product Name', 'Quantity', 'Unit Price', 'Total Amount', 'Date', 'Customer'];
                $headingRow = 2;
                $columnIndex = 'A';

                foreach ($headings as $heading) {
                    $cell = $columnIndex . $headingRow;
                    $event->sheet->setCellValue($cell, $heading);

                    // Apply styling for headers
                    $event->sheet->getStyle($cell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['argb' => 'FFFFFF'],
                            'size' => 12
                        ],
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

                // Set row height for header row
                $event->sheet->getRowDimension(2)->setRowHeight(30);

                // Set column widths for better readability
                $columnWidths = [
                    'A' => 8,   // ID
                    'B' => 25,  // Product Name
                    'C' => 10,  // Quantity
                    'D' => 12,  // Unit Price
                    'E' => 15,  // Total Amount
                    'F' => 18,  // Date
                    'G' => 20   // Customer
                ];

                foreach ($columnWidths as $col => $width) {
                    $event->sheet->getColumnDimension($col)->setWidth($width);
                }

                // Apply borders to all data rows
                $rowCount = PosOrderItem::count() + 2;
                $event->sheet->getStyle("A1:G{$rowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ],
                    ],
                ]);

                // Alternate row colors for better readability
                for ($i = 3; $i <= $rowCount; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle("A{$i}:G{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'F2F2F2']
                            ]
                        ]);
                    }
                }

                // Format currency fields
                $event->sheet->getStyle("D3:E{$rowCount}")->getNumberFormat()->setFormatCode('#,##0.00');
            },
        ];
    }
}
