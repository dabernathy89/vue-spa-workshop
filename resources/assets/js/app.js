window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');
import VueRouter from 'vue-router';

Vue.use(VueRouter)

import Home from './components/Home.vue';
import Create from './components/Create.vue';
import ShowOwner from './components/ShowOwner.vue';
import ShowParticipant from './components/ShowParticipant.vue';
import ShowSolutions from './components/ShowSolutions.vue';

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: Home },
        { path: '/hunts/create', component: Create }
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
