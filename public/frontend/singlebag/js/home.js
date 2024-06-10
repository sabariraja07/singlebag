(function ($) {
    
    new Vue({
        el: '#home_app',
        data: {
            message: "gfgf",
            rating: 3,
            latest_products: [],
            random_products: [],
            trending_products: [],
            best_selling_products: [],
            sliders: [],
            menu_categories: [],
            bump_adds: [],
            banner_adds: [],
            get_offerable_products: [],
            params: {
                latest_product: 1,
                random_product: 1,
                trending_products: 1,
                best_selling_product: 1,
                sliders: 1,
                menu_category: 1,
                bump_adds: 1,
                banner_adds: 1,
                get_offerable_products: 1
            }
        },
        watch: {},
        computed: {},
        created: function () {},
        mounted() {
            this.get_trending_products();
            // window.app.get_products();
        },
        updated: function () {

            $(".hero-slider-1").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                loop: true,
                dots: true,
                arrows: true,
                prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-angle-left"></i></span>',
                nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-angle-right"></i></span>',
                appendArrows: ".hero-slider-1-arrow",
                autoplay: true
            });

            $(".carausel-10-columns").each(function (key, item) {
                var id = $(this).attr("id");
                var sliderID = "#" + id;
                var appendArrowsClassName = "#" + id + "-arrows";

                $(sliderID).slick({
                    dots: false,
                    infinite: true,
                    speed: 1000,
                    arrows: true,
                    autoplay: false,
                    slidesToShow: 10,
                    slidesToScroll: 1,
                    loop: true,
                    adaptiveHeight: true,
                    responsive: [{
                            breakpoint: 1025,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        }
                    ],
                    prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
                    nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
                    appendArrows: appendArrowsClassName
                });
            });

        },
        methods: {
            get_trending_products: function () {
                axios.get('/get_home_page_products', {
                        params: this.params
                    })
                    .then(r => r.data)
                    .then((response) => {
                        console.log(response);
                        this.latest_products = response['get_latest_products'];
                        this.random_products = response['get_random_products'];
                        this.trending_products = response['get_trending_products'];
                        this.best_selling_products = response['get_best_selling_product'];
                        this.sliders = response['sliders'];
                        this.menu_categories = response['get_menu_category'];
                        this.bump_adds = response['bump_adds'];
                        this.banner_adds = response['banner_adds'];
                        this.get_offerable_products = response['get_offerable_products'];
                    });
            },
            add_product_to_cart: function(id){
                this.api_add_product_to_cart(id);
            },
            add_to_wishlist: function(id){
                this.api_add_to_wishlist(id);
            }
        }
    });

})(jQuery);
