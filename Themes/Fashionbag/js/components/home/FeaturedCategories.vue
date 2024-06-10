<template>
    <section class="popular-categories section-padding">
        <div class="container wow animate__animated animate__fadeIn">
            <div class="section-title">
                <div class="title">
                    <h3>{{ $trans('Featured Categories') }}</h3>
                    <!-- <ul class="list-inline nav nav-tabs links">
                        <li class="list-inline-item nav-item"><a class="nav-link" href="javascript::void(0)" onclick="return false;">Cake &
                                Milk</a></li>
                        <li class="list-inline-item nav-item"><a class="nav-link" href="javascript::void(0)" onclick="return false;">Coffes
                                & Teas</a></li>
                        <li class="list-inline-item nav-item"><a class="nav-link active"
                                href="javascript::void(0)" onclick="return false;">Pet Foods</a></li>
                        <li class="list-inline-item nav-item"><a class="nav-link"
                                href="javascript::void(0)" onclick="return false;">Vegetables</a></li>
                    </ul> -->
                </div>
                <div class="slider-arrow slider-arrow-2 flex-right carausel-10-columns-arrow"
                    id="carausel-10-columns-arrows"></div>
            </div>
            <div class="carausel-10-columns-cover position-relative">
                <div class="carausel-10-columns" id="carausel-10-columns" ref="homeFeaturedCategories">
                    <div v-for="category in categories" :key="category.id"
                        class="card-2 bg-9 wow animate__animated animate__fadeInUp" data-wow-delay=".1s" style="padding: 5px 0px 0px 11px;min-height: 158px;">
                        <figure class="img-hover-scale overflow-hidden">
                            <a :href="'category/' + category.slug + '/' + category.id">
                                <img :src="category.url" alt="" style="max-width: 55px;" /></a>
                        </figure>
                        <h6 style="margin-bottom:-5px;font-size: 12px;overflow: hidden !important;"><a :href="'category/' + category.slug + '/' + category.id">{{ category.name }}</a></h6>
                        <!-- <span>{{ category.products_count }} {{ $trans('items') }}</span> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: ['categories'],
        mounted() {
            $(".carausel-10-columns").each(function (key, item) {
                var id = $(this).attr("id");
                var sliderID = "#" + id;
                var appendArrowsClassName = "#" + id + "-arrows";

                $(sliderID).slick({
                    dots: false,
                    infinite: true,
                    speed: 1000,
                    arrows: true,
                    autoplay: false,
                    slidesToShow: 10,
                    slidesToScroll: 1,
                    loop: true,
                    adaptiveHeight: true,
                    responsive: [{
                            breakpoint: 1025,
                            settings: {
                                slidesToShow: 4,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        }
                    ],
                    prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
                    nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
                    appendArrows: appendArrowsClassName
                });
            });
            // $(this.$refs.homeFeaturedCategories).slick({
            //        dots: false,
            //         infinite: true,
            //         speed: 1000,
            //         arrows: true,
            //         autoplay: false,
            //         slidesToShow: 10,
            //         slidesToScroll: 1,
            //         loop: true,
            //         adaptiveHeight: true,
            //         responsive: [{
            //                 breakpoint: 1025,
            //                 settings: {
            //                     slidesToShow: 4,
            //                     slidesToScroll: 1
            //                 }
            //             },
            //             {
            //                 breakpoint: 768,
            //                 settings: {
            //                     slidesToShow: 3,
            //                     slidesToScroll: 1
            //                 }
            //             },
            //             {
            //                 breakpoint: 480,
            //                 settings: {
            //                     slidesToShow: 2,
            //                     slidesToScroll: 1
            //                 }
            //             }
            //         ],
            //         prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            //         nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            //         appendArrows: '#carausel-10-columns-arrows'
            // });
        }
    };

</script>
