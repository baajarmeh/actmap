<?php

namespace App\Orchid\Layouts\Category;

use App\Orchid\Filters\NameFilter;
use App\Orchid\Filters\TypeFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class CategoryFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            TypeFilter::class,
            NameFilter::class,
        ];
    }
}
