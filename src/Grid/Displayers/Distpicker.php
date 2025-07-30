<?php

namespace Dcat\Admin\Grid\Displayers;

use Dcat\Admin\Grid\Displayers\AbstractDisplayer;

class Distpicker extends AbstractDisplayer
{
    public function display()
    {
        return 'getAreaName';
        // return DcatDistpickerHelper::getAreaName($this->value);
    }
}
