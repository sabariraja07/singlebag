<template>
<div class="product-area pt-30 pb-30 fix">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-8">
                <div class="section-title-8">
                    <h2 class="bold">{{ $trans('All Deals') }}</h2>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="banner-btn-4 black-2 banner-btn-4-right banner-btn-4-mrg-none">
                    <a :href="'/shop?type=offer'">{{ $trans('Browse All Products') }} 
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="7.373" viewBox="0 0 15 7.373" class="injected-svg inject-me arrow-mrg-dec">
                            <path id="Icon_awesome-long-arrow-alt-down" data-name="Icon awesome-long-arrow-alt-down" d="M5.026,4.489V14.6a.4.4,0,0,1-.4.4H2.749a.4.4,0,0,1-.4-.4V4.489H.805A.8.8,0,0,1,.237,3.117L3.118.235a.8.8,0,0,1,1.136,0L7.136,3.117a.8.8,0,0,1-.568,1.372H5.026Z" transform="translate(15) rotate(90)" fill="#333"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-100">
            <!-- <div class="col-lg-4 col-md-4">
                <div class="section-title-7 mb-30">
                    <h2>Flagship Sale <br>Up to  70% Off</h2>
                    <div class="banner-btn-4 banner-btn-4-automobile">
                        <a href="shop.html">Browse All Categories <img class="inject-me" src="assets/images/icon-img/right-arrow-banner.svg" alt=""></a>
                    </div>
                </div>
            </div> -->
            <div class="col-lg-12">
                <div class="product-slider-active-1 nav-style-1">
                    <offer-product-card v-for="product in products" :key="product.id" :product="product"></offer-product-card>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="product-slider-active-1 nav-style-1">
        <div class="deal-area bg-gray deal-area-padding pt-15" v-for="product in products" :key="product.id">
            <div class="container">
                <offer-product-card v-for="product in products" :key="product.id" :product="product"></offer-product-card>
                <offer-product-card-view  :product="product"></offer-product-card-view>
            </div>
        </div>
    </div> -->
</template>

<script>
import OfferProductCard from '../products/OfferProductCard.vue';
import OfferProductCardView from '../products/OfferProductCardView.vue';

export default {
    components: {
        OfferProductCard,
        OfferProductCardView
    },
    props: ['products'],
    mounted() {
        $('.product-slider-active-1').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            loop: true,
            dots: false,
            arrows: true,
            autoplay: true,
            autoplaySpeed: 10000,
            prevArrow: '<span class="slider-icon slider-icon-prev"><i class="icofont-simple-left"></i></span>',
            nextArrow: '<span class="slider-icon slider-icon-next"><i class="icofont-simple-right"></i></span>',
            responsive: [{
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        autoplay: false,
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        autoplay: false,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    }
};
</script>
