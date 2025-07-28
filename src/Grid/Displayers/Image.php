<?php

namespace Dcat\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;

class Image extends AbstractDisplayer
{
    public function display($server = '', $size = 200, $data = [])
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        $data = collect($data)->put('key', $this->getKey());

        return collect((array) $this->value)->filter()->map(function ($path, $index) use ($server, $size, $data) {
            if (url()->isValidUrl($path) || mb_strpos($path, 'data:image') === 0) {
                $src = $path;
            } elseif ($server) {
                $src = rtrim($server, '/').'/'.ltrim($path, '/');
            } else {
                $src = Storage::disk(config('admin.upload.disk'))->url($path);
            }

            $data->put('index', $index);

            return "<img data-action='preview-img' src='$src' style='max-width:{$size}px;max-height:{$size}px;' class='img img-thumbnail' data-info='{$data->sort()->toJson()}' />";
        })->implode('&nbsp;');
    }
}
