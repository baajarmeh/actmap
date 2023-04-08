<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Map;

use App\Models\Map;
use App\Orchid\Layouts\Map\MapEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;


class MapEditScreen extends Screen
{
    /**
     * @var Map
     */
    public $map;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param Map $map
     *
     * @return array
     */
    public function query(Map $map): iterable
    {
        return [
            'map' => $map,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->map->exists ? __('Edit Map') : __('Create Map');
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return __('Details such as name and type');
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
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Are you sure you want to remove this map?'))
                ->method('remove')
                ->canSee($this->map->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(MapEditLayout::class)
                // ->async('asyncGetMapData')
                // ->title(__('Map Information'))
                // ->commands(
                //     Button::make(__('Save'))
                //         ->type(Color::DEFAULT())
                //         ->icon('check')
                //         ->canSee($this->map->exists)
                //         ->method('save')
                // ),
        ];
    }

    // public function asyncGetMapData(Map $map): array
    // {
    //     return [
    //         'map' => $map,
    //     ];
    // }

    /**
     * @param Map $map
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Map $map, Request $request)
    {
        $request->validate([
            'map.name' => ['required', 'string'],
        ]);

        $map->fill($request->input('map'));

        $map->active = $request->input('map.active', false);
        //dd($map);
        // if ($request->hasFile('map_photo')) {
        //     $file = $request->file('map_photo');
        //     //dd($file);
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = Str::uuid() . '.' . $extension;

        //     // Create the maps folder if it doesn't exist
        //     Storage::makeDirectory('maps');

        //     Storage::putFileAs('maps', $file, $filename);
        //     //dd($filename);
        //     $map->photo_path = "maps/{$filename}";
        // }

        // $map->photo_path = ' ';
        $map->save();

        // to deactivate other maps
        if ($map->active) {
            Map::where('active', 0)
                ->where('id', '<>', $map->id)
                ->update(['active' => 1]);
        }

        Toast::info(__('Map was saved.'));

        return redirect()->route('platform.map');
    }

    /**
     * @param Map $map
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Map $map)
    {
        $map->delete();

        Toast::info(__('Map was removed'));

        return redirect()->route('platform.map');
    }
}
