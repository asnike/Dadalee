
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

/*require('./bootstrap');*/

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});*/


var U = (function(){
    var global = {}, init = function(){

    },
    http = function(end, url, data){
        var type = data.method,
            header = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            };
        delete data.method;
        if(data['notUseCSRF']){
            delete data['notUseCSRF'];
            delete header['X-CSRF-TOKEN'];
        }
        console.log('http data:', data);
        U.Modal.blockOpen();
        $.ajax({
            type:type,
            url:url,
            headers:header,
            data:data
        }).done(function(data){
            U.Modal.blockClose();
            end(data);
        }).fail(function(xhr, status){
            var data;
            U.Modal.blockClose();
            console.log(xhr, status);
            data = {
                result:0,
            };
            switch(xhr.status){
                case 422:
                    data.validation = xhr.responseJSON;
                    break;
                case 500:
                    return U.Modal.alert('서버 에러 발생!! :(');
                    break;
            }
            end(data);
        });
    },
    Form = (function(){
        var setText = function(sel, val){

            if(!$(sel)[0]) throw new Error(sel + ' is not exist.');
            switch($(sel)[0].tagName) {
               case 'INPUT':{
                   if($(sel)[0].type == 'checkbox') $(sel).attr('checked', val);
                   else $(sel).val(val);
               }
               break;
               case 'DIV':$(sel).html(val);
               break;
               case 'TEXTAREA':$(sel).val(val), console.log('ddkddkdkdk');
               break;
            }
        },
        setTextWithForm = function(data){
            for(key in data){
                setText(key, data[key]);
            }
        },
        getValueWithForm = function(sel, filter){
            var targets, data, val, el, t0;
            targets = (t0 = $(sel + ' input[type]')).length > 0 ? t0 : [];
            if((t0 = $(sel + ' textarea')).length > 0) targets.splice(targets.length, 0, t0[0]);
            if((t0 = $(sel + ' select')).length > 0) targets.splice(targets.length, 0, t0[0]);

            for(data = {}, i = 0, j = targets.length ; i < j ; i++){
                el = targets[i];
                //if(!el.name) return;
                console.log(el.name, '/', el.tagName);
                switch(el.tagName) {
                    case 'INPUT':{
                        if(el.type == 'checkbox'){
                            val = [];
                            t0 = $(sel + ' :checkbox[name="'+el.name+'"]:checked');
                            t0.each(function(idx){
                                t0.length > 1 ? val[idx] = $(this).val() : val = $(this).val();
                            });
                        }else if(el.type == 'radio'){
                            val = $(sel + ' :radio[name="'+el.name+'"]:checked').val();
                        }else val = $(sel + ' [name="'+el.name+'"]').val();
                    }
                        break;
                    case 'TEXTAREA':val = $(sel + ' textarea[name="'+el.name+'"]').val();
                        break;
                    case 'SELECT':val = $(sel + ' select[name="'+el.name+'"]').val();
                        break;
                }
                if(val) data[el.name] = filter ? filter(val):val;
            }
            console.log('data!!! : ', data);
            return data;
        };
        return{
            setTextWithForm:setTextWithForm,
            getValueWithForm:getValueWithForm,
        }
    })(),
    Modal = (function(){
        var alert = function (msg) {
            $('#alert .modal-body>.contents').html(msg);
            $('#alert .btn-ok').off();
            $('#alert .btn-ok').click(alertClose);
            $('#alert').modal();
        },
        alertClose = function(){
            $('#alert').modal('hide');
        },
        confirm = function(opt) {
            $('#confirm .modal-body>.contents').html(opt.msg);
            $('#confirm .btn-ok').off();
            $('#confirm .btn-cancel').off();
            $('#confirm .btn-ok').click(function(){
                if(typeof opt.callback == 'function') opt.callback();
                confirmClose();
            });
            $('#confirm .btn-cancel').click(confirmClose);
            $('#confirm').modal();
        },
        confirmClose = function(){
            $('#confirm').modal('hide');
        },
        blockOpen = function(){
            $('#block').fadeIn();
            $('body').css({'overflow':'hidden'});
        },
        blockClose = function(){
            $('#block').fadeOut();
            $('body').css({'overflow':'auto'});
        };
        return {
            alert:alert,
            confirm:confirm,
            blockOpen:blockOpen,
            blockClose:blockClose,
            confirmClose:confirmClose,
        }
    })(),
    Format = (function(){
        var percent = function(e){
            $(e.target).val(numeral($(e.target).val()).format('0.00%'));
        },
        comma = function(e){
            $(e.target).val(numeral($(e.target).val()).format('0,0'));
        },
        remove = function(e){
            $(e.target).val(numeral($(e.target).val()).value());
        };
        return {
            percent:percent,
            remove:remove,
            comma:comma,
        }
    })(),
    isMobile = function(){
        var check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    };
    return {
        Form:Form,
        Modal:Modal,
        Format:Format,

        http:http,
        global:global,

        isMobile:isMobile,
    }
})();