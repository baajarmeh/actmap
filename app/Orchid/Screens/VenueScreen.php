<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Orchid\Screen\Screen;

class VenueScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'rooms' => Category::filters()->defaultSort('id', 'desc')->all(),
            'places' => Category::filters()->defaultSort('id', 'desc')->all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Venues';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [];
    }
}

// Select::make('user')
//     ->fromModel(User::class, 'email');

// Select::make('user')
//     ->fromQuery(User::where('balance', '!=', '0'), 'email');

// Select::make('select')
//     ->options([
//         'index'   => 'Index',
//         'noindex' => 'No index',
//     ])
//     ->title('Select tags')
//     ->help('Allow search bots to index');

// For array
// Select::make('user')
//     ->options([
//         1  => 'Option 1',
//         2  => 'Option 2',
//     ])
//     ->empty('No select');

// // For model
// Select::make('user')
//     ->fromModel(User::class, 'name')
//     ->empty('No select');

// Select::make('user')
//     ->empty('No select', 0)
//     ->options([
//         1  => 'Option 1',
//         2  => 'Option 2',
//     ]);

// Relation::make('idea')
//     ->fromModel(Idea::class, 'name')
//     ->title('Choose your idea');

// Relation::make('ideas.')
//     ->fromModel(Idea::class, 'name')
//     ->multiple()
//     ->title('Choose your ideas');

// class Idea extends Model
// {

//     /**
//      * @param Builder $query
//      *
//      * @return Builder
//      */
//     public function scopeActive(Builder $query)
//     {
//         return $query->where('active', true);
//     }
// }
// Relation::make('idea')
//     ->fromModel(Idea::class, 'name')
//     ->applyScope('active')
//     ->title('Choose your idea');
// You can also pass additional parameters to the method:

// Relation::make('idea')
//     ->fromModel(Idea::class, 'name')
//     ->applyScope('status', 'active')
//     ->title('Choose your idea');
// You can add one or several fields, which will be additionally searched for:

// Relation::make('idea')
//      ->fromModel(Idea::class, 'name')
//      ->searchColumns('author', 'description')
//      ->title('Choose your idea');


// public function getFullAttribute(): string
// {
//     return $this->attributes['name'] . ' (' . $this->attributes['email'] . ')';
// }
// }
// To indicate the displayed field you must:

// Relation::make('users.')
// ->fromModel(User::class, 'name')
// ->displayAppend('full')
// ->multiple()
// ->title('Select users');


// DropDown::make()
//     ->icon('options-vertical')
//     ->list([
//         Link::make(__('Edit'))
//             ->route('platform.users.edit', $user->id)
//             ->icon('pencil'),
//         Button::make(__('Delete'))
//             ->method('remove')
//             ->icon('trash')
//             ->confirm(__('Are you sure you want to delete the user?'))
//             ->parameters([
//                 'id' => $user->id,
//             ]),
//     ]);


// TD::make('color')->filter(TD::FILTER_SELECT, ['red'=>'Red', 'green'=>'Green']);
// TD::make('last_name')->width('100px');
// TD::make('full_name')
//     ->render(function ($user) {
//         return e($user->first_name) . ' ' . e($user->last_name);
//     });
// use Orchid\Screen\Actions\Link;

// TD::make()
//         ->render(function ($user) {
//             return Link::make($user->last_name)
//                    ->route('platform.user.edit', $user);
//         });
//         use Orchid\Screen\Actions\Link;
//         use Orchid\Screen\Fields\Group;

//      TD::make()
//             ->render(function ($user) {
//                 return Group::make([
//                     Link::make('Show')->route('platform.user.show', $user),
//                     Link::make('Edit')->route('platform.user.edit', $user),
//                 ]);
//             });
//     TD::make()
//             ->render(function (User $user){
//                 return CheckBox::make('users[]')
//                     ->value($user->id)
//                     ->placeholder($user->name)
//                     ->checked(false);
//         });
// use App\View\CompochangingOrderShortInformation;

// TD::make('status')->component(OrderShortInformation::class);
// public function __construct(Application $application, Order $order, int $limit = 300)
// {
//     $this->order = $order;
//     // ...
// }
// Other additional arguments, for example, limit. You can specify in the following way:

// TD::make('status')->component(OrderShortInformation::class, [
//     'limit' => 100
// ]);

// Layout::modal('exampleModals', [
//     Layout::rows([]),
// ])
//     ->withoutApplyButton()
//     ->withoutCloseButton();

// use Orchid\Screen\Layouts\Modal;

// Layout::modal('exampleModals', [
//     Layout::rows([]),
// ])
//     ->type(Modal::TYPE_RIGHT);

// Layout::modal('asyncModal', [
//     ExampleRow::class,
// ])->async('asyncGetData');
// All asynchronous screen methods start with the async prefix:

// /**
//  * @return array
//  */
// public function asyncGetData(string $welcome): array
// {
//     return [
//         'welcome' => $welcome,
//     ];
// }

// https://orchid.software/en/docs/custom-template/
