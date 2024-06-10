<template>
    <section class="banners mb-25">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 d-md-none d-lg-flex mb-25" v-for="(banner, index) in banners" :key="index">
                    <div v-if="banner.title == null" class="banner-img mb-sm-0 wow animate__ animate__fadeInUp animated" data-wow-delay=".4s">
                        <img :src="banner.image" alt="" />
                        <div class="banner-text">
                            <!-- <h4>The best Organic <br />Products Online</h4>-->
                            <a :href="banner.url" class="btn btn-xs">{{ $trans('Shop Now') }} <i
                                    class="fi-rs-arrow-small-right"></i></a> 
                        </div>
                    </div>
                    <div v-else class="banner-img mb-sm-0 wow animate__ animate__fadeInUp animated" data-wow-delay=".4s">
                        <img :src="banner.image" alt="" />
                        <div class="banner-text" :class="banner.position == 'right' ? 'txt-right' : ''">
                            <h4  class="banner-font" v-if="banner.title != null" v-bind:style="{ color: banner.text_color }">{{ banner.title }}</h4>
                            <!-- <h1 class="display-2 mb-40"></h1> -->
                            <p class="mb-20"  v-if="banner.description != null" v-bind:style="{ color: banner.text_color }">
                                {{ banner.description  }}
                            </p>
                            <a :href="banner.url" class="btn btn-xs" v-if="banner.btn_text">
                                {{ banner.btn_text }} 
                                <i class="fi-rs-arrow-small-right"></i>
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<style scoped>
.banner-img img{
    max-height: 260px !important;
}
.banner-img p{
    color: #253D4E !important;
}
.banner-img .banner-text h4 {
    min-height: 0px !important;
}
.banner-img .txt-right{
    text-align:right !important;
    width:100% !important;
}
.banner-font {
    font-size: 20px !important;
}
.banner-img .banner-text {
    top: 40% !important;
}
</style>
<script>
    export default {
        props: ['banners'],
        methods: {
        }
    };

</script>