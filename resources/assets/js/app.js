
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
        $.ajax({
            type:type,
            url:url,
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:data
        }).done(end);
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
               case 'TEXTAREA':$(sel).val(val);
               break;
            }
        },
        setTextWithForm = function(data){
            for(key in data){
                setText(key, data[key]);
            }
        },
        getValueWithForm = function(sel){
            var targets, data, val, el, t0;
            targets = (t0 = $(sel + ' input[type]')).length > 0 ? t0 : [];
            if((t0 = $(sel + ' textarea')).length > 0) targets.splice(targets.length, 0, t0);

            for(data = {}, i = 0, j = targets.length ; i < j ; i++){
                el = targets[i];
                //if(!el.name) return;
                console.log(el.name, '/', el.tagName);
                switch(el.tagName) {
                    case 'INPUT':{
                        if(el.type == 'checkbox'){
                            val = [];
                            $(sel + ' :checkbox[name="'+el.name+'"]:checked').each(function(idx){
                                val[idx] = $(this).val();
                            });
                        }else if(el.type == 'radio'){
                            val = $(sel + ' :radio[name="'+el.name+'"]:checked').val();
                        }else val = $(sel + ' [name="'+el.name+'"]').val();
                    }
                        break;
                    case 'TEXTAREA':val = $(sel).val();
                        break;
                }
                data[el.name] = val;
            }
            return data;
        };
        return{
            setTextWithForm:setTextWithForm,
            getValueWithForm:getValueWithForm,
        }
    })();
    return {
        Form:Form,
        http:http,
        global:global,
    }
})();