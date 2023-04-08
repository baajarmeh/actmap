<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'categories';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn (Category $category) => ModalToggle::make($category->name)
                        ->modal('asyncEditCategoryModal')
                        ->method('saveCategory')
                        ->title(__('Edit category'))
                        ->icon('pencil')
                        ->asyncParameters([
                            'category' => $category->id,
                        ])),
    
            TD::make('type', __('Type'))
                ->render(fn (Category $category) => $category->type)
                ->sort(),
    
            TD::make('created_at', __('Created'))
                ->render(fn (Category $category) => $category->created_at->toDateTimeString())
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Category $category) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.category.edit', $category->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(__('Are you sure you want to remove this category?'))
                            ->method('remove', [
                                'id' => $category->id,
                            ]),
                    ])),
        ];
    }
}
