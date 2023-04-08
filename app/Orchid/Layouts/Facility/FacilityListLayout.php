<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Facility;

use App\Models\Facility;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;


class FacilityListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'facilities';

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
                ->render(fn (Facility $facility) => ModalToggle::make($facility->name)
                        ->modal('asyncEditFacilityModal')
                        ->method('saveFacility')
                        ->title(__('Edit facility'))
                        ->icon('pencil')
                        ->asyncParameters([
                            'facility' => $facility->id,
                        ])),
    
            TD::make('type', __('Type'))
                ->render(fn (Facility $facility) => $facility->type)
                ->sort(),

            TD::make('active', __('Status'))
                ->render(fn (Facility $facility) => $facility->active
                    ? "<span class='text-success'>" . __('Active') . "</span>"
                    : "<span class='text-danger'>" . __('Disabled') . "</span>"),
            
            TD::make('created_at', __('Created'))
                ->render(fn (Facility $facility) => $facility->created_at->toDateTimeString())
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Facility $facility) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.facility.edit', $facility->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(__('Are you sure you want to remove this facility?'))
                            ->method('remove', [
                                'id' => $facility->id,
                            ]),
                    ])),
        ];
    }
}
