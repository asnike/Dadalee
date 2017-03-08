@section('navbarsub')
<div class="navbar-sub">
    <div class="custom-control-panel">
        <div class="visible-xs visible-sm navbar-top text-center">
            {{ trans('common.realestateAdd') }}
        </div>
        <div class="">
            <div class="col-xs-12 col-md-6">

                <form action="" class="search-form" onsubmit="return Controller.onSubmit();" style="margin: 0;">
                    <div class="form-group clearfix">
                        <div class="col-xs-12 col-md-2 form-item"><input type="text" required class="form-control input-sm" id="name" placeholder="{{ trans('common.name') }}" maxlength="50" /></div>
                        <div class="col-xs-12 col-md-4 form-item"><input type="text" required class="form-control input-sm" id="addr" readonly onfocus="daumAddressOpen()" placeholder="{{ trans('common.address') }}" maxlength="255" /></div>
                        <div class="col-xs-12 col-md-2 form-item">
                            <div class="checkbox">
                                <input id="own" class="styled" type="checkbox">
                                <label for="own">
                                    {{ trans('common.own') }}
                                </label>
                            </div>

                        </div>
                        <div class="col-xs-12 col-md-2"><button type="submit" class="btn btn-success btn-sm btn-block">{{ trans('common.realestateAdd') }}</button></div>
                        <div class="col-xs-12 col-md-2"><div class="btn btn-success btn-sm btn-block btn-import-ga">{{ trans('common.goodauctionImport') }}</div></div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@stop