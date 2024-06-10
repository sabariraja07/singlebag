<template>
  <div class="slider-wrapper hidden-pixel-x side-borders">
    <div class="swiper-button-prev visible-lg"></div>
    <div class="swiper-button-next visible-lg"></div>
    <div
      class="swiper-container"
      data-breakpoints="1"
      data-xs-slides="1"
      data-sm-slides="1"
      data-md-slides="2"
      data-lt-slides="2"
      data-slides-per-view="2"
      data-space="30"
      data-autoplay="5000"
    >
      <div class="swiper-wrapper">
        <div
          v-for="(banner, index) in banners"
          :key="index"
          class="swiper-slide"
          v-bind:style="{ backgroundImage: 'url(' + banner.image + ')' }"
        >
          <div class="banner-shortcode style-1">
            <div
              class="background"
              v-bind:style="{ backgroundImage: 'url(' + banner.image + ')' }"
            ></div>
            <div
              class="description valign-middle"
              :style="banner.position == 'left' ? 'left:15px;' : ''"
            >
              <div class="valign-middle-content">
                <!-- <div class="simple-article size-3 light fulltransparent">DON'T MISS!</div> -->
                <div class="banner-title color" v-bind:style="{ color: banner.text_color }">{{ banner.title }}</div>
                <div class="h4 light" v-bind:style="{ color: banner.text_color }">{{ banner.description }}</div>
                <div class="empty-space col-xs-b25"></div>
                <a
                  class="button size-1 style-3"
                  :href="banner.url"
                  v-if="banner.btn_text"
                >
                  <span class="button-wrapper">
                    <span class="icon"
                      ><img src="frontend/electrobag/img/icon-1.png" alt=""
                    /></span>
                    <span class="text">{{ banner.btn_text }}</span>
                  </span>
                </a>
              </div>
              <div class="empty-space col-xs-b60 col-sm-b0"></div>
            </div>
          </div>
        </div>
      </div>
      <div
        class="swiper-pagination relative-pagination visible-xs visible-sm"
      ></div>
    </div>
  </div>
</template>
<style scoped>
.h1.light {
  font-size: 28px;
}
@media (min-width: 768px) {
  .banner-shortcode.style-1 .description {
    width: auto !important;
  }
}
</style>
<script>
export default {
  props: ["banners"],
};
</script>
