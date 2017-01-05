<div class="row">

    <form action="" class="search-form" onsubmit="return Controller.onSubmit();">
        <div class="fomr-group clearfix">
            <div class="col-md-2"><input type="text" required class="form-control" id="name" placeholder="{{ trans('common.name') }}" /></div>
            <div class="col-md-3"><input type="text" required class="form-control" id="addr" readonly onfocus="sample2_execDaumPostcode()" placeholder="{{ trans('common.address') }}" /></div>
            <div class="col-md-2"><input type="text" required class="form-control" id="price" placeholder="{{ trans('common.price') }}" /></div>
            <div class="col-md-2"><input type="text" required class="form-control" id="deposit" placeholder="{{ trans('common.deposit') }}" /></div>
            <div class="col-md-2"><input type="text" class="form-control" id="monthlyfee" placeholder="{{ trans('common.monthlyFee') }}" /></div>
            <div class="col-md-2"><select class="form-control">
                    @for($i = (int)date('Y', strtotime('-60 month', time())), $j = (int)date('Y') ; $i < $j ; $i++)
                        <option value="{{ $i }}">{{ $i.'년' }}</option>
                    @endfor
                </select></div>
            <div class="col-md-1"><select class="form-control">
                    @for($i = 1, $j = 12 ; $i < $j ; $i++)
                        <option value="{{ $i }}">{{ $i.'월' }}</option>
                    @endfor
                </select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary">{{ trans('common.realestateAdd') }}</button></div>
        </div>
    </form>

</div>