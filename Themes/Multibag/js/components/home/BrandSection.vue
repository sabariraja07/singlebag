<template>
<div class="brand-logo-area pb-30">
    <div class="container">
        <div class="section-categories-wrap mb-30">
            <div class="section-title section-title-width-1">
                <h2>{{ $trans('Top Brand') }}</h2>
                <p></p>
            </div>
            <!-- <div class="categories-btn section-btn-width-1 btn-style-1 btn-hover">
                    <a class="animated btn-border-1 btn-ptb-2" href="#">Browse All Categories </a>
                </div> -->
        </div>
        <div class="brand-logo-active">
            <div class="single-brand-plr" v-for="( brand, index) in brands" :key="index">
                <div class="single-brand-logo">
                    <a :href="'shop?brand=' + brand.slug">
                        <img :src="brand.image" height="85" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: ['brands'],
    updated: function () {},
    mounted() {
        $('.brand-logo-active , .brand-logo-active-2').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            loop: true,
            dots: false,
            arrows: false,
            responsive: [{
                    breakpoint: 1500,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        autoplay: false,
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        autoplay: false,
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    }
};
</script>
