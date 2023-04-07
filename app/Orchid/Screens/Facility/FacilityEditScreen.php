<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Facility;

use App\Models\Facility;
use App\Orchid\Layouts\Facility\FacilityEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;


class FacilityEditScreen extends Screen
{
    /**
     * @var Facility
     */
    public $facility;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param Facility $facility
     *
     * @return array
     */
    public function query(Facility $facility): iterable
    {
        return [
            'facility' => $facility,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->facility->exists ? __('Edit Facility') : __('Create Facility');
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
            'platform.facilities',
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
                ->confirm(__('Are you sure you want to remove this facility?'))
                ->method('remove')
                ->canSee($this->facility->exists),

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
            Layout::block(FacilityEditLayout::class)
                ->title(__('Facility Information'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->facility->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Facility $facility
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Facility $facility, Request $request)
    {
        $request->validate([
            'facility.name' => ['required', 'string'],
            'facility.type' => ['required', 'string'],
            'facility.active' => ['required', 'integer|in:0,1'],
        ]);

        $facility->fill($request->input('facility'))->save();

        Toast::info(__('Facility was saved.'));

        return redirect()->route('platform.facility');
    }

    /**
     * @param Facility $facility
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Facility $facility)
    {
        $facility->delete();

        Toast::info(__('Facility was removed'));

        return redirect()->route('platform.facilities');
    }
}
