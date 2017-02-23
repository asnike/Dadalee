@extends('layouts.master')

@include('map.partial.map', ['realestates'=>$realestates, 'type'=>'realestate'])

@include('realestate.partial.navbarsub')
@include('realestate.partial.sidebar')
@include('realestate.partial.modal')
@include('layouts.partial.info', ['type'=>'realestate'])


@section('handlebars')
    @include('realestate.partial.handlebars')
@stop




@section('script')

        <script>
        var Controller = (function(){
            var map, realestates, markers = [], searchedInfo = {}, addrInfo = {}, selectedData = {}, selectedRealestate,
                repayMethods,
            init = function(){
                initMap();
                initMapControl();
                getRealestates();
                initDetailModal();

                $("select").select2();
                $('#datetimepicker').datetimepicker({format:'YYYY-MM-DD', icons:{
                    previous: 'fa fa-arrow-left',
                    next: 'fa fa-arrow-right',
                }});
                new Cleave('#loanPanel input[name="interest_rate"]', {
                    blocks:[1,2],
                    delimiters:['.'],
                    numericOnly:true,
                });
                new Cleave('#loanPanel input[name="repay_commission"]', {
                    blocks:[1,2],
                    delimiters:['.'],
                    numericOnly:true,
                });

                new Cleave('#loanPanel input[name="account_no"]', {
                    numericOnly:true,
                });
            },
                initMapControl = function(){
                    var zoomControl = new daum.maps.ZoomControl();
                    map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);
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
            },
            basicInfoEdit = function(e){
                var data = U.Form.getValueWithForm('#basicPanel');
                data = $.extend(data, {method:'POST', lat:searchedInfo.lat, lng:searchedInfo.lng, '_method':'put'});
                console.log('data : ',data);
                U.http(function(data){
                    U.Modal.alert(data.msg);
                }, '/realestates/' + selectedData.id, data);

                return false;
            },
            earningInfoEdit = function(e){
                var data = U.Form.getValueWithForm('#earningPanel', function(val){ return numeral(val).value(); });
                data = $.extend(data, {method:'POST','_method':'put'});
                data.rate = (data.real_earning?data.real_earning:0)*12/(data.investment?data.investment:1)*100;
                console.log('data : ',data);
                U.http(function(data){
                    if(data.result) U.Modal.alert(data.msg);
                    else U.Modal.alert(data.data);
                }, '/realestates/' + selectedData.id + '/earning', data);

                return false;
            },
            loanInfoEdit = function(e){
                var data = U.Form.getValueWithForm('#loanPanel', function(val){
                    if(isNaN(+(val.replace(/,/g, '')))) return val;
                    return numeral(val).value();
                });
                data = $.extend(data, {method:'POST','_method':'put'});
                console.log('data : ',data);
                U.http(function(data){
                    if(data.result) U.Modal.alert(data.msg);
                    else{
                        if(data.validation){
                            var msg = [], k, sk;
                            for(k in data.validation){
                                for(sk in data.validation[k]){
                                    msg[msg.length] = data.validation[k][sk];
                                }
                            }
                            U.Modal.alert(msg.join('<br>'));
                        }
                    }
                }, '/realestates/' + selectedData.id + '/loan', data);

                return false;
            },
            tenantInfoEdit = function(e){
                var data = U.Form.getValueWithForm('#tenantPanel');
                data = $.extend(data, {method:'POST','_method':'put'});
                console.log('data : ',data);
                U.http(function(data){
                    U.Modal.alert(data.msg);
                }, '/realestates/' + selectedData.id + '/tenant', data);

                return false;
            },
            initMap = function(){
                var mapContainer = document.getElementById('map'),
                    mapOption = {
                        center: new daum.maps.LatLng(37.5635710006, 126.9755292634),
                        level: 2
                    };
                map = new daum.maps.Map(mapContainer, mapOption);

                $(window).resize(function(){
                    map.relayout();
                });
            },
            onSubmit = function(){
                var name = $('#name').val(),
                    addr = $('#addr').val(),
                    own = $('#own').is(':checked');
                if(name && addr){
                    U.http(getRealestates, '/realestates', {
                        method:'POST',
                        name:name,
                        address:searchedInfo.title + ' ' + (searchedInfo.buildingAddress ? searchedInfo.buildingAddress : ''),
                        lat:searchedInfo.lat,
                        lng:searchedInfo.lng,
                        own:own,
                        sigungu:searchedInfo.localName_1 + ' ' + searchedInfo.localName_2 + ' ' + searchedInfo.localName_3,
                        sigungu_code:addrInfo.sigunguCode,
                        main_no:searchedInfo.mainAddress,
                        sub_no:searchedInfo.subAddress,
                        new_address:searchedInfo.newAddress,
                        building_name:addrInfo.buildingName,
                    });
                }
                return false;
            },
            searchCallback = function(result, data){
                console.log('result : ', result, data);
                searchedInfo = result;
                addrInfo = data;
            },
            setMarker = function(data, moveCenter){
                var coords = new daum.maps.LatLng(data.lat, data.lng);

                var imageSrc = '/image/house-marker.png',
                    imageSize = new daum.maps.Size(56, 62),
                    imageOprion = {offset: new daum.maps.Point(27, 69)}
                var markerImage = new daum.maps.MarkerImage(imageSrc, imageSize, imageOprion);


                var marker = new daum.maps.Marker({
                    map: map,
                    position: coords,
                    /*image:markerImage*/
                }), markerData;
                if(moveCenter) map.setCenter(coords);
                markers[markers.length] = markerData = {data:data, marker:marker, click:function(){ showInfo(markerData); }};
                daum.maps.event.addListener(marker, 'click', markerData.click);

            },
            getRealestates = function(data){
                if(data && data.msg) U.Modal.alert(data.msg);
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
                    tmplData = {
                        'id': realestate.id,
                        'name': realestate.name,
                        'address': realestate.address,
                        'earningRate': realestate.earning_rate ? realestate.earning_rate.rate : 0,
                        'bunji':realestate.main_no+','+realestate.sub_no
                    };
                    if(realestate.own) ownHtml += template(tmplData);
                    else attensionHtml += template(tmplData);
                }
                var emptyTmpl = Handlebars.compile($('#realestate-list-no-item').html());
                if(ownHtml == '') ownHtml = emptyTmpl();
                if(attensionHtml == '') attensionHtml = emptyTmpl();

                $('.realestate-own-list>.list-group-item').off();
                $('.realestate-own-list').html(ownHtml);
                $('.realestate-own-list>.list-group-item').click(focusRealestate);

                $('.realestate-attension-list>.list-group-item').off();
                $('.realestate-attension-list').html(attensionHtml);
                $('.realestate-attension-list>.list-group-item').click(focusRealestate);

                $('.realestate-list>.list-group-item>.tools>.btn-detail').off();
                $('.realestate-list>.list-group-item>.tools>.btn-price').off();
                $('.realestate-list>.list-group-item>.tools>.btn-del').off();
                $('.realestate-list>.list-group-item').click(focusRealestate);
                $('.realestate-list>.list-group-item>.tools>.btn-detail').click(openDetailModal);
                $('.realestate-list>.list-group-item>.tools>.btn-price').click(showPrice);
                $('.realestate-list>.list-group-item>.tools>.btn-del').click(delConfirm);


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
                var i, j, id = $(this).attr('data-id'), idx, coords, projection;
                if(!id) return;
                for(i = 0, j = realestates.length ; i < j ; i++){
                    if(realestates[i].id == id){
                        idx = i;
                        break;
                    }
                }
                coords = new daum.maps.LatLng(realestates[idx].lat, realestates[idx].lng);
                if($('.price-info.show')[0]){
                    projection = map.getProjection();
                    point = projection.pointFromCoords(coords);
                    point.x -= 215;
                    coords = projection.coordsFromPoint(point);
                }
                map.setCenter(coords);
            },
            hideInfo = function(target){
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
                    realestate = data.data.realestate;
                    console.log(data);
                    l.stop();
                    selectedData = realestate;
                    searchedInfo.lat = realestate.lat,
                    searchedInfo.lng = realestate.lng;

                    if(repayMethods){
                        repayMethodsRender();
                    }else{
                        U.http(function(data) {
                            repayMethods = data.lists;
                            repayMethodsRender();
                        }, '/repaymethods', {method:'GET'});
                    }

                    U.Form.setTextWithForm({
                        '#basicPanel input[name="name"]':realestate.name,
                        '#basicPanel input[name="address"]':realestate.address,
                        '#basicPanel input[name="own"]':realestate.own,
                        '#basicPanel input[name="floor"]':realestate.floor,
                        '#basicPanel input[name="completed_at"]':realestate.completed_at,
                        '#basicPanel input[name="exclusive_size"]':realestate.exclusive_size,
                        '#basicPanel textarea[name="memo"]':realestate.memo,
                    });
                    if(realestate.earning_rate){
                        U.Form.setTextWithForm({
                            '#earningPanel input[name="price"]':numeral(realestate.earning_rate.price).format('0,0'),
                            '#earningPanel input[name="deposit"]':numeral(realestate.earning_rate.deposit).format('0,0'),
                            '#earningPanel input[name="monthlyfee"]':numeral(realestate.earning_rate.monthlyfee).format('0,0'),
                            '#earningPanel input[name="investment"]':numeral(realestate.earning_rate.investment).format('0,0'),
                            '#earningPanel input[name="mediation_cost"]':numeral(realestate.earning_rate.mediation_cost).format('0,0'),
                            '#earningPanel input[name="judicial_cost"]':numeral(realestate.earning_rate.judicial_cost).format('0,0'),
                            '#earningPanel input[name="tax"]':numeral(realestate.earning_rate.tax).format('0,0'),
                            '#earningPanel input[name="etc_cost"]':numeral(realestate.earning_rate.etc_cost).format('0,0'),
                            '#earningPanel input[name="interest_amount"]':numeral(realestate.earning_rate.interest_amount).format('0,0'),
                            '#earningPanel input[name="real_earning"]':numeral(realestate.earning_rate.real_earning).format('0,0'),
                        });
                    }
                    if(realestate.loan){
                        U.Form.setTextWithForm({
                            '#loanPanel input[name="amount"]':numeral(realestate.loan.amount).format('0,0'),
                            '#loanPanel input[name="interest_rate"]':realestate.loan.interest_rate,
                            '#loanPanel input[name="repay_commission"]':realestate.loan.repay_commission,
                            '#loanPanel input[name="unredeem_period"]':realestate.loan.unredeem_period,
                            '#loanPanel input[name="repay_period"]':realestate.loan.repay_period,
                            '#loanPanel select[name="repay_method_id"]':realestate.loan.repay_method_id,
                            '#loanPanel input[name="bank"]':realestate.loan.bank,
                            '#loanPanel input[name="account_no"]':realestate.loan.account_no,
                            '#loanPanel textarea[name="options"]':realestate.loan.options,
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
            },
            calcEarning = function (e) {
                var price = numeral($('#earningPanel input[name="price"]').val()).value(),
                    deposit = numeral($('#earningPanel input[name="deposit"]').val()).value(),
                    monthlyfee = numeral($('#earningPanel input[name="monthlyfee"]').val()).value(),


                    loan = numeral($('#loanPanel input[name="amount"]').val()).value(),
                    mediation_cost = numeral($('#loanPanel input[name="mediation_cost"]').val()).value(),
                    judicial_cost = numeral($('#loanPanel input[name="judicial_cost"]').val()).value(),
                    tax = numeral($('#loanPanel input[name="tax"]').val()).value(),
                    etc_cost = numeral($('#loanPanel input[name="etc_cost"]').val()).value(),
                    interest_rate = +$('#loanPanel input[name="interest_rate"]').val(),
                    investment = price - deposit - loan + mediation_cost + judicial_cost + tax + etc_cost,
                    interest_amount = loan*interest_rate/100/12,
                    real_earning = monthlyfee - interest_amount;

                $('#earningPanel input[name="investment"]').val(investment);
                $('#earningPanel input[name="interest_amount"]').val(interest_amount);
                $('#earningPanel input[name="real_earning"]').val(real_earning);

            },
            calcRealEarning = function(e){
                var monthlyfee = numeral($('#earningPanel input[name="monthlyfee"]').val()).value(),
                    interest_amount = numeral($('#earningPanel input[name="interest_amount"]').val()).value(),
                    real_earning = monthlyfee - interest_amount;

                $('#earningPanel input[name="real_earning"]').val(numeral(real_earning).format('0,0'));
            },
            removeFormat = function(e){
                $(e.target).val(numeral($(e.target).val()).value());
            },
            addFormat = function(e){
                $(e.target).val(numeral($(e.target).val()).format('0,0'));
            },
            delConfirm = function(e){
                var id = $(this).attr('data-id');

                U.Modal.confirm({msg:'삭제하시겠습니까?', callback:function(){del(id);}});
            },
            del = function(id){
                U.http(delEnd, '/realestates/' + id, {method:'POST','_method':'delete'})
            },
            delEnd = function(data){
                if(data.result){
                    U.Modal.confirmClose();
                }
            },
            showPrice = function(e){
                var bunji = $(this).attr('data-bunji'),
                    id = $(this).attr('data-id'),
                    i, j;

                for(i = 0, j = realestates.length ; i < j ; i++){
                    if(id == realestates[i].id) selectedRealestate = realestates[i];
                }

                getPriceHistory(bunji);
                getRentalHistory(bunji);
                //console.log(e.target, e.currentTarget);
                selectedTarget = e.currentTarget;
            },
            getPriceHistory = function(bunji){
                U.http(getPriceHistoryEnd, 'actualprices/' + bunji, {method:'GET'})
            },
            getPriceHistoryEnd = function(data){
                var selectData, sizes, i, j, key;

                $('#selectedName').html(selectedRealestate.building_name);
                $('#selectedAddr').html(selectedRealestate.address);
                console.log('get history : ', data);

                for(selectData = [], sizes = {}, i = 0, j = data.actualprices.length ; i < j ; i++){
                    sizes[data.actualprices[i].exclusive_size] = 1;
                }
                i = 0;
                selectData[0] = {id:0, text:'전체'};
                for(key in sizes){
                    selectData[selectData.length] = {id:key, text:key};
                    i++;
                }
                //$('#actualPrices').html(html);
                $('#actualPricesTable').bootstrapTable('destroy');
                $('#actualPricesTable').bootstrapTable({data:data.actualprices});


                $('#tradeSize').select2('destroy');
                $('#tradeSize').html('');
                $('#tradeSize').select2({
                    data:selectData
                });
                $('#tradeSize').on('select2:select', function (e) {
                    var opt = $('#tradeSize').val() == 0 ? {} : {'exclusive_size':+$('#tradeSize').val()};
                    $('#actualPricesTable').bootstrapTable('filterBy', opt);
                });

                $('.price-info').addClass('show');
                if(selectedTarget){
                    focusRealestate.apply(selectedTarget);
                    selectedTarget = null;
                }
            },
            getRentalHistory = function(bunji){
                U.http(getRentalHistoryEnd, 'rentalcosts/' + bunji, {method:'GET'})
            },
            getRentalHistoryEnd = function(data){
                var selectData, sizes, i, j, key;

                console.log('get history : ', data);

                for(selectData = [], sizes = {}, i = 0, j = data.rentalcosts.length ; i < j ; i++){
                    sizes[data.rentalcosts[i].exclusive_size] = 1;
                }
                i = 0;
                selectData[0] = {id:0, text:'전체'};
                for(key in sizes){
                    selectData[selectData.length] = {id:key, text:key};
                    i++;
                }
                //$('#actualPrices').html(html);
                $('#rentalCostsTable').bootstrapTable('destroy');
                $('#rentalCostsTable').bootstrapTable({data:data.rentalcosts});


                $('#rentalSize').select2('destroy');
                $('#rentalSize').html('');
                $('#rentalSize').select2({
                    data:selectData
                });
                $('#rentalSize').on('select2:select', function (e) {
                    var opt = $('#rentalSize').val() == 0 ? {} : {'exclusive_size':+$('#rentalSize').val()};
                    $('#rentalCostsTable').bootstrapTable('filterBy', opt);
                });
            },
            closePriceInfo = function(){
                $('.price-info.show').removeClass('show');
            };
            init();
            return {
                onSubmit:onSubmit,
                searchCallback:searchCallback,
                hideInfo:hideInfo,
                openDetailModal:openDetailModal,
                calcEarning:calcEarning,
                calcRealEarning:calcRealEarning,
                removeFormat:removeFormat,
                addFormat:addFormat,
                basicInfoEdit:basicInfoEdit,
                earningInfoEdit:earningInfoEdit,
                loanInfoEdit:loanInfoEdit,
                tenantInfoEdit:tenantInfoEdit,
                closePriceInfo:closePriceInfo,
            }
        })();
    </script>
@stop



