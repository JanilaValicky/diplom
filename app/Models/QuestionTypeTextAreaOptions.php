<?php

namespace App\Models;

use App\Enums\HtmlWritingDirection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionTypeTextAreaOptions extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'form_question_id',
        'required',
        'html_id',
        'value',
        'label',
        'autocapitalize',
        'autocorrect',
        'autofocus',
        'disabled',
        'cols',
        'dirname',
        'maxlength',
        'minlength',
        'placeholder',
        'readonly',
        'rows',
        'spellcheck',
    ];

    protected $casts = [
        'autocapitalize' => 'boolean',
        'autocorrect' => 'boolean',
        'autofocus' => 'boolean',
        'disabled' => 'boolean',
        'dirname' => HtmlWritingDirection::class,
        'readonly' => 'boolean',
        'spellcheck' => 'boolean',

    ];

    public function formQuestion(): BelongsTo
    {
        return $this->belongsTo(FormQuestion::class);
    }
}
