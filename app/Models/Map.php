<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Attachment\Attachable;


class Map extends Model
{
    use HasFactory, AsSource, Filterable, SoftDeletes, Attachable;

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
        'photo_path'
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

    protected static function booted()
    {
        static::addGlobalScope('withoutTrashed', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Map $map) {
            if ($map->active) {
                static::deactivateOtherEvents($map);
            }
        });
    }

    protected static function deactivateOtherMaps(Map $map)
    {
        DB::transaction(function () use ($map) {
            static::where('active', 1)
                ->where('id', '<>', $map->id)
                ->update(['active' => 0]);
        });
    }

    public function photo()
    {
        return $this->hasOne(Attachment::class, 'id', 'photo_attachment_id');
    }
}
