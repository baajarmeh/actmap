<?php

namespace App\Orchid\Layouts\Venue;

use App\Orchid\Filters\NameFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;


class VenueFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            NameFilter::class
        ];
    }
}
