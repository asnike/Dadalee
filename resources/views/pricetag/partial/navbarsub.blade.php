@section('navbarsub')
<div class="navbar-sub">
    <div class="custom-control-panel">
        <div class="col-md-10">

            <form action="" class="search-form" onsubmit="return Controller.onSubmit();" style="margin: 0;" name="priceTagForm">
                <div class="fomr-group clearfix">
                    <div class="col-md-1"><input type="text" required class="form-control input-sm" required name="price" id="price" placeholder="{{ trans('common.price') }}" maxlength="20" /></div>
                    <div class="col-md-1"><input type="text" required class="form-control input-sm" name="deposit" id="deposit"  placeholder="{{ trans('common.deposit') }}" maxlength="20" /></div>
                    <div class="col-md-1"><input type="text" required class="form-control input-sm" name="rental_cost" id="rental_cost"  placeholder="{{ trans('common.rentalCost') }}" maxlength="20" /></div>
                    <div class="col-md-3"><input type="text" required class="form-control input-sm" required name="addr" id="addr" readonly onfocus="daumAddressOpen()" placeholder="{{ trans('common.address') }}" maxlength="255" /></div>
                    <div class="col-md-1"><input type="text" class="form-control input-sm" name="floor" id="floor" placeholder="{{ trans('common.floor') }}" maxlength="2" /></div>
                    <div class="col-md-1"><input type="text" class="form-control input-sm" name="completed_at" id="completed_at"  placeholder="{{ trans('common.completed_at') }}" maxlength="20" /></div>
                    <div class="col-md-2"><input type="text" class="form-control input-sm" name="reported_at" id="reported_at"  placeholder="{{ trans('common.reported_at') }}" maxlength="20" /></div>
                    <div class="col-md-1"><button type="submit" class="btn btn-success btn-sm btn-block">{{ trans('common.realestateAdd') }}</button></div>
                </div>
            </form>

        </div>
    </div>
</div>
@stop