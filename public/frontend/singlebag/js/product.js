(function ($) {
    
    new Vue({
        el: '#product_app',
        data: {
            product: {},
            rating:1,
            variation:[],
            qty: 1,
            form:{},
            ratings: [],
            option: {},
        },
        watch: {},
        computed: {},
        created: function () {},
        mounted() {
        },
        methods: {
            get_products: function () {
                axios.get('/get_home_page_products', {
                        params: this.params
                    })
                    .then(r => r.data)
                    .then((response) => {
                        console.log("response");
                        console.log(response);
                    });
            },
            addCart: function(id){
                axios.post('/addtocart', {'id' : id, 'variation' : this.variation, 'qty' : this.qty, 'option': this.option })
                .then(r => r.data)
                .then((response) => {
                    window.app.get_cart();
                }).catch((error) => {
                })
                .finally(() => {
                    hideBtnCartLoader();
                });
            },
            qtyDown: function(){
              if(this.qty > 1){
                  this.qty = this.qty - 1;
              }else{
                  this.qty = 1;
              }
            },
            qtyUp: function(){
                this.qty = this.qty + 1;
            },
            add_to_wishlist: function(id){
            //   this.api_add_to_wishlist(id).then((response) => {
            //     hideWishlistLoader();
            //   });
              axios.get('/add_to_wishlist/' + id)
                .then(r => r.data)
                .then((response) => {
                    window.app.get_wishlist_count();
                }).catch((error) => {
                })
                .finally(() => {
                    hideWishlistLoader();
                });
            },
            updateReview: function(id){
                $('.button-contactForm').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Send Review').attr('disabled');
                axios.post('/make-review/' + id, this.form)
                .then(r => r.data)
                .then((response) => {
                    this.form = {};
                }).catch((error) => {
                })
                .finally(() => {
                    $('.button-contactForm').html('Send Review').removeAttr('disabled');
                });
            },
            updateCheckboxTypeOptionValue(optionId, e) {
                let values = $(e.target)
                    .parents('.option-check')
                    .find('input[type="checkbox"]:checked')
                    .map((_, el) => {
                        return el.value;
                    });
    
                this.$set(this.option, optionId, values.get());

                this.updatePrice();
            },

            customRadioTypeOptionValueIsActive(optionId, valueId) {
                if (! this.option.hasOwnProperty(optionId)) {
                    return false;
                }
    
                return this.option[optionId] === valueId;
            },
    
            syncCustomRadioTypeOptionValue(optionId, valueId) {
                if (this.customRadioTypeOptionValueIsActive(optionId, valueId)) {
                    this.$delete(this.option, optionId);
                } else {
                    this.$set(this.option, optionId, valueId);
    
                    // this.errors.clear(`options.${optionId}`);
                }

                this.updatePrice();
            },

            updatePrice: function(){
                var price = parseFloat($('.current-price').data('price') ?? 0);
                var regular_price = parseFloat($('.current-price').data('regular-price') ?? 0);
                var new_price = price;
                $('.option-check .options:checked').each(function () {
                    var amount = parseFloat($(this).data('amount') ?? 0);
                    var amount_type = parseFloat($(this).data('amount-type') ?? 0);

                    if(amount_type == 1){
                        new_price += amount; 
                    } else{
                        new_price += (price + (amount / 100));
                    }
                });

                if($('.save-price').length != 0){
                    var saved_price = 0;
                    regular_price = regular_price + (new_price - price);
                    saved_price = (((regular_price - new_price) / regular_price) * 100).toFixed(0);
                    $('.save-price').html( currncy_format(saved_price) + ' % Off');
                    $('.old-price').html( currncy_format(regular_price));
                }

                $('.current-price').html(currncy_format(new_price));
            }
        }
    });

})(jQuery);