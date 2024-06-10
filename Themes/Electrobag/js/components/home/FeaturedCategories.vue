<template>
    <section class="popular-categories section-padding">
        <div class="swiper-container arrows-align-top" data-breakpoints="1" data-xs-slides="1" data-sm-slides="3" data-md-slides="6" data-lt-slides="6" data-slides-per-view="6">
            <div class="h4 swiper-title">{{ $trans('Featured Categories') }}</div>
            <div class="empty-space col-xs-b20"></div>
            <div class="swiper-button-prev style-1"></div>
            <div class="swiper-button-next style-1"></div>
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="category in categories" :key="category.id">
                    <a class="product-shortcode style-2" href="javascript:void(0)">
                        <span class="preview">
                            <a :href="'category/' + category.slug + '/' + category.id">
                                <img :src="category.url" alt="" /></a>
                        </span>
                        <span class="description">
                            <span class="h6 animate-to-green"><a :href="'category/' + category.slug + '/' + category.id">{{ category.name }}</a></span>
                            <span class="simple-article size-1 animate-to-fulltransparent">{{ category.products_count }} PRODUCTS</span>
                        </span>
                    </a>

                </div>
            </div>
            <div class="swiper-pagination relative-pagination visible-xs"></div>
        </div>
    </section>
</template>

<style scoped>
.img-wrapper{
  width: 100%;
  height: 50%;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center; 
}
img {
  height: auto;
  width: 100%;
}
</style>
<script>
export default {
    props: ['categories'],
    mounted() {

    }
};
</script>
