require('./singlebag');
require('./directives');

import './config/interceptors';

import Vue from 'vue';
import store from './store';
import EmptyPage from './components/EmptyPage.vue';
import ValidationErrors from './components/ValidationErrors.vue';
import { notify, trans, chunk, formatPrice, isMobileNumber } from './functions';
import VueToast from 'vue-toast-notification';
import vClickOutside from 'v-click-outside';
import VPagination from './components/VPagination.vue';
import HeaderSearch from './components/layout/HeaderSearch.vue';
import HomeSlider from './components/home/HomeSlider.vue';
import BannerAds from './components/home/BannerAds.vue';
import HomeCategory from './components/home/HomeCategory.vue';
import OfferProductsList from './components/home/OfferProductsList.vue';
import FeaturedCategories from './components/home/FeaturedCategories.vue';
import PopularProducts from './components/home/PopularProducts.vue';
import HomeColumnListProducts from './components/home/HomeColumnListProducts.vue';
import ProductListCard from './components/products/ProductListCard.vue';
import SingleSelect from './components/SingleSelect.vue';
import CartIndex from './components/cart/Index';
import CheckoutCreate from './components/checkout/create';
import ProductIndex from './components/products/Index';
import ProductCardGridView from './components/products/index/ProductCardGridView.vue';
import ProductShow from './components/products/Show';
import MyWishlist from './components/account/wishlist/Index';


import 'vue-toast-notification/dist/theme-default.css';

Vue.use(VueToast);
Vue.use(vClickOutside);

// Vue.prototype.route = route;
Vue.prototype.$notify = notify;
Vue.prototype.$trans = trans;
Vue.prototype.$chunk = chunk;
Vue.prototype.$formatPrice = formatPrice;
Vue.prototype.$isMobileNumber = isMobileNumber;

Vue.component('v-pagination', VPagination);
Vue.component('empty-page', EmptyPage);
Vue.component('header-search', HeaderSearch);
Vue.component('home-slider', HomeSlider);
Vue.component('home-category', HomeCategory);
Vue.component('banner-ads', BannerAds);
Vue.component('featured-categories', FeaturedCategories);
Vue.component('popular-products', PopularProducts);
Vue.component('home-column-list-products', HomeColumnListProducts);
Vue.component('offer-products-list', OfferProductsList);
Vue.component('product-list-card', ProductListCard);
Vue.component('single-select', SingleSelect);
Vue.component('cart-index', CartIndex);
Vue.component('checkout-create', CheckoutCreate);
Vue.component('product-index', ProductIndex);
Vue.component('product-card-grid-view', ProductCardGridView);
Vue.component('product-show', ProductShow);
Vue.component('validation-errors', ValidationErrors);
Vue.component('my-wishlist', MyWishlist);


import axios from 'axios';

new Vue({
    el: '#singlebag',

    computed: {
        cart() {
            return store.state.cart;
        },

        wishlistCount() {
            return store.wishlistCount();
        },
    },
    methods: {
        removeCart(cartItem) {
            store.removeCartItem(cartItem);

            axios.get('/remove_cart' , {params: {'id' : cartItem.id}}).then((response) => {
                store.updateCart(response.data);
            });
        },
    }
});
