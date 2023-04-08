<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereBetween;


class Map extends Model
{
    use HasFactory, AsSource, Filterable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'maps';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'active',
        'photo_path',
        'image_id',
    ];

    protected $attributes = [
        'photo_path' => 'default.jpg', // set a default value
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        'id'            => Where::class,
        'name'          => Like::class,
        'active'        => WhereBetween::class,
        'created_at'    => WhereBetween::class,
        'deleted_at'    => WhereBetween::class,
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'active',
        'created_at',
        'deleted_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            self::where('active', 0)
                ->where('id', '<>', $model->id)
                ->update(['active' => 1]);

            // $model->createSlug($model->slug);
        });
    }

    /**
     * Get only active map.
     *
     * @param Builder $query
     * @param int  $active
     *
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'image_id')->withDefault();
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => Attachment::find($this->image_id)->url ?? null,
        );
    }
}


// public function __construct(public $title,public $message,public $file = null)
// {
//     $this->title = $title;
//     $this->message = $message;
//     $attachment = Attachment::find($file) ?? null;
//     $this->file = $attachment ? storage_path('app/public/'.$attachment->path.$attachment->name.'.'.$attachment->extension) : null;
// }