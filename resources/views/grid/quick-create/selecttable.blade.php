<div class="input-group quick-form-field">
    <div class="select-resource">
        <div class="input-group input-group-sm">
            <div {!! $attributes !!}>
                <span class="default-text" style="opacity:0.75">{{ $placeholder }}</span>
                <span class="option d-none"></span>

                @if(! $disabled)
                    <input name="{{ $name }}" type="hidden" value="{{ implode(',', Dcat\Admin\Support\Helper::array($value)) }}" />
                @endif
            </div>

            <div class="input-group-append">
                {!! $dialog !!}
            </div>
        </div>
    </div>
</div>

<script require="@select-table" init="{!! $selector !!}">
    var dialogId = $this.parent().find('{!! $dialogSelector !!}').attr('id');
    var $input = $(this).find('input');

    Dcat.grid.SelectTable({
        dialog: '[data-id="' + dialogId + '"]',
        container: $this,
        input: $input,
        @if(isset($max))
        multiple: true,
        max: {{ $max }},
        @endif
        values: {!! json_encode($options) !!},
    });

    @if(! empty($loads))
    var fields = '{!! $loads['fields'] !!}'.split('^');
    var urls = '{!! $loads['urls'] !!}'.split('^');

    $input.on('change', function () {
        var values = this.value;

        Dcat.helpers.loadFields(this, {
            group: '.fields-group',
            urls: urls,
            fields: fields,
            textField: "{{ $loads['textField'] }}",
            idField: "{{ $loads['idField'] }}",
            values: values,
        });
    }).trigger('change');
    @endif
</script>