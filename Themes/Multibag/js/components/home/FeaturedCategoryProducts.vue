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
            <div class="product-tab-list-1 tab-list-1-left nav mt-40 mb-65">
                <a class="active" href="#popularProducts" data-bs-toggle="tab">
                    {{ $trans('Popular Products') }}
                </a>
                <a v-for="category in categories" :key="category.id" :href="'#category_' + category.id"  @click="getProducts(category.id)" data-bs-toggle="tab">
                    {{ category.name }}
                </a>
            </div>
            <div class="tab-content jump">
                <div id="popularProducts" class="tab-pane active">
                    <div class="row" v-if="popularProducts.length > 0">
                        <div class="col-lg-3 col-md-6 col-sm-6"  v-for="product in popularProducts" :key="product.id">
                            <ProductCard :product="product"/>
                        </div>
                    </div>
                    <div class="row" v-else-if="!loading">
                        <div class="col">
                        <empty-page :header="'No products found.'" :message="''" :img="'assets/img/empty_products.png'"></empty-page>
                        </div>
                    </div>
                </div>
                <div v-for="category in categories" :key="category.id" :id="'category_' + category.id" class="tab-pane">
                    <div class="row" v-if="products.length > 0">
                        <div class="col-lg-3 col-md-6 col-sm-6"  v-for="product in products" :key="product.id">
                            <ProductCard :product="product"/>
                        </div>
                    </div>
                    <div class="row" v-else-if="!loading">
                        <div class="col">
                        <empty-page :header="'No products found.'" :message="''" :img="'assets/img/empty_products.png'"></empty-page>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </template>
    
    <script>
        import ProductCard from '../products/ProductCard.vue';
        import axios from 'axios';
    
        export default {
            components: { ProductCard },
            props: ['categories', 'popularProducts'],
            data() {
                return {
                    loading : true,
                    categoryId: null,
                    products: []
                }
            },
            methods: {
                getProducts(id){
                    this.loading = true;
                        this.categoryId = id;
                        this.products = [];
                        axios.get('/get_shop_products?category_id=' + id).then((response) => {
                            this.products = response.data.data;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },
            },
            mounted() {
                // if(this.categories.length > 0){
                //     this.getProducts(this.categories[0].id);
                // }
            }
        };
    
    </script>