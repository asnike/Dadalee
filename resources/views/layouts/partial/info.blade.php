@section('info')
    <div class="price-info {{ $type=='actualprice' ? 'actual-prices':'' }}">
        <div class="actual-price-info">
            <div class="actual-price-info-header">
                <h4 id="selectedName"></h4>
                <h5 id="selectedAddr"></h5>
                <img src="{{ url('/image/cancel.png') }}" class="btn-close" onclick="Controller.closePriceInfo();" />
            </div>
            <div class="actual-price-info-body">
                @if($type=='pricetag' || $type=='realestate')
                    <h4>{{ trans('common.myLog') }}</h4>
                    <div style="margin-bottom:10px;">
                        <span>{{ trans('common.exclusiveSize') }} </span><select style="width:150px;display: inline-block;" name="" class="form-control select2" id="myLogSize"></select>
                    </div>
                    <table class="table table-striped table-bordered table-condensed" id="myLogTable">
                        <thead>
                        <tr>
                            <th data-field="exclusive_size" data-sortable="false">{{ trans('common.exclusiveSize') }}</th>
                            <th data-field="price" data-sortable="false">{{ trans('common.tradePrice') }}</th>
                            <th data-field="deposit" data-sortable="false">{{ trans('common.deposit') }}</th>
                            <th data-field="rental_cost" data-sortable="false">{{ trans('common.rentalCost') }}</th>
                            <th data-field="floor" data-sortable="false">{{ trans('common.floor') }}</th>
                            <th data-field="reported_at" data-sortable="true">{{ trans('common.reportedAt') }}</th>
                        </tr>
                        </thead>

                    </table>
                    <hr>
                @endif
                <h4>국토교통부 실거래가</h4>
                <div class="avg-title">같은동 비슷한 물건 평균가</div>
                <div class="avg-info"><span class="avg-value"></span>만원</div>
                <div class="pcost-title">같은동 비슷한 물건 평단가</div>
                <div class="pcost-info"><span class="pcost-value"></span>만원</div>
                <h5>{{ trans('common.tradePrice') }}</h5>
                <div style="margin-bottom:10px;">
                    <span>{{ trans('common.exclusiveSize') }} </span><select style="width:150px;display: inline-block;" name="" class="form-control select2" id="tradeSize"></select>
                </div>
                <table class="table table-striped table-bordered table-condensed" id="actualPricesTable">
                    <thead>
                    <tr>
                        <th data-field="exclusive_size" data-sortable="false">{{ trans('common.exclusiveSize') }}</th>
                        <th data-field="price" data-sortable="false">{{ trans('common.tradePrice') }}</th>
                        <th data-field="floor" data-sortable="false">{{ trans('common.floor') }}</th>
                        <th data-field="yearmonth" data-sortable="true">{{ trans('common.tradeYearMonth') }}</th>
                        <th data-field="day" data-sortable="false">{{ trans('common.tradeDay') }}</th>
                    </tr>
                    </thead>

                </table>
                <hr>
                <h5>{{ trans('common.rentPrice') }}</h5>
                <div style="margin-bottom:10px;">
                    <span>{{ trans('common.exclusiveSize') }} </span><select style="width:150px;display: inline-block;" name="" class="form-control select2" id="rentalSize"></select>
                </div>
                <table class="table table-striped table-bordered table-condensed" id="rentalCostsTable">
                    <thead>
                    <tr>
                        <th data-field="exclusive_size" data-sortable="false">{{ trans('common.exclusiveSize') }}</th>
                        <th data-field="deposit" data-sortable="false">{{ trans('common.deposit') }}</th>
                        <th data-field="rental_cost" data-sortable="false">{{ trans('common.rentalCost') }}</th>
                        <th data-field="floor" data-sortable="false">{{ trans('common.floor') }}</th>
                        <th data-field="yearmonth" data-sortable="true">{{ trans('common.tradeYearMonth') }}</th>
                        <th data-field="day" data-sortable="false">{{ trans('common.tradeDay') }}</th>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@stop