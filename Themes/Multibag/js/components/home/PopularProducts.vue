<template>
    <section class="product-area pt-50 pb-95">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-5">
                    <div class="section-title-6">
                        <h2>{{ $trans('Popular Products') }}</h2>
                    </div>
                </div>
            </div>
            <!--End nav-tabs-->
            <div class="jump mt-80">
                <div class="row product-grid-4">
                    <div class="col-lg-3 col-md-4 col-12 col-sm-6 mb-30" v-for="product in products" :key="product.id" >
                        <ProductCard :product="product"/>
                    </div>
                    <!--end product card-->
                </div>
            </div>
            <!--End tab-content-->
        </div>
    </section>
</template>
<script>
    import ProductCard from '../products/ProductCard.vue';
    // import { slickPrevArrow, slickNextArrow } from '../../functions';

    export default {
        components: { ProductCard },

        props: ['products'],

        methods: {
        },
    };
</script>
