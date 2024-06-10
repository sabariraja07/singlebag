<template>
<div class="container">
    <div class="empty-cart-content text-center">
        <img :src="img" alt="">
        <h3>{{ header }}</h3>
        <p>{{ message }}</p>
        <div class="empty-cart-btn" v-if="url">
            <a :href="url">{{ btn }}</a>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        'header': {
            default: '',
            type: String
        },
        'message': {
            default: '',
            type: String
        },
        'img': {
            default: 'assets/img/empty_products.png',
            type: String
        },
        'url': {
            default: '',
            type: String
        },
        'btn': {
            default: '',
            type: String
        }
    },
}
</script>
