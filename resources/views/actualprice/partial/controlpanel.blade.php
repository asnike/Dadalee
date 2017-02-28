@section('mapcontrol')
<div class="map-control-panel" style="background: #fff;border-radius: 7px; border:1px solid #ddd;width: 400px; position: absolute; top:65px;left:15px; z-index: 101;padding:15px;">
    <div class="input-group easy-autocomplete">
        <input type="text" class="form-control" name="address" placeholder="{{ trans('common.address') }}">
        <div class="input-group-btn"><div class="btn btn-success btn-go"><i class="fa fa-search"></i></div></div>
    </div>
</div>
@stop