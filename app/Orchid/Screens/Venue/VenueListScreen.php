<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Venue;

use App\Orchid\Layouts\Venue\VenueListLayout;
use App\Models\Venue;
use App\Orchid\Layouts\Venue\VenueEditLayout;
use App\Orchid\Layouts\Venue\VenueFiltersLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class VenueListScreen extends Screen
{

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Venue');
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return __('All venues');
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.venues',
        ];
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'venues' => Venue::filters(VenueFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('icon-plus')
                ->href(route('platform.venue.create')),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            VenueFiltersLayout::class,
            VenueListLayout::class,

            Layout::modal('asyncEditVenueModal', VenueEditLayout::class)
                ->async('asyncGetVenue'),
        ];
    }

    /**
     * @param √
     *
     * @return array
     */
    public function asyncGetVenue(Venue $venue): iterable
    {
        return [
            'venue' => $venue,
        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Venue::findOrFail($request->get('id'))->delete();

        Toast::info(__('Venue was removed'));
    }

    /**
     * @param Request $request
     * @param Venue $venue
     */
    public function saveVenue(Request $request, Venue $venue): void
    {
        $request->validate([
            'venue.name' => ['required', 'string'],
            'venue.type' => ['required', 'string'],
            'venue.active' => ['required', 'integer|in:0,1'],
        ]);

        $venue->fill($request->input('venue'))->save();

        Toast::info(__('Venue was saved.'));
    }
}
