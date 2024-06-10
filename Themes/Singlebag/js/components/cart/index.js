import store from '../../store';
import CartHelpersMixin from '../../mixins/CartHelpersMixin';
import ProductHelpersMixin from '../../mixins/ProductHelpersMixin';
import axios from 'axios';

export default {
    mixins: [
        CartHelpersMixin,
        ProductHelpersMixin,
    ],

    data() {
        return {
            shippingMethodName: null,
            crossSellProducts: [],
            coupon: null,
            loadingOrderSummary: false
        };
    },

    computed: {
        hasAnyCrossSellProduct() {
            return this.crossSellProducts.length !== 0;
        },
    },

    created() {
        // this.$nextTick(() => {
        //     if (this.firstShippingMethod) {
        //         this.updateShippingMethod(this.firstShippingMethod);
        //     }

        //     this.fetchCrossSellProducts();
        // });
    },

    methods: {
        optionValues(option) {
            let values = [];

            for (let value of option.values) {
                values.push(value.label);
            }

            return values.join(', ');
        },

        updateQuantity(cartItem, qty) {
            if (qty < 1 || this.exceedsMaxStock(cartItem, qty)) {
                return;
            }

            if (isNaN(qty)) {
                qty = 1;
            }

            this.loadingOrderSummary = true;

            cartitem.quantity = qty;

            $.ajax({
                method: 'PUT',
                url: route('cart.items.update', { cartItemId: cartItem.id }),
                data: { qty: qty || 1 },
            }).then((cart) => {
                store.updateCart(cart);
            }).catch((xhr) => {
                this.$notify(xhr.responseJSON.message);
            }).always(() => {
                this.loadingOrderSummary = false;
            });
        },

        exceedsMaxStock(cartItem, qty) {
            return cartItem.product.manage_stock && cartItem.product.quantity < qty;
        },

        updateCart(){
            this.loadingOrderSummary = true;
            axios.get('/get_cart').then((response) => {
                store.updateCart(response.data);
            }).catch((error) => {
                this.$notify(error);
            })
            .finally(() => {
                this.loadingOrderSummary = false;
            });
        },

        remove(cartItem) {
            this.loadingOrderSummary = true;

            store.removeCartItem(cartItem);

            // if (store.cartIsEmpty()) {
            //     this.crossSellProducts = [];
            // }

            axios.get('/remove_cart' , {params: {'id' : cartItem.id}}).then((response) => {
                store.updateCart(response.data);
            }).catch((error) => {
                this.$notify(error);
            })
            .finally(() => {
                this.loadingOrderSummary = false;
            });
        },

        clearCart() {

            // if (store.cartIsEmpty()) {
            //     this.crossSellProducts = [];
            // }

            axios.get('/cart-clear').then((response) => {
                store.updateCart(response.data);
                store.clearCart();
            }).catch((error) => {
            })
            .finally(() => {
            });
        },

        changeShippingMethod(shippingMethod) {
            this.shippingMethodName = shippingMethod.name;
        },

        fetchCrossSellProducts() {
            $.ajax({
                method: 'GET',
                url: route('cart.cross_sell_products.index'),
            }).then((crossSellProducts) => {
                this.crossSellProducts = crossSellProducts;
            }).catch((xhr) => {
                this.$notify(xhr.responseJSON.message);
            });
        },

        applyCoupon: function(){
            $('.update-coupon-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Apply').attr('disabled');
            axios.post('/apply_coupon', {'code': this.coupon}).then((response) => {
                this.$notify(response.data.message, 'success');
                this.updateCart();
            }).catch((error) => {
            })
            .finally(() => {
                $('.update-coupon-btn').html('<i class="fi-rs-label mr-5"></i>Apply').removeAttr('disabled');
            });
        }
    },
};
