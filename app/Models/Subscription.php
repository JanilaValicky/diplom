<?php

namespace App\Models;

use App\Enums\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    public const HUMAN_DATE_TIME_FORMAT = 'd.m.Y H:i:s';

    protected $primaryKey = 'id';

    protected $fillable = ['payment_id', 'plan_id', 'status', 'end_date'];

    protected $casts = [
        'status' => SubscriptionStatusEnum::class,
        'end_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }
}
