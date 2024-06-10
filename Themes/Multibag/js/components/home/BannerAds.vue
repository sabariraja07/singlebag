<template>
<div class="pt-50 pb-30">
    <div class="container-fuild">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="product-slider-active-2 nav-style-1">
                    <div class="banner-wrap ml-30" v-for="(banner, index) in banners" :key="index">
                        <div class="banner-img banner-img-overflow">
                            <a :href="banner.url"><img :src="banner.image" alt=""></a>
                        </div>
                        <div class="banner-content-23 banner-content-23-position2" v-if="banner.title == null">
                            <div class="banner-btn-4 black">
                                <a :href="banner.url" v-if="banner.btn_text">{{ banner.btn_text ? banner.btn_text : $trans('Shop Now')  }}
                                    <svg xmlns="http://www.w3.org/2000/svg" id="arrow" width="18.502" height="8.223" viewBox="0 0 18.502 8.223" class="injected-svg inject-me arrow-mrg-dec">
                                        <path id="Path_698" data-name="Path 698" d="M.1,139.884a.334.334,0,0,1,.246-.1H13.705v-2.4a.323.323,0,0,1,.2-.311.334.334,0,0,1,.375.064l4.112,3.79a.351.351,0,0,1,.107.257.338.338,0,0,1-.107.246l-4.112,3.748a.344.344,0,0,1-.375.054.31.31,0,0,1-.2-.31v-2.4H.342A.33.33,0,0,1,0,142.187v-2.056A.334.334,0,0,1,.1,139.884Z" transform="translate(0 -137.048)" fill="#885643"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="banner-content-23" v-else :class="banner.position == 'right' ? 'banner-content-23-position1' : 'banner-content-23-position2'">
                            <h3 v-if="banner.title != null" v-bind:style="{ color: banner.text_color }">{{ banner.title }}</h3>
                            <h2 v-if="banner.description != null" v-bind:style="{ color: banner.text_color }">{{ banner.description  }}</h2>
                            <div class="banner-btn-4 black">
                                <a :href="banner.url" v-if="banner.btn_text">{{ banner.btn_text ? banner.btn_text : $trans('Shop Now')  }}
                                    <svg xmlns="http://www.w3.org/2000/svg" id="arrow" width="18.502" height="8.223" viewBox="0 0 18.502 8.223" class="injected-svg inject-me arrow-mrg-dec">
                                        <path id="Path_698" data-name="Path 698" d="M.1,139.884a.334.334,0,0,1,.246-.1H13.705v-2.4a.323.323,0,0,1,.2-.311.334.334,0,0,1,.375.064l4.112,3.79a.351.351,0,0,1,.107.257.338.338,0,0,1-.107.246l-4.112,3.748a.344.344,0,0,1-.375.054.31.31,0,0,1-.2-.31v-2.4H.342A.33.33,0,0,1,0,142.187v-2.056A.334.334,0,0,1,.1,139.884Z" transform="translate(0 -137.048)" fill="#885643"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<style scoped>
.banner-content-23.banner-content-23-position1{
    text-align: right;
}
.banner-wrap .banner-content-23 h2 {
    font-size: 20px !important;
}
</style>
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
