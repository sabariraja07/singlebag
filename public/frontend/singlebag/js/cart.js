(function ($) {
    
    new Vue({
        el: '#cart_app',
        data: {
            cart: {
                items: null,
            },
            coupon: null,
        },
        watch: {},
        computed: {},
        created: function () {},
        mounted() {
            this.get_cart();
            // window.app.get_products();
        },
        updated: function () {
        },
        methods: {
            get_cart: function(){
                $('.get-cart-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Update Cart').attr('disabled');
                axios.get('/get_cart').then((response) => {
                    this.cart = response.data;
                    //Sweet('success','Link copied to clipboard.');
                }).catch((error) => {
                })
                .finally(() => {
                $('.get-cart-btn').html('<i class="fi-rs-refresh mr-10"></i> Update Cart').removeAttr('disabled');
                });
            },
            add_to_cart: function(id, options){

            },
            update_cart: function(id){
                axios.get('/add_to_cart/' + id).then((response) => {
                    this.cart = response.data;
                    window.app.get_cart();
                }).catch((error) => {
                });
            },
            remove_cart: function(id){
                $('.remove-cart-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                axios.get('/remove_cart' , {params: {'id' : id}}).then((response) => {
                    this.cart = response.data;
                    window.app.get_cart();
                }).catch((error) => {
                })
                .finally(() => {
                    $('.remove-cart-btn').html('<i class="fi-rs-trash"></i>').removeAttr('disabled');
                });
            },
            clear_cart: function(){
                $('.clear-cart-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Clear Cart').attr('disabled');
                axios.get('/cart-clear').then((response) => {
                    this.cart = response.data;
                    window.app.get_cart();
                }).catch((error) => {
                })
                .finally(() => {
                    $('.clear-cart-btn').html('<i class="fi-rs-trash mr-5"></i> Clear Cart').removeAttr('disabled');
                });
            },
            applyCoupon: function(){
                console.log('in apply coupon');
                $('.update-coupon-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Apply').attr('disabled');
                axios.post('/apply_coupon', {'code': this.coupon}).then((response) => {
                    this.get_cart();
                    window.app.get_cart();
                    console.log(response.data);
                }).catch((error) => {
                })
                .finally(() => {
                    $('.update-coupon-btn').html('<i class="fi-rs-label mr-5"></i>Apply').removeAttr('disabled');
                });
            }
        }
    });

    function Sweet(icon,title,time=3000){
		
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: time,
			timerProgressBar: true,
			onOpen: (toast) => {
				toast.addEventListener('mouseenter', Swal.stopTimer)
				toast.addEventListener('mouseleave', Swal.resumeTimer)
			}
		})

		
		Toast.fire({
			icon: icon,
			title: title,
		})
	}


})(jQuery);