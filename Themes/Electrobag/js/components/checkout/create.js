import store from '../../store';
import Errors from '../../Errors';
import CartHelpersMixin from '../../mixins/CartHelpersMixin';
import ProductHelpersMixin from '../../mixins/ProductHelpersMixin';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import axios from 'axios';

export default {
    mixins: [
        CartHelpersMixin,
        ProductHelpersMixin,
    ],

    props: ['customerEmail', 'gateways', 'locations'],
    components: {
        flatPickr,
      },
    data() {
        return {
            // form: {
            //     customer_email: this.customerEmail,
            //     billing: {},
            //     shipping: {},
            //     billingAddressId: null,
            //     shippingAddressId: null,
            //     newBillingAddress: false,
            //     newShippingAddress: false,
            //     customer_phone: window.customer_phone,
            //     address: ''
            // },
            form: {
                location: null
            },
            shipping_methods: [],
            coupon: null,
            validationErrors: null,
            location: null,
            // locations: [],
            autocomplete : null,
            address1Field : null,
            billingAddressSearch : null,
            states: {
                billing: {},
                shipping: {},
            },
            placingOrder: false,
            errors: new Errors(),
            stripe: null,
            stripeCardElement: null,
            stripeError: null,
            loadingOrderSummary: false,
            flatConfig: {
                wrap: true, // set wrap to true only when using 'input-group'
                altFormat: 'M j, Y',
                altInput: true,
                dateFormat: 'Y-m-d',
              }, 
              shipping_price: 0,
              isLoading: false
        };
    },
    computed: {       
        hasAddress() {
            return Object.keys(this.addresses).length !== 0;
        },

        firstCountry() {
            return Object.keys(this.countries)[0];
        },

        hasBillingStates() {
            return Object.keys(this.states.billing).length !== 0;
        },

        hasShippingStates() {
            return Object.keys(this.states.shipping).length !== 0;
        },

        hasNoPaymentMethod() {
            return Object.keys(this.gateways).length === 0;
        },

        firstPaymentMethod() {
            return Object.keys(this.gateways)[0];
        },

        shouldShowPaymentInstructions() {
            return ['bank_transfer', 'check_payment'].includes(this.form.payment_method);
        },

        paymentInstructions() {
            if (this.shouldShowPaymentInstructions) {
                return this.gateways[this.form.payment_method].instructions;
            }
        },
    },

    watch: {
        'form.billing.city': function (newCity) {
            if (newCity) {
                this.addTaxes();
            }
        },

        'form.shipping.city': function (newCity) {
            if (newCity) {
                this.addTaxes();
            }
        },

        'form.billing.zip': function (newZip) {
            if (newZip) {
                this.addTaxes();
            }
        },

        'form.shipping.zip': function (newZip) {
            if (newZip) {
                this.addTaxes();
            }
        },

        'form.billing.state': function (newState) {
            if (newState) {
                this.addTaxes();
            }
        },

        'form.shipping.state': function (newState) {
            if (newState) {
                this.addTaxes();
            }
        },

        'form.ship_to_a_different_address': function () {
            this.addTaxes();
        },

        'form.terms_and_conditions': function () {
            this.errors.clear('terms_and_conditions');
        },

        'form.payment_method': function (newPaymentMethod) {
            if (newPaymentMethod === 'paypal') {
                this.$nextTick(this.renderPayPalButton);
            }

            if (newPaymentMethod !== 'stripe') {
                this.stripeError = '';
            }
        },
    },

    created() {
        // if (this.defaultAddress.address_id) {
        //     this.form.billingAddressId = this.defaultAddress.address_id;
        //     this.form.shippingAddressId = this.defaultAddress.address_id;
        // }

        // this.form.newBillingAddress = ! this.hasAddress;
        // this.form.newShippingAddress = ! this.hasAddress;

        // this.changeBillingCountry(this.firstCountry);
        // this.changeShippingCountry(this.firstCountry);
        // this.$nextTick(() => {
        //     if (this.firstPaymentMethod) {
        //         this.changePaymentMethod(this.firstPaymentMethod);
        //     }

        //     if (this.firstShippingMethod) {
        //         this.updateShippingMethod(this.firstShippingMethod);
        //     }

        //     if (window.Stripe) {
        //         this.stripe = window.Stripe(Lotus18.stripePublishableKey);

        //         this.renderStripeElements();
        //     }
        // });
    },
    mounted(){
        //this.initAutocomplete();
    },
    methods: {
        get_cart: function(){
            axios.get('/get_cart').then((response) => {
                // this.cart = response.data;
                store.updateCart(response.data);
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
            this.shipping_price = 0;
            // var total = $('#cart-total').val();
            // $('#cart_total').html(this.$formatPrice(parseFloat(total)));
            axios.get('/location-shipping-methods' , {params: {'id' : this.form.location ?? null}}).then((response) => {
                this.shipping_methods = response.data;
            }).catch((error) => {
            });
        },
        updateShippingPrice: function(price){
            // var total = $('#cart-total').val();
            // $('#cart_total').html(this.$formatPrice(parseFloat(total) + parseFloat(price)));
            this.shipping_price = parseFloat(price);
        },
        create_order: function(){
            this.validationErrors = null;
            if(this.isLoading) return;
            this.isLoading = true;
           $('.place-order-btn').html('<span class="spinner-border spinner-border-sm mr-5" role="status" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;Please Wait..').attr('disabled');
            axios.post('/make_order' , this.form).then((response) => {
                if(response.request.responseURL){
                    window.location.href = response.request.responseURL;
                }
                if(response.data.url){
                    window.location.href = response.data.url;
                }
                if(response.data.message){
                    this.$notify(response.data.message, 'success');
                }
            }).catch((error) => {
                if (error.response.status == 422){
                    this.validationErrors = error.response.data.errors;
                    window.scrollTo(0,0);
                }
                if(error.response.data.message){
                    this.$notify(error.response.data.message, 'error');
                }
            })
            .finally(() => {
                this.isLoading = false;
                $('.place-order-btn').html('Place an Order<i class="fi-rs-sign-out ml-15"></i>').removeAttr('disabled');
            });
        },
      applyCoupon: function(){
            $('.update-coupon-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Apply').attr('disabled');
            axios.post('/apply_coupon', {'code': this.coupon}).then((response) => {
                this.$notify(response.data.message, 'success');
                this.get_cart();
            }).catch((error) => {
            })
            .finally(() => {
                $('.update-coupon-btn').html('Apply Coupon').removeAttr('disabled');
               
            });
        },
        //Default
        getAddressData: function (addressData, placeResultData, id) {
            this.address = addressData;
        },
        addNewBillingAddress() {
            this.errors.reset();
            this.form.newBillingAddress = ! this.form.newBillingAddress;
            // if(this.form.newBillingAddress)
            //this.initAutocomplete();
        },

        addNewShippingAddress() {
            this.errors.reset();

            this.form.newShippingAddress = ! this.form.newShippingAddress;
        },

        changeBillingCity(city) {
            this.$set(this.form.billing, 'city', city);
        },

        changeShippingCity(city) {
            this.$set(this.form.shipping, 'city', city);
        },

        changeBillingZip(zip) {
            this.$set(this.form.billing, 'zip', zip);
        },

        changeShippingZip(zip) {
            this.$set(this.form.shipping, 'zip', zip);
        },

        changeBillingCountry(country) {
            this.$set(this.form.billing, 'country', country);

            this.fetchStates(country, (states) => {
                this.$set(this.states, 'billing', states);
                this.$set(this.form.billing, 'state', '');
            });
        },

        changeShippingCountry(country) {
            this.$set(this.form.shipping, 'country', country);

            this.fetchStates(country, (states) => {
                this.$set(this.states, 'shipping', states);
                this.$set(this.form.shipping, 'state', '');
            });
        },

        fetchStates(country, callback) {
            $.ajax({
                method: 'GET',
                url: route('countries.states.index', { code: country }),
            }).then(callback);
        },

        changeBillingState(state) {
            this.$set(this.form.billing, 'state', state);
        },

        changeShippingState(state) {
            this.$set(this.form.shipping, 'state', state);
        },

        changePaymentMethod(paymentMethod) {
            this.$set(this.form, 'payment_method', paymentMethod);
        },

        changeShippingMethod(shippingMethod) {
            this.$set(this.form, 'shipping_method', shippingMethod.name);
        },

        addTaxes() {
            this.loadingOrderSummary = true;

            $.ajax({
                method: 'POST',
                url: route('cart.taxes.store'),
                data: this.form,
            }).then((cart) => {
                store.updateCart(cart);
            }).catch((xhr) => {
                this.$notify(xhr.responseJSON.message);
            }).always(() => {
                this.loadingOrderSummary = false;
            });
        },

        addAddressTaxes(address , type) {
            this.loadingOrderSummary = true;

            this.$set(this.form, type, address);

            $.ajax({
                method: 'POST',
                url: route('cart.taxes.store'),
                data: this.form,
            }).then((cart) => {
                store.updateCart(cart);
            }).catch((xhr) => {
                this.$notify(xhr.responseJSON.message);
            }).always(() => {
                this.loadingOrderSummary = false;
            });
        },

        placeOrder() {
            if (! this.form.terms_and_conditions || this.placingOrder) {
                return;
            }

            if (! this.form.newBillingAddress && this.form.billingAddressId) {
                this.form.billing = this.addresses[this.form.billingAddressId];
            }

            if (
                this.form.ship_to_a_different_address
                && ! this.form.newShippingAddress
                && this.form.shippingAddressId
            ) {
                this.form.shipping = this.addresses[this.form.shippingAddressId];
            }

            this.placingOrder = true;

            $.ajax({
                method: 'POST',
                url: route('checkout.create'),
                data: this.form,
            }).then((response) => {
                if (response.redirectUrl) {
                    window.location.href = response.redirectUrl;
                } else if (this.form.payment_method === 'stripe') {
                    this.confirmStripePayment(response);
                } else if (this.form.payment_method === 'razorpay') {
                    this.confirmRazorpayPayment(response);
                } else {
                    this.confirmOrder(response.orderId, this.form.payment_method);
                }
            }).catch((xhr) => {
                if (xhr.status === 422) {
                    this.errors.record(xhr.responseJSON.errors);
                }

                this.$notify(xhr.responseJSON.message);

                this.placingOrder = false;
            });
        },

        confirmOrder(orderId, paymentMethod, params = {}) {
            $.ajax({
                method: 'GET',
                url: route('checkout.complete.store', { orderId, paymentMethod, ...params }),
            }).then(() => {
                window.location.href = route('checkout.complete.show');
            }).catch((xhr) => {
                this.placingOrder = false;
                this.loadingOrderSummary = false;

                this.deleteOrder(orderId);
                this.$notify(xhr.responseJSON.message);
            });
        },

        deleteOrder(orderId) {
            if (! orderId) {
                return;
            }

            $.ajax({
                method: 'GET',
                url: route('checkout.payment_canceled.store', { orderId }),
            });
        },

        renderPayPalButton() {
            let vm = this;
            let response;

            window.paypal.Buttons({
                async createOrder() {
                    try {
                        response = await $.ajax({
                            method: 'POST',
                            url: route('checkout.create'),
                            data: vm.form,
                        });

                        return response.resourceId;
                    } catch (xhr) {
                        if (xhr.status === 422) {
                            vm.errors.record(xhr.responseJSON.errors);
                        } else {
                            vm.$notify(xhr.responseJSON.message);
                        }
                    }
                },
                onApprove() {
                    vm.loadingOrderSummary = true;

                    vm.confirmOrder(response.orderId, 'paypal', response);
                },
                onError() {
                    vm.deleteOrder(response.orderId);
                },
                onCancel() {
                    vm.deleteOrder(response.orderId);
                },
            }).render('#paypal-button-container');
        },

        renderStripeElements() {
            this.stripeCardElement = this.stripe.elements().create('card', {
                hidePostalCode: true,
            });

            this.stripeCardElement.mount('#stripe-card-element');
        },

        async confirmStripePayment({ orderId, clientSecret }) {
            let result = await this.stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: this.stripeCardElement,
                    billing_details: {
                        email: this.form.customer_email,
                        name: `${this.form.billing.first_name} ${this.form.billing.last_name}`,
                        address: {
                            city: this.form.billing.city,
                            country: this.form.billing.country,
                            line1: this.form.billing.address_1,
                            line2: this.form.billing.address_2,
                            postal_code: this.form.billing.zip,
                            state: this.form.billing.state,
                        },
                    },
                },
            });

            if (result.error) {
                this.placingOrder = false;
                this.stripeError = result.error.message;

                this.deleteOrder(orderId);
            } else {
                this.confirmOrder(orderId, 'stripe', result);
            }
        },
        // initAutocomplete() {
        //     this.address1Field = document.querySelector("#autofill_address");
        //     this.autocomplete = new google.maps.places.Autocomplete(this.address1Field, {
        //         componentRestrictions: { country: window.supported_countries ?? ["us", "ca", "in"] },
        //         fields: ["address_components", "geometry"],
        //         types: ["address"],
        //     });
        //     this.address1Field.focus();
        //     this.autocomplete.addListener("place_changed", this.fillInAddress);
        // },
        fillInAddress() {
            this.form.billing = {};
            const place = this.autocomplete.getPlace();
            let postcode = "";
            let address1 = "";
    
            for (const component of place.address_components) {
                const componentType = component.types[0];
    
    
                switch (componentType) {
                case "street_number": {
                    address1 = `${component.long_name} ${address1}`;
                    break;
                }
    
                case "route": {
                    address1 += component.short_name;
                    break;
                }
    
                case "postal_code": {
                    postcode = `${component.long_name}${postcode}`;
                    break;
                }
    
                case "postal_code_suffix": {
                    postcode = `${postcode}-${component.long_name}`;
                    break;
                }
                case "locality":
                    this.form.billing.city = component.long_name;
                    break;
    
                case "administrative_area_level_1": {
                    this.form.billing.state = component.short_name;
                    break;
                }
                case "country":
                    this.form.billing.country = component.short_name;
                    break;
                }
            }
    
            this.form.billing.zip = postcode;
            this.form.billing.address_1 = address1;
            this.billingAddressSearch = "";
        },

        confirmRazorpayPayment(razorpayOrder) {
            this.placingOrder = false;

            let vm = this;

            new window.Razorpay({
                key: Lotus18.razorpayKeyId,
                name: Lotus18.storeName,
                description: `Payment for order #${razorpayOrder.receipt}`,
                image: Lotus18.storeLogo,
                order_id: razorpayOrder.id,
                handler(response) {
                    vm.placingOrder = true;

                    vm.confirmOrder(razorpayOrder.receipt, 'razorpay', response);
                },
                modal: {
                    ondismiss() {
                        vm.deleteOrder(razorpayOrder.receipt.receipt);
                    },
                },
                prefill: {
                    name: `${vm.form.billing.first_name} ${vm.form.billing.last_name}`,
                    email: vm.form.customer_email,
                    contact: vm.form.customer_phone,
                },
            }).open();
        },    
    },
};
