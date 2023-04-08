<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Map;

use App\Models\Map;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;


class MapListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'maps';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::make('image_id',__('Image'))
                ->render(function(Map $map){
                    return view('utils.image',[
                        'src' => $map->image->url ?? '',
                        'path' => $map->imageUrl,
                    ]);
                }),

            // TD::make('photo_path', __('Photo'))
            //     ->render(fn(Map $map) => 
            //         "<img src=\"" . route('map.photo', ['photoname' => $map->photo_path]) . "\" alt=\"" . __('Map photo') . "\">"
            //     )

            //     // ->render(function (Map $map) {
            //     //     return '<img src="{{ route('map.photo', ['filename' => $map->photo_path]) }}" alt="{{ __('Map photo') }}">'
            //     // })
            //     // ->render(function (Map $map) {
            //     //     $attachment = Attachment::get($map->photo->id);

            //     //     if ($attachment) {
            //     //         return Picture::make()
            //     //             ->src($attachment->url())
            //     //             ->width(100)
            //     //             ->height(100);
            //     //     } else {
            //     //         return __('No photo');
            //     //     }
            //     // })
            //     ->align(TD::ALIGN_CENTER)
            //     ->width('100px'),

            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT),

            TD::make('active', __('Status'))
            ->render(fn (Map $map) => $map->active
                    ? "<span class='text-success'>" . __('Active') . "</span>"
                    : "<span class='text-danger'>" . __('Disabled') . "</span>")
            ->align(TD::ALIGN_CENTER),
                // ->render(fn (Map $map) =>
                //     match ($map->active) {
                //         true => "<span class='text-success'>" . __('Active') . "</span>",
                //         false => "<span class='text-danger'>" . __('Disabled') . "</span>",
                //     }
                // )
                // ->align(TD::ALIGN_CENTER),

            TD::make('created_at', __('Created'))
                ->render(fn (Map $map) => $map->created_at->toDateTimeString())
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Map $map) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.map.edit', $map->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(__('Are you sure you want to remove this map?'))
                            ->method('remove', [
                                'id' => $map->id,
                            ]),
                    ])),
        ];
    }
}
