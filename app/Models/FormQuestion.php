<?php

namespace App\Models;

use App\Enums\FormQuestionTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'label',
        'description',
        'status',
        'form_id',
        'depends_on',
    ];

    protected $casts = [
        'status' => FormQuestionTypes::class,
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
