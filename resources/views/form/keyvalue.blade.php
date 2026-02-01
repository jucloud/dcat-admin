<style>
    td .form-group {margin-bottom: 7px !important;}
    .kv-item {padding-left: 1rem !important; width: 45%;}
    .kv-item .col-sm-12 {padding-left:0;}
    .kv-remove, .kv-add {margin-top:8px;}
</style>
@if($label!=false)
<div class="row">
    <div class="{{$viewClass['label']}}"><h4 class="pull-right">{!! $label !!}</h4></div>
    <div class="{{$viewClass['field']}}"></div>
</div>
<hr style="margin-top: 0px;">
@endif

<div class="{{$viewClass['form-group']}} {{ $class }}">
    <div class="col-md-12">
        <span name="{{$name}}"></span>
        <input name="{{ $name }}[{{ \Dcat\Admin\Form\Field\KeyValue::DEFAULT_FLAG_NAME }}]" type="hidden" />

        <div class="help-block with-errors"></div>

        <table class="table table-hover">
            <thead>
            <tr>
                <th class="kv-item">{!! $keyLabel !!}</th>
                <th class="kv-item">{!! $valueLabel !!}</th>
                <th style="width: 50px;"></th>
            </tr>
            </thead>
            <tbody class="kv-table">
                @foreach(($value ?: []) as $k => $v)
                <tr>
                    <td class="kv-item">
                        @if($keyType == 'select')
                        <div class="form-group">
                            <div class="help-block with-errors"></div>
                            <select class="form-control {{$class}}_kv" style="width: 100%;" name="{{ $name }}[keys][{{ $loop->index }}]" {!! $attributes !!} >
                                <option value=""></option>
                                @foreach($options as $select => $option)
                                    <option value="{{$select}}" {{ Dcat\Admin\Support\Helper::equal($select, $k) ?'selected':'' }}>{{$option}}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="help-block with-errors"></div>
                                <input name="{{ $name }}[keys][{{ $loop->index }}]" value="{{ $k }}" class="form-control" required/>
                            </div>
                        </div>
                        @endif
                    </td>
                    <td class="kv-item">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="help-block with-errors"></div>
                                <input name="{{ $name }}[values][{{ $loop->index }}]" value="{{ $v }}" class="form-control" {!! $attributes !!}/>
                            </div>
                        </div>
                    </td>

                    <td class="form-group kv-action">
                        <div>
                            <div class="kv-remove btn btn-white btn-sm pull-right">
                                <i class="feather icon-trash"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <div class="kv-add btn btn-primary btn-outline btn-sm pull-right">
                        <i class="feather icon-plus"></i>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

    <template>
        <tr>
            <td class="kv-item">
                @if($keyType == 'select')
                <div class="form-group">
                    <div class="help-block with-errors"></div>
                    <select class="form-control {{$class}}_kv" style="width: 100%;" name="{{ $name }}[keys][{key}]" >
                        <option value=""></option>
                        @foreach($options as $select => $option)
                            <option value="{{$select}}">{{$option}}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="help-block with-errors"></div>
                        <input name="{{ $name }}[keys][{key}]" class="form-control" required/>
                    </div>
                </div>
                @endif
            </td>
            <td class="kv-item">
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="help-block with-errors"></div>
                        <input name="{{ $name }}[values][{key}]" class="form-control" {!! $attributes !!}/>
                    </div>
                </div>
            </td>
            <td class="form-group">
                <div>
                    <div class="kv-remove btn btn-white btn-sm pull-right">
                        <i class="feather icon-trash"></i>
                    </div>
                </div>
            </td>
        </tr>
    </template>
</div>

@include('admin::form.keyvalue-script')