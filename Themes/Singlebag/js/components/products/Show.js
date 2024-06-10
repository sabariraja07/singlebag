import store from '../../store';
import Errors from '../../Errors';
import StarRating from 'vue-star-rating';
import ProductCardMixin from '../../mixins/ProductCardMixin';
//import RelatedProducts from './show/RelatedProducts.vue';
import axios from 'axios';

export default {
    components: { StarRating },

    mixins: [
        ProductCardMixin,
    ],

    props: ['product', 'avgRating'],

    data() {
        return {
            // price: this.product.formatted_price,
            activeTab: 'description',
            currentReviewPage: 1,
            fetchingReviews: false,
            reviews: [],
            // addingNewReview: false,
            // reviewForm: {},
            // inquiryForm:{
            //     product_id: this.product.id,
            //     quantity: 1,
            // },
            // cartItemForm: {
            //     product_id: this.product.id,
            //     qty: 1,
            //     options: {},
            // },
            errors: new Errors(),
            variation:[],
            qty: 1,
            form:{},
            option: {},
            reviewValidationErrors: null,
        };
    },

    computed: {
        totalReviews() {
            if (! this.reviews.total) {
                return this.reviewCount;
            }

            return this.reviews.total;
        },

        ratingPercent() {
            return (this.avgRating / 5) * 100;
        },

        emptyReviews() {
            return this.totalReviews === 0;
        },

        totalReviewPage() {
            return Math.ceil(this.reviews.total / 10);
        },
    },

    created() {
    },

    mounted() {
        // $(this.$refs.upSellProducts).slick({
        //     rows: 0,
        //     dots: false,
        //     arrows: true,
        //     infinite: true,
        //     slidesToShow: 1,
        //     slidesToScroll: 1,
        //     rtl: window.singlebag.rtl,
        // });

        this.fetchReviews();
    },

    methods: {
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
            } 
            // else {
                this.$set(this.option, optionId, valueId);

                // this.errors.clear(`options.${optionId}`);
            // }
            this.updatePrice();
        },

        updatePrice: function(){
            var price = $('.current-price').data('price') ?? 0;
            var regular_price = $('.current-price').data('regular-price') ?? 0;
            var new_price = price;
            $('.option-check .options:checked').each(function () {
                var amount = $(this).data('amount') ?? 0;
                var amount_type = $(this).data('amount-type') ?? 0;

                if(amount_type == 1){
                    new_price += amount;
                } else{
                    new_price += parseFloat((price * (amount * 0.01).toFixed(2)));
                }
            });

            if($('.save-price').length != 0){
                var saved_price = 0;
                regular_price = regular_price + (new_price - price);
                saved_price = parseFloat(((regular_price - new_price) / regular_price) * 100).toFixed(2);
                $('.save-price').html( this.$formatPrice(saved_price) + ' % Off');
                $('.old-price').html( this.$formatPrice(regular_price));
            }

            $('.current-price').html(this.$formatPrice(new_price));
        },

        updateQuantity(qty) {
            if (qty < 1 || this.exceedsMaxStock(qty)) {
                return;
            }

            if (isNaN(qty)) {
                qty = 1;
            }

            // this.cartItemForm.qty = qty;
            this.qty = qty;
        },

        exceedsMaxStock(qty) {
            return this.product.stock.stock_manage && this.product.stock.stock_qty < qty;
        },

        updateReview: function(id){
            this.reviewValidationErrors = null;
            $('.button-contactForm').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Send Review').attr('disabled');
            axios.post('/make-review/' + id, this.form)
            .then(r => r.data)
            .then((response) => {
                this.form = {};
                this.fetchReviews();
                this.$notify('Your review updated successfully.');
            }).catch((error) => {
                if (error.response.status == 422){
                    this.reviewValidationErrors = error.response.data.errors;
                    window.scrollTo(0,0);
                }
                // if(error.response.data.message){
                //     this.$notify(error.response.data.message, 'error');
                // }
            })
            .finally(() => {
                $('.button-contactForm').html('Send Review').removeAttr('disabled');
            });
        },

        fetchReviews(page = 1) {
            this.fetchingReviews = true;
            this.currentReviewPage = page;

            axios.get('/product-reviews/' + this.product.id , { params: { page : page }})
            .then(r => r.data)
            .then((response) => {
                this.reviews = response;
                this.currentReviewPage = response.current_page;
            }).catch((error) => {
            })
            .finally(() => {
                this.fetchingReviews = false;
            });
        },

        addCart: function(event){
            if(this.checkRequiredOptionValidation() == false) return;
            this.addingToCart = true;
            axios.post('/add-to-cart', {'id' : this.product.id, 'variation' : this.variation, 'qty' : this.qty, 'option': this.option })
            .then(r => r.data)
            .then((cart) => {
                this.$notify(this.$trans('Product added to your cart'));
                this.option = {};
                $('#add-to-cart-form')[0].reset();
                store.updateCart(cart);
            }).catch((error) => {
                this.$notify('Product not added to your cart', 'error');
            })
            .finally(() => {
                this.addingToCart = false;
            });
        },

        checkRequiredOptionValidation(){
            var required_options =  [];
            var is_valid = true;
            
            this.product.options.forEach(option => {
                if(option.is_required == 1){
                    required_options.push(option.id);
                }
            });
            for (let index = 0; index < required_options.length; index++) {
                if(required_options.length > 0){
                    if(this.option[required_options[index]] === undefined || this.option[required_options[index]] == null || (Array.isArray(this.option[required_options[index]]) && this.option[required_options[index]].length == 0)){
                      
                        this.$notify('Please Select required options.', 'error');
                        is_valid = false;
                        break;
                    }
                }
            }

            return is_valid;
        }

        // addToCart() {
        //     this.addingToCart = true;

        //     $.ajax({
        //         method: 'POST',
        //         url: route('cart.items.store', this.cartItemForm),
        //     }).then((cart) => {
        //         store.updateCart(cart);

        //         $('.header-cart').trigger('click');
        //     }).catch((xhr) => {
        //         if (xhr.status === 422) {
        //             this.errors.record(xhr.responseJSON.errors);
        //         } else {
        //             this.$notify(xhr.responseJSON.message);
        //         }
        //     }).always(() => {
        //         this.addingToCart = false;
        //     });
        // },

    },
};
