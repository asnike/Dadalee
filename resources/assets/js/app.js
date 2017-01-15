
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
    var init = function(){

    },
    Form = (function(){
        var setText = function(sel, val){
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
        };
        return{
            setTextWithForm:setTextWithForm
        }
    })();
    return {
        Form:Form
    }
})();