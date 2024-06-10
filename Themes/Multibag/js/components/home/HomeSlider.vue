<template>
    <div class="slider-area bg-light-green slider-mt-1">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-12">
                    <div class="slider-area">
                        <div class="slider-active-5 dot-style-6 dot-style-6-position-1">
                            <div class="single-slider slider-height-13 custom-d-flex custom-align-item-center bg-img" 
                            v-for="(slider, index) in sliders" :key="index"
                            v-bind:style="{ 'background-image': 'url(' + slider.image + ')' }">
                                <div class="container">
                                    <div class="slider-content-12 slider-animated-1" :class="slider.position == 'right' ? 'slider-content-right' : ''">
                                        <h1 class="animated" v-if="slider.title" v-bind:style="{ color: slider.text_color }">{{ slider.title }}</h1>
                                        <p class="animated" v-if="slider.description" v-bind:style="{ color: slider.text_color }">
                                            {{ slider.description }}
                                        </p>
                                        <div class="btn-style-3 btn-hover">
                                            <a class="btn3-ptb-6 btn3-bg-green-3 animated" :href="slider.url" tabindex="0">
                                                {{ slider.btn_text }}
                                            </a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-3">
                    <div class="banner-wrap ml-15">
                        <div class="banner-img fix">
                            <a href="product-details.html"><img src="assets/images/banner/banner-54.jpg" alt="banner"></a>
                        </div>
                        <div class="banner-content-21">
                            <h3><span>35%</span> off</h3>
                            <p>Modern Furniture</p>
                        </div>
                        <div class="banner-btn-4 black text-center mega-fashion1-banner-btn">
                            <a href="shop.html">Browse Store <img class="inject-me" src="../../../assets/images/icon-img/right-arrow-banner-2.svg" alt=""></a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

</template>
<style scoped>
.slider-content-right {
    text-align: right;
}
.slider-content-right  p{
    width: 100%;
}
</style>
<script>
export default {
    props: ['sliders'],
    updated: function () {},
    mounted() {
        $('.slider-active-5').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            loop: true,
            dots: true,
            arrows: false,
            prevArrow: '<span class="slider-icon slider-icon-prev"><i class="icofont-simple-left"></i></span>',
            nextArrow: '<span class="slider-icon slider-icon-next"><i class="icofont-simple-right"></i></span>',
        });
    }
};
</script>
