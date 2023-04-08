<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereBetween;
use App\Models\Venue;


class Category extends Model
{
    use HasFactory, AsSource, Filterable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'order'
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
        'type'          => Where::class,
        'created_at'    => WhereBetween::class,
        'deleted_at'    => WhereBetween::class,
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'type',
        'order',
        'created_at',
        'deleted_at',
    ];

    // protected static function booted()
    // {
    //     static::addGlobalScope('withoutTrashed', function (Builder $builder) {
    //         $builder->whereNull('deleted_at');
    //     });
    // }

    /**
     * Venues relationship.
     *
     * @return HasMany
     */
    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class, 'type_id');
    }

    /**
     * Get only categories with a custom type.
     *
     * @param Builder $query
     * @param string  $categoryType
     *
     * @return Builder
     */
    public function scopeType(Builder $query, string $categoryType): Builder
    {
        return $query->where('type', $categoryType);
    }

    /**
     * Get only categories from an array of custom types.
     *
     * @param Builder $query
     * @param array   $type
     *
     * @return Builder
     */
    public function scopeTypeIn(Builder $query, array $type): Builder
    {
        return $query->whereIn('type', $type);
    }
}
