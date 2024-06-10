<template>
    <div class="col-sm-6 col-md-3 col-xs-b25">
        <div class="h4 col-xs-b25">{{ title }}</div>
        <SmallProductCard v-for="(product, index) in products.slice(0,5)" :key="index" :product="product"></SmallProductCard>
    </div>
</template>

<script>
    import SmallProductCard from '../products/SmallProductCard.vue';

    export default {
        components: { SmallProductCard },

        props: ['products' , 'title'],

        methods: {
        },
    };

</script>
