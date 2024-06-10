<template>
    <div class="rate-wrapper align-inline">
        <i v-for="index in start" :key="index" :class="index <= rating ? 'fa fa-star' : rating > index-1 ? 'fa fa-star-half-o' : 'fa fa-star-o'" aria-hidden="true"></i>
    </div>
</template>
<script>
    export default {
        props: {
            rating: {
                type: Number|String,
                required: true,
                default: 0
            },
            start: {
                type: Number,
                required: false,
                default: 5
            }
        }
    }
</script>