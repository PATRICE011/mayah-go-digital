<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return User::where('role_id', 3) // Ensure this is the correct role ID for customers
                   ->select('name', 'mobile') // Only fetch name and mobile
                   ->get();
    }

    public function headings(): array
    {
        return ['Name', 'Phone Number']; // Updated headings to reflect actual data being fetched
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFFF00']]],
            2 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF0000']]]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->mergeCells('A1:B1');
                $event->sheet->setCellValue('A1', 'CUSTOMER REPORT');
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Set column widths to ensure content fits well
                $event->sheet->getColumnDimension('A')->setWidth(20);
                $event->sheet->getColumnDimension('B')->setWidth(30);

                // Ensure headings are also explicitly set if missing
                $headings = ['Name', 'Phone Number']; // Corrected headings
                foreach ($headings as $index => $heading) {
                    $cell = chr(65 + $index) . '2';  // 'A2', 'B2'
                    $event->sheet->setCellValue($cell, $heading);
                }
            },
        ];
    }
}
