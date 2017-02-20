@if($type == 'actualprice')
    <div class="actual-price-info">
        <div class="actual-price-info-header">
            <h4 id="selectedName"></h4>
            <h5 id="selectedAddr"></h5>
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
@else
    <div class="sidebar-content">
        <div class="clearfix">
            <div class="btn-group btn-tab pull-right">
                <a href="#own-tab" class="btn btn-default active" data-toggle="tab">{{ trans('common.own') }}</a>
                <a href="#attension-tab" class="btn btn-default" data-toggle="tab">{{ trans('common.attension') }}</a>
            </div>
        </div>


        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="own-tab">

                <ul class="list-group realestate-list realestate-own-list">
                    @foreach($realestates as $realestate)
                        @if($realestate->own)
                            <li class="list-group-item" data-id="{{ $realestate->id }}">
                                <span>{{ $realestate->name }}</span>
                                <div class="addr">{{ $realestate->address }}</div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane" id="attension-tab">

                <ul class="list-group realestate-list realestate-attension-list">
                    @foreach($realestates as $realestate)
                        @if($realestate->own)
                            <li class="list-group-item" data-id="{{ $realestate->id }}">
                                <span>{{ $realestate->name }}</span>
                                <div class="addr">{{ $realestate->address }}</div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
@endif