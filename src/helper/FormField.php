<?php namespace processdrive\laravelform\helper;

use Form, Config;

class FormField {

    /**
     * Instance
     *
     * @var Way\Form\FormField
     */
    protected static $instance;

    /**
     * Make the form field
     *
     * @param string $name
     * @param array $args
     */
    public function make($name, array $args)
    {
        $wrapper = $this->createWrapper();
        $field = $this->createField($name, $args);

        return str_replace('{{FIELD}}', $field, $wrapper);
    }

    /**
     * Prepare the wrapping container for
     * each field.
     */
    protected function createWrapper()
    {
        $wrapper = Config::get('form_field_config.wrapper');
        $wrapperClass = Config::get('form_field_config.wrapperClass');

        return "<$wrapper class='$wrapperClass'>{{FIELD}}</$wrapper>";
    }

    /**
     * Create the form field
     *
     * @param string $name
     * @param array $args
     */
    protected function createField($name, $args)
    {
        // If the user specifies an input type,
        // we'll just use that. Otherwise, we'll take
        // a best guess approach, falling back to text
        $type = array_get($args, 'type') ?: $this->guessInputType($name);

        // Next, again, the user can specify a custom label
        // Or, we'll prettify the provided name as best as possible.
        $label = array_get($args, 'label') ?: ($this->prettifyFieldName($name) . ': ');

        // We'll default to Bootstrap-friendly input class names
        $args = array_merge(['class' => Config::get('form_field_config.inputClass')], $args);

        // Now, let's build and return the form field HTML
        $field = Form::label($name, $label);

        if ($type == 'password')
        {
            $field .= Form::password($name, $args);
        }
        else
        {
            $field .= Form::$type($name, null, $args);
        }

        return $field;
    }

    /**
     * Provide a best guess for what the
     * input type should be.
     *
     * @param string $name
     */
    protected function guessInputType($name)
    {
        return array_get(Config::get('form_field_config.commonInputsLookup'), $name) ?: 'text';
    }

    /**
     * Clean up the field name for the label
     *
     * @param string $name
     */
    protected function prettifyFieldName($name)
    {
        return ucwords(preg_replace('/(?<=\w)(?=[A-Z])/', " $1", $name));
    }

    public static function __callStatic($name, $args)
    {
        $args = empty($args) ? [] : $args[0];

        $instance = static::$instance;
        if ( ! $instance) $instance = static::$instance = new static;

        return $instance->make($name, $args);
    }

}
