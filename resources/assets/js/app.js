window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');

Vue.component('home', require('./components/Home.vue'));

const app = new Vue({
    el: '#app',
});
