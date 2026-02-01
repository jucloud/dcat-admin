@if($keyType == 'select')
@include('admin::scripts.keyvalue')
<script require="@select2?lang={{ config('app.locale') === 'en' ? '' : str_replace('_', '-', config('app.locale')) }}" init="{!! $selector !!}_kv">

    var configs = {!! admin_javascript_json($configs) !!};
    
    @yield('admin.select-load')

    @if(isset($remoteOptions))
    $.ajax({!! admin_javascript_json($remoteOptions) !!}).done(function(data) {
        configs.data = data;
        
        $this.each(function (_, select) {
            select = $(select);

            select.select2(configs);

            var value = select.data('value') + '';

            if (value) {
                select.val(value.split(',')).trigger("change")
            }
        });
    });
    @else
    $this.select2(configs);
    @endif
</script>
@endif

<script init="{!! $selector !!}">
    var index = {{ $count }};
    $this.find('.kv-add').on('click', function () {
        var tpl = $this.find('template').html().replace('{key}', index).replace('{key}', index);
        $this.find('tbody.kv-table').append(tpl);

        index++;
    });

    $this.find('tbody.kv-table').on('click', '.kv-remove', function () {
        $(this).closest('tr').remove();
    });
</script>
