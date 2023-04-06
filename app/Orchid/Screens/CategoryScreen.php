<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;


class CategoryScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => Category::latest()->get();
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Categories');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make(__('Add Category'))
                ->modal('categoryModal')
                ->method('create')
                ->icon('plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('categories', [
                TD::make('name'),

                TD::make(__('Actions'))
                    ->alignRight()
                    ->render(function (Category $category) {
                        return Button::make(__('Delete Task'))
                            ->confirm(__('After deleting, the task will be gone forever.'))
                            ->method('delete', ['category' => $category->id]);
                    }),
            ]),

            Layout::modal('categoryModal', Layout::rows([
                Input::make('category.name')
                    ->title(__('Name'))
                    ->placeholder(__('Enter category name')),
            ]))
                ->title(__('Create Category'))
                ->applyButton(__('Add Category')),

        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        // Validate form data, save task to database, etc.
        $request->validate([
            'category.name' => 'required|max:100',
        ]);

        $category = new Category();
        $category->name = $request->input('category.name');
        $category->save();
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    public function delete(Category $category)
    {
        $category->delete();
    }
}
