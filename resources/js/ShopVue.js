window.Vue = require('vue');
import axios from 'axios';
import VueAxios from 'vue-axios';

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
};
Vue.use(VueAxios, axios);

Vue.prototype.$siteUrl = 'http://localhost:8000/';
Vue.prototype.$route = 'http://localhost:8000/';
// Vue.component('Swiper', require('swiper/vue').default);
// Vue.component('SwiperSlide', require('swiper/vue').default);
//components
import Counter from "./SiteComponent/Counter";
import OfferTime from "./SiteComponent/OfferTime";
import ShoppingCart from "./SiteComponent/ShoppingCart";
import AddressList from "./SiteComponent/AddressList";
import AddressForm from "./SiteComponent/AddressForm";
import OrderingTime from "./SiteComponent/OrderingTime";
import GiftCart from "./SiteComponent/GiftCart";
import DiscountBox from "./SiteComponent/DiscountBox";
import ProductBox from "./SiteComponent/ProductBox";
import CompareProductList from './SiteComponent/CompareProductList';
import CommentList from './SiteComponent/CommentList';

Vue.component('pagination', require('laravel-vue-pagination'));
const app = new Vue({
    el: '#app',
    components: {
        Counter,
        OfferTime,
        ShoppingCart,
        AddressList,
        AddressForm,
        OrderingTime,
        GiftCart,
        DiscountBox,
        ProductBox,
        CompareProductList,
        CommentList,
    }
});
