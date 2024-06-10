(function ($) {
    
    window.checkoutapp = new Vue({
        el: '#checkout_app',
        data: {
            form: {
                location: null
            },
            shipping_methods: [],
            coupon: null,
            validationErrors: null,
            location: null,
            locations: [],
        },
        watch: {},
        computed: {},
        created: function () {},
        watch: {
        },
        mounted() {
            // this.get_shipping_methods();
            this.getLocations();
        },
        updated: function () {},
        methods: {
            get_cart: function(){
                axios.get('/get_cart').then((response) => {
                    this.cart = response.data;
                }).catch((error) => {
                });
            },
            getLocations: function(){
                axios.get('/get-locations').then((response) => {
                    this.locations = response.data;
                }).catch((error) => {
                });
            },
            get_shipping_methods: function(){
                axios.get('/location-shipping-methods' , {params: {'id' : this.form.location ?? null}}).then((response) => {
                    this.shipping_methods = response.data;
                }).catch((error) => {
                });
            },
            create_order: function(){
                this.validationErrors = null;
               $('.place-order-btn').html('<span class="spinner-border spinner-border-sm mr-5" role="status" aria-hidden="true"></span>Place an Order').attr('disabled');
                axios.post('/make_order' , this.form).then((response) => {
                    if(response.request.responseURL){
                        window.location.href = response.request.responseURL;
                    }
                    if(response.data.url){
                        window.location.href = response.data.url;
                    }
                    if(response.data.message){
                        Sweet('success' , response.data.message);
                    }
                }).catch((error) => {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                        window.scrollTo(0,0);
                    }
                    if(error.response.data.message){
                        Sweet('error' , error.response.data.message);
                    }
                })
                .finally(() => {
                    $('.place-order-btn').html('Place an Order<i class="fi-rs-sign-out ml-15"></i>').removeAttr('disabled');
                });
            },
            applyCoupon: function(){
                $('.update-coupon-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Apply').attr('disabled');
                axios.post('/apply_coupon', {'code': this.coupon}).then((response) => {
                    this.get_cart();
                    console.log(response.data);
                }).catch((error) => {
                })
                .finally(() => {
                    $('.update-coupon-btn').html('Apply Coupon').removeAttr('disabled');
                });
            }
        }
    });

})(jQuery);