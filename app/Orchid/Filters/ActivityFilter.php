<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateRange;


class ActivityFilter extends Filter
{
    /**
     * The value delimiter.
     *
     * @var string
     */
    protected static $delimiter = ':';

    /**
     * @var array
     */
    public $parameters = [
        'activity_date',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Activity date');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('activity_date', '>', $this->request->input('activity_date.start'))
            ->where('activity_date', '<', $this->request->input('activity_date.end'));
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            DateRange::make('activity_date')
                ->title($this->name())
                ->value([
                    'start' => $this->request->input('activity_date.start'),
                    'end'   => $this->request->input('activity_date.end'),
                ]),
        ];
    }
}
