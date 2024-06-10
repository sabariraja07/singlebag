<template>
    <section class="section-padding pb-5">
        <div class="container">
            <div class="section-title wow animate__animated animate__fadeIn" data-wow-delay="0">
                <h3 class="">{{ $trans('Deals Of The Day') }}</h3>
                <a class="show-all" :href="'/shop?type=offer'">
                    {{ $trans('All Deals') }}
                    <i class="fi-rs-angle-right"></i>
                </a>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6" v-for="product in products" :key="product.id">
                    <offer-product-card :product="product"></offer-product-card>
                </div>
            </div>
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
