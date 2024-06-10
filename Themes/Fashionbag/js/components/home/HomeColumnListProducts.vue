<template>

<div v-if="count > 0" class="col-md-6 col-lg-3 col-12 mb-8" data-aos="fade-up" data-aos-delay="300">
    <div class="product-list-title">
        <h2 class="title pb-3 mb-0">{{ title }}</h2>
        <span></span>
    </div>
    <div v-if="products.length > 0">
        <ProductListCard v-for="(product, index) in products.slice(0,5)" :key="index" :product="product"></ProductListCard>
    </div>
    <div class="row" v-else>
        <div class="col">
        <empty-page :header="'No products found.'" :message="''" :img="'assets/img/empty_products.png'"></empty-page>
        </div>
    </div>
    <!-- <div class="product-list-carousel">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide product-list-wrapper mb-n6">
                    <ProductListCard v-for="(product, index) in products.slice(0,5)" :key="index" :product="product"></ProductListCard>
                </div>
                <div class="swiper-slide product-list-wrapper mb-n6">
                    <ProductListCard v-for="(product, index) in products.slice(5,10)" :key="index" :product="product"></ProductListCard>
                </div>
            </div>
            <div class="swiper-product-list-next swiper-button-next"><i class="pe-7s-angle-right"></i></div>
            <div class="swiper-product-list-prev swiper-button-prev"><i class="pe-7s-angle-left"></i></div>
        </div>
    </div> -->
</div>
<div v-else class="col-md-6 col-lg-4 col-12 mb-8" data-aos="fade-up" data-aos-delay="300">
    <div class="product-list-title">
        <h2 class="title pb-3 mb-0">{{ title }}</h2>
        <span></span>
    </div>
    <div v-if="products.length > 0">
        <ProductListCard v-for="(product, index) in products.slice(0,5)" :key="index" :product="product"></ProductListCard>
    </div>
    <div class="row" v-else>
        <div class="col">
        <empty-page :header="'No products found.'" :message="''" :img="'assets/img/empty_products.png'"></empty-page>
        </div>
    </div>
</div>
</template>

<script>
import ProductListCard from '../products/ProductListCard.vue';
// import DynamicTabsMixin from '../../mixins/DynamicTabsMixin';
// import { slickPrevArrow, slickNextArrow } from '../../functions';
export default {
    components: {
        ProductListCard
    },
    props: ['products', 'title' ,'count'],
    methods: {},
    // mounted(){
    //     var productCarousel = new Swiper('.product-list-carousel .swiper-container', {
    //         loop: true,
    //         slidesPerView: 1,
    //         spaceBetween: 0,
    //         pagination: false,
    //         navigation: true,
    //         observer: true,
    //         observeParents: true,
    //         navigation: {
    //             nextEl: '.product-list-carousel .swiper-product-list-next',
    //             prevEl: '.product-list-carousel .swiper-product-list-prev'
    //         },
    //         // pagination: {
    //         // 	el: '.product-list-carousel .swiper-pagination',
    //         // 	type: 'bullets',
    //         // 	clickable: 'true'
    //         // }
    //     });
    // }
};
</script>