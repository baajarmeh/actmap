<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Map;

use App\Models\Map;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\ModalToggle;
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
            TD::make('photo_path', __('Photo'))
                ->render(function (Map $map) {
                    if (!empty($map->photo_path)) {
                        return Attachment::make()
                            ->src($map->photo_path)
                            ->preview(function ($preview) use ($map) {
                                $preview->file($map->photo_path);
                            });
                    } else {
                        return __('No photo');
                    }
                })
                ->align(TD::ALIGN_CENTER)
                ->width('100px'),

            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn (Map $map) => ModalToggle::make($map->name)
                        ->modal('asyncEditMapModal')
                        ->method('saveMap')
                        ->title(__('Edit map'))
                        ->icon('pencil')
                        ->asyncParameters([
                            'map' => $map->id,
                        ])),
                        TD::make('photo_path', __('Photo'))
                        ->render(function (Map $map) {
                            if (!empty($map->photo_path)) {
                                return Attachment::make()
                                    ->src($map->photo_path)
                                    ->preview(function ($preview) use ($map) {
                                        $preview->file($map->photo_path);
                                    });
                            } else {
                                return __('No photo');
                            }
                        }),

            TD::make('active', __('Status'))
                ->filter(TD::FILTER_SELECT)
                ->options([
                    1 => __('Active'),
                    0 => __('Disabled'),
                ])
                ->render(function (Map $map) {
                    return $map->active
                        ? "<span class='text-success'>" . __('Active') . "</span>"
                        : "<span class='text-danger'>" . __('Disabled') . "</span>";
                })
                ->align(TD::ALIGN_CENTER),

            TD::make('created_at', __('Created'))
                ->render(fn (Map $map) => $map->created_at->toDateTimeString())
                ->sort(),
        ];
    }
}
