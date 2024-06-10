import axios from 'axios';
import { values } from 'lodash';
import store from '../store';

export default {
    data() {
        return {
            addingToCart: false,
            loadingWishlist: false,
        };
    },

    computed: {
        productUrl() {
            return 'product/' + this.product.slug + '/' + this.product.id;
        },

        hasAnyOption() {
            return this.product.options.length > 0;
        },

        hasNoOption() {
            return !this.hasAnyOption;
        },

        hasBaseImage() {
            return this.product.base_image.length !== 0;
        },

        productImgUrl(){
            return this.product.image ?? '/assets/img/empty/450x600.jpg';
        },

        categoryUrl(){
            return 'category/' + this.product.category.category.slug + '/' + this.product.category.category.id;
        },

        inWishlist() {
            return store.inWishlist(this.product.id);
        },
    },

    methods: {

        syncWishlist() {
            this.loadingWishlist = true;
            if (store.inWishlist(this.product.id)) {
                this.removeFromWishlist();
            } else {
                this.addToWishlist();
            }
        },

        addToWishlist() {
            axios.get('/add_to_wishlist/' + this.product.id)
                .then(r => r.data)
                .then((response) => {
                    store.updateWishlist(response.data);
                    this.$notify(response.message);
                }).catch((error) => {})
                .finally(() => {
                    this.loadingWishlist = false;
                });
        },

        removeFromWishlist() {

            axios.get('/wishlist/remove/' + this.product.id)
                .then(r => r.data)
                .then((response) => {
                    store.updateWishlist(response.data);
                    this.$notify(response.message);
                }).catch((error) => {})
                .finally(() => {
                    this.loadingWishlist = false;
                });
        },

        addToCart: function(){
            this.addingToCart = true;
            axios.post('/add-to-cart', {'id' : this.product.id, 'variation' : [], 'qty' : 1, 'option': [] })
            .then(r => r.data)
            .then((response) => {
                store.updateCart(response);
                this.$notify(this.$trans('Product added to your cart'));
            }).catch((error) => {
                this.$notify('Product not added to your cart', 'error');
            })
            .finally(() => {
                this.addingToCart = false;
            });
        },

        isOutOfStock(){
            if (this.product.stock == null) {
                return false;
            }
            if(this.product.stock.stock_status == 0 && this.product.stock.stock_manage == 1){
                return true;
            }
            if (this.product.stock.stock_manage == 0) {
                return false;
            }
            if (this.product.stock.stock_manage == 1 && this.product.stock.stock_qty > 0) {
                return false;
            }
            return false;
        },
        
        isRequiredOption(){
            var is_required = false
            if(this.product.options.length != 0){
                this.product.options.forEach(option => {
                    if(option.is_required == 1){
                        is_required = true;
                    }
                });
            }
            return is_required;
        }
    },
};
