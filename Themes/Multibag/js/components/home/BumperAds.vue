<template>
<div class="pt-80 pb-30 pl-30">
    <div class="banner-area">
        <div class="container-fuild p-0">
            <div class="row g-0">
                <div class="col-lg-12">
                    <div class="product-slider-active-2 nav-style-1">
                        <div class="banner-wrap banner-mrg-right-1" v-for="(banner, index) in banners" :key="index" :class=" index % 2 == 0 ? 'banner-bg-color-honeydew' : 'banner-bg-color-azure'">
                            <div class="row g-0 align-items-center">
                                <div class="col-lg-6 col-md-6 col-12 col-sm-6" :class="banner.position == 'right' ? 'order-last': ''">
                                    <div class="banner-content-2">
                                        <h2 v-if="banner.title != null" v-bind:style="{ color: banner.text_color }">{{ banner.title }}</h2>
                                        <p v-if="banner.description != null" v-bind:style="{ color: banner.text_color }">{{ banner.description  }}</p>
                                        <div class="btn-style-1 btn-hover">
                                            <a class="btn-border-1 btn-ptb-3" :href="banner.url" v-if="banner.btn_text">{{ banner.btn_text ? banner.btn_text : $trans('Shop Now')  }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 col-sm-6">
                                    <div class="banner-img banner-img-overflow">
                                        <a :href="banner.url"><img :src="banner.image" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: ['banners'],
    methods: {},
    mounted() {
        $('.product-slider-active-2').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            loop: true,
            dots: false,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 10000,
            // prevArrow: '<span class="slider-icon slider-icon-prev"><i class="icofont-simple-left"></i></span>',
            // nextArrow: '<span class="slider-icon slider-icon-next"><i class="icofont-simple-right"></i></span>',
            responsive: [{
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        autoplay: false,
                        slidesToShow: 1,
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
