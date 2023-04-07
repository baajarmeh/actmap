<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Facility;

use App\Orchid\Layouts\Facility\FacilityListLayout;
use App\Models\Facility;
use App\Orchid\Layouts\Facility\FacilityEditLayout;
use App\Orchid\Layouts\Facility\FacilityFiltersLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class FacilityListScreen extends Screen
{

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Facility');
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return __('All facilitys');
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
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'facilitys' => Facility::filters(FacilityFiltersLayout::class)
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
                ->href(route('platform.facility.create')),
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
            FacilityFiltersLayout::class,
            FacilityListLayout::class,

            Layout::modal('asyncEditFacilityModal', FacilityEditLayout::class)
                ->async('asyncGetFacility'),
        ];
    }

    /**
     * @param √
     *
     * @return array
     */
    public function asyncGetFacility(Facility $facility): iterable
    {
        return [
            'facility' => $facility,
        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Facility::findOrFail($request->get('id'))->delete();

        Toast::info(__('Facility was removed'));
    }

    /**
     * @param Request $request
     * @param Facility $facility
     */
    public function saveFacility(Request $request, Facility $facility): void
    {
        $request->validate([
            'facility.name' => ['required', 'string'],
            'facility.type' => ['required', 'string'],
            'facility.active' => ['required', 'integer|in:0,1'],
        ]);

        $facility->fill($request->input('facility'))->save();

        Toast::info(__('Facility was saved.'));
    }
}
