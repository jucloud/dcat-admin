<?php

namespace Dcat\Admin\Widgets;

use Closure;
use Dcat\Admin\Admin;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Grid\LazyRenderable as LazyGrid;
use Dcat\Admin\Support\Helper;
use Dcat\Admin\Traits\InteractsWithRenderApi;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

class Slider extends Widget
{
    // use InteractsWithRenderApi;

    protected $view = 'admin::widgets.slider-table';

    /**
     * @var string|Closure|Renderable
     */
    protected $id;

    /**
     * @var string|Closure|Renderable
     */
    protected $title;

    /**
     * @var string|Closure|Renderable
     */
    protected $content;

    /**
     * @var string|Closure|Renderable
     */
    protected $footer;

    /**
     * @var string|Closure|Renderable
     */
    protected $button;

    /**
     * @var string
     */
    protected $size = 'xl';

    /**
     * @var string
     */
    protected $centered = '';

    /**
     * @var string
     */
    protected $scrollable = '';

    /**
     * @var array
     */
    protected $events = ['shown' => null, 'hidden' => null, 'toggle' => null, 'destroy' => null, 'load' => null];

    /**
     * @var int
     */
    protected $delay = 10;

    /**
     * @var bool
     */
    protected $join = false;

    /**
     * Modal constructor.
     *
     * @param  string|Closure|Renderable  $title
     * @param  string|Closure|Renderable|LazyRenderable  $content
     */
    public function __construct($title = null, LazyRenderable $content = null)
    {
        $this->id('slider-' . Str::random(10));
        $this->title($title);
        $this->content($content);

        $this->elementClass = 'slider-container';

        $this->class('slider-content');
    }

    /**
     * 设置loading效果延迟时间.
     *
     * @param  int  $delay
     * @return $this
     */
    public function delay(int $delay)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * 设置按钮.
     *
     * @param  string|Closure|Renderable  $button
     * @return $this
     */
    public function button($button)
    {
        $this->button = $button;
        return $this;
    }

    /**
     * 设置弹窗标题.
     *
     * @param  string|Closure|Renderable  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 设置弹窗内容.
     *
     * @param  string|Closure|Renderable|LazyRenderable  $content
     * @return $this
     */
    public function content(?LazyRenderable $content)
    {
        if (! $content) {
            return $this;
        }

        if ($content instanceof LazyGrid) {

            $this->content = LazyTable::make()
                ->from($content)
                ->simple()
                ->load(false);

            $this->onShown("target.find('{$table->getElementSelector()}').trigger('table:load')");
        }

        if ($content instanceof LazyRenderable) {
            $this->setRenderable($content);
        } else {
            $this->content = $content;
        }

        return $this;
    }

    /**
     * 设置是否返回弹窗HTML.
     *
     * @param  bool  $value
     * @return $this
     */
    public function join(bool $value = true)
    {
        $this->join = $value;
        return $this;
    }

    /**
     * 设置弹窗底部内容.
     *
     * @param  string|Closure|Renderable|LazyRenderable  $footer
     * @return $this
     */
    public function footer($footer)
    {
        $this->footer = $footer;
        return $this;
    }

    /**
     * 监听弹窗打开事件.
     *
     * @param  string  $script
     * @return $this
     */
    public function onShown(string $script)
    {
        $this->events['open'] .= ';'.$script;

        return $this;
    }

    /**
     * 监听弹窗隐藏事件.
     *
     * @param  string  $script
     * @return $this
     */
    public function onHidden(string $script)
    {
        $this->events['close'] .= ';'.$script;

        return $this;
    }

    /**
     * 监听弹窗隐藏事件.
     *
     * @param  string  $script
     * @return $this
     */
    public function onToggle(string $script)
    {
        $this->events['toggle'] .= ';'.$script;

        return $this;
    }

    /**
     * 监听弹窗隐藏事件.
     *
     * @param  string  $script
     * @return $this
     */
    public function onDestroy(string $script)
    {
        $this->events['destroy'] .= ';'.$script;

        return $this;
    }

    /**
     * 监听表格加载完毕事件.
     *
     * @param  string  $script
     * @return $this
     */
    public function onLoad(string $script)
    {
        $this->events['load'] .= ';'.$script;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addVariables([
            'id'        => $this->id(),
            'title'     => $this->title,
            'button'    => $this->renderButton(),
            'content'   => $this->content,
            'footer'    => $this->renderFooter(),
            'events'    => $this->events,
        ]);

        return parent::render();
    }

    protected function renderTitle()
    {
        return Helper::render($this->title);
    }

    protected function renderContent()
    {
        return $this->content->render();
        // return Helper::render($this->content);
    }

    protected function renderFooter()
    {
        $footer = Helper::render($this->footer);

        if (! $footer) {
            return;
        }

        return <<<HTML
<div class="slider-footer">{$footer}</div>
HTML;
    }


    protected function renderButton()
    {
        if (! $this->button) {
            return;
        }

        $button = Helper::render($this->button);

        // 如果没有HTML标签则添加一个 a 标签
        if (! preg_match('/(\<\/[\d\w]+\s*\>+)/i', $button)) {
            $button = "<a href=\"javascript:void(0)\">{$button}</a>";
        }

        return $button;
    }
}
