<template>
<div class="small-items-line slider-wrapper hidden-pixel-y">
    <div class="row nopadding">
        <div class="col-sm-4 col-lg-4" v-for="product in products.slice(0,12)" :key="product.id">
        <PopularProductCard :product="product"></PopularProductCard>
        </div>
    </div>                                
</div>
               
</template>
<script>
    import PopularProductCard from '../products/PopularProductCard.vue';

    export default {
        components: {
            PopularProductCard
        },
        props: ['products'],
    };

</script>
