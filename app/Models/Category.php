<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;


class Category extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'order'
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'type',
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
        'type',
        'order',
        'publish_at',
        'created_at',
        'deleted_at',
    ];
}
