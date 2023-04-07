<?php

namespace App\Orchid\Layouts\Map;

use App\Orchid\Filters\NameFilter;
use App\Orchid\Filters\StatusFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;


class FacilityFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            NameFilter::class,
            StatusFilter::class
        ];
    }
}
