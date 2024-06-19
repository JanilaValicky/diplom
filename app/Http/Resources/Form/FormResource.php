<?php

namespace App\Http\Resources\Form;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->user_id,
            'updated_by' => $this->updated_by,
            'slug' => $this->slug,
            'status' => $this->status,
            'start_date' => $this->start_date->format(Form::HUMAN_DATE_FORMAT),
            'end_date' => $this->end_date->format(Form::HUMAN_DATE_FORMAT),
            'created_at' => $this->created_at->format(Form::HUMAN_DATE_FORMAT),
            'updated_at' => $this->updated_at->format(Form::HUMAN_DATE_FORMAT),
        ];
    }
}
