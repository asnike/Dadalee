@section('info')
    <div class="price-info">
        <div class="actual-price-info">
            <div class="actual-price-info-header">
                <h4 id="selectedName"></h4>
                <h5 id="selectedAddr"></h5>
                <img src="{{ url('/image/cancel.png') }}" class="btn-close" onclick="Controller.closePriceInfo();" />
            </div>
            <div class="actual-price-info-body">
                <h6>{{ trans('common.tradePrice') }}</h6>
                <div style="margin-bottom:10px;">
                    <span>{{ trans('common.exclusiveSize') }} </span><select style="width:150px;display: inline-block;" name="" class="form-control" id="tradeSize"></select>
                </div>
                <table class="table table-striped table-bordered table-condensed" id="actualPricesTable">
                    <thead>
                    <tr>
                        <th data-field="exclusive_size" data-sortable="true">{{ trans('common.exclusiveSize') }}</th>
                        <th data-field="yearmonth" data-sortable="true">{{ trans('common.tradeYearMonth') }}</th>
                        <th data-field="day" data-sortable="true">{{ trans('common.tradeDay') }}</th>
                        <th data-field="price" data-sortable="true">{{ trans('common.tradePrice') }}</th>
                    </tr>
                    </thead>

                </table>
                <hr>
                <h6>{{ trans('common.rentPrice') }}</h6>
                <div style="margin-bottom:10px;">
                    <span>{{ trans('common.exclusiveSize') }} </span><select style="width:150px;display: inline-block;" name="" class="form-control" id="rentalSize"></select>
                </div>
                <table class="table table-striped table-bordered table-condensed" id="rentalCostsTable">
                    <thead>
                    <tr>
                        <th data-field="exclusive_size" data-sortable="true">{{ trans('common.exclusiveSize') }}</th>
                        <th data-field="yearmonth" data-sortable="true">{{ trans('common.tradeYearMonth') }}</th>
                        <th data-field="day" data-sortable="true">{{ trans('common.tradeDay') }}</th>
                        <th data-field="deposit" data-sortable="true">{{ trans('common.deposit') }}</th>
                        <th data-field="rental_cost" data-sortable="true">{{ trans('common.rentalCost') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop