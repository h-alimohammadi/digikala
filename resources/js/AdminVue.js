window.Vue = require('vue');
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios, axios);

Vue.prototype.$siteUrl = 'http://localhost:8000/';
Vue.prototype.$route = 'http://localhost:8000/';

//components
Vue.component('pagination', require('shetabit-laravel-vue-pagination').default);
Vue.component('ExampleComponent', require('vue-cleave-component'), {name: 'example-component'});
import IncredibleOffers from "./AdminComponent/IncredibleOffers";

const app = new Vue({
    el: '#app',
    components: {
        IncredibleOffers,

    }
});
