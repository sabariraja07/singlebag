import store from '../../../store';
// import VPagination from '../../VPagination.vue';
// import ProductHelpersMixin from '../../../mixins/ProductHelpersMixin';
import StarRating from 'vue-star-rating';
import axios from 'axios';

export default {
    components: { StarRating },

    // mixins: [
    //     ProductHelpersMixin,
    // ],

    data() {
        return {
            // fetchingWishlist: false,
            // products: { data: [] },
            // currentPage: 1,
        };
    },

    computed: {
        // wishlistIsEmpty() {
        //     return this.products.data.length === 0;
        // },

        // totalPage() {
        //     return Math.ceil(this.products.total / 20);
        // },
    },

    created() {
       // this.fetchWishlist();
       this.get_cart();
    },

    methods: {
        get_cart: function(){
            axios.get('/get_cart').then((response) => {
                // this.cart = response.data;
                store.updateCart(response.data);
            }).catch((error) => {
            });
        },
        fetchWishlist() {
            this.fetchingWishlist = true;

            $.ajax({
                method: 'GET',
                url: route('wishlist.products.index', {
                    page: this.currentPage,
                }),
            }).then((products) => {
                this.products = products;
            }).catch((xhr) => {
                this.$notify(xhr.responseJSON.message);
            }).always(() => {
                this.fetchingWishlist = false;
            });
        },

        remove(id) {
            axios.get('/wishlist/remove/' + id)
                .then(r => r.data)
                .then((response) => {
                    store.updateWishlist(response.data);
                    this.$notify(response.message);
                    window.location.reload();
                }).catch((error) => {}).finally(() => {
                }).finally(() => {
                    // this.loadingWishlist = false;
                });
        },
    },
};
