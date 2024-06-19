<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'payment_intent',
        'session_id',
        'status',
        'amount',
    ];

    protected $casts = [
        'status' => PaymentStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::hasUser()) {
                $model->user_id = Auth::id();
            }
        });

        static::deleting(function ($user) {
            $user->payments()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
