<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereBetween;
use App\Models\Venue;
use App\Models\Photo;


class Event extends Model
{
    use HasFactory, AsSource, Filterable, SoftDeletes, Attachable;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'activity_date',
        'starts_at',
        'ends_at',
        'venue_id',
        'room_number_id',
        'description',
        'px',
        'py',
        'main_photo'
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
        'activity_date' => WhereBetween::class,
        'starts_at'     => WhereBetween::class,
        'ends_at'       => WhereBetween::class,
        'venue_id'      => Where::class,
        'room_number_id'=> Where::class,
        'created_at'    => WhereBetween::class,
        'deleted_at'    => WhereBetween::class,
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'activity_date',
        'venue_id',
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
     * Venue relationship.
     *
     * @return \BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * Room relationship.
     * 
     * @return \BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'room_number_id');
    }

    /**
     * Photos relationship.
     *
     * @return \HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class, 'event_id');
    }
}
