<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionTypeHeaderOptions extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'form_question_id',
        'html_id',
        'name',
        'value',
        'size',
    ];

    public function formQuestion(): BelongsTo
    {
        return $this->belongsTo(FormQuestion::class);
    }
}
