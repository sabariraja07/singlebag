(function ($) {
    
    new Vue({
        el: '#shop_app',
        data: {
            category_id: null,
            products: [],
            current_page: 1,
            total: 0,
            categories: [],
            brands:[],
            attributes: [],
            filter:{
                categories: [],
                attrs: [],
                order: 'desc',
                limit: 20
            }
        },
        watch: {},
        computed: {},
        created: function () {},
        mounted() {
            this.filter.term = this.getUrlParameter('term') ?? "";
            if($('#category_id').val())
            this.filter.category_id = $('#category_id').val();
            this.get_shop_attributes();
            this.get_shop_products();
        },
        methods: {
            get_shop_products: function(page = 1){
                console.log(this.filter);
                axios.get('/get_shop_products', { params : this.filter})
                .then(r => r.data)
                .then((response) => {
                    this.products = response.data;
                    this.current_page = response.current_page;
                    this.total = response.total;
                }).catch((error) => {
                });
            },
            getUrlParameter: function(sParam) {
                var sPageURL = window.location.search.substring(1),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;
            
                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');
            
                    if (sParameterName[0] === sParam) {
                        return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                    }
                }
                return null;
            },
            get_shop_attributes: function(){
                axios.get('/get_shop_attributes').then((response) => {
                    this.categories = response.data.categories;
                    this.brands = response.data.brands;
                    this.attributes = response.data.attributes;
                }).catch((error) => {
                });
            },
            pageLimit: function(limit){
                this.filter.limit = limit;
                this.hideDropDown();
                this.get_shop_products();
            },
            shortBy: function(order){
              this.filter.order = order;
              this.hideDropDown();
              this.get_shop_products();
            },
            add_product_to_cart: function(id){
                this.api_add_product_to_cart(id);
            },
            add_to_wishlist: function(id){
                this.api_add_to_wishlist(id);
            },
            hideDropDown: function(){
                $('.sort-by-cover').removeClass('show');
                $('.sort-by-dropdown').removeClass('show');
            }
        }
    });

})(jQuery);