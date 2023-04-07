<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Venue;

use App\Models\Category;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
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
            
            Field::select('venue.type_id')
                ->fromModel(Category::class, 'name', 'id')
                ->allowAdd()
                ->title(__('Category')),
        ];
    }
}
