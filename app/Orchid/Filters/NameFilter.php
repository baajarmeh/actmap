<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;


class NameFilter extends Filter
{
    /**
     * @return string
     */
    public function name(): string
    {
        return __('Name');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['name'];
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('name', 'like', '%'.$this->request->get('name').'%');
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Input::make('name')
                ->placeholder(__('Search by name'))
                ->value($this->request->get('name'))
                ->title(__('Name')),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->name().': '.$this->request->get('name');
    }
}
