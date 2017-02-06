
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
        var type = data.method;
        delete data.method;
        console.log('http data:', data);
        U.Modal.blockOpen();
        $.ajax({
            type:type,
            url:url,
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:data
        }).done(function(data){
            U.Modal.blockClose();
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
            $('#alert').modal('hide');
        },
        blockOpen = function(){
            $('#block').fadeIn();
        },
        blockClose = function(){
            $('#block').fadeOut();
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
    })();
    return {
        Form:Form,
        Modal:Modal,
        Format:Format,

        http:http,
        global:global,
    }
})();