<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Venue;

use App\Models\Category;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;


class VenueEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('venue.name')
                ->type('text')
                ->max(100)
                ->required()
                ->title(__('Name')),
            
            Relation::make('venue.type_id')
                ->title(__('Type of Venue'))
                ->fromModel(Category::class, 'name')
                ->required(),
        ];
    }
}
