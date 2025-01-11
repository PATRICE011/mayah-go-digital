<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoriesExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Category::select('category_name')
                        ->get();
    }

    public function headings(): array
    {
        return ['Category Name'];
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
                $event->sheet->mergeCells('A1:A1');
                $event->sheet->setCellValue('A1', 'CATEGORY LIST');
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getColumnDimension('A')->setWidth(25);

                // Explicitly set heading for the data column
                $event->sheet->setCellValue('A2', 'Category Name'); // Make sure the heading is exactly as defined in headings()
            },
        ];
    }
}
