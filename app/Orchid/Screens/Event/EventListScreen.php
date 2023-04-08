<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Event;

use App\Models\Event;
use App\Models\Map;
use App\Orchid\Layouts\Event\EventListLayout;
use App\Orchid\Layouts\Event\EventEditLayout;
use App\Orchid\Layouts\Event\EventFiltersLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class EventListScreen extends Screen
{

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Event');
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return __('All events');
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
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'map' => Map::with('photo')->active()->first(),
            'events' => Event::with('main_photo', 'gallery_photos', 'venue', 'room')
                ->filters(EventFiltersLayout::class)
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
                ->href(route('platform.event.create')),
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
            EventFiltersLayout::class,
            EventListLayout::class,

            Layout::modal('asyncEditEventModal', EventEditLayout::class)
                ->async('asyncGetEvent'),
        ];
    }

    /**
     * @param √
     *
     * @return array
     */
    public function asyncGetEvent(Event $event): iterable
    {
        return [
            'event' => $event,
        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Event::findOrFail($request->get('id'))->delete();

        Toast::info(__('Event was removed'));
    }

    // /**
    //  * @param Request $request
    //  * @param Event $event
    //  */
    // public function saveEvent(Request $request, Event $event): RedirectResponse
    // {
    //     $request->validate([
    //         'event.name' => ['required', 'string'],
    //         'event.photo' => ['nullable', 'image', 'max:2048'],
    //     ]);

    //     $event->fill($request->input('event'));

    //     if ($request->hasFile('photo')) {
    //         $attachment = $event->getAttachment('photo');
    //         if ($attachment) {
    //             $attachment->delete();
    //         }
    //         $attachment = $event->attachOne('photo', $request->file('photo'));
    //         $event->photo_path = $attachment->url();
    //     }
    
    //     $event->save();

    //     Toast::info(__('Event was saved.'));

    //     return redirect()->route('platform.event');
    // }
}
