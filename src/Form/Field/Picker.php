<?php

namespace Dcat\Admin\Form\Field;

use Illuminate\Support\Str;

class Picker extends Text
{
    protected $view = 'admin::form.picker';

    protected $options = [
        'province'  => '江苏省',
        'city'      => '常州市',
        'district'  => '溧阳市'
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

    /**
     * {@inheritDoc}
     */
    protected function prepareInputValue($value)
    {
        return empty($value) ? 0 : $value;
    }

    /**
     * {@inheritDoc}
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return (int) parent::value();
        }

        return parent::value($value);
    }

    public function render()
    {

        $pickerId = uniqid('picker-', false);

        $this->defaultAttribute('id', $pickerId)
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('name', $this->getElementName())
            ->defaultAttribute('value', $this->value())
            ->defaultAttribute('class', 'hidden '.$this->getElementClassString())
            ->defaultAttribute('data-toggle', 'city-picker')
            ->defaultAttribute('placeholder', $this->placeholder())
            ->defaultAttribute('readonly', 'readonly');

        $this->prepend("<i class='feather icon-globe'></i>");
        // $this->append("<span class='btn btn-primary btn-{$pickerId} shadow-0'><i class='feather icon-chevron-down'></i></span>");

        return parent::render();
    }
}
