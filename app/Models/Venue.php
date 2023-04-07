<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use App\Models\Category;
use App\Models\Event;


class Venue extends Model
{
    use HasFactory, AsSource, Filterable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'venues';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type_id'
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
        'id',
        'name',
        'type_id',
        'publish_at',
        'created_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'type_id',
        'publish_at',
        'created_at',
        'deleted_at',
    ];

    protected static function booted()
    {
        static::addGlobalScope('withoutTrashed', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }

    /**
     * Category relationship.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'type_id');
    }

    /**
     * Events relationship.
     *
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'venue_id');
    }

    /**
     * Rooms relationship.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Event::class, 'room_number_id');
    }
}
