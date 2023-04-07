<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Facility;

use App\Models\Facility;
use Orchid\Screen\Actions\ModalToggle;
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
                ->asyncFilter('statusFilter')
                ->render(function (Facility $facility) {
                    return $facility->active
                        ? "<span class='text-success'>" . __('Active') . "</span>"
                        : "<span class='text-danger'>" . __('Disabled') . "</span>";
                })
                ->align(TD::ALIGN_CENTER),
            
            TD::make('created_at', __('Created'))
                ->render(fn (Facility $facility) => $facility->created_at->toDateTimeString())
                ->sort(),
        ];
    }

    public function statusFilter(Request $request): array
    {
        return [
            1 => __('Active'),
            0 => __('Disabled'),
        ];
    }

}
