<?php

namespace App\Orchid\Layouts\Event;

use App\Orchid\Filters\NameFilter;
use App\Orchid\Filters\ActivityFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;


class EventFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            NameFilter::class,
            ActivityFilter::class
        ];
    }
}
