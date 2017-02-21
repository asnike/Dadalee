@section('navbarsub')
<div class="navbar-sub">
    <div class="custom-control-panel">
        <div class="col-md-6">

            <form action="" class="search-form" onsubmit="return Controller.onSubmit();" style="margin: 0;">
                <div class="fomr-group clearfix">
                    <div class="col-md-2"><input type="text" required class="form-control input-sm" id="name" placeholder="{{ trans('common.name') }}" maxlength="50" /></div>
                    <div class="col-md-4"><input type="text" required class="form-control input-sm" id="addr" readonly onfocus="daumAddressOpen()" placeholder="{{ trans('common.address') }}" maxlength="255" /></div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <input id="own" class="styled" type="checkbox">
                            <label for="own">
                                {{ trans('common.own') }}
                            </label>
                        </div>

                    </div>
                    <div class="col-md-2"><button type="submit" class="btn btn-success btn-sm btn-block">{{ trans('common.realestateAdd') }}</button></div>
                </div>
            </form>

        </div>
    </div>
</div>
@stop