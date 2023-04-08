<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Orchid\Screen\AsMultiSource;
use Orchid\Filters\Filterable;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereBetween;
use App\Models\Venue;
use App\Models\Photo;
use Orchid\Screen\AsSource;


class Event extends Model
{
    use HasFactory, Filterable, SoftDeletes, AsSource;

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
        'main_image_id',
        'description',
        'px',
        'py'
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

    /**
     * Venue relationship.
     *
     * @return BelongsTo
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * Room relationship.
     * 
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'room_number_id');
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'main_image_id')->withDefault();
    }

    // public function galleryPhotos(): HasMany
    // {
    //     return $this->hasMany(Attachment::class, 'attachable_id')->where('attachable_type', 'Event')->orderBy('sort');
    // }
}
