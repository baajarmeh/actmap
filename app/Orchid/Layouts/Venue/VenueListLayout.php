<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Venue;

use App\Models\Venue;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;


class VenueListLayout extends Table
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
                ->render(fn (Venue $venue) => ModalToggle::make($venue->name)
                        ->modal('asyncEditVenueModal')
                        ->method('saveVenue')
                        ->title(__('Edit venue'))
                        ->icon('pencil')
                        ->asyncParameters([
                            'venue' => $venue->id,
                        ])),
    
            Relation::make(__('Category'))
                ->fromModel(Category::class, 'name')
                ->required()
                ->title(__('Category of venue'))
                ->autocomplete('off')
                ->allowNull(false),

            TD::make('created_at', __('Created'))
                ->render(fn (Venue $venue) => $venue->created_at->toDateTimeString())
                ->sort(),
        ];
    }
}
