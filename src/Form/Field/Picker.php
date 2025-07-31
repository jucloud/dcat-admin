<?php

namespace Dcat\Admin\Form\Field;

use Illuminate\Support\Str;

class Picker extends Text
{
    use CanLoadFields;

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
     * {@inheritdoc}
     */
    public function render()
    {

        $pickerId = uniqid('picker-', false);

        $this->defaultAttribute('id', $pickerId)
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('name', $this->getElementName())
            ->defaultAttribute('value', collect($this->options)->implode('/'))
            ->defaultAttribute('class', ' '.$this->getElementClassString())
            ->defaultAttribute('data-toggle', 'city-picker')
            ->defaultAttribute('placeholder', $this->placeholder())
            ->defaultAttribute('readonly', 'readonly');

        $this->prepend("<i class='feather icon-globe'></i>");

        $this->addVariables([
            'id'            => $pickerId,
            'options'       => json_encode(collect($this->options), JSON_UNESCAPED_UNICODE)
        ]);
        return parent::render();
    }
}
