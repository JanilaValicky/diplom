<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionTypeCheckboxOptions extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'form_question_id',
        'required',
        'html_id',
        'name',
        'value',
        'label',
        'autofocus',
        'checked',
        'disabled',
    ];

    protected $casts = [
        'required' => 'boolean',
        'autofocus' => 'boolean',
        'checked' => 'boolean',
        'disabled' => 'boolean',
    ];

    public function formQuestion(): BelongsTo
    {
        return $this->belongsTo(FormQuestion::class);
    }
}
