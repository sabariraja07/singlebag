<template>    
        <ProductCard :product="product"/>   
</template>
<script>
    import ProductCard from './../ProductCard.vue';

    export default {
        components: { ProductCard },

        props: ['product'],
    };
</script>