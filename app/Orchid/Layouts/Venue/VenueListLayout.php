<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Venue;

use App\Models\Venue;
use App\Models\Category;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;


class VenueListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'venues';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn (Venue $venue) => ModalToggle::make($venue->name)
                        ->modal('asyncEditVenueModal')
                        ->method('saveVenue')
                        ->title(__('Edit venue'))
                        ->icon('pencil')
                        ->asyncParameters([
                            'venue' => $venue->id,
                        ])),

            TD::make('type_id', __('Type of venue'))
                ->render(fn (Venue $venue) => optional($venue->category)->name ?? '-'),

            TD::make('created_at', __('Created'))
                ->render(fn (Venue $venue) => $venue->created_at->toDateTimeString())
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Venue $venue) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.venue.edit', $venue->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(__('Are you sure you want to remove this venue?'))
                            ->method('remove', [
                                'id' => $venue->id,
                            ]),
                    ])),
        ];
    }
}
