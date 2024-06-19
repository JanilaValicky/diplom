<?php

namespace App\Exports;

use App\Http\Resources\Form\FormCollection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormsCsvExport implements FromCollection, WithHeadings
{
    private FormCollection $data;

    public function __construct(FormCollection $data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'id' => __('forms.id'),
            'name' => __('forms.name'),
            'slug' => __('forms.slug'),
            'start_date' => __('forms.start_date'),
            'end_date' => __('forms.end_date'),
            'status' => __('forms.status'),
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return $this->mapData($item);
        });
    }

    private function mapData($item)
    {
        return [
            'id' => $item->resource->id,
            'name' => $item->resource->name,
            'slug' => $item->resource->slug,
            'start_date' => $item->resource->start_date,
            'end_date' => $item->resource->end_date,
            'status' => $item->resource->status->value,
        ];
    }
}