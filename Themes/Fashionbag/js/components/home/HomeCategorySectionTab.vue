<template>
     <!-- Product Section Start -->
     <div class="section section-padding mt-0">
        <div class="container">
            <!-- Section Title & Tab Start -->
            <div class="row">
                <!-- Tab Start -->
                <div class="col-12">
                    <ul class="product-tab-nav nav justify-content-center mb-10 title-border-bottom mt-n3">
                        <li class="nav-item" data-aos="fade-up" data-aos-delay="300" v-for="(category, index) in categories" :key="index">
                            <a class="nav-link mt-3" @click="getProducts(category.id)" :class="categoryId == category.id ? 'active' : ''" data-bs-toggle="tab" href="#tab-product-all">{{ category.name }}</a>
                        </li>
                        <!-- <li class="nav-item" data-aos="fade-up" data-aos-delay="400"><a class="nav-link mt-3" data-bs-toggle="tab" href="#tab-product-clothings">Best Sellers</a></li>
                        <li class="nav-item" data-aos="fade-up" data-aos-delay="500"><a class="nav-link mt-3" data-bs-toggle="tab" href="#tab-product-all">Sale Items</a></li> -->
                    </ul>
                </div>
                <!-- Tab End -->
            </div>
            <!-- Section Title & Tab End -->

            <!-- Products Tab Start -->
            <!-- <div class="row">
                <div class="col">
                    
                </div>
            </div> -->
            <div class="row shop_wrapper grid_4" v-if="products.length > 0">
                <div class="col-lg-3 col-md-4 product col-12 col-sm-6 mb-30" v-for="product in products" :key="product.id" >
                    <ProductCard :product="product"/>
                </div>
            </div>
            <div class="row" v-else-if="!loading">
                <div class="col">
                <empty-page :header="'No products found.'" :message="''" :img="'assets/img/empty_products.png'"></empty-page>
                </div>
            </div>
            <!-- Products Tab End -->
        </div>
    </div>
    <!-- Product Section End -->
</template>
<script>

    import axios from 'axios';
    import ProductCard from '../products/ProductCard.vue';

    export default {
        components: { ProductCard },
        props: ['categories'],
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
            if(this.categories.length > 0){
                this.getProducts(this.categories[0].id);
            }
        }
    };

</script>