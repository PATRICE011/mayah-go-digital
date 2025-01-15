<?php

namespace App\Exports;

use App\Models\PosOrderItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    private $fromDate;
    private $toDate;

    // Constructor to handle date filters
    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    // Query data to be exported
    public function query()
    {
        $query = PosOrderItem::with(['product', 'order.user'])->latest();

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        }

        return $query;
    }

    // Define the headers
    public function headings(): array
    {
        return [
            '#',
            'Product Name',
            'Quantity',
            'Unit Price',
            'Amount',
            'Date',
            'Customer',
        ];
    }

    // Define how each row should be mapped
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
}

