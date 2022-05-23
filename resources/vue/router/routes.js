import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

const routes = [
    {path: '/', redirect: '/home'},
    {
        path: '/',
        name: 'master',
        component: require('../layouts/Master').default,
        children:[
            {
                path: '/home',
                name: 'home',
                component: require('../pages/Index').default
            },
            {
                path: '/product',
                name: 'product',
                component: require('../pages/product/Product').default
            },
            {
                path: '/customer',
                name: 'customer',
                component: require('../pages/customer/Customer').default
            },
            {
                path: '/order',
                name: 'order',
                component: require('../pages/order/Order').default
            }

        ]
    },

]
// console.log('heelo from route')



export default new VueRouter({
    routes,
    mode: 'history'
});
