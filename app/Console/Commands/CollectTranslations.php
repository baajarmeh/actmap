<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


class CollectTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
	protected $signature = 'translation:collect {locale}';

	/**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect all language translations into separate files';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locale = $this->argument('locale');
		// $translations = json_decode(trans()->getLoader()->load($locale, '*', '*'), true);
		$translations = app('translation.loader')->load($locale, '*', '*');
        // $translations = trans()->getLoader()->load($locale, '*', '*');

        foreach ($translations as $group => $keys) {
            foreach ($keys as $key => $value) {
                File::append("resources/lang/{$locale}/{$group}.php", "'{$key}' => '{$value}'," . PHP_EOL);
            }
        }

        $this->info("Translations for locale '{$locale}' collected successfully.");
    }
}
