<template>
    <!-- Brand Logo Start -->
    <div class="section">
        <div class="container">
            <div class="border-top">

                <div class="row">
                <div class="col-12">
                    <!-- Brand Logo Wrapper Start -->
                    <div class="brand-logo-carousel">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">

                                <!-- Single Brand Logo Start -->
                                <div class="swiper-slide single-brand-logo" v-for="( brand, index) in brands" :key="index" data-aos="fade-up" :data-aos-delay="( 300 + (index * 100) )">
                                    <a :href="'shop?brand=' + brand.slug">
                                        <img :src="brand.image"
                                        height="84" alt="">
                                    </a>
                                </div>
                                <!-- Single Brand Logo End -->
                            </div>
                        </div>
                    </div>
                    <!-- Brand Logo Wrapper End -->
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- Brand Logo End -->
</template>

<script>
    export default {
        props: ['brands'],
        updated: function(){
        },
        mounted() {

             var brandCarousel = new Swiper('.brand-logo-carousel .swiper-container', {
		loop: true,
		speed: 750,
		spaceBetween: 30,
		slidesPerView: 5,
		effect: 'slide',
		//autoplay: {},

		// Responsive breakpoints
		breakpoints: {
			// when window width is >= 320px
			320: {
			slidesPerView: 2,
			spaceBetween: 20
			},
			// when window width is >= 480px
			480: {
			slidesPerView: 3,
			spaceBetween: 30
			},
			// when window width is >= 768px
			768: {
			slidesPerView: 4,
			spaceBetween: 30
			},
			// when window width is >= 992px
			992: {
			slidesPerView: 5,
			spaceBetween: 30
			}
		}
	});
        }
    };

</script>