<div class="row">

    <form action="" class="search-form" onsubmit="return Controller.onSubmit();">
        <div class="fomr-group clearfix">
            <div class="col-md-2"><input type="text" required class="form-control" id="name" placeholder="{{ trans('common.name') }}" /></div>
            <div class="col-md-5"><input type="text" required class="form-control" id="addr" readonly onfocus="sample2_execDaumPostcode()" placeholder="{{ trans('common.address') }}" /></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary">{{ trans('common.realestateAdd') }}</button></div>
        </div>
    </form>

</div>