<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make(__('Type of Venues'))
                ->icon('organization')
                ->route('platform.category'),

            Menu::make(__('Venues'))
                ->icon('globe-alt')
                ->route('platform.venue')
                ->permission('platform.venues'),

            Menu::make(__('Facilities'))
                ->icon('building')
                ->route('platform.facility')
                ->permission('platform.facilities'),

            Menu::make(__('Map'))
                ->icon('map')
                ->route('platform.map')
                ->permission('platform.maps'),

            Menu::make(__('Events'))
                ->icon('event')
                ->route('platform.event')
                ->permission('platform.events'),

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make(__('Profile'))
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users'))
                ->addPermission('platform.venues', __('Venues'))
                ->addPermission('platform.facilities', __('Facilities'))
                ->addPermission('platform.maps', __('Maps'))
                ->addPermission('platform.events', __('Events')),
        ];
    }
}
