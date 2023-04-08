<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereBetween;


class Facility extends Model
{
    use HasFactory, AsSource, Filterable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'facilities';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'active',
        'type',
        'description'
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
        'type'          => Like::class,
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
        'type',
        'created_at',
        'deleted_at',
    ];

    /*protected static function booted()
    {
        static::addGlobalScope('withoutTrashed', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }*/

    /**
     * Get only facilities with a custom status.
     *
     * @param Builder $query
     * @param int  $active
     *
     * @return Builder
     */
    public function scopeActive(Builder $query, int $active): Builder
    {
        return $query->where('active', $active);
    }
}
