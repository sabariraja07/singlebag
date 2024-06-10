<template>
    <section class="product-tabs section-padding position-relative">
        <div class="container">
            <div class="section-title style-2 wow animate__animated animate__fadeIn">
                <h3>{{ $trans('Popular Products') }}</h3>
            </div>
            <!--End nav-tabs-->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                    <div class="row product-grid-4">
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6 mb-30" v-for="product in products" :key="product.id" >
                            <ProductCard :product="product"/>
                        </div>
                        <!--end product card-->
                    </div>
                    <!--End product-grid-4-->
                </div>
                <!--En tab one-->
            </div>
            <!--End tab-content-->
        </div>
    </section>
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
