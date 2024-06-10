<template>
    <div class="slider-wrapper">
        <div class="swiper-button-prev visible-lg"></div>
        <div class="swiper-button-next visible-lg"></div>
        <div class="swiper-container" data-parallax="1" data-auto-height="1"  data-autoplay="5000">
            <div class="swiper-wrapper">
                <div v-for="(slider, index) in sliders" :key="index" class="swiper-slide"
                    v-bind:style="{ backgroundImage: 'url(' + slider.image + ')' }">
                    <div class="container">
                        <div class="row">
                            <div :class="slider.position == 'left' ? 'col-sm-6' : 'col-sm-offset-6 col-sm-6 text-right'">
                                <div class="cell-view page-height">
                                    <div class="col-xs-b40 col-sm-b80"></div>
                                    <div data-swiper-parallax-x="-500">
                                        <h1 class="h1 light" v-bind:style="{ color: slider.text_color }">{{ slider.title }}</h1>
                                        <div class="title-underline light left"><span></span></div>
                                    </div>
                                    <div data-swiper-parallax-x="-400" style="transform: translate3d(0px, 0px, 0px);">
                                        <div class="simple-article size-4 light transparent">
                                            <p v-bind:style="{ color: slider.text_color }">{{ slider.description }}</p>
                                        </div>
                                        <div class="col-xs-b30"></div>
                                    </div>
                                    <div data-swiper-parallax-x="-300">
                                        <div class="buttons-wrapper">
                                            <a class="button size-2 style-1" :href="slider.url">
                                                <span class="button-wrapper">
                                                    <span class="icon"><img src="frontend/electrobag/img/icon-1.png"
                                                            alt=""></span>
                                                    <span class="text">{{ slider.btn_text }}</span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-xs-b40 col-sm-b80"></div>
                                </div>
                            </div>
                        </div>
                        <div class="slider-product-preview" data-swiper-parallax-x="-600"></div>
                        <div class="empty-space col-xs-b80 col-sm-b0"></div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination swiper-pagination-white"></div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['sliders'],

    }

</script>
