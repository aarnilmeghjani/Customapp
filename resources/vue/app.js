require('../js/bootstrap');

import router from "./router/routes";
import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios,axios);



// window.Vue = require('vue').default;


import PolarisVue from '@hulkapps/polaris-vue';
import '@hulkapps/polaris-vue/dist/polaris-vue.min.css';
Vue.use(PolarisVue);





const app = new Vue({
    router,
    el: '#app',
});
