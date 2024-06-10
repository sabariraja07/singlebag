import Vue from 'vue';
import { isEmpty } from './functions';

export default {
    state: Vue.observable({
        cart: multibag.cart,
        wishlist: multibag.wishlist,
    }),

    cartIsEmpty() {
        return isEmpty(this.state.cart.items);
    },

    updateCart(cart) {
        this.state.cart = cart;
    },

    removeCartItem(cartItem) {
        Vue.delete(this.state.cart.items, cartItem.id);
    },

    clearCart() {
        this.state.cart.items = {};
    },

    updateWishlist(wishlist) {
        this.state.wishlist = wishlist;
    },

    wishlistCount() {
        return Object.keys(this.state.wishlist).length;
    },

    objectToArray(value){
        return Object.keys(value).map((key) => {
            return value[key]
        });
    },

    inWishlist(productId) {
        let list = this.objectToArray(this.state.wishlist);
        for(var i = 0; i < list.length; i++){
            if(list[i]['id'] == productId){
                return true;
            }
        }
        return false;
    },

    getWishlistId(productId) {
        let list = this.objectToArray(this.state.wishlist);
        for(var i = 0; i < list.length; i++){
            if(list[i]['id'] == productId){
                return list[i]['rowId'];
            }
        }
        return null;
    },

    syncWishlist(productId) {
        if (this.inWishlist(productId)) {
            this.removeFromWishlist(productId);
        } else {
            this.addToWishlist(productId);
        }
    },

    addToWishlist(productId) {
        // if (multibag.loggedIn) {
        //     this.state.wishlist.push(productId);
        // } else {
        //     return window.location.href = multibag.baseUrl + '/user/login';
        // }

        axios.get('/add_to_wishlist/' + productId).then((response) => {
            this.state.wishlist = response.data;
        }).catch((error) => {
        }).finally(()=> {
         });
    },

    removeFromWishlist(productId) {
        this.state.wishlist.splice(this.state.wishlist.indexOf(productId), 1);

        $.ajax({
            method: 'DELETE',
            url: route('wishlist.destroy', { productId }),
        });
    },
};