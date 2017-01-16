@extends('layouts.master')

@include('map.partial.map', ['realestates'=>$realestates, 'type'=>'realestate'])

@section('script')

    <script>
        var Controller = (function(){
            var map, realestates, markers = [], searchedInfo = {}, selectedData = {},
                repayMethods,
            init = function(){
                initMap();
                getRealestates();
                initDetailModal();
            },
            initDetailModal = function(){
                $('.btn-tab').click(function(e){
                    $('.btn-tab>.btn').removeClass('active');
                    $(e.target).addClass('active');
                });

                $('.btn-close').click(function(e){
                    U.global.isDetailModalOpened = false;
                    $('#realestate-detail').modal('hide');
                });

                $('.btn-basic-edit').click(basicInfoEdit);
            },
            basicInfoEdit = function(e){
                var data = U.Form.getValueWithForm('#basicPanel');
                data = $.extend(data, {method:'POST', lat:searchedInfo.lat, lng:searchedInfo.lng, '_method':'put'});
                console.log('data : ',data);
                U.http(function(data){
                    U.Modal.alert(data.msg);
                }, '/realestates/' + selectedData.id, data);
            },
            initMap = function(){
                var mapContainer = document.getElementById('map'),
                    mapOption = {
                        center: new daum.maps.LatLng(37.5635710006, 126.9755292634),
                        level: 6
                    };
                map = new daum.maps.Map(mapContainer, mapOption);
            },
            onSubmit = function(){
                var name = $('#name').val(),
                    addr = $('#addr').val(),
                    own = $('#own').is(':checked');
                if(name && addr){
                    U.http(getRealestates, '/realestates', {
                        method:'POST',
                        name:name,
                        address:searchedInfo.title + (searchedInfo.buildingAddress ? searchedInfo.buildingAddress : ''),
                        lat:searchedInfo.lat,
                        lng:searchedInfo.lng,
                        own:own,
                    });
                }
                return false;
            },
            searchCallback = function(result){
                console.log('result : ', result);
                searchedInfo = result;
            },
            setMarker = function(data, moveCenter){
                var coords = new daum.maps.LatLng(data.lat, data.lng);
                var marker = new daum.maps.Marker({
                    map: map,
                    position: coords
                }), markerData;
                if(moveCenter) map.setCenter(coords);
                markers[markers.length] = markerData = {data:data, marker:marker, click:function(){ showInfo(markerData); }};
                daum.maps.event.addListener(marker, 'click', markerData.click);

            },
            getRealestates = function(){
                U.http(getRealestatesSuccess, '/realestates', {method:'GET'});
            },
            getRealestatesSuccess = function(data){
                var i, j, ownHtml, attensionHtml, realestate;
                console.log(data);
                realestates = data.lists;

                for(i = 0, j = markers.length ; i < j ; i++){
                    daum.maps.event.removeListener(markers[i].marker, 'click', markers[i].click);
                    markers[i].marker.setMap(null);
                }
                markers = [];

                var template = Handlebars.compile($('#realestate-list-item').html());

                for(ownHtml = attensionHtml = '', i = 0, j = data.lists.length ; i < j ; i++){
                    realestate = data.lists[i];
                    setMarker(data.lists[i], i == 0);
                    if(realestate.own) {
                        ownHtml += template({
                            'id': realestate.id,
                            'name': realestate.name,
                            'address': realestate.address,
                        });
                    }else{
                        attensionHtml += template({
                            'id': realestate.id,
                            'name': realestate.name,
                            'address': realestate.address,
                        });
                    }
                }

                $('.realestate-own-list>.list-group-item').off();
                $('.realestate-own-list').html(ownHtml);
                $('.realestate-own-list>.list-group-item').click(focusRealestate);

                $('.realestate-attension-list>.list-group-item').off();
                $('.realestate-attension-list').html(attensionHtml);
                $('.realestate-attension-list>.list-group-item').click(focusRealestate);

                $('.realestate-list>.list-group-item>.tools>.btn-detail').off();
                $('.realestate-list>.list-group-item').click(focusRealestate);
                $('.realestate-list>.list-group-item>.tools>.btn-detail').click(openDetailModal);


            },
            showInfo = function(markerData){
                if(markerData.overlay) return;
                var template = Handlebars.compile($('#info-window').html()),
                    content = template({
                        'title':markerData.data.name,
                        'contents':markerData.data.address,
                        'id':markerData.data.id,
                    });

                var infowindow = new daum.maps.InfoWindow({
                    content:content,
                    removable:true
                });

                var overlay = new daum.maps.CustomOverlay({
                    content: content,
                    map: map,
                    position: markerData.marker.getPosition()
                });
                overlay.setMap(map);
                markerData.overlay = overlay;
            },
            focusRealestate = function(e){
                var i, j, id = $(this).attr('data-id'), idx, coords;
                for(i = 0, j = realestates.length ; i < j ; i++){
                    if(realestates[i].id == id){
                        idx = i;
                        break;
                    }
                }
                coords = new daum.maps.LatLng(realestates[idx].lat, realestates[idx].lng);
                map.setCenter(coords);
            },
            hideInfo = function(target) {
                console.log(target);

                var i, j, id = $(target).attr('data-id'), overlay;
                for(i = 0, j = markers.length ; i < j ; i++){
                    if(markers[i].data.id == id){
                        overlay = markers[i].overlay;
                        break;
                    }
                }
                overlay.setMap(null);
                markers[i].overlay = overlay = null;
            },
            openDetailModal = function(e){
                var l = Ladda.create(this);
                l.start();

                U.http(function(data){
                    console.log(data);
                    l.stop();
                    selectedData = data;
                    searchedInfo.lat = data.data.realestate.lat,
                    searchedInfo.lng = data.data.realestate.lng;

                    if(repayMethods){
                        repayMethodsRender();
                    }else{
                        U.http(function(data) {
                            repayMethods = data.lists;
                            repayMethodsRender();
                        }, '/repaymethods', {method:'GET'});
                    }

                    U.Form.setTextWithForm({
                        '#basicPanel input[name="name"]':data.data.realestate.name,
                        '#basicPanel input[name="address"]':data.data.realestate.address,
                        '#basicPanel input[name="own"]':data.data.realestate.own,


                    });
                    if(data.data.earningrate){
                        U.Form.setTextWithForm({
                            '#earningPanel input[name="price"]':data.data.earningrate.price,
                            '#earningPanel input[name="deposit"]':data.data.earningrate.deposit,
                            '#earningPanel input[name="monthly_fee"]':data.data.earningrate.monthlyfee,
                            '#earningPanel input[name="investment"]':data.data.earningrate.investment,
                            '#earningPanel input[name="interest_amount"]':data.data.earningrate.interest_amount,
                            '#earningPanel input[name="real_earning"]':data.data.earningrate.real_earning,
                        });
                    }
                    if(data.data.loan){
                        U.Form.setTextWithForm({
                            '#loanPanel input[name="amount"]':data.data.loan.amount,
                            '#loanPanel input[name="interest_rate"]':data.data.loan.interest_rate,
                            '#loanPanel input[name="repay_commission"]':data.data.loan.repay_commission,
                            '#loanPanel input[name="unredeem_period"]':data.data.loan.unredeem_period,
                            '#loanPanel input[name="repay_period"]':data.data.loan.repay_period,
                            '#loanPanel select[name="repay_method_id"]':data.data.loan.repay_method_id,
                            '#loanPanel input[name="bank"]':data.data.loan.bank,
                            '#loanPanel input[name="account_no"]':data.data.loan.account_no,
                            '#loanPanel input[name="options"]':data.data.loan.options,
                        });
                    }

                    U.global.isDetailModalOpened = true;
                    $('#realestate-detail').modal();
                }, '/realestates/' + $(this).attr('data-id') + '/edit', {method:'GET'});
            },
            repayMethodsRender = function(){
                var html, i, j, template = Handlebars.compile($('#option-item').html());
                for(html = [], i = 0, j = repayMethods.length ; i < j ; i++){
                    html[html.length] = template({value:repayMethods[i].id, name:repayMethods[i].name});
                }
                $('select[name="repay_method_id"]').html(html.join(''));
            };
            init();
            return {
                onSubmit:onSubmit,
                searchCallback:searchCallback,
                hideInfo:hideInfo,
                openDetailModal:openDetailModal,
            }
        })();
    </script>
@stop

@include('realestate.partial.handlebars')
@include('realestate.partial.modal')