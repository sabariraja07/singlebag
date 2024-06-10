<template>
    <div class="empty-page">
        <div class="empty-container">
            <div class="empty-icon">
                <img :src="img" alt="">
            </div>
            <div class="empty-header" v-html="header"></div>
            <div class="empty-message" v-html="message"></div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['header', 'message' , 'img'],
    }
</script>
<style scoped>
    .empty-page {
        width: 100%;
        margin: 40px auto;
        background: #ffffff;
        /* box-shadow: 1px 2px 10px #e1e3ec; */
        border-radius: 4px;
    }

    .empty-container {
        padding: 48px;
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .empty-container .empty-icon {
        width: 200px;
        height: 200px;
        display: flex;
        align-items: center;
        border-radius: 200px;
        justify-content: center;
    }

    .empty-container .empty-icon img {
        width: 170px;
    }

    .empty-container .empty-header {
        color: #38a169;
        font-size: 1.5rem;
        font-weight: 500;
        margin-top: 30px;
        margin-bottom: 25px;
    }

    .empty-container .empty-message {
        color: #a2a5b9;
        font-size: 0.875rem;
    }

</style>
