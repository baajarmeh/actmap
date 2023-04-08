<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Facility;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Rows;


class FacilityEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('facility.type')
                ->type('text')
                ->max(100)
                ->required()
                ->title(__('Type')),
            
            Switcher::make('facility.active')
                ->title(__('Status'))
                ->placeholder(__('Select active status'))
                ->options([
                    1 => __('Active'),
                    0 => __('Disabled'),
                ]),

            Input::make('facility.name')
                ->type('text')
                ->max(100)
                ->required()
                ->title(__('Name')),
            
            TextArea::make('facility.description')
                ->rows(1)
                ->title(__("Description"))
        ];
    }
}
