<template>
<div class="categories-area bg-gray-7 pt-155 pb-155 padding-3-row-col fix">
    <div class="container">
        <div class="section-title-6 mb-65">
            <h2>{{ $trans('Featured Categories') }}</h2>
        </div>
        <div class="categories-wrapper">
            <div class="row">
                <div class="product-slider-active-2" >
                    <div class="mr-20" v-for="category in categories" :key="category.id">
                        <div class="single-categories-4 text-center mb-20">
                            <a :href="'category/' + category.slug + '/' + category.id">
                                <img :src="category.url" style="min-height:150px;" alt="">
                            </a>
                            <div class="categorie-content-5">
                                <h4><a :href="'category/' + category.slug + '/' + category.id">{{ category.name }}</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="banner-btn-4 banner-btn-4-modify banner-btn-4-right">
                <a href="/shop">View Collection <img class="inject-me" src="" alt=""></a>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: ['categories'],
        mounted() {
            $('.product-slider-active-2').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        loop: true,
        dots: false,
        arrows: false,
        // responsive: [{
        //         breakpoint: 1500,
        //         settings: {
        //             slidesToShow:6,
        //             slidesToScroll: 1,
        //         }
        //     },
        //     {
        //         breakpoint: 1199,
        //         settings: {
        //             slidesToShow: 4,
        //             slidesToScroll: 1,
        //         }
        //     },
        //     {
        //         breakpoint: 991,
        //         settings: {
        //             slidesToShow: 3,
        //             slidesToScroll: 1,
        //         }
        //     },
        //     {
        //         breakpoint: 767,
        //         settings: {
        //             autoplay: false,
        //             slidesToShow: 2,
        //             slidesToScroll: 1,
        //         }
        //     },
        //     {
        //         breakpoint: 575,
        //         settings: {
        //             autoplay: false,
        //             slidesToShow: 1,
        //             slidesToScroll: 1,
        //         }
        //     }
        // ]
    });
        }
    };

</script>
