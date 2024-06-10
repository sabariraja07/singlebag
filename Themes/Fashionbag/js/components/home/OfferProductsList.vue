<template>
<div class="section section-padding mt-0 overflow-hidden">
    <div class="container">

        <!-- Section Title & Tab Start -->
        <div class="row">
            <!-- Tab Start -->
            <div class="col-12">
                <div class="section-title-produt-tab-wrapper">
                    <div class="section-title m-0" data-aos="fade-right" data-aos-delay="300">
                        <h1 class="title">{{ $trans('All Deals') }}</h1>
                    </div>
                </div>
            </div>
            <!-- Tab End -->
        </div>
        <div class="row">
            <div class="col">
                <div class="tab-content position-relative">
                    <div class="tab-pane fade show active" id="product-deal-all">
                        <div class="product-deal-carousel">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <!-- Product Start -->
                                    <div class="swiper-slide product-wrapper" data-aos="fade-right" data-aos-delay="600" v-for="product in products" :key="product.id">
                                        <offer-product-card :product="product"></offer-product-card>
                                    </div>
                                    <!-- Product End -->
                                </div>

                                <!-- Swiper Pagination Start -->
                                <div class="swiper-pagination d-md-none"></div>
                                <!-- Swiper Pagination End -->

                                <!-- Next Previous Button Start -->
                                <div class="swiper-product-deal-next swiper-button-next swiper-button-white d-md-flex d-none"><i class="pe-7s-angle-right"></i></div>
                                <div class="swiper-product-deal-prev swiper-button-prev swiper-button-white d-md-flex d-none"><i class="pe-7s-angle-left"></i></div>
                                <!-- Next Previous Button End -->
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
import OfferProductCard from '../products/OfferProductCard.vue';

export default {
    components: {
        OfferProductCard
    },
    props: ['products'],
    mounted(){
        /* Product Deal Crousel Activation */
	var productCarousel = new Swiper('.product-deal-carousel .swiper-container', {
        loop: true,
        slidesPerView: 1,
		spaceBetween: 0,
		pagination: true,
		navigation: true,
		observer: true,
		observeParents: true,

		navigation: {
			nextEl: '.product-deal-carousel .swiper-product-deal-next',
			prevEl: '.product-deal-carousel .swiper-product-deal-prev'
		},
		pagination: {
			el: '.product-deal-carousel .swiper-pagination',
			type: 'bullets',
			clickable: 'true'
		},
		breakpoints: {
			// when window width is >= 320px
			320: {
				slidesPerView: 1,
			},
			// when window width is >= 480px
			480: {
				slidesPerView: 1,
			},
			// when window width is >= 575px
			575: {
				slidesPerView: 1,
			},
			// when window width is >= 768px
			768: {
				slidesPerView: 1,
			}
		}
	});
    }
};
</script>
