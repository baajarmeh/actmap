<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Event;

use App\Models\Event;
use App\Models\Map;
use App\Orchid\Layouts\Event\EventEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;


class EventEditScreen extends Screen
{
    /**
     * @var Event
     */
    public $event;

    /**
     * @var Map
     */
    public $map;

    public function __construct(Event $event, Map $map)
    {
        $this->event = $event;
        $this->map = Map::active()->get();
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param Event $event
     *
     * @return array
     */
    public function query(Event $event): iterable
    {
        return [
            'map' => Map::with('photo')->active()->first(),
            'event' => $event,
            'gallery_photos' => $event->gallery_photos,
            'main_photo' => $event->main_photo,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->event->exists ? __('Edit Event') : __('Create Event');
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
            'platform.events',
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
                ->confirm(__('Are you sure you want to remove this event?'))
                ->method('remove')
                ->canSee($this->event->exists),

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
            Layout::block(EventEditLayout::class)
                ->async('asyncGetEventData')
                ->title(__('Event Information'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->event->exists)
                        ->method('save')
                ),
        ];
    }

    public function asyncGetEventData(Event $event): array
    {
        $activeMap = Map::with('photo')
                        ->where('photo_attachment_id', '!=', null)
                        ->active()
                        ->first();

        return [
            'event' => $event->with(['venue', 'roomNumber', 'mainPhoto', 'galleryPhotos'])->first(),
            'activeMap' => $activeMap,
        ];
    }

    /**
     * @param Event $event
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Event $event, Request $request)
    {
        $request->validate([
            'event.name' => 'required|max:100',
            'event.venue_id' => 'required|exists:venues,id',
            'event.room_number_id' => 'required|exists:venues,id',
            'event.description' => 'nullable|string',
        ]);

        $event->fill($request->input('event'));

        // Save the main photo, if any
        if ($request->hasFile('event.mainPhoto')) {
            $event->clearMediaCollection('mainPhoto');
            $event->addMediaFromRequest('event.mainPhoto')
                  ->toMediaCollection('mainPhoto');
        }

        // Save the gallery photos, if any
        if ($request->hasFile('event.galleryPhotos')) {
            $event->clearMediaCollection('galleryPhotos');
            foreach ($request->file('event.galleryPhotos') as $file) {
                $event->addMedia($file)
                      ->toMediaCollection('galleryPhotos');
            }
        }

        $event->save();

        Toast::info(__('Event was saved.'));

        return redirect()->route('platform.event');
    }

    /**
     * @param Event $event
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Event $event)
    {
        $event->delete();

        Toast::info(__('Event was removed'));

        return redirect()->route('platform.event');
    }
}
