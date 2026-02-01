<?php

namespace Dcat\Admin\Form\Field;

use Dcat\Admin\Form\Field;
use Dcat\Admin\Support\Helper;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Exception\RuntimeException;

class KeyValue extends Field
{

    const DEFAULT_FLAG_NAME = '_def_';

    protected $keyType      = 'text';
    protected $keyLabel     = '';
    protected $valueLabel   = '';
    protected $options      = [];
    protected $configs      = [];

    /**
     * Set options.
     *
     * @param  array|\Closure|string  $options
     * @return $this|mixed
     */
    public function options($options = [])
    {
        $this->keyType = 'select';
        
        if ($options instanceof \Closure) {
            $this->options = $options;

            return $this;
        }

        // remote options
        if (is_string($options)) {
            // reload selected
            if (class_exists($options) && in_array(Model::class, class_parents($options))) {
                return $this->model(...func_get_args());
            }

            return $this->loadRemoteOptions(...func_get_args());
        }

        $this->options = Helper::array($options);
        
        return $this;
    }

    /**
     * Load options from current selected resource(s).
     *
     * @param  string  $model
     * @param  string  $idField
     * @param  string  $textField
     * @return $this
     */
    public function model($model, string $idField = 'id', string $textField = 'name')
    {
        if (! class_exists($model)
            || ! in_array(Model::class, class_parents($model))
        ) {
            throw new RuntimeException("[$model] must be a valid model class");
        }

        $this->options = function ($value) use ($model, $idField, $textField) {
            if (empty($value)) {
                return [];
            }

            $resources = [];

            if (is_array($value)) {
                if (Arr::isAssoc($value)) {
                    $resources[] = Arr::get($value, $idField);
                } else {
                    $resources = array_column($value, $idField);
                }
            } else {
                $resources[] = $value;
            }

            return $model::whereIn($idField, $resources)->pluck($textField, $idField)->toArray();
        };

        return $this;
    }

    /**
     * Load options from remote.
     *
     * @param  string  $url
     * @param  array  $parameters
     * @param  array  $options
     * @return $this
     */
    protected function loadRemoteOptions(string $url, array $parameters = [], array $options = [])
    {
        $ajaxOptions = [
            'url' => admin_url($url.'?' . http_build_query($parameters)),
        ];
        
        $ajaxOptions = array_merge($ajaxOptions, $options);
        
        return $this->addVariables(['remoteOptions' => $ajaxOptions]);
    }

    /**
     * Load value from ajax results.
     *
     * @param  string  $url
     * @param $params
     * @return $this
     */
    public function load(string $url, array $parameters = [], array $maps = [])
    {
        $url = admin_url($url);
        return $this->addVariables(['load' => compact('url', 'parameters', 'maps')]);
    }
    
    /**
     * @param  string|array  $key
     * @param  mixed  $value
     * @return $this
     */
    public function addDefaultConfig($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->addDefaultConfig($k, $v);
            }

            return $this;
        }

        if (! isset($this->configs[$key])) {
            $this->configs[$key] = $value;
        }

        return $this;
    }

    protected function formatOptions()
    {
        if ($this->options instanceof \Closure) {
            $this->options = $this->options->bindTo($this->values());

            $this->options(call_user_func($this->options, $this->value(), $this));
        }

        $this->options = array_filter($this->options, 'strlen');
    }

    /**
     * Disable clear button.
     *
     * @return $this
     */
    public function showClearButton()
    {
        return $this->configs('allowClear', true);
    }

    public function setLabel(?string $keyLabel, ?string $valueLabel)
    {
        $this->keyLabel     = $keyLabel;
        $this->valueLabel   = $valueLabel;
        return $this;
    }

    public function setKeyLabel(?string $label)
    {
        $this->keyLabel = $label;

        return $this;
    }

    public function setValueLabel(?string $label)
    {
        $this->valueLabel = $label;

        return $this;
    }

    public function getKeyLabel()
    {
        return $this->keyLabel ?: __('Key');
    }

    public function getKeyType()
    {
        return $this->keyType ?: __('KeyType');
    }

    public function getValueLabel()
    {
        return $this->valueLabel ?: __('Value');
    }

    /**
     * {@inheritdoc}
     */
    public function formatFieldData($data)
    {
        $this->data = $data;

        $value = Helper::array($this->getValueFromData($data, null, $this->value));

        unset($value[static::DEFAULT_FLAG_NAME]);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidator(array $input)
    {
        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        if (! is_string($this->column)) {
            return false;
        }

        $rules = $attributes = [];

        if (! $fieldRules = $this->getRules()) {
            return false;
        }

        if (! Arr::has($input, $this->column)) {
            return false;
        }

        $rules["{$this->column}.keys.*"] = 'distinct';
        $rules["{$this->column}.values.*"] = $fieldRules;
        $attributes["{$this->column}.keys.*"] = $this->getKeyLabel();
        $attributes["{$this->column}.values.*"] = $this->getValueLabel();

        $input = $this->prepareValidatorInput($input);

        return validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    protected function prepareValidatorInput(array $input)
    {
        Arr::forget($input, $this->column.'.'.static::DEFAULT_FLAG_NAME);

        return $input;
    }

    protected function prepareInputValue($value)
    {
        unset($value[static::DEFAULT_FLAG_NAME]);

        if (empty($value)) {
            return [];
        }

        return array_combine($value['keys'], $value['values']);
    }

    public function render()
    {
        $value = $this->value();

        $this->addDefaultConfig([
            'allowClear'  => false,
            'placeholder' => [
                'id'   => '',
                'text' => $this->placeholder(),
            ],
        ]);
        
        $this->formatOptions();
        
        $this->addVariables([
            'count'      => $value ? count($value) : 0,
            'keyType'    => $this->getKeyType(),
            'keyLabel'   => $this->getKeyLabel(),
            'valueLabel' => $this->getValueLabel(),
            
            'options'    => $this->options,
            'configs'    => $this->configs,
        ]);

        $this->attribute('data-value', implode(',', Helper::array($this->value())));

        return parent::render();
    }
}
