// require('vue-resource');
require("babel-polyfill");

window.Vue = require('vue');
// import Vuex from 'vuex'
//
// window.Vue.use(Vuex);
//
import VueLocalStorage from 'vue-localstorage';
window.Vue.use(VueLocalStorage);

import VueResource from 'vue-resource';
window.Vue.use(VueResource);

import VueViewports from 'vue-viewports'
window.Vue.use(VueViewports, { 320: 'mobile', 1270: 'desktop' });

import VeeValidate from 'vee-validate';
window.Vue.use(VeeValidate);

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);


window.Vue.directive('Clickoutside', {
    bind: function(el, binding, vNode) {
        // Provided expression must evaluate to a function.
        if (typeof binding.value !== 'function') {
            const compName = vNode.context.name;
            let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`;
            if (compName) {
                warn += `Found in component '${compName}'`;
            }

            console.warn(warn);
        }
        // Define Handler and cache it on the element
        const bubble = binding.modifiers.bubble;
        const handler = (e) => {
            if (bubble || (!el.contains(e.target) && el !== e.target)) {
                binding.value(e);
            }
        }
        el.__vueClickOutside__ = handler;

        // add Event Listeners
        document.addEventListener('click', handler);
    },

    unbind: function(el, binding) {
        // Remove Event Listeners
        document.removeEventListener('click', el.__vueClickOutside__);
        el.__vueClickOutside__ = null;
    }
});
Vue.config.productionTip = false;

import langRU from './components/locale/ru';
import langRO from './components/locale/ro';
import locale from 'element-ui/lib/locale';


// configure language for element-ui
if(Laravel.lang === 'ru'){
    locale.use(langRU);
}
else{
    locale.use(langRO);
}

let messages = require('./messages.js');
window.appMessages = messages.default;

/*import VueYouTubeEmbed from 'vue-youtube-embed';
Vue.use(VueYouTubeEmbed);*/
//------------------

//------------------
window.$ = window.jQuery = require('jquery');
window.velocity = require('velocity-animate');

window.moment = require('moment');

var _ = require('lodash');

let loadTouchEvents = require('jquery-touch-events');
loadTouchEvents($);

require('viewport-units-buggyfill').init();
// require('./markermap');


// require('element-ui')
require('slick-carousel');

// require('./clust.js');
require('./main');
require('./map');
require('./service_cabinet');
require('./autoservice-card');
require('./order');
require('./login_register');

require('./home');

require('./adverts/add.js');