<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Map;

use App\Models\Map;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\Upload;
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
                ->sendTrueOrFalse()
                ->title(__('Status')),

            // Switcher::make('map.active')
            //     ->title(__('Status'))
            //     ->placeholder(__('Select active status'))
            //     ->options([
            //         1 => __('Active'),
            //         0 => __('Disabled'),
            //     ]),

            Group::make([
                Picture::make('map.image_id')
                    ->title(__('Map Image'))
                    ->targetId()
                    ->help(__('Map Image')),

                // Upload::make('map_photo')
                //     ->storage('maps')
                //     ->maxFiles(1)
                //     ->maxFileSize(2)
                //     ->acceptedFiles('.jpg, .jpeg, .png')
                //     ->multiple(false)
                //     ->title(__('Map photo'))
                //     ->placeholder(__('Drag and drop files here or click to upload'))
                //     ->hint(__('Max file size: 2MB'))
                //     ->value(optional($this->map)->photo_path ?? null),
                ]),
        ];
    }
}
