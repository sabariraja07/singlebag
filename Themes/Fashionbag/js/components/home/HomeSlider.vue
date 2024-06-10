<template>
    <!-- Hero/Intro Slider Start -->
    <div class="section">
        <div class="hero-slider">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <!-- Single Hero Slider Item Start -->
                    <div class="hero-slide-item-two swiper-slide" v-for="(slider, index) in sliders" :key="index">

                        <!-- Hero Slider Background Image Start-->
                        <div class="hero-slide-bg">
                            <img :src="slider.image" alt="" />
                        </div>
                        <!-- Hero Slider Background Image End-->

                        <!-- Hero Slider Container Start -->
                        <div class="container">

                            <div class="row" :class="slider.position == 'right' ? 'justify-content-end' : ''">
                                <div class="hero-slide-content col-lg-8 col-xl-6 col-12 text-lg-center text-left">
                                    <h2 v-if="slider.title" v-bind:style="{ color: slider.text_color }" class="title">
                                        {{ slider.title }}
                                    </h2>
                                    <p v-if="slider.description" v-bind:style="{ color: slider.text_color }">{{ slider.description }}</p>
                                    <a :href="slider.url" class="btn btn-lg btn-fb btn-hover-dark">{{ slider.btn_text }}</a>
                                </div>
                            </div>

                        </div>
                        <!-- Hero Slider Container End -->

                    </div>
                    <!-- Single Hero Slider Item End -->
                </div>

                <!-- Swiper Pagination Start -->
                <div class="swiper-pagination d-md-none"></div>
                <!-- Swiper Pagination End -->

                <!-- Swiper Navigation Start -->
                <div class="home-slider-prev swiper-button-prev main-slider-nav d-md-flex d-none"><i class="pe-7s-angle-left"></i></div>
                <div class="home-slider-next swiper-button-next main-slider-nav d-md-flex d-none"><i class="pe-7s-angle-right"></i></div>
                <!-- Swiper Navigation End -->

            </div>
        </div>
    </div>
    <!-- Hero/Intro Slider End -->
    
</template>

<script>
    export default {
        props: ['sliders'],
        updated: function(){
        },
        mounted() {

             var swiper = new Swiper('.hero-slider .swiper-container', {
        loop: true,
        speed: 1150,
        spaceBetween: 30,
        slidesPerView: 1,
		effect: 'fade',
		pagination: true,
		navigation: true,


        // Navigation arrows
        navigation: {
            nextEl: '.hero-slider .home-slider-next',
            prevEl: '.hero-slider .home-slider-prev'
        },
        pagination: {
            el: '.hero-slider .swiper-pagination',
			type: 'bullets',
			clickable: 'true'
		},
		// Responsive breakpoints
	});
        }
    };

</script>
