window.Vue = require('vue');
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios, axios);

Vue.prototype.$siteUrl = 'http://localhost:8000/';
Vue.prototype.$route = 'http://localhost:8000/';

//components
import Counter from "./SiteComponent/Counter";
import OfferTime from "./SiteComponent/OfferTime";

const app = new Vue({
    el: '#app',
    components: {
        Counter,
        OfferTime,

    }
});
