import Vue from 'vue'
import VueRouter from 'vue-router'

import App from './App.vue'
import Results from './Results.vue'
import Search from './Search.vue'

Vue.use(VueRouter);


const Page404 = {template: `<div><h3>Страница не найдена</h3> <router-link to="/">На главную</router-link></div>`};
const routes = [
    {path: '/', component: Search},
    {path: '/results', component: Results},
    {path: '/search', component: Search},

    // {path: '/box/:boxId(\\d+)', component: Box, name: 'box'},
    {path: '*', component: Page404},
];

const router = new VueRouter({
    routes: routes,
});

const app = new Vue({
    router: router,
    render: h => h(App)
}).$mount('#app');
