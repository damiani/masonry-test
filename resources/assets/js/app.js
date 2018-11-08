require('./bootstrap');
window.Vue = require('vue');

import DemoPage from './components/DemoPage.vue';

new Vue({
    components: {
        DemoPage,
    },
}).$mount('#app');
