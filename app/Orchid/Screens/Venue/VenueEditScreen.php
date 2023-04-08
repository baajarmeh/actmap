<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Venue;

use App\Models\Venue;
use App\Orchid\Layouts\Venue\VenueEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;


class VenueEditScreen extends Screen
{
    /**
     * @var Venue
     */
    public $venue;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param Venue $venue
     *
     * @return array
     */
    public function query(Venue $venue): iterable
    {
        return [
            'venue' => $venue,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->venue->exists ? __('Edit Venue') : __('Create Venue');
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
            'platform.venues',
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
                ->confirm(__('Are you sure you want to remove this venue?'))
                ->method('remove')
                ->canSee($this->venue->exists),

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
            Layout::block(VenueEditLayout::class)
                ->title(__('Venue Information'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->venue->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Venue $venue
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Venue $venue, Request $request)
    {
        $request->validate([
            'venue.name' => ['required', 'string', 'max:100'],
            'venue.type_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $venue->fill($request->input('venue'))->save();

        Toast::info(__('Venue was saved.'));

        return redirect()->route('platform.venue');
    }

    /**
     * @param Venue $venue
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Venue $venue)
    {
        $venue->delete();

        Toast::info(__('Venue was removed'));

        return redirect()->route('platform.venue');
    }
}
