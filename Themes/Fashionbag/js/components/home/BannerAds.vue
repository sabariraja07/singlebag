<template>
    <!-- Banner Section Start -->
    <div class="section section-padding">
        <div class="container">
            <!-- Banners Start -->
            <div class="row mb-n6 overflow-hidden">
                <!-- Banner Start -->
                <div class="col-md-6 col-12 mb-6" v-for="(banner, index) in banners" :key="index">
                    <div class="banner" data-aos="fade-right" data-aos-delay="300">
                        <div class="banner-image">
                            <a :href="banner.url"><img :src="banner.image" alt=""></a>
                        </div>
                        <div class="info" :class="banner.position == 'right' ? 'info-right' : ''">
                            <div class="small-banner-content">
                                <h3 v-if="banner.title" class="title" v-bind:style="{ color: banner.text_color }">{{ banner.title  }}</h3>
                                <h4 v-if="banner.description" class="sub-title" v-bind:style="{ color: banner.text_color }">{{ banner.description }}</h4>
                                <a :href="banner.url" class="btn btn-fb btn-hover-dark btn-sm">
                                    {{ banner.btn_text ? banner.btn_text : $trans('Shop Now')  }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Banner End -->
            </div>
            <!-- Banners End -->
        </div>
    </div>
    <!-- Banner Section End -->
</template>
<style scoped>
/* .banner-img img{
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
} */
.info.info-right{
    text-align: right;
    right: 30px !important;
}
</style>
<script>
    export default {
        props: ['banners'],
        methods: {
        }
    };

</script>