@extends('singlebag::layouts.app')
@section('breadcrumb')
    <span></span> <a href="{{ url('/shop') }}">{{ __('Product') }}</a>
    <span></span> {{ $info->title }}
@endsection
@push('css')
    <style>
        .avatar {
            /* Center the content */
            display: inline-block;
            vertical-align: middle;

            /* Used to position the content */
            position: relative;

            /* Colors */
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;

            /* Rounded border */
            border-radius: 50%;
            height: 65px;
            width: 64px;
        }

        .avatar__letters {
            /* Center the content */
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .embed-responsive-item{
            width: 100%;
            max-width: 650px;
            aspect-ratio: 16 / 9;
        }
    </style>
@endpush
@section('content')
    <product-show :product="{{ $info }}" :avg-rating="{{ $info->avg_rating ?? 0 }}"
        :review-count="{{ $info->reviews_count ?? 0 }}" inline-template>
        <div class="container mb-30">
            <div class="row">
                <div class="col-xl-10 col-lg-12 m-auto">
                    <div class="row">
                        <div class="product-detail accordion-detail">
                            <div class="row mb-50 mt-30">
                                <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                    <div class="detail-gallery">
                                        <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                        <!-- MAIN SLIDES -->
                                        <div class="product-image-slider">
                                            @foreach ($gallery as $index => $row)
                                                <figure class="border-radius-10">
                                                    <img src="{{ asset($row->url) }}" alt="product image" />
                                                </figure>
                                            @endforeach
                                        </div>
                                        <!-- THUMBNAILS -->
                                        <div class="slider-nav-thumbnails">
                                            @foreach ($gallery as $index => $row)
                                                <div><img src="{{ asset($row->url) }}" alt="product image" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- End Gallery -->
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="detail-info pr-30 pl-30">
                                        @if (!$info->inStock())
                                            <span class="stock-status out-stock"> {{ __('Out of stock') }} </span>
                                        @endif

                                        <h2 class="title-detail mb-1">{{ $info->title }} </h2>
                                        <star-rating :show-rating="false" :star-size="18" v-model="avgRating"
                                            :read-only="true" :increment="0.5"></star-rating>
                                        <span class="font-small ml-5 text-muted"> ({{ $info->reviews_count }}
                                            {{ __('reviews') }})</span>
                                        <div class="clearfix product-price-cover">
                                            @if ($info->price->starting_date != null)
                                                <div class="product-price primary-color float-left">
                                                    <span class="current-price text-brand"
                                                        data-price="{{ $info->price->selling_price }}"
                                                        data-regular-price="{{ $info->price->regular_price }}">{{ amount_format($info->price->selling_price) }}</span>
                                                    @if ($info->offer_discount > 0)
                                                        <span class="discount-price-info">
                                                            <span class="save-price font-md color3 ml-15">
                                                                {{ $info->offer_discount }} % Off
                                                            </span>
                                                            <span class="old-price font-md ml-15">{{ amount_format($info->price->regular_price) }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="product-price primary-color float-left">
                                                    <span class="current-price text-brand"
                                                        data-price="{{ $info->price->selling_price }}"
                                                        data-regular-price="{{ $info->price->regular_price }}">{{ amount_format($info->price->selling_price) }}</span>
                                                    <!-- <span>
                                                            <span class="save-price font-md color3 ml-15">26% Off</span>
                                                            <span class="old-price font-md ml-15">$52</span>
                                                        </span> -->
                                                </div>
                                            @endif
                                        </div>
                                        <div class="short-desc mb-30">
                                            <p class="font-lg">{{ $info->short_description }}</p>
                                        </div>
                                        <form id="add-to-cart-form" @submit.prevent="addCart()" novalidate>
                                            @if (count($info->options) > 0)
                                                <hr>
                                            @endif
                                            {{-- @foreach ($variations as $key => $item)
                                    <div class="attr-detail attr-size mb-30">
                                        <strong class="mr-10">{{ $key }} : </strong>
                                        <ul class="list-filter size-filter font-small">
                                            @foreach ($item as $row)
                                            <li>
                                                <a @click="variation[{{ $row->attribute->id }}] = {{ $row->variation->id }}">
                                                    {{ $row->variation->name }} 
                                                    <input type="radio" class="variation" hidden v-model="variation" value="{{ $row->variation->id }}" name="variation[{{ $row->attribute->id }}]">
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endforeach
                                    @if (count($info->options) > 0)
                                    <hr>
                                    @endif --}}
                                            @foreach ($info->options as $key => $option)
                                                <div class="form-group option-check">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-4">
                                                            <label>
                                                                <strong>{{ $option->name }} @if ($option->is_required == 1)
                                                                        <span class="text-danger">*</span>
                                                                    @endif :
                                                                </strong>
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6 col-lg-8">
                                                            @foreach ($option->variants as $row)
                                                                @if ($option->select_type == 1)
                                                                    <div class="custome-checkbox">
                                                                        <input
                                                                            class="form-check-input options @if ($option->is_required == 1) req @endif"
                                                                            type="checkbox"
                                                                            name="option[{{ $option->id }}][]"
                                                                            id="option_{{ $row->id }}"
                                                                            value="{{ $row->id }}"
                                                                            @if ($option->is_required == 1) required @endif
                                                                            data-amount="{{ $row->price ?? $row->amount }}"
                                                                            data-amount-type="{{ $row->amount_type }}"
                                                                            @change="updateCheckboxTypeOptionValue({{ $option->id }}, $event)" />
                                                                        <label class="form-check-label"
                                                                            for="option_{{ $row->id }}">
                                                                            <span>{{ $row->name }}</span>
                                                                        </label>
                                                                    </div>
                                                                @else
                                                                    <div class="custome-radio">
                                                                        <input
                                                                            class="form-check-input options @if ($option->is_required == 1) req @endif"
                                                                            type="radio"
                                                                            name="option[{{ $option->id }}]"
                                                                            id="option_{{ $row->id }}"
                                                                            @if ($option->is_required == 1) required @endif
                                                                            value="{{ $row->id }}"
                                                                            data-amount="{{ $row->price ?? $row->amount }}"
                                                                            data-amount-type="{{ $row->amount_type }}"
                                                                            @click.stop="syncCustomRadioTypeOptionValue({{ $option->id }}, {{ $row->id }})" />
                                                                        <label class="form-check-label"
                                                                            for="option_{{ $row->id }}">
                                                                            <span>{{ $row->name }}</span>
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if (count($info->options) > 0)
                                                <hr>
                                            @endif
                                            <div class="detail-extralink mb-50">
                                                @if ($info->inStock())
                                                    <div class="detail-qty border radius">
                                                        <a @click="updateQuantity(qty-1)" class="qty-down"><i
                                                                class="fi-rs-angle-small-down"></i></a>
                                                        <span class="qty-val" v-text="qty"></span>
                                                        <a @click="updateQuantity(qty+1)" class="qty-up"><i
                                                                class="fi-rs-angle-small-up"></i></a>
                                                    </div>
                                                @endif
                                                <div class="product-extra-link2">
                                                    @if ($info->inStock())
                                                        <button type="submit" class="button button-add-to-cart">
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                v-if="addingToCart" aria-hidden="true">
                                                            </span>
                                                            <i class="fi-rs-shopping-cart mr-5" v-else></i>
                                                            {{ __('Add to cart') }}
                                                        </button>
                                                    @endif

                                                    <a aria-label="Add To Wishlist"
                                                        class="action-btn add-wishlist-btn-sm hover-up"
                                                        :class="{ 'active': inWishlist }" @click="syncWishlist">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            v-if="loadingWishlist" aria-hidden="true"></span>
                                                        <i class="fi-rs-heart" v-else></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="font-xs">
                                            <ul class="float-start">
                                                @if (!empty($info->stock->sku))
                                                @if ($info->stock->stock_manage == 1 || isset($info->stock->sku))
                                                    <li>SKU:<span
                                                            class="in-stock text-brand ml-5">{{ $info->stock->sku }}</span>
                                                    </li>
                                                @endif
                                                @endif
                                                @if (current_shop_type() == 'reseller')
                                                    @if (!empty($info->category))
                                                        <li class="mb-5">{{ __('Category') }} :
                                                            <a href="{{ url('/category/' . $info->category->slug . '/' . $info->category->id) }}"
                                                                rel="tag">{{ $info->category->name }}</a>
                                                        </li>
                                                    @endif
                                                @else
                                                    @if (count($info->categories) > 0)
                                                        @if (!empty($info->categories))
                                                            <li class="mb-5">{{ __('Category') }} :
                                                                @foreach ($info->categories as $row)
                                                                    <a href="{{ url('/category/' . $row->slug . '/' . $row->id) }}"
                                                                        rel="tag">{{ $row->name }}</a>
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endif
                                                @if (!empty($info->brand))
                                                    <li class="mb-5">{{ __('Brand') }} :
                                                        <a href="{{ url('/brand/' . $info->brand->slug . '/' . $info->brand->id) }}"
                                                            rel="tag">{{ $info->brand->name }}</a>
                                                    </li>
                                                @endif
                                                @if(domain_info('shop_type') == 'seller')
                                                    @if ($info->inStock() && $info->stock->stock_manage == 1)
                                                        <li>Stock:<span
                                                                class="in-stock text-brand ml-5">{{ $info->stock->stock_qty }}
                                                                Items In Stock</span>
                                                        </li>
                                                    @endif
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Detail Info -->
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="tab-style3">
                                    <ul class="nav nav-tabs text-uppercase">
                                        <li class="nav-item">
                                            <a class="nav-link" id="description-tab" data-bs-toggle="tab"
                                                @click="activeTab = 'description'"
                                                :class="{ active: activeTab === 'description' }">{{ 'Description' }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="variations-tab" data-bs-toggle="tab"
                                                @click="activeTab = 'variations'"
                                                :class="{ active: activeTab === 'variations' }">{{ __('Additional info') }}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                                                @click="activeTab = 'reviews'"
                                                :class="{ active: activeTab === 'reviews' }">{{ __('Review') }}</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content shop_info_tab entry-main-content">
                                        <div class="tab-pane fade" :class="{ 'show active': activeTab === 'description' }"
                                            id="description">
                                            <div>
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
                                        <div class="tab-pane fade" :class="{ 'show active': activeTab === 'variations' }"
                                            id="variations">
                                            <table class="font-md">
                                                <tbody>
                                                    @foreach ($variations as $key => $attribute)
                                                        <tr class="stand-up">
                                                            <th>{{ $attribute->name }}</th>
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
                                        <div class="tab-pane fade" :class="{ 'show active': activeTab === 'reviews' }"
                                            id="reviews">
                                            <!--comment form-->
                                            <div class="comment-form">
                                                <h4 class="mb-15">{{ __('Leave Your Review') }}</h4>
                                                <br>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-12">
                                                        <form class="form-contact comment_form" method="post"
                                                            @submit.prevent="updateReview({{ $info->id }})"
                                                            action="{{ url('/make-review', $info->id) }}"
                                                            id="commentForm">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <validation-errors :errors="reviewValidationErrors"
                                                                        v-if="reviewValidationErrors"></validation-errors>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="">{{ __('Your Rating') }}</label>
                                                                        <star-rating :star-size="26"
                                                                            :increment="1" v-model="form.rating">
                                                                        </star-rating>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control w-100" v-model="form.comment" name="comment" id="comment" cols="30"
                                                                            maxlength="200" rows="9" placeholder="Write Comment"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                @if (Auth::guard('customer')->check())
                                                                    <button type="submit"
                                                                        class="button button-contactForm">{{ __('Send Review') }}</button>
                                                                @else
                                                                    <a href="{{ url('/user/login') }}"
                                                                        class="btn btn-info review_btn">
                                                                        <i class="fas fa-sign-in-alt"></i>
                                                                        {{ __('Please Login') }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--Comments-->
                                            <div class="comments-area">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        {{-- <h4 class="mb-30">{{ __('Customer reviews') }}</h4> --}}
                                                        <div class="comment-list">
                                                            <div v-for="review in reviews.data" :key="review.id"
                                                                class="single-comment justify-content-between d-flex mb-30" v-if="review.status == 1">
                                                                <div class="user justify-content-between d-flex">
                                                                    <div class="thumb text-center">
                                                                        <div class="avatar">
                                                                            <div class="avatar__letters">
                                                                                <span style="font-size: 31px;">@{{ review.name.match(/\b(\w)/g).join('') }}</span>
                                                                            </div>
                                                                        </div>
                                                                        {{-- <img src="{{ asset('frontend/singlebag/imgs/blog/author-4.png') }}" alt=""> --}}
                                                                        <br>
                                                                        <a href="#"
                                                                            class="font-heading text-brand">@{{ review.name }}</a>
                                                                    </div>
                                                                    <div class="desc">
                                                                        <div class="d-flex justify-content-between mb-10">
                                                                            <div class="d-flex align-items-center">
                                                                                <span
                                                                                    class="font-xs text-muted">@{{ review.created_at | humanReadableTime }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex justify-content-end mb-10">
                                                                            <star-rating :show-rating="false"
                                                                                :star-size="18"
                                                                                v-model="review.rating"
                                                                                :read-only="true"
                                                                                :increment="0.5"></star-rating>
                                                                        </div>
                                                                        <p class="mb-10">@{{ review.comment }}</a></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4" style="display: none;">
                                                        <h4 class="mb-30">Customer reviews</h4>
                                                        <div class="d-flex mb-30">
                                                            <div class="product-rate d-inline-block mr-15">
                                                                <div class="product-rating" style="width: 90%"></div>
                                                            </div>
                                                            <h6>4.8 out of 5</h6>
                                                        </div>
                                                        <div class="progress">
                                                            <span>5 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                                aria-valuemax="100">50%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>4 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                                                                aria-valuemax="100">25%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>3 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: 45%" aria-valuenow="45" aria-valuemin="0"
                                                                aria-valuemax="100">45%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>2 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: 65%" aria-valuenow="65" aria-valuemin="0"
                                                                aria-valuemax="100">65%</div>
                                                        </div>
                                                        <div class="progress mb-30">
                                                            <span>1 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: 85%" aria-valuenow="85" aria-valuemin="0"
                                                                aria-valuemax="100">85%</div>
                                                        </div>
                                                        <a href="#" class="font-xs text-muted">How are ratings
                                                            calculated?</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (count($related) > 0)
                                <div class="row mt-60">
                                    <div class="col-12">
                                        <h2 class="section-title style-1 mb-30">{{ __('Related products') }}</h2>
                                    </div>
                                    <div class="col-12">
                                        <div class="row related-products">
                                            @foreach ($related as $product)
                                                <product-card-grid-view :key="{{ $product->id }}" :product="{{ $product }}">
                                                </product-card-grid-view>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="max_qty"
                @if ($info->stock->stock_manage == 1) value="{{ $info->stock->stock_qty }}" 
        @else
        value="999" @endif>
            <input type="hidden" id="term" value="{{ $info->id }}">
        </div>
    </product-show>
@endsection
@push('js')
    <script>
        //     if ($('#add-to-cart-form').validate()) {

        // } else {

        // var validator = $('#form').validate();

        // $.each(validator.errorMap, function (index, value) {

        // });

        // }
    </script>
    <!-- <script src="{{ asset('frontend/singlebag/js/product.js') }}"></script> -->
@endpush
