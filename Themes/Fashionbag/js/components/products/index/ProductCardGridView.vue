<template>
    <div class="col-lg-4 col-md-4 col-sm-6 product" data-aos="fade-up" data-aos-delay="200">
        <ProductGridCard :product="product"/>
    </div>
</template>
<script>
    import ProductGridCard from './../ProductGridCard.vue';

    export default {
        components: { ProductGridCard },

        props: ['product'],
    };
</script>