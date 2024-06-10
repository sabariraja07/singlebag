@extends('fashionbag::layouts.app')
{{-- @section('breadcrumb')
    <span></span> <a href="{{ url('/shop') }}">{{ __('Product') }}</a>
    <span></span> {{ $info->title }}
@endsection --}}
@push('css')
    <style>
        .offer-price {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #FDC040;
        }

        .avatar {
            display: inline-block;
            vertical-align: middle;
            position: relative;
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
            border-radius: 50%;
            height: 65px;
            width: 64px;
        }

        .avatar__letters {
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        
        .stock-status {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-family: "Quicksand", sans-serif;
            font-size: 14px;
            font-weight: 700;
            line-height: 1;
        }

        .stock-status.out-stock {
            color: #f74b81;
            background: #fde0e9;
        }
        .embed-responsive-item{
            width: 100%;
            max-width: 650px;
            aspect-ratio: 16 / 9;
        }
    </style>
@endpush
@section('breadcrumb')
@php
$title = $info->title;
@endphp
<li><a href="{{ url('/shop') }}">{{ __('Products') }}</a></li>
<li class="active">{{ $info->title }}</li>
@endsection
@section('content')
    <product-show :product="{{ $info }}" :avg-rating="{{ $info->avg_rating ?? 0 }}"
        :review-count="{{ $info->reviews_count ?? 0 }}" inline-template>
        <!-- Shop Section Start -->
        <div class="section section-margin">
            <div class="container">

                <div class="row">
                    <div class="col-lg-5 offset-lg-0 col-md-8 offset-md-2 col-custom">

                        <!-- Product Details Image Start -->
                        <div class="product-details-img">

                            <!-- Single Product Image Start -->
                            <div class="single-product-img swiper-container gallery-top">
                                <div class="swiper-wrapper popup-gallery">
                                    @foreach ($gallery as $index => $row)
                                        {{-- <a class="swiper-slide w-100" href="{{ asset($row->url) }}"> --}}
                                            <img class="w-100" src="{{ asset($row->url) }}">
                                        {{-- </a> --}}
                                    @endforeach
                                </div>
                            </div>
                            <!-- Single Product Image End -->

                            <!-- Single Product Thumb Start -->
                            <div class="single-product-thumb swiper-container gallery-thumbs">
                                <div class="swiper-wrapper">
                                    @foreach ($gallery as $index => $row)
                                        <div class="swiper-slide">
                                            <img src="{{ asset($row->url) }}" alt="Product">
                                            {{-- <iframe src="https://www.youtube.com/embed/V2cF4uE0mXs?enablejsapi=1&html5=1&rel=0" frameborder="0" allowfullscreen iframe-video></iframe> --}}
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Next Previous Button Start -->
                                <div class="swiper-button-next swiper-button-white"><i class="pe-7s-angle-right"></i></div>
                                <div class="swiper-button-prev swiper-button-white"><i class="pe-7s-angle-left"></i></div>
                                <!-- Next Previous Button End -->

                            </div>
                            <!-- Single Product Thumb End -->

                        </div>
                        <!-- Product Details Image End -->

                    </div>
                    <div class="col-lg-7 col-custom">

                        <!-- Product Summery Start -->
                        <div class="product-summery position-relative">
                            @if (!$info->inStock())
                            <span class="stock-status out-stock"> {{ __('Out of stock') }} </span>
                            @endif
                            <!-- Product Head Start -->
                            <div class="product-head mb-3">
                                <h2 class="product-title">{{ $info->title }}</h2>
                            </div>
                            <!-- Product Head End -->

                            <!-- Price Box Start -->
                            <div class="price-box mb-2">
                                <span class="regular-price" style="font-size: 24px;" data-price="{{ $info->price->selling_price }}"
                                    data-regular-price="{{ $info->price->regular_price }}">{{ amount_format($info->price->selling_price) }}</span>
                                @if ($info->offer_discount > 0)
                                    <span class="old-price" style="margin-right: 10px;">
                                        <del>{{ amount_format($info->price->regular_price) }} </del>
                                    </span>
                                    <span class="offer-price">
                                        {{ $info->offer_discount }}%
                                        Off
                                    </span>
                                @endif
                            </div>
                            <!-- Price Box End -->

                            <!-- Rating Start -->
                            <span class="ratings justify-content-start">
                                <star-rating :show-rating="true" :star-size="18" v-model="avgRating"
                                    :read-only="true" :increment="0.5"></star-rating>
                            </span>
                            {{-- <span class="ratings justify-content-start">
                                <span class="rating-wrap">
                                    <span class="star" style="width: 100%"></span>
                        </span>
                        <span class="rating-num">(4)</span>
                        </span> --}}
                            <!-- Rating End -->

                            <!-- Description Start -->
                            <p class="desc-content mb-5">{{ $info->short_description }}</p>
                            <!-- Description End -->
                            @if (count($info->options) > 0)
                                <div style="padding-bottom: 20px;"></div>
                            @endif
                            <form id="add-to-cart-form" @submit.prevent="addCart()" novalidate>
                                @foreach ($info->options as $key => $option)
                                    <div class="pro-details-color-wrap rating-form-style option-check"
                                        style="margin-bottom: 20px;">
                                        <span @if ($option->is_required != 1) style="display: inline-block;" @endif>
                                            <label style="float: left;margin-right:5px;color:#212121;">{{ $option->name }}
                                                : </label>
                                            @if ($option->is_required == 1)
                                                <span class="text-danger" style="font-size: 16px;">*</span>
                                            @endif
                                        </span>
                                        @foreach ($option->variants as $row)
                                            @if ($option->select_type == 0)
                                                <div class="custome-radio" style="padding-top: 10px;">
                                                    <input class="form-check-input options" type="radio"
                                                        name="option[{{ $option->id }}]" id="option_{{ $row->id }}"
                                                        @if ($option->is_required == 1) required @endif
                                                        value="{{ $row->id }}" data-amount="{{ $row->price ?? $row->amount }}"
                                                        data-amount-type="{{ $row->amount_type }}"
                                                        @click.stop="syncCustomRadioTypeOptionValue({{ $option->id }}, {{ $row->id }})">
                                                    <label class="form-check-label mr-3" style="padding-left: 10px;"
                                                        for="option_{{ $row->id }}">{{ $row->name }}</label>
                                                </div>
                                            @else
                                                <div class="checkout-form-list create-acc">
                                                    <input class="option-input options" type="checkbox"
                                                        id="option_{{ $row->id }}"
                                                        name="option[{{ $option->id }}][]"
                                                        id="option_{{ $row->id }}" value="{{ $row->id }}"
                                                        @if ($option->is_required == 1) required @endif
                                                        data-amount="{{ $row->price ?? $row->amount }}"
                                                        data-amount-type="{{ $row->amount_type }}"
                                                        @change="updateCheckboxTypeOptionValue({{ $option->id }}, $event)">
                                                    <label for="option_{{ $row->id }}">{{ $row->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                                @if (count($info->options) > 0)
                                    <div style="padding-bottom: 20px;"></div>
                                @endif
                                <!-- Quantity Start -->
                                @if ($info->inStock())
                                    <div class="quantity mb-5">
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" name="qtybutton" v-model="qty"
                                                type="text" disabled>
                                            <div class="dec qtybutton" @click="updateQuantity(qty-1)">-</div>
                                            <div class="inc qtybutton" @click="updateQuantity(qty+1)">+</div>
                                        </div>
                                    </div>
                                @endif
                                <!-- Quantity End -->

                                <!-- Cart & Wishlist Button Start -->
                                <div class="cart-wishlist-btn mb-40">
                                    @if ($info->inStock())
                                        <div class="add-to_cart">
                                            <a class="btn btn-fb btn-hover-primary" @click="addCart()">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    v-if="addingToCart" aria-hidden="true">
                                                </span>
                                                {{ __('Add to cart') }}
                                            </a>
                                        </div>
                                    @endif

                                    <div class="add-to-wishlist">
                                        <a class="btn btn-light btn-hover-light" @click="syncWishlist">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                v-if="loadingWishlist" aria-hidden="true"></span>
                                            <i class="fa fa-heart" style="color:red;" v-else-if="inWishlist"></i>
                                            <i class="fa fa-heart" v-else></i></a>
                                    </div>
                                </div>
                                <!-- Cart & Wishlist Button End -->

                                <!-- Social Shear Start -->
                                {{-- <div class="social-share">
                            <span>Share :</span>
                            <a href="javascript::void(0)"><i class="fa fa-facebook-square facebook-color"></i></a>
                            <a href="javascript::void(0)"><i class="fa fa-twitter-square twitter-color"></i></a>
                            <a href="javascript::void(0)"><i class="fa fa-linkedin-square linkedin-color"></i></a>
                            <a href="javascript::void(0)"><i class="fa fa-pinterest-square pinterest-color"></i></a>
                        </div> --}}
                                <!-- Social Shear End -->

                                <!-- Product Delivery Policy Start -->
                                {{-- <ul class="product-delivery-policy border-top pt-4 mt-4 border-bottom pb-4">
                            <li> <i class="fa fa-check-square"></i> <span>Security Policy (Edit With Customer Reassurance Module)</span></li>
                            <li><i class="fa fa-truck"></i><span>Delivery Policy (Edit With Customer Reassurance Module)</span></li>
                            <li><i class="fa fa-refresh"></i><span>Return Policy (Edit With Customer Reassurance Module)</span></li>
                        </ul> --}}
                                <!-- Product Delivery Policy End -->
                            </form>

                            @if (!empty($info->stock->sku))
                                <div class="product-meta mb-5">
                                    <div class="product-metarial">
                                        @if ($info->stock->stock_manage == 1 || isset($info->stock->sku))
                                            <strong>SKU :</strong>
                                            <a href="javascript::void(0)">{{ $info->stock->sku }}</a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if (current_shop_type() == 'reseller')
                                @if (!empty($info->category))
                                <div class="product-meta mb-5">
                                    <div class="product-metarial">
                                        <strong>{{ __('Category') }} :</strong>
                                            <a href="{{ url('/category/' . $info->category->slug . '/' . $info->category->id) }}"><strong>{{ $info->category->name }}</strong></a>
                                    </div>
                                </div>
                                @endif
                            @else
                                @if (count($info->categories) > 0)
                                    @if (!empty($info->categories))
                                        <div class="product-meta mb-5">
                                            <!-- Product Metarial Start -->
                                            <div class="product-metarial">
                                                <strong>{{ __('Category') }} :</strong>
                                                @foreach ($info->categories as $row)
                                                    <a
                                                        href="{{ url('/category/' . $row->slug . '/' . $row->id) }}"><strong>{{ $row->name }}</strong></a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                            <!-- Product Metarial End -->
                                        </div>
                                    @endif
                                @endif
                            @endif
                            @if (isset($info->brand))
                                <div class="product-meta mb-5">
                                    <!-- Product Metarial Start -->
                                    <div class="product-metarial">
                                        <strong>{{ __('Brand') }} :</strong>
                                        <a href="{{ url('/brand/' . $info->brand->slug . '/' . $info->brand->id) }}"><strong>{{ $info->brand->name }}</strong></a>
                                    </div>
                                    <!-- Product Metarial End -->
                                </div>
                            @endif
                            @if(domain_info('shop_type') == 'seller')
                                @if ($info->inStock() && $info->stock->stock_manage == 1)
                                    <div class="product-meta mb-5">
                                        <!-- Product Metarial Start -->
                                        <div class="product-metarial">
                                            <strong>{{ __('Stock') }} :</strong>
                                            <a href="javascript::void(0)">
                                                {{ $info->stock->stock_qty }}
                                                Items In Stock
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                    </div>
                    <!-- Product Summery End -->

                </div>
                <div class="row section-margin">
                    <!-- Single Product Tab Start -->
                    <div class="col-lg-12 col-custom single-product-tab">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a data-bs-toggle="tab" href="#tab-description" @click="activeTab = 'description'"
                                    class="nav-link text-uppercase" id="description-tab" role="tab"
                                    aria-selected="true"
                                    :class="{ active: activeTab === 'description' }">{{ 'Description' }}</a>
                            </li>
                            <li class="nav-item">
                                <a data-bs-toggle="tab" href="#tab-variations" @click="activeTab = 'variations'"
                                    class="nav-link text-uppercase" id="variations-tab" role="tab"
                                    aria-selected="true"
                                    :class="{ active: activeTab === 'variations' }">{{ __('Additional info') }}</a>
                            </li>
                            <li class="nav-item">
                                <a data-bs-toggle="tab" href="#tab-reviews" @click="activeTab = 'reviews'"
                                    class="nav-link text-uppercase" id="reviews-tab" role="tab" aria-selected="true"
                                    :class="{ active: activeTab === 'reviews' }">{{ __('Review') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content mb-text" id="myTabContent">
                            <div class="tab-pane fade" :class="{ 'show active': activeTab === 'description' }"
                                id="tab-description" role="tabpanel" aria-labelledby="description-tab">
                                <div class="desc-content border p-3">
                                    <div style="margin-bottom: 60px;">
                                        @if ($info->video_provider == 'youtube' && isset(explode('=', $info->video_url)[1]))
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $info->video_url)[1] }}"></iframe>
                                        @elseif ($info->video_provider == 'dailymotion' && isset(explode('video/', $info->video_url)[1]))
                                            <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $info->video_url)[1] }}"></iframe>
                                        @elseif ($info->video_provider == 'vimeo' && isset(explode('vimeo.com/', $info->video_url)[1]))
                                            <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $info->video_url)[1] }}" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        @endif
                                    </div>
                                    {!! $info->description !!}
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-variations"
                                :class="{ 'show active': activeTab === 'variations' }" role="tabpanel"
                                aria-labelledby="variations-tab">
                                <!-- Start Single Content -->
                                <div class="product_tab_content  border p-3">
                                    <div class="size-tab table-responsive-lg">
                                        <table class="table border mb-0">
                                            @foreach ($variations as $key => $attribute)
                                                <tr>
                                                    <td class="width1" style="border: 1px solid #e8e8e8 !important">
                                                        <strong>{{ $attribute->name }}</strong>
                                                    </td>
                                                    <td>
                                                        @foreach ($attribute->options as $value)
                                                            <p>
                                                                {{ $value->name }}
                                                                @if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            </p>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Single Content -->
                            </div>
                            <div class="tab-pane fade" id="tab-reviews"
                                :class="{ 'show active': activeTab === 'reviews' }" role="tabpanel"
                                aria-labelledby="reviews-tab">
                                <div class="product_tab_content  border p-3">
                                    <!-- Start Single Review -->
                                    <div class="single-review d-flex mb-40" v-for="review in reviews.data"
                                        :key="review.id" v-if="review.status == 1">
                                        <!-- Review Thumb Start -->
                                        <div class="review_thumb">
                                            <div class="avatar">
                                                <div class="avatar__letters">
                                                    <span style="font-size: 31px;">@{{ review.name.match(/\b(\w)/g).join('') }}</span>
                                                </div>
                                            </div>
                                            {{-- <img alt="review images" src="assets/images/review/1.jpg"> --}}
                                        </div>
                                        <!-- Review Thumb End -->

                                        <!-- Review Details Start -->
                                        <div class="review_details" style="width: 100%;">
                                            <div class="review_info mb-2">

                                                <!-- Rating Start -->
                                                <span class="ratings justify-content-start mb-3">
                                                    <star-rating :show-rating="true" :star-size="18"
                                                        v-model="review.rating" :read-only="true"
                                                        :increment="0.5"></star-rating>
                                                </span>
                                                {{-- <span class="ratings justify-content-start mb-3">
                                                        <span class="rating-wrap">
                                                            <span class="star" style="width: 100%"></span>
                                                </span>
                                                <span class="rating-num">(1)</span>
                                                </span> --}}
                                                <!-- Rating End -->

                                                <!-- Review Title & Date Start -->
                                                <div class="review-title-date d-flex">
                                                    <h5 class="title">@{{ review.name }}</span> - </h5><span>
                                                        @{{ review.created_at | humanReadableTime }}</span>
                                                </div>
                                                <!-- Review Title & Date End -->

                                            </div>
                                            <p>@{{ review.comment }}</p>
                                        </div>
                                        <!-- Review Details End -->
                                    </div>
                                    <!-- End Single Review -->

                                    <!-- Rating Wrap Start -->
                                    {{-- <div class="rating_wrap">
                                        <h5 class="rating-title mb-2">Add a review </h5>
                                        <p class="mb-2">Your email address will not be published. Required fields are marked *</p>
                                        <!-- Rating End -->
    
                                    </div> --}}
                                    <!-- Rating Wrap End -->

                                    <!-- Comments ans Replay Start -->
                                    <div class="comments-area comments-reply-area">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h2 class="title pb-3" style="font-size: 24px;">
                                                    {{ __('Leave Your Review') }}
                                                </h2>
                                            </div>
                                            <div class="col-lg-12 col-custom">

                                                <!-- Comment form Start -->
                                                <form class="comment-form-area" method="post"
                                                    @submit.prevent="updateReview({{ $info->id }})"
                                                    action="{{ url('/make-review', $info->id) }}" id="commentForm">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <validation-errors :errors="reviewValidationErrors"
                                                                v-if="reviewValidationErrors"></validation-errors>
                                                        </div>
                                                    </div>
                                                    <div class="comment-input mb-3">
                                                        <!-- Input Email Start -->
                                                        <label>{{ __('Your Rating') }} <span>*</span></label>
                                                        <star-rating :star-size="26" :increment="1"
                                                            v-model="form.rating">
                                                        </star-rating>
                                                        <!-- Input Email End -->

                                                    </div>
                                                    <!-- Comment Texarea Start -->
                                                    <div class="comment-form-comment mb-3">
                                                        <label>{{ __('Your review') }}</label>
                                                        <textarea class="comment-notes" required="required" v-model="form.comment" name="comment" id="comment"
                                                            cols="30" maxlength="200" rows="9" placeholder="Write Comment"></textarea>
                                                    </div>
                                                    <!-- Comment Texarea End -->

                                                    <!-- Comment Submit Button Start -->

                                                    <div class="comment-form-submit">
                                                        @if (Auth::guard('customer')->check())
                                                            <button class="btn btn-fb btn-hover-primary"
                                                                type="submit">{{ __('Send Review') }}</button>
                                                        @else
                                                            <a class="btn btn-fb btn-hover-primary"
                                                                href="{{ url('/user/login') }}" role="button">
                                                                <i class="fas fa-sign-in-alt"></i>
                                                                {{ __('Please Login') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <!-- Comment Submit Button End -->

                                                </form>
                                                <!-- Comment form End -->

                                            </div>
                                        </div>
                                    </div>
                                    <!-- Comments ans Replay End -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single Product Tab End -->
                </div>



                <!-- Products Start -->
                @if (count($related) > 0)
                    <div class="row">

                        <div class="col-12">
                            <!-- Section Title Start -->
                            <div class="section-title aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                                <h2 class="title pb-3">{{ __('Related products') }}</h2>
                                <span></span>
                                <div class="title-border-bottom"></div>
                            </div>
                            <!-- Section Title End -->
                        </div>


                        <div class="col">
                            <div class="product-carousel">
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">

                                        @foreach ($related as $product)
                                            <div class="swiper-slide product-wrapper">
                                                <related-product-card :product="{{ $product }}">
                                                </related-product-card>
                                            </div>
                                        @endforeach

                                        {{-- <!-- Product Start -->
                                <div class="swiper-slide product-wrapper">

                                    <!-- Single Product Start -->
                                    <div class="product product-border-left" data-aos="fade-up" data-aos-delay="300">
                                        <div class="thumb">
                                            <a href="single-product.html" class="image">
                                                <img class="first-image" src="assets/images/products/medium-size/1.jpg" alt="Product" />
                                                <img class="second-image" src="assets/images/products/medium-size/5.jpg" alt="Product" />
                                            </a>
                                            <div class="actions">
                                                <a href="javascript::void(0)" class="action wishlist"><i class="pe-7s-like"></i></a>
                                                <a href="javascript::void(0)" class="action quickview" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="pe-7s-search"></i></a>
                                                <a href="javascript::void(0)" class="action compare"><i class="pe-7s-shuffle"></i></a>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <h4 class="sub-title"><a href="single-product.html">Studio Design</a></h4>
                                            <h5 class="title"><a href="single-product.html">Brother Hoddies in Grey</a></h5>
                                            <span class="ratings">
                                                    <span class="rating-wrap">
                                                        <span class="star" style="width: 100%"></span>
                                            </span>
                                            <span class="rating-num">(4)</span>
                                            </span>
                                            <span class="price">
                                                    <span class="new">$38.50</span>
                                            <span class="old">$42.85</span>
                                            </span>
                                            <button class="btn btn-sm btn-fb btn-hover-primary">Add To Cart</button>
                                        </div>
                                    </div>
                                    <!-- Single Product End -->

                                </div>
                                <!-- Product End --> --}}
                                    </div>

                                    <!-- Swiper Pagination Start -->
                                    <div class="swiper-pagination d-md-none"></div>
                                    <!-- Swiper Pagination End -->

                                    <!-- Next Previous Button Start -->
                                    <div
                                        class="swiper-product-button-next swiper-button-next swiper-button-white d-md-flex d-none">
                                        <i class="pe-7s-angle-right"></i>
                                    </div>
                                    <div
                                        class="swiper-product-button-prev swiper-button-prev swiper-button-white d-md-flex d-none">
                                        <i class="pe-7s-angle-left"></i>
                                    </div>
                                    <!-- Next Previous Button End -->
                                </div>

                            </div>
                        </div>

                    </div>
                @endif
                <!-- Products End -->

            </div>
        </div>
        <!-- Shop Section End -->
    </product-show>
@endsection
@push('js')
    <script>
        /*-- Single product with Thumbnail Horizental -- */
        var galleryThumbs = new Swiper('.single-product-thumb', {
            spaceBetween: 10,
            slidesPerView: 4,
            // loop: false,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 2,
                },
                // when window width is >= 575px
                575: {
                    slidesPerView: 3,
                },
                // when window width is >= 767px
                767: {
                    slidesPerView: 4,
                },
                // when window width is >= 991px
                991: {
                    slidesPerView: 3,
                },
                // when window width is >= 1200px
                1200: {
                    slidesPerView: 4,
                },
            }
        });
        var galleryTop = new Swiper('.single-product-img', {
            spaceBetween: 10,
            loop: true,
            navigation: {
                nextEl: '.single-product-thumb, .swiper-button-horizental-next',
                prevEl: '.single-product-thumb, .swiper-button-horizental-prev',
            },
            // loop: true,
            // loopedSlides: 5, //looped slides should be the same
            thumbs: {
                swiper: galleryThumbs,
            },
        });
        var productCarousel = new Swiper('.product-carousel .swiper-container', {
            loop: false,
            slidesPerView: 3,
            spaceBetween: 0,
            pagination: true,
            navigation: true,
            observer: true,
            observeParents: true,

            navigation: {
                nextEl: '.product-carousel .swiper-product-button-next',
                prevEl: '.product-carousel .swiper-product-button-prev'
            },
            pagination: {
                el: '.product-carousel .swiper-pagination',
                type: 'bullets',
                clickable: 'true'
            },

            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                },
                // when window width is >= 480px
                480: {
                    slidesPerView: 1,
                },
                // when window width is >= 575px
                575: {
                    slidesPerView: 2,
                },
                // when window width is >= 768px
                768: {
                    slidesPerView: 2,
                },
                // when window width is >= 992px
                992: {
                    slidesPerView: 3,
                },
                // when window width is >= 1200px
                1200: {
                    slidesPerView: 4,
                }
            }
        });
    </script>
@endpush
