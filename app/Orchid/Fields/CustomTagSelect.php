<?php

namespace App\Orchid\Fields;

use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Toast;

class CustomTagSelect extends Select
{
    /**
     * The model class for the options list.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Set the model class for the options list.
     *
     * @param string $modelClass
     * @return $this
     */
    public function modelClass(string $modelClass): self
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    /**
     * Build the options list for the select field.
     *
     * @return array
     */
    protected function buildOptions(): array
    {
        $options = parent::buildOptions();

        // Add the Create button to the options list
        $options[] = [
            'key' => '__create__',
            'label' => __('Create new'),
            'type' => 'button',
            'action' => ModalToggle::make(__('Create new'))
                ->icon('icon-plus')
                ->modal('create-modal')
                ->method('createRecord')
                ->async('asyncGetCategories'),
        ];

        return $options;
    }

    public function asyncGetCategories(Request $request)
    {
        $query = $request->get('query');

        $categories = $this->modelClass::query()
            ->where('name', 'LIKE', "%$query%")
            ->get(['id', 'name']);

        $results = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'text' => $category->name,
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Create a new record and add it to the options list.
     *
     * @param string $name
     * @return void
     */
    public function createRecord(string $name)
    {
        // Create a new record
        $record = new $this->modelClass([
            'name' => $name,
        ]);

        // Save the record
        $record->save();

        // Add the record to the options list and select it
        $this->options[] = [
            'key' => $record->id,
            'label' => $name,
        ];

        $this->value([$record->id]);

        Toast::info(__('Record created successfully.'));
    }
}
