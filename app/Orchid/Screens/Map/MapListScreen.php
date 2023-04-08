<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Map;

use App\Orchid\Layouts\Map\MapListLayout;
use App\Models\Map;
use App\Orchid\Layouts\Map\MapEditLayout;
use App\Orchid\Layouts\Map\MapFiltersLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class MapListScreen extends Screen
{

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Map');
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return __('All maps');
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.maps',
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
            'maps' => Map::filters(MapFiltersLayout::class)
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
                ->href(route('platform.map.create')),
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
            MapFiltersLayout::class,
            MapListLayout::class,

            Layout::modal('asyncEditMapModal', MapEditLayout::class)
                ->async('asyncGetMap'),
        ];
    }

    /**
     * @param Map $map
     *
     * @return array
     */
    public function asyncGetMap(Map $map): iterable
    {
        return [
            'map' => $map,
        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Map::findOrFail($request->get('id'))->delete();

        Toast::info(__('Map was removed'));
    }
}
