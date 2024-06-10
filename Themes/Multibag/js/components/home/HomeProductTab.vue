<template>
<div class="product-area pt-30 pb-50">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-8">
                <div class="section-title-8">
                    <h2 class="bold"></h2>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="banner-btn-4 black-2 banner-btn-4-right banner-btn-4-mrg-none">
                    <a href="/shop">Browse All Products <img class="inject-me arrow-mrg-dec" src="../../../assets/images/icon-img/right-arrow-banner-2.svg" alt=""></a>
                </div>
            </div>
        </div>
        <div class="product-tab-list-1 tab-list-1-left nav mt-100 mb-65">
            <a class="active" href="#topSelling" data-bs-toggle="tab">
                {{ $trans('Top Selling') }}
            </a>
            <a href="#trendingProducts" data-bs-toggle="tab">
                {{ $trans('Trending Products') }}
            </a>
            <a href="#recentlyAdded" data-bs-toggle="tab">
                {{ $trans('Recently added') }}
            </a>
            <a href="#topRated" data-bs-toggle="tab">
                {{ $trans('Top Rated') }}
            </a>
        </div>
        <div class="tab-content jump">
            <div id="topSelling" class="tab-pane active">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6"  v-for="product in topSelling" :key="product.id">
                        <ProductCard :product="product"/>
                    </div>
                </div>
            </div>
            <div id="trendingProducts" class="tab-pane">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6"  v-for="product in trendingProducts" :key="product.id">
                        <ProductCard :product="product"/>
                    </div>
                </div>
            </div>
            <div id="recentlyAdded" class="tab-pane">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6"  v-for="product in recentlyAdded" :key="product.id">
                        <ProductCard :product="product"/>
                    </div>
                </div>
            </div>
            <div id="topRated" class="tab-pane">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6"  v-for="product in topRated" :key="product.id">
                        <ProductCard :product="product"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import ProductCard from '../products/ProductCard.vue';

    export default {
        components: { ProductCard },
        props: ['topRated', 'recentlyAdded', 'trendingProducts', 'topSelling'],
        data() {
            return {
                loading : true,
                categoryId: null,
                products: []
            }
        },
        methods: {
        },
        mounted() {
        }
    };

</script>