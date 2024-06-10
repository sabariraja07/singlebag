import { trans } from '../../functions';
import axios from 'axios';

export default {
    props: [
        'categoryId'
    ],

    data() {
        return {
            products: [],
            current_page: 1,
            totalPages: 0,
            total: 0,
            categories: [],
            brands:[],
            attributes: [],
            filter:{
                categories: [],
                attrs: [],
                order: 'desc',
                limit: 20,
                page: 1,
                category_id: this.categoryId,
                type: null,
                brands: [],
            },
        };
    },

    computed: {
    },

    mounted() {
        this.filter.term = this.getUrlParameter('term') ?? "";
        this.filter.type = this.getUrlParameter('type') ?? "";
        this.filter.brand = this.getUrlParameter('brand') ?? "";
        this.get_shop_attributes();
        this.get_shop_products();
    },

    methods: {
        get_shop_products: function(page = 1){
            this.filter.page = page;
            axios.get('/get_shop_products', { params : this.filter})
            .then(r => r.data)
            .then((response) => {
                this.products = response.data;
                this.current_page = response.current_page;
                this.total = response.total
                this.totalPages = Math.ceil(this.total / this.filter.limit);
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
    },
};
