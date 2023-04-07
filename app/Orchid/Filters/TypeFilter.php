<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;


class TypeFilter extends Filter
{
    /**
     * @return string
     */
    public function name(): string
    {
        return __('Type');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['type'];
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('type', $this->request->get('type'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('type')
                ->placeholder(__('Search by type'))
                ->options([
                    'room' => __('Room'),
                    'place' => __('Place'),
                ])
                ->empty()
                ->value($this->request->get('type'))
                ->title(__('Type')),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->name().': '.$this->request->get('type');
    }
}
