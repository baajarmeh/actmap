<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryEditLayout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryEditScreen extends Screen
{
    /**
     * @var Category
     */
    public $category;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @param Category $category
     *
     * @return array
     */
    public function query(Category $category): iterable
    {
        return [
            'category' => $category,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->category->exists ? __('Edit Category') : __('Create Category');
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
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Are you sure you want to remove this category?'))
                ->method('remove')
                ->canSee($this->category->exists),

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
            Layout::block(CategoryEditLayout::class)
                ->title(__('Category Information'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->canSee($this->category->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Category $category
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Category $category, Request $request)
    {
        $request->validate([
            'category.name' => ['required', 'string'],
            'category.type' => ['required', Rule::in(['room', 'place'])],
        ]);

        $category->fill($request->input('category'))->save();

        Toast::info(__('Category was saved.'));

        return redirect()->route('platform.category');
    }

    /**
     * @param Category $category
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Category $category)
    {
        $category->delete();

        Toast::info(__('Category was removed'));

        return redirect()->route('platform.categories');
    }
}
