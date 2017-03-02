@section('mapcontrol')
<div class="map-control-panel" style="background: #fff;border-radius: 7px; border:1px solid #ddd;width: 400px; position: absolute; top:65px;left:15px; z-index: 101;padding:15px;">
    <div class="input-group">
        <input type="text" class="form-control" name="address" placeholder="{{ trans('common.address') }}"
            style="border-bottom-left-radius: 0;"
        >
        <div class="input-group-btn"><div class="btn btn-success btn-go" style="border-bottom-right-radius: 0;"><i class="fa fa-search"></i></div></div>
    </div>
    <div class="input-group" style="width: 100%;">
        <select name="type" class="form-control"
                style="width:122px;border-top-left-radius: 0; border-top:0;border-right:0;">
            <option value="0">{{ trans('common.total') }}</option>
            <option value="1">{{ trans('common.jeonse') }}</option>
            <option value="2">{{ trans('common.wolse') }}</option>
        </select>
        <select name="size" class="form-control"
                style="width:122px;border-top-left-radius: 0; border-top-right-radius: 0;border-top:0;border-right:0;">
            <option value="0">{{ trans('common.total') }}</option>
            <option value="59">{{ trans('common.size59under') }}</option>
            <option value="60">{{ trans('common.size60over') }}</option>
        </select>
        <select name="year" class="form-control"
                style="width:124px;border-top-right-radius: 0;border-top:0;">
            <option value="0">{{ trans('common.total') }}</option>
            <option value="1999">{{ trans('common.oldHouse') }}</option>
            <option value="2010">{{ trans('common.midHouse') }}</option>
            <option value="latest">{{ trans('common.newHouse') }}</option>
        </select>

    </div>
</div>
@stop