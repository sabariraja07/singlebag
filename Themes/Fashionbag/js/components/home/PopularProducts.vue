<template>
    <!-- Product Section Start -->
    <div class="section section-padding mt-0">
        <div class="container">
            <!-- Section Title & Tab Start -->
            <div class="row">
                <!-- Tab Start -->
                <div class="col-12">
                    <div class="section-title-produt-tab-wrapper">
                        <div class="section-title m-0 aos-init aos-animate" data-aos="fade-right" data-aos-delay="300">
                            <h1 class="title">{{ $trans('Popular Products') }}</h1>
                        </div>
                    </div>
                </div>
                <!-- Tab End -->
            </div>
            <!-- Section Title & Tab End -->

            <!-- Products Tab Start -->
            <div class="row shop_wrapper grid_4">
                <div class="col-lg-3 col-md-4 product col-12 col-sm-6 mb-30" v-for="product in products" :key="product.id" >
                    <ProductCard :product="product"/>
                </div>
            </div>
            <!-- Products Tab End -->
        </div>
    </div>
    <!-- Product Section End -->
</template>
<script>
    import ProductCard from '../products/ProductCard.vue';

    export default {
        components: { ProductCard },

        props: ['products'],

        methods: {
        },
    };
</script>
