<?php

namespace Dcat\Admin\Grid\Displayers;

use Dcat\Admin\Grid\Displayers\AbstractDisplayer;

class Picker extends AbstractDisplayer
{
    public function display()
    {
        return 'getAreaName';
        // return DcatDistpickerHelper::getAreaName($this->value);
    }
}
