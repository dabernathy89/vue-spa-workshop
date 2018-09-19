window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');

Vue.component('home', require('./components/Home.vue'));
Vue.component('create', require('./components/Create.vue'));
Vue.component('show-owner', require('./components/ShowOwner.vue'));
Vue.component('show-participant', require('./components/ShowParticipant.vue'));
Vue.component('show-solutions', require('./components/ShowSolutions.vue'));

const app = new Vue({
    el: '#app',

    methods: {
        success(message) {
            this.successMessage = message;
        }
    },

    data: {
        successMessage: '',
    },
});
