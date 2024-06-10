<template>
    <div class="home-slide-cover mt-30">
        <div class="hero-slider-1 style-5 dot-style-1 dot-style-1-position-2" id="" ref="homeSliderContainer">
            <div v-for="(slider, index) in sliders" :key="index" class="single-hero-slider single-animation-wrap"
                v-bind:style="{ backgroundImage: 'url(' + slider.image + ')' }">
                <div class="slider-content" v-if="slider.position == 'left'">
                    <h1 class="display-2 mb-40" style="font-size: 25px !important;" v-bind:style="{ color: slider.text_color }">
                        {{ slider.title }}
                    </h1>
                     <p class="display-2 mb-40" v-bind:style="{ color: slider.text_color }">
                        {{ slider.description }}
                    </p>
                    <a :href="slider.url" class="btn btn-xs mb-50">
                        {{ slider.btn_text }} 
                    </a>
                </div>
                <div class="hero-slider-left"  v-else>
                    <h1 class="display-2 mb-40" v-bind:style="{ color: slider.text_color }">
                        {{ slider.title }}
                    </h1>
                     <p class="hero-slider-para" v-bind:style="{ color: slider.text_color }">
                        {{ slider.description }}
                    </p>
                    <a :href="slider.url" class="hero-slider-left2">
                        {{ slider.btn_text }} 
                    </a>
                </div>
            </div>
        </div>
        <div class="slider-arrow hero-slider-1-arrow"></div>
    </div>
    
</template>

<script>
    export default {
        props: ['sliders'],
        updated: function(){
        },
        mounted() {

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
            // this.$nextTick(function () {
            //     $(this.$refs.homeSliderContainer).slick({
            //         slidesToShow: 1,
            //         slidesToScroll: 1,
            //         fade: true,
            //         loop: true,
            //         dots: true,
            //         arrows: true,
            //         prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-angle-left"></i></span>',
            //         nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-angle-right"></i></span>',
            //         appendArrows: ".hero-slider-1-arrow",
            //         autoplay: true
            //     });
            // });
        }
    };

</script>
