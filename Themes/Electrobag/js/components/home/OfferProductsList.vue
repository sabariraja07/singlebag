<template>
    <!-- <div class="container">
        <div class="swiper-container arrows-align-top" data-breakpoints="1" data-xs-slides="1" data-sm-slides="1"
            data-md-slides="1" data-lt-slides="1" data-slides-per-view="1" data-center="false">
            <div class="h4 swiper-title">{{ $trans('Deals Of The Day') }}</div>
            <div class="empty-space col-xs-b20"></div>
            <div class="swiper-button-prev style-1"></div>
            <div class="swiper-button-next style-1"></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="product in products" :key="product.id">
                    <offer-product-card :product="product"></offer-product-card>
                </div>
            </div>
            <div class="swiper-pagination relative-pagination visible-xs"></div>
        </div>
    </div> -->
        <section class="popular-categories section-padding">
        <div class="swiper-container arrows-align-top" data-center="0" data-space="30" data-breakpoints="1" data-xs-slides="1" data-sm-slides="1" data-md-slides="3" data-lt-slides="3" data-slides-per-view="3">
            <div class="h4 swiper-title">{{ $trans('Deals Of The Day') }}</div>
            <div class="empty-space col-xs-b20"></div>
            <div class="swiper-button-prev style-1"></div>
            <div class="swiper-button-next style-1"></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="product in products" :key="product.id">
                    <offer-product-card :product="product"></offer-product-card>
                </div>
            </div>
            <div class="swiper-pagination relative-pagination visible-xs"></div>
        </div>
    </section>
</template>
<script>
    import OfferProductCard from '../products/OfferProductCard.vue';

    export default {
        components: {
            OfferProductCard
        },
        props: ['products'],
    };

</script>
