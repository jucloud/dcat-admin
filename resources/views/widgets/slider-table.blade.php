<span class="{{ $class }}">
    <span class="btn-slider">{!! $button !!}</span>
    <template class="content">
        <div {!! $attributes !!} id="{{ $id }}">
            <div class="slider-panel">
                <div class="slider-header">
                    <h4 class="slider-title">{!! $title !!}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="slider-body">{!! $content !!}</div>
                {!! $footer !!}
            </div>
        </div>
    </template>

    {{-- 事件监听 --}}
    <template class="event">
        {!! $events['shown'] !!}

        @if(!empty($events['load']))
            $t.on('table:loaded', function (e) { {!! $events['load'] !!} });
        @endif
    </template>
</span>

<script init=".{{ $class }}">
    var slider,
        _container = '.slider-content',
        _id,
        _temp,
        _title,
        _event,
        _btnId;

    setId(id);

    function hidden(index) {
        {!! $events['hidden'] !!}

        getLayer(index).find(_container).trigger('dialog:hidden');
    }

    function open(btn) {
        console.log('open');
    }

    function setDataId(obj) {
        if (! obj.attr('data-id')) {
            obj.attr('data-id', id);
        }
    }

    function setId(val) {
        if (! val) return;

        _id = '#' + val;
        _temp = _id + ' .content';
        _title = _id + ' .title';
        _event = _id + ' .event';
        _btnId = _id + ' .btn-slider';
    }

    function initSlider() {

       slider = new Dcat.Slider({
           target: '#{{ $id }}',
       });
       slider.$container.find('.slider-content').append($(_temp).html());
       setTimeout(slider.open.bind(slider), 10);


   }

    function openSlider () {

        console.log('111');

        var slider;

        setId($(this).attr('data-id'));
        setDataId($(this));
        //
        // if (! $(this).attr('layer')) {
        //     open($(this));
        // }
    }

    function getLayer(index) {
        return $('#layui-layer'+index)
    }

    function closeDialog() {
        var index = $(this).attr('layer');

        getLayer(index).find(_container).removeAttr('layer');
        $(_btnId).removeAttr('layer');

        if (index) {
            layer.close(index);
            hidden(index);
        }
    }

    $(_btnId).on('click', function () {

        if (! slider) {
            initSlider()
        }

        slider.toggle();

        return false
    });

</script>
