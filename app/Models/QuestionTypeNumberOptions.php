<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionTypeNumberOptions extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'form_question_id',
        'required',
        'html_id',
        'value',
        'label',
        'min',
        'max',
        'placeholder',
        'readonly',
        'step',
    ];

    protected $casts = [
        'required' => 'boolean',
        'readonly' => 'boolean',
    ];

    public function formQuestion(): BelongsTo
    {
        return $this->belongsTo(FormQuestion::class);
    }
}
