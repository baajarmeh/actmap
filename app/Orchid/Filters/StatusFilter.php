<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;


class StatusFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'status1',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Status');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        switch ($this->request->get('status1')) {
            case 'active':
                return $builder->active(1);
            case 'disabled':
                return $builder->active(0);
            default:
                return $builder;
        }
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            CheckBox::make('status1')
                ->value($this->request->get('status1'))
                ->options([
                    'active' => __('Published'),
                    'disabled' => __('Draft'),
                ])
                ->empty()
                ->title(__('Status'))
                ->autocomplete('off'),
        ];
    }
}
