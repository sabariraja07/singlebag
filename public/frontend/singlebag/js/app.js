(function ($) {

    window.app = new Vue({
        el: '#app',
        data: {
            cart: [],
            wishlist: [],
            menu_categories: [],
            filter:{
                category_id: null,
                term: '',
                categories: [],
                limit:10,
                order:'desc'
            },
            suggestions: [],
            suggestion_items: 0,
            params: {
                latest_product: 0,
                random_product: 0,
                trending_products: 0,
                best_selling_product: 0,
                sliders: 0,
                menu_category: 1,
                bump_adds: 0,
                banner_adds: 0,
                get_offerable_products: 0
            }
        },
        watch: {},
        computed: {},
        created: function () {},
        mounted() {
            this.get_cart();
            this.get_products();
            this.get_wishlist_count();
        },
        methods: {
            get_shop_products: function(page = 1){
                if(this.filter.category_id) this.filter.categories[0] = this.filter.category_id;
                else this.filter.categories = [];
                axios.get('/get_shop_products', { params : this.filter})
                .then(r => r.data)
                .then((response) => {
                    this.suggestions = response.data;
                    this.suggestion_items = response.total;
                }).catch((error) => {
                });
            },
            get_products: function () {
                axios.get('/get_home_page_products', {
                        params: this.params
                    })
                    .then(r => r.data)
                    .then((response) => {
                        this.menu_categories = response['get_menu_category'];
                    });
            },
            get_wishlist_count: function(){
                axios.get('/wishlist-count').then((response) => {
                    this.wishlist = response.data;
                    return response.data;
                }).catch((error) => {
                });
            },
            get_cart: function(){
                axios.get('/get_cart').then((response) => {
                    this.cart = response.data;
                    console.log(response.data);
                    return response.data;
                }).catch((error) => {
                    
                });
            },
            add_to_cart: function(id, options){

            },
            add_product_to_cart: function(id){
                axios.get('/add_to_cart/' + id).then((response) => {
                    this.cart = response.data;
                    return response.data;
                }).catch((error) => {
                    
                });
            },
            remove_cart: function(id){
                axios.get('/remove_cart' , {params: {'id' : id}}).then((response) => {
                    this.cart = response.data;
                    console.log(response.data);
                    return response.data;
                }).catch((error) => {
                    
                });
            },
            get_wishlist: function(){
                axios.get('/get-wishlist/' + id).then((response) => {
                    this.wishlist = response.data;
                    this.get_wishlist_count();
                    return response.data;
                }).catch((error) => {
                });
            },
            add_to_wishlist: function(id){
                axios.get('/add_to_wishlist/' + id).then((response) => {
                    this.wishlist = response.data;
                    this.get_wishlist_count();
                    return response.data;
                }).catch((error) => {
                });
            },
            remove_wishlist: function(id){

            }
        }
    });

})(jQuery);