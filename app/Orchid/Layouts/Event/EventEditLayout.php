<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Event;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fieldset;
use App\Models\Venue;
use App\Models\Map;
use App\Models\Event;


class EventEditLayout extends Rows
{
    /**
     * The event model.
     * @var Event
     */
    public $event;

    /**
     * The active map model.
     * @var Map
     */
    public $activeMap;

    /**
     * EventEditLayout constructor.
     *
     * @param Event $event
     */
    public function __construct(Event $event, Map $activeMap)
    {
        $this->event = $event;
        $this->activeMap = $activeMap;
    }

    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        // $activeMap = Map::with('photo')
        //                 ->where('photo_attachment_id', '!=', null)
        //                 ->active()
        //                 ->first();

        return [
            Input::make('event.name')
                ->type('text')
                ->max(100)
                ->required()
                ->title(__('Name'))
                ->class('activity-name'),

            Group::make([
                DateTimer::make('event.start_at')
                    // ->disabled($this->query->get('coupon')->exists)
                    // ->readonly($this->query->get('coupon')->exists)
                    ->title(__('Start At')),
                
                DateTimer::make('event.expire_at')
                    ->title(__('Expire At')),
            ]),

            Select::make('event.venue_id')
                ->title(__('Room number'))
                ->options(
                    Venue::with('category')
                        ->whereHas('category', function ($query) {
                            $query->where('type', 'place');
                        })
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                ->required(),
                // ->value($this->event?->venue?->id),

            Select::make('event.room_number_id')
                ->title(__('Room number'))
                ->options(
                    Venue::with('category')
                        ->whereHas('category', function ($query) {
                            $query->where('type', 'room');
                        })
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                ->required(),
                // ->value($this->event?->roomNumber?->id),

                Cropper::make('active_map')
                        ->targetId()
                        ->title(__('Active map'))
                        ->width(640)
                        ->height(480)
                        ->value(optional($this->activeMap)->photo ? $this->activeMap->photo->url() : '')
                        ->class('active-map-photo'),
            
            Input::make('event.px')->class('event-pin-x')->hidden(),
            Input::make('event.py')->class('event-pin-y')->hidden(),

            Group::make([
                Cropper::make('event.mainPhoto')
                    ->title(__('Main Photo'))
                    ->targetId()
                    ->width(640)
                    ->height(480)
                    ->popover(__('Upload the main photo of the event'))
                    ->autoCrop(true)
                    ->default(optional($this->event->mainPhoto)->url() ?? '')
                    ->value(old('event.mainPhoto')),
            ]),

            // Group::make([
            //     Cropper::make('event.mainPhoto')
            //         ->title(__('Main Photo'))
            //         ->targetId()
            //         ->width(640)
            //         ->height(480)
            //         ->popover(__('Upload the main photo of the event'))
            //         ->autoCrop(true)
            //         ->default(optional($this->event->mainPhoto)->url() ?? ''),
            // ]),

            Group::make([
                Upload::make('event.galleryPhotos')
                    ->group('gallery')
                    ->maxFiles(10)
                    ->maxFileSize(2)
                    ->multiple()
                    ->acceptedFiles('image/*')
                    ->title(__('Gallery Photos'))
                    ->placeholder(__('Drag and drop files here or click to upload'))
                    ->hint(__('Max file size: 2MB'))
                    // ->value(old('event.gallery_photos', optional($this->event->galleryPhotos)->pluck('url')->toArray()))
                    ->value(old('event.galleryPhotos', optional($this->event->galleryPhotos)->pluck('url')->toArray()))
            ]),
            
            // Group::make([
            //     Upload::make('event.galleryPhotos')
            //         ->group('gallery')
            //         ->maxFiles(10)
            //         ->maxFileSize(2)
            //         ->multiple()
            //         ->acceptedFiles('image/*')
            //         ->title(__('Gallery Photos'))
            //         ->placeholder(__('Drag and drop files here or click to upload'))
            //         ->hint(__('Max file size: 2MB'))
            //         ->value(optional($this->event->galleryPhotos)->pluck('url')->toArray() ?? [])
            // ]),

            TextArea::make('event.description')
                ->rows(1)
                ->title(__("Description"))
        ];
    }

    protected function js(): array
    {
        return [
            /**
             * Set up the EasyPin library on the map photo input element
             */
            \Orchid\Support\Facades\Layout::js('js/easypin.js'),

            /**
             * Create a new EasyPin instance
             */
            \Orchid\Support\Facades\Layout::js("
                const easypin = new EasyPin(document.querySelector('.active-map-photo'), {
                    // additional configuration options go here..
                });

                if ($('.event-pin-x').val() && $('.event-pin-y').val()) {
                    var mapWidth = $('.active-map-photo').width();
                    var mapHeight = $('.active-map-photo').height();
                    var x = (parseFloat($('.event-pin-x').val()) / 100) * mapWidth;
                    var y = (parseFloat($('.event-pin-y').val()) / 100) * mapHeight;
                    var img = $('.event-main-photo img').attr('src');
                    var name = $('.activity-name').val();

                    var pin = '<div class='pin'><div class='pin-image'><img src='+ img +'></div><div class='pin-label'>'+ name +'</div></div>';
                    easypin.addPin(x, y, pin);
                }
            "),

            /**
             * Listen for the pin event and update the X and Y fields with the pin position
             */
            \Orchid\Support\Facades\Layout::js("
                easypin.on('pin', function(position) {
                    document.querySelector('.event-pin-x').value = position.x.toFixed(2);
                    document.querySelector('.event-pin-y').value = position.y.toFixed(2);
                });
            "),

            /**
             * Restrict to one pin
             */
            \Orchid\Support\Facades\Layout::js("
                easypin.on('pin', function(position) {
                    easypin.removeListeners();
                });
            "),

            /**
             * Add the pin to the map photo
             */
            \Orchid\Support\Facades\Layout::js("
                function updatePin() {
                    easypin.removeListeners();
                    var px = parseFloat($('#px').val());
                    var py = parseFloat($('#px').val());
                    easypin.pin({x: px, y: py});
                    easypin.on('pin:update', function(pin) {
                        $('#px').val(pin.x);
                        $('#py').val(pin.y);
                    });
                });
            "),
        ];
    }

    // public function with(): array
    // {
    //     return [
    //         'event' => $this->event,
    //         'activeMap' => $this->activeMap,
    //     ];
    // }

    // public function async(string $method): array
    // {
    //     switch ($method) {
    //         case 'getEventData':
    //             return $this->getEventData();
    //             break;
    //         default:
    //             return [];
    //             break;
    //     }
    // }
}
