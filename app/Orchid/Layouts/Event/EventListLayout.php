<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Event;

use App\Models\Event;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;


class EventListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'events';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::make('main_photo', __('Photo'))
                ->render(function (Event $event) {
                    if (!empty($event->main_photo)) {
                        return Attachment::make()
                            ->src($event->main_photo)
                            ->preview(function ($preview) use ($event) {
                                $preview->file($event->main_photo);
                            });
                    } else {
                        return __('No photo');
                    }
                })
                ->align(TD::ALIGN_CENTER)
                ->width('100px'),

            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT),

            TD::make('time_of_event', __('Event DateTime'))
                ->cantHide()
                ->render(fn (Event $event) => $event->activity_date->toDateString() .' '.__('at'). $event->starts_at .' - '. $event-ends_at),

            TD::make('venue.name', __('Venue'))
                ->filter(TD::FILTER_TEXT)
                ->render(fn (Event $event) => optional($event->venue)->name ?? '-'),

            TD::make('room.name', __('Room'))
                ->filter(TD::FILTER_TEXT)
                ->render(fn (Event $event) => optional($event->room)->name ?? '-'),

            TD::make('created_at', __('Created'))
                ->render(fn (Event $event) => $event->created_at->toDateTimeString())
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Event $event) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.event.edit', $event->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(__('Are you sure you want to remove this event?'))
                            ->method('remove', [
                                'id' => $event->id,
                            ]),
                    ])),
        ];
    }
}
