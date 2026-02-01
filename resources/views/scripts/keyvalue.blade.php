@if(isset($load))
{{--load联动--}}
<script once>
    var selector = '{!! $selector !!}_kv';

    $(document).off('change', selector);
    $(document).on('change', selector, function () {

        let params = $.extend({!! json_encode($load['parameters']) !!}, {
            key: $(this).val()
        });
        
        $.ajax("{!! admin_javascript_json($load['url']) !!}" + '?' + $.param(params)).done(function(result) {
            $("[name='" + $(this).attr('name').replace('keys', 'values') + "']").val(result.message);
        });
    });
    $(selector).trigger('change');
</script>
@endif

<script once>
    // on first focus (bubbles up to document), open the menu
    $(document).off('focus', '.select2-selection.select2-selection--single')
        .on('focus', '.select2-selection.select2-selection--single', function (e) {
            $(this).closest(".select2-container").siblings('select:enabled').select2('open');
        });

    // steal focus during close - only capture once and stop propogation
    $(document).off('select2:closing', 'select.select2')
        .on('select2:closing', 'select.select2', function (e) {
            $(e.target).data("select2").$selection.one('focus focusin', function (e) {
                e.stopPropagation();
            });
        });
</script>
