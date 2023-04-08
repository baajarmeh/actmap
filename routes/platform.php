<?php

declare(strict_types=1);

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

use App\Orchid\Screens\Category\CategoryListScreen;
use App\Orchid\Screens\Category\CategoryEditScreen;

use App\Orchid\Screens\Facility\FacilityListScreen;
use App\Orchid\Screens\Facility\FacilityEditScreen;

use App\Orchid\Screens\Map\MapListScreen;
use App\Orchid\Screens\Map\MapEditScreen;

use App\Orchid\Screens\Venue\VenueListScreen;
use App\Orchid\Screens\Venue\VenueEditScreen;

use App\Orchid\Screens\Event\EventListScreen;
use App\Orchid\Screens\Event\EventEditScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/', PlatformScreen::class)
    ->name('platform.index');

Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

Route::screen('categories', CategoryListScreen::class)
    ->name('platform.category');
Route::screen('categories/create', CategoryEditScreen::class)
    ->name('platform.category.create');
Route::screen('categories/{category}/edit', CategoryEditScreen::class)
    ->name('platform.category.edit')
    /*->breadcrumbs(fn (Trail $trail, $category) => $trail
        ->parent('platform.category')
        ->push($category->name, route('platform.category.edit', $category)))*/;

Route::screen('facilities', FacilityListScreen::class)
    ->name('platform.facility');
Route::screen('facilities/create', FacilityEditScreen::class)
    ->name('platform.facility.create');
Route::screen('facilities/{facility}/edit', FacilityEditScreen::class)
    ->name('platform.facility.edit')
    /*->breadcrumbs(fn (Trail $trail, $facility) => $trail
        ->parent('platform.facility')
        ->push($facility->name, route('platform.facility.edit', $facility)))*/;

Route::screen('maps', MapListScreen::class)
    ->name('platform.map');
Route::screen('maps/create', MapEditScreen::class)
    ->name('platform.map.create');
Route::screen('maps/{map}/edit', MapEditScreen::class)
    ->name('platform.map.edit')
    /*->breadcrumbs(fn (Trail $trail, $map) => $trail
        ->parent('platform.map')
        ->push($map->name, route('platform.map.edit', $map)))*/;

Route::screen('venues', VenueListScreen::class)
    ->name('platform.venue');
Route::screen('venues/create', VenueEditScreen::class)
    ->name('platform.venue.create');
Route::screen('venues/{venue}/edit', VenueEditScreen::class)
    ->name('platform.venue.edit')
    /*->breadcrumbs(fn (Trail $trail, $venue) => $trail
        ->parent('platform.venue')
        ->push($venue->name, route('platform.venue.edit', $venue)))*/;

Route::screen('events', EventListScreen::class)
    ->name('platform.event');
Route::screen('events/create', EventEditScreen::class)
    ->name('platform.event.create');
Route::screen('events/{event}/edit', EventEditScreen::class)
    ->name('platform.event.edit')
     /*->breadcrumbs(fn (Trail $trail, $event) => $trail
        ->parent('platform.event')
        ->push($event->name, route('platform.event.edit', $event)))*/;

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));
