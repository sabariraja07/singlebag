"use strict";

function currncy_format(price = 0) {
    // price = price.toFixed(2);
    var currency_position = $('#currency_position').val();
    var currency_name = $('#currency_name').val();
    var currency_icon = $('#currency_icon').val();

    if (currency_position == 'left') {
        var currency = currency_icon + price;
    } else {
        var currency = price + currency_icon;
    }
    return currency;
}

Vue.component('star-rating', VueStarRating.default);

Vue.directive('select', {
    twoWay: true,
    bind: function (el, binding, vnode) {
        $(el).select2().on("select2:select", (e) => {
        el.dispatchEvent(new Event('change', { target: e.target }));
        });
    },
});

Vue.component('select-component',{
	props: ['options'],
  mounted() {
  	var self = this;
  	this.$select2 = $(this.$el).select2()
    .on('change', function(e){
    	self.$emit('input', $(e.target).val());
    });
  },
  
  watch: {
  	options (newOpts) {
    	this.$select2.empty().select2({
      	data: newOpts
			});
		}, 
  },
  template: '<select> <option v-for="option in options" :key="option" :value="option.id">{{ option.name }}</option></select>'
});

Vue.component('select2', {
    props: ['options', 'value'],
    template: '<select><slot></slot></select>',
    mounted: function () {
      var vm = this
      $(this.$el)
        .val(this.value)
        // init select2
        .select2({ data: this.options })
        .trigger('change')
        // emit event on change.
        .on('change', function () {
          vm.$emit('input', this.value)
        });
    },
    watch: {
      value: function (value) {
        // update value
        $(this.$el).val(value).trigger('change');
        this.$emit('change',value);
      },
      options: function (options) {
        // update options
        $(this.$el).select2({ data: options });
      }
    },
    destroyed: function () {
      $(this.$el).off().select2('destroy')
    }
  });

Vue.component('validation-errors', {
    data(){
        return {
            
        }
    },
    props: ['errors'],
    template: `<div v-if="validationErrors">
                <ul class="alert alert-danger">
                    <li v-for="(value, key, index) in validationErrors">{{ value }}</li>
                </ul>
            </div>`,
    computed: {
        validationErrors(){
            let errors = Object.values(this.errors);
            errors = errors.flat();
            return errors;
        }
    }
});

Vue.mixin({
    methods: {
        format_price: function (price = 0) {
            return (window.currency ? window.currency.currency_icon : "") + price;
        },
        getRating: function(rating){
            return rating;
        },
        addTocart: function(data){
            axios.post('/addtocart', data).then((response) => {
                return response.data;
            }).catch((error) => {
                return error;
            });
        },
        api_get_cart: function(){
            axios.get('/get_cart').then((response) => {
            }).catch((error) => {
            })
            .finally(() => {
            });
        },
        api_clear_cart: function(){
            axios.get('/cart-clear').then((response) => {
            }).catch((error) => {
            })
            .finally(() => {
            });
        },
        api_add_to_wishlist: function (id) {
            axios.get('/add_to_wishlist/' + id).then((response) => {
                window.app.get_wishlist_count();
            }).catch((error) => {
            })
            .finally(() => {
                hideWishlistLoader();
            });
        },
        api_add_product_to_cart: function(id, params = {}){
            axios.get('/add_to_cart/' + id , { params: params}).then((response) => {
                window.app.get_cart();
            }).catch((error) => {
            })
            .finally(() => {
                hideCartLoader();
            });
        },
        api_add_to_cart: function(data){
            axios.post('/addtocart', data).then((response) => {
                return response.data;
            }).catch((error) => {
                return error;
            });
        },
    },
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