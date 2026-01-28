<?php

namespace Dcat\Admin\Form\Field;

use Illuminate\Support\Str;

class Picker extends Text
{
    use CanLoadFields;

    protected $view = 'admin::form.picker';

    protected $configs = [
        'level' => 'district',
        'responsive' => false,
    ];

    /**
     * @var array
     */
    protected static $js = [
        '@admin/dcat/plugins/picker/data.js',
        '@admin/dcat/plugins/picker/picker.js',
    ];

    /**
     * @var array
     */
    protected static $css = [
        '@admin/dcat/plugins/picker/css/picker.css'
    ];

    public function __construct($column, $arguments = []) {
        $this->configs = collect($this->configs);

        parent::__construct($column, $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $pickerId = uniqid('picker-', false);

        $this->configs();

        $this->defaultAttribute('id', $pickerId)
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('name', $this->getElementName())
            ->defaultAttribute('class', 'hidden '. $this->getElementClassString())
            ->defaultAttribute('placeholder', $this->placeholder())
            ->defaultAttribute('data-toggle', 'city-picker')
            ->defaultAttribute('readonly', 'readonly');

        $this->prepend("<i class='feather icon-globe'></i>");
        
        $this->addVariables([
            'id'            => $pickerId,
            'configs'       => $this->configs->toJson()
        ]);

        return parent::render();
    }

    /**
     * Set level value of number field.
     * province: Only province, city: province + city, district: province + city + district
     * @param  string  $value
     * @return $this
     */
    public function level($value = 'district')
    {
        $this->configs->put('level', $value);

        return $this;
    }

    /**
     * Set responsive value of bool field.
     * make the drop down and mask span responsive on width.
     * @param  bool  $value Default: `false`
     * @return $this
     */
    public function responsive($value = false)
    {
        $this->configs->put('responsive', $value);

        return $this;
    }

    /**
     * Set placeholder value of string field.
     * Show placeholder (with an `<option>` element).
     * @param  string  $value
     * @return $this
     */
    public function placeholder($placeholder = null)
    {
        if ($placeholder === null) {
            return $this->placeholder ? : '请选择省 / 市 / 区';
        }

        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Set config.
     *
     * @param  array|\Closure  $configs
     * @return $this
     */
    public function configs($configs = [])
    {
        $this->configs = $this->configs->merge($configs);

        return $this;
    }
}
