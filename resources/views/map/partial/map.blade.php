@section('content')
    @if($type == 'realestate')
        @include('realestate.partial.add')
    @elseif($type == 'pricetag')
        @include('pricetag.partial.add')
    @endif

    <div class="row clearfix">
        <div class="col-md-8">
            <div id="map" style="width:100%;min-height:768px;border:1px solid #ddd;"></div>
        </div>
        <div class="col-md-4" style="padding:15px 8px;">
            @if($type == 'actualprice')
                <div class="actual-price-info">
                    <div class="actual-price-info-header">
                        <h4 id="selectedName"></h4>
                        <h5 id="selectedAddr"></h5>
                    </div>
                    <div class="actual-price-info-body">
                        <h6>{{ trans('common.tradePrice') }}</h6>
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
                        <h6>{{ trans('common.rendPrice') }}</h6>
                        {{--<table class="table table-striped table-bordered table-condensed" id="rentPricesTable">
                            <thead>
                            <tr>
                                <th>{{ trans('common.exclusiveSize') }}</th>
                                <th>{{ trans('common.tradeYearMonth') }}</th>
                                <th>{{ trans('common.tradeDay') }}</th>
                                <th>{{ trans('common.tradePrice') }}</th>
                            </tr>
                            </thead>
                            <tbody id="rentPrices">
                            <tr><td colspan="4">{{ trans('common.notSelectedRealestate') }}</td></tr>
                            </tbody>
                        </table>--}}
                    </div>
                </div>
            @else
                <div>
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


        </div>
    </div>




    <div id="layer" style="display:none;position:fixed;overflow:hidden;z-index:5000;-webkit-overflow-scrolling:touch;">
        <img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
    </div>

    <script type="text/javascript" src="//apis.daum.net/maps/maps3.js?apikey=ec5b7e847efe7670751fbc7fd1aa5e4a&libraries=services,clusterer"></script>
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
    <script>
        var element_layer = document.getElementById('layer');

        function closeDaumPostcode() {
            element_layer.style.display = 'none';
        }

        function daumAddressOpen() {
            new daum.Postcode({
                oncomplete: function(data) {
                    var fullAddr = data.address;
                    var extraAddr = '';

                    if(data.addressType === 'R'){
                        if(data.bname !== ''){
                            extraAddr += data.bname;
                        }
                        if(data.buildingName !== ''){
                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                    }

                    if(U.global.isDetailModalOpened) $('#basicPanel input[name="address"]').val(data.jibunAddress);
                    else $('#addr').val(data.jibunAddress);
                    console.log(data);
                    console.log(fullAddr);

                    addr2coord(data);

                    element_layer.style.display = 'none';
                },
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            element_layer.style.display = 'block';
            initLayerPosition();
        }

        function initLayerPosition(){
            var width = 450;
            var height = 460;
            var borderWidth = 5;

            element_layer.style.width = width + 'px';
            element_layer.style.height = height + 'px';
            element_layer.style.border = borderWidth + 'px solid';
            element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
            element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
        }

        function addr2coord(data) {
            var geocoder = new daum.maps.services.Geocoder();

            geocoder.addr2coord(data.jibunAddress, function (status, result) {
                if (status === daum.maps.services.Status.OK) {
                    if (typeof Controller.searchCallback == 'function') {
                        Controller.searchCallback(result.addr[0], data);
                    }
                }else{
                    console.log(status, result);
                }
            });
        }
    </script>
@stop
