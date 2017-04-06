@extends('admin.layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h5 class="page-header">{{ trans('common.actualPriceList') }}</h5>

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('common.search') }}</div>
                <div class="panel-body">
                    <form action="{{ route('admin.prices.store') }}" method="post" class="form-inline" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="" class="control-label">{{ trans('common.excel') }}</label>
                            <input type="file" class="form-control" name="excel" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ trans('common.upload') }}</button>
                        </div>

                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('common.geocoding') }}</div>
                <div class="panel-body">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-geocoding">{{ trans('common.geocoding') }}</button>
                    </div>
                </div>
            </div>

            <table class="table table-hover table-striped table-bordered table-condensed">
                <thead>
                <th>{{ trans('common.id') }}</th>
                <th>{{ trans('common.building_name') }}</th>
                <th>{{ trans('common.mainNo') }}</th>
                <th>{{ trans('common.subNo') }}</th>
                </thead>
                <tbody>
                @foreach($prices as $price)
                    <tr>
                        <td>{{ $price->id }}</td>
                        <td>{{ $price->building_name }}</td>
                        <td>{{ $price->main_no }}</td>
                        <td>{{ $price->sub_no }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            <div class="text-center">{{ $prices->links() }}</div>
        </div>
    </div>

    <script type="text/javascript" src="//apis.daum.net/maps/maps3.js?apikey=ec5b7e847efe7670751fbc7fd1aa5e4a&libraries=services"></script>
@endsection

@section('script')
    <script>
        (function(){
            var YOUR_API_KEY = 'AIzaSyDp7NxG06QPH1aXl0vS8-t9cZUMajMhS48',
                geocoder = new daum.maps.services.Geocoder(),
                prices = JSON.parse({!! json_encode(json_encode($all)) !!}),
                geoCache = {},
                currCnt = 0, totalCnt = 0,
            init = function(){
                totalCnt = prices.length;
                $('.btn-geocoding').click(geocodingStart);
            },
            geocodingStart = function(){
                var t0;
                t0 = prices[currCnt];
                if(t0.lng && t0.lat){
                    currCnt++;
                    geocodingStart();
                    return;
                }
                console.log('addr : ', t0.sigungu + ' ' + +t0.main_no  + '-' + +t0.sub_no);
                geocoding(t0.sigungu + ' ' + +t0.main_no  + '-' + +t0.sub_no);

            },
            geocoding = function(address){
                var t0;
                if(t0 = geoCache[address]){
                    return saveGeocodeExist(t0);
                }
                setTimeout(function(){
                    /*geocoder.addr2coord(address, function(status, result) {
                        if(status === daum.maps.services.Status.OK) {
                            saveGeocode(result.addr[0]);
                        }
                    });*/
                    U.http(function(data){
                        if(data.status == 'OK'){
                            saveGeocode(data.results[0].geometry.location);
                        }
                    }, 'https://maps.googleapis.com/maps/api/geocode/json?key='+YOUR_API_KEY+'&address=' + address, {method:'GET', 'notUseCSRF':true});

                }, 1000);
            },
            saveGeocode = function(result){
                var t0 = prices[currCnt],
                    id = t0.id,
                    lng = result.lng,
                    lat = result.lat;

                console.log(result);

                geoCache[t0.sigungu + ' ' + +t0.main_no  + '-' + +t0.sub_no] = data = {method:'POST', id:id, lng:lng, lat:lat};
                ajaxCall(data);
            },
            saveGeocodeExist = function(data){
                console.log(data);
                data.id = prices[currCnt].id;
                data.method = 'POST';
                ajaxCall(data);
            },
            ajaxCall = function(data){
                console.log(data);
                U.http(function(data){
                    if(currCnt < totalCnt){
                        currCnt++;
                        geocodingStart();
                    }
                }, '/admin/prices/geocoding/', data);
            };

            init();
        })();
    </script>
@endsection