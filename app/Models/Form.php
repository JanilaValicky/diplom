<?php

namespace App\Models;

use App\Enums\FormStatusEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

/**
 * App\Models\Form
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $updated_by
 * @property string $name
 * @property string $slug
 * @property FormStatusEnum $status
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\FormFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Form findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Form newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Form newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Form onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Form query()
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Form withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Form withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Form withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Form extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    public const MYSQL_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public const HUMAN_DATE_TIME_FORMAT = 'd.m.Y H:i:s';

    public const HUMAN_DATE_FORMAT = 'd.m.Y';

    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::hasUser()) {
                $model->user_id = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::hasUser()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'status' => FormStatusEnum::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Hashids::encode($value)
        );
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
