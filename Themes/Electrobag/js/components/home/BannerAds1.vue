<template>
<div class="slider-wrapper hidden-pixel-x side-borders">
    <div class="swiper-button-prev visible-lg"></div>
    <div class="swiper-button-next visible-lg"></div>
    <div class="swiper-container" data-breakpoints="1" data-xs-slides="1" data-sm-slides="1" data-md-slides="2" data-lt-slides="2"  data-slides-per-view="2" data-space="30" data-autoplay="5000">
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-for="(banner, index) in banners" :key="index">
                <!-- <div class="theme-banner-shortcode style-1 big">        
                    <a :href="banner.url" ><img :src="banner.image"  alt="" class="theme-banner-img" /></a>        
                </div> -->
                <div class="banner-shortcode style-4 rounded-image text-center" :style="'background-image: url('+ banner.image + ');'">
                    <div class="valign-middle-cell">
                        <div class="valign-middle-content">
                            <!-- <div class="simple-article size-3 light transparent uppercase col-xs-b5">convenience</div> -->
                            <h3 class="h3 light" v-if="banner.title != null">{{ banner.title }}</h3>
                            <div class="title-underline light center"><span></span></div>
                            <div class="simple-article size-4 light transparent col-xs-b30" v-if="banner.description != null">
                                {{ banner.description  }}
                            </div>
                            <a class="button size-2 style-2" :href="banner.url" v-if="banner.btn_text">
                                <span class="button-wrapper">
                                    <span class="icon"><img src="frontend/electrobag/img/icon-1.png" alt=""></span>
                                    <span class="text">{{ banner.btn_text }} </span>
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="angle-left hidden-xs"></div>
                </div>   
            </div>              
        </div>
        <div class="swiper-pagination relative-pagination visible-xs visible-sm"></div>
    </div>
</div>
     
</template>
<script>
    export default {
        props: ['banners'],
    };

</script>