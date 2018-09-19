window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');
import VueRouter from 'vue-router';

Vue.use(VueRouter)

import Home from './components/Home.vue';
import Create from './components/Create.vue';
import ShowHunt from './components/ShowHunt.vue';
Vue.component('show-owner', require('./components/ShowOwner.vue'));
Vue.component('show-participant', require('./components/ShowParticipant.vue'));
import ShowSolutions from './components/ShowSolutions.vue';

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: Home },
        { path: '/hunts/create', component: Create },
        { path: '/hunts/:id', component: ShowHunt }
    ]
});

const app = new Vue({
    el: '#app',

    router: router,

    methods: {
        success(message) {
            this.successMessage = message;
        }
    },

    data: {
        successMessage: '',
    },
});
