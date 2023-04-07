<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Map;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fieldset;


class MapEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('map.name')
                ->type('text')
                ->max(100)
                ->required()
                ->title(__('Name')),
            
            Switcher::make('map.active')
                ->title(__('Status'))
                ->placeholder(__('Select active status'))
                ->options([
                    1 => __('Active'),
                    0 => __('Disabled'),
                ]),

            Group::make([
                Picture::make('photo')
                    ->targetId()
                    ->maxFileSize(2) // Set max file size to 2 MB
                    ->attachOne('photo', Attachment::class)
                    ->value($this->query->get('photo') ?? $this->query->get('photo_path'))
                    ->title(__('Photo'))
                    ->placeholder(__('Select a photo'))
                    ->help(__('Upload a photo for the map'))
                ])->title(__('Photo'))->collapsed(),
        ];
    }
}
