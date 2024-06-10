<template>
<div class="product-shortcode style-1 big">    
    <div class="product-shortcode style-1">  
        <div class="preview">
            <img  :src="productImgUrl" style="width:200px;height:200px;" alt="">
            <div class="preview-buttons valign-middle">
                <div class="valign-middle-content">
                    <a class="button size-2 style-2" :href="productUrl">
                        <span class="button-wrapper">
                            <span class="icon"><img src="frontend/electrobag/img/icon-1.png" alt=""></span>
                            <span class="text">Learn More</span>
                        </span>
                    </a>
                    <a class="button size-2 style-3" @click="addToCart">
                        <span class="button-wrapper">
                            <span class="icon"><img src="frontend/electrobag/img/icon-3.png" alt=""></span>
                            <span class="text">{{ $trans('Add To Cart') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="title">
            <div class="simple-article size-1 color col-xs-b5"><a :href="productUrl">{{ product.title }}</a></div>
            <div class="h6 animate-to-green">
                <a :href="categoryUrl">
                        {{ product.category.category.name }}
                    </a>
            </div>
        </div>
        <div class="price">
            <div class="simple-article size-4"><span class="color">productcard1 ::: {{ $formatPrice(product.price.selling_price) }}</span>&nbsp;&nbsp;&nbsp;<span class="line-through">{{ $formatPrice(product.price.regular_price) }}</span></div>
        </div>
        <div class="description">
            <div class="simple-article text size-2">Mollis nec consequat at In feugiat molestie tortor a malesuada etiam a venenatis ipsum</div>
            <div class="simple-article text size-2">{{ product.stock.sku }}</div>
            <div class="icons">
                
                <a class="entry" :href="productUrl"><i class="fa fa-eye" aria-hidden="true"></i></a>
                <a class="entry" @click="syncWishlist">
                    <i class="fa fa-heart" aria-hidden="true" style="color:red;" v-if="inWishlist"></i>
                    <i class="fa fa-heart-o" aria-hidden="true" v-else></i>
                </a>
            </div>
        </div>    
    </div>
</div>   
    
</template>   
