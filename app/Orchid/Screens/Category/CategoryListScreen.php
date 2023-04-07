<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Category;

use App\Orchid\Layouts\Category\CategoryListLayout;
use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryEditLayout;
use App\Orchid\Layouts\Category\CategoryFiltersLayout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class CategoryListScreen extends Screen
{

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Category');
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return __('All categories');
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => Category::filters(CategoryFiltersLayout::class)
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
                ->href(route('platform.category.create')),
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
            CategoryFiltersLayout::class,
            CategoryListLayout::class,

            Layout::modal('asyncEditCategoryModal', CategoryEditLayout::class)
                ->async('asyncGetCategory'),
        ];
    }

    /**
     * @param √
     *
     * @return array
     */
    public function asyncGetCategory(Category $category): iterable
    {
        return [
            'category' => $category,
        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Category::findOrFail($request->get('id'))->delete();

        Toast::info(__('Category was removed'));
    }

    /**
     * @param Request $request
     * @param Category $category
     */
    public function saveCategory(Request $request, Category $category): void
    {
        $request->validate([
            'category.name' => ['required', 'string'],
            'category.type' => ['required', Rule::in(['room', 'place'])],
        ]);

        $category->fill($request->input('category'))->save();

        Toast::info(__('Category was saved.'));
    }
}
