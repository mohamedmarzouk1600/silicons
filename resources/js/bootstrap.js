window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue/dist/vue');
window.Vuex = require('vuex/dist/vuex');
window.Tether = require('tether');
window.Bootstrap = require('bootstrap');
window.PerfectScrollbar = require('perfect-scrollbar/jquery')($);
window.toastr = require('./vendors/toastr.min.js');
window.swal = require('sweetalert2');
require('./vendors/datepicker/js/bootstrap-datetimepicker');
require('./vendors/unison');
require('./vendors/pace');
require('jquery-blockui');
require('jquery-match-height');
require('screenfull');
window.Chart = require('chart.js/auto').default;

import vSelect from 'vue-select'
Vue.component('v-select', vSelect)

import Select2 from 'v-select2-component';
Vue.component('Select2', Select2);


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
