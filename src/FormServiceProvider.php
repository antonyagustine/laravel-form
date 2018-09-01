<?php namespace processdrive\LaravelForm;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FormServiceProvider extends ServiceProvider {

    public function register() {}

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/form_field_config.php' => base_path('config/form_field_config.php'),
        ], 'formField');

        AliasLoader::getInstance()->alias('FormField', '\processdrive\form\helper\FormField');
    }
}
