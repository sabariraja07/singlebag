@extends('electrobag::layouts.app')
@push('css')

<style>
    .table-borderless > tbody > tr > td,
    .table-borderless > tbody > tr > th,
    .table-borderless > tfoot > tr > td,
    .table-borderless > tfoot > tr > th,
    .table-borderless > thead > tr > td,
    .table-borderless > thead > tr > th {
        border: none !important;
    }


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
        <div class="container">
            <div class="empty-space col-xs-b15 col-sm-b30"></div>
            <div class="breadcrumbs">
                <a href="{{ url('/') }}">{{ __('Home') }}</a>
                <a href="{{ url('/shop') }}">{{ __('Product') }}</a>
                <a href="javascript:void(0)">{{ $info->title }}</a>
            </div>
            <div class="empty-space col-xs-b15 col-sm-b50 col-md-b100"></div>
            <div class="row">
                <div class="col-md-9 col-md-push-32">
                    <div class="row">
                        <div class="col-sm-6 col-xs-b30 col-sm-b0">
                            <div class="main-product-slider-wrapper swipers-couple-wrapper">
                                <div class="swiper-container swiper-control-top">
                                    <div class="swiper-button-prev hidden"></div>
                                    <div class="swiper-button-next hidden"></div>
                                    <div class="swiper-wrapper">
                                        @foreach ($gallery as $index => $row)
                                            <div class="swiper-slide">
                                                <div class="swiper-lazy-preloader"></div>
                                                <div class="product-big-preview-entry swiper-lazy"
                                                    data-background="{{ asset($row->url) }}"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="empty-space col-xs-b30 col-sm-b60"></div>
                                <div class="swiper-container swiper-control-bottom" data-breakpoints="1" data-xs-slides="3"
                                    data-sm-slides="3" data-md-slides="4" data-lt-slides="5" data-slides-per-view="5"
                                    data-center="1" data-click="1">
                                    <div class="swiper-button-prev hidden"></div>
                                    <div class="swiper-button-next hidden"></div>
                                    <div class="swiper-wrapper">
                                        @foreach ($gallery as $index => $row)
                                            <div class="swiper-slide">
                                                <div class="product-small-preview-entry">
                                                    <img src="{{ asset($row->url) }}" width="70" height="70"
                                                        alt="" />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="h3 col-xs-b25">{{ $info->title }}</div>
                            <div class="simple-article size-3 grey col-xs-b5">{{ $info->short_description }}
                            </div>

                            <div class="row col-xs-b25">
                                <div class="col-sm-6">
                                    @if ($info->price->starting_date != null)
                                        <div class="product-price primary-color float-left">
                                            <span class="current-price text-brand" data-price="{{ $info->price->selling_price }}"
                                                data-regular-price="{{ $info->price->regular_price }}">{{ amount_format($info->price->selling_price) }}</span>
                                            @if ($info->offer_discount > 0)
                                                <span class="discount-price-info">
                                                    <span class="save-price font-md color3 ml-15">
                                                        {{ $info->offer_discount }}
                                                        % Off</span>
                                                    <span
                                                        class="old-price font-md ml-15">{{ amount_format($info->price->regular_price) }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="product-price primary-color float-left">
                                            <span class="current-price text-brand" data-price="{{ $info->price->selling_price }}"
                                                data-regular-price="{{ $info->price->regular_price }}">{{ amount_format($info->price->selling_price) }}</span>
                                            <!-- <span>
                                                                <span class="save-price font-md color3 ml-15">26% Off</span>
                                                                <span class="old-price font-md ml-15">$52</span>
                                                            </span> -->
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-6 col-sm-text-right">
                                    <div class="rate-wrapper align-inline">
                                        <star-rating :show-rating="false" :star-size="18" v-model="avgRating"
                                            :read-only="true" :increment="0.5"></star-rating>
                                    </div>
                                    <div class="simple-article size-2 align-inline">{{ $info->reviews_count }}
                                        {{ __('reviews') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if (!empty($info->stock->sku))
                                @if ($info->stock->stock_manage == 1 || isset($info->stock->sku))
                                    <div class="col-sm-6">
                                        <div class="simple-article size-3 col-xs-b5">{{ __('SKU') }}: <span
                                                class="grey">{{ $info->stock->sku }}</span></div>
                                    </div>
                                @endif
                                @endif
                                @if(domain_info('shop_type') == 'seller')
                                    @if ($info->inStock() && $info->stock->stock_manage == 1)
                                        <div class="col-sm-6">
                                            <div class="simple-article size-3 col-xs-b5">Stock: <span
                                                    class="grey">{{ $info->stock->stock_qty }} Items In Stock</span></div>
                                        </div>
                                    @endif
                                @endif
                                @if(!$info->inStock())
                                    <div class="col-sm-6">
                                        <div class="simple-article size-3 col-xs-b5">
                                            Stock:<span style="color:red;">
                                                Out Of Stock
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <form id="add-to-cart-form" @submit.prevent="addCart()" novalidate>
                                @foreach ($info->options as $key => $option)
                                    <div class="empty-space col-xs-b30 col-sm-b30"></div>
                                    <div class="row col-xs-b40">
                                        <div class="col-sm-3">
                                            <div class="h6 detail-data-title size-1">{{ $option->name }} @if ($option->is_required == 1)
                                                    <span class="text-danger">*</span>
                                                @endif :</div>
                                        </div>
                                        <div class="col-sm-9 option-check">

                                            @foreach ($option->variants as $row)
                                                @if ($option->select_type == 1)
                                                    <label class="checkbox-entry">
                                                        <input
                                                            class="form-check-input options @if ($option->is_required == 1) req @endif"
                                                            type="checkbox" name="option[{{ $option->id }}][]"
                                                            id="option_{{ $row->id }}" value="{{ $row->id }}"
                                                            @if ($option->is_required == 1) required @endif
                                                            data-amount="{{ $row->price ?? $row->amount }}"
                                                            data-amount-type="{{ $row->amount_type }}"
                                                            @change="updateCheckboxTypeOptionValue({{ $option->id }}, $event)" />

                                                        <span>{{ $row->name }}</span>
                                                    </label>
                                                    <div class="empty-space col-xs-b30 col-sm-b10"></div>
                                                @else
                                                    <label class="checkbox-entry radio">
                                                        <input
                                                            class="form-check-input options @if ($option->is_required == 1) req @endif"
                                                            type="radio" name="option[{{ $option->id }}]"
                                                            id="option_{{ $row->id }}"
                                                            @if ($option->is_required == 1) required @endif
                                                            value="{{ $row->id }}"
                                                            data-amount="{{ $row->price ?? $row->amount }}"
                                                            data-amount-type="{{ $row->amount_type }}"
                                                            @click.stop="syncCustomRadioTypeOptionValue({{ $option->id }}, {{ $row->id }})" />

                                                        <span>{{ $row->name }}</span>
                                                    </label>
                                                    <div class="empty-space col-xs-b30 col-sm-b10"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                @if($info->inStock())
                                <div class="empty-space col-xs-b30 col-sm-b50"></div>
                                <div class="row col-xs-b40">
                                    <div class="col-sm-3">
                                        <div class="h6 detail-data-title size-1">quantity:</div>
                                    </div>
                                    <div class="col-sm-9">
                                            <div class="quantity-select">
                                                <span @click="updateQuantity(qty-1)" class="minus"></span>
                                                <span class="qty-val" v-text="qty"></span>
                                                <span @click="updateQuantity(qty+1)" class="plus"></span>
                                            </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row m5 col-xs-b40">
                                    <div class="col-sm-6 col-xs-b10 col-sm-b0">
                                        @if ($info->inStock())
                                            <a class="button size-2 style-2 block" href="javascript:void(0)"
                                                @click="addCart">
                                                <span class="button-wrapper">
                                                    <span class="icon"><img
                                                            src="{{ asset('frontend/electrobag/img/icon-2.png') }}"
                                                            alt=""></span>
                                                    <span class="text">{{ __('Add to cart') }}</span>
                                                </span>
                                            </a>
                                        @endif
                                        @if (!$info->inStock())
                                            <a class="button size-2 style-2 block" href="javascript:void(0)"><span
                                                    class="button-wrapper">
                                                    <span class="icon"><img
                                                        src="{{ asset('frontend/electrobag/img/icon-2.png') }}"
                                                        alt=""></span>
                                                    <span class="text">
                                                        {{ __('Out of stock') }}
                                                    </span></span>
                                            </a>
                                        @endif



                                    </div>
                                    <div class="col-sm-6">
                                        <a class="button size-2 style-1 block noshadow"
                                            :class="inWishlist ?  'style-3' : 'style-1'"
                                            @click="syncWishlist">
                                            <span class="button-wrapper">
                                                <span class="icon">
                                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                </span>
                                                <span class="text" v-if="inWishlist">Remove favourites</span>
                                                <span class="text" v-else>Add to favourites</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                @if (current_shop_type() == 'reseller')
                                    @if (!empty($info->category))
                                    <div class="col-sm-12">
                                        <div class="simple-article size-3 col-xs-b20">{{ __('Category') }}:
                                            <a href="{{ url('/category/' . $info->category->slug . '/' . $info->category->id) }}"><span class="grey">{{ $info->category->name }}</span></a>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    @if (!empty($info->categories) && count($info->categories) > 0)
                                        <div class="col-sm-12">
                                            <div class="simple-article size-3 col-xs-b20">{{ __('Category') }}:
                                                @foreach ($info->categories as $row)
                                                    <a href="{{ url('/category/' . $row->slug . '/' . $row->id) }}"
                                                        rel="tag"><span class="grey">{{ $row->name }}</span></a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                @if (isset($info->brand))
                                    <div class="col-sm-12">
                                        <div class="simple-article size-3 col-xs-b20">{{ __('Brand') }}:
                                            <a href="{{ url('/brand/' . $info->brand->slug . '/' . $info->brand->id) }}"
                                                rel="tag"><span class="grey">{{ $info->brand->name }}</span></a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="empty-space col-xs-b35 col-md-b70"></div>

                    <div class="tabs-block">
                        <div class="tabulation-menu-wrapper text-center">
                            <div class="tabulation-title simple-input">description</div>
                            <ul class="tabulation-toggle">
                                <li><a class="tab-menu" @click="activeTab = 'description'"
                                        :class="{ active: activeTab === 'description' }">description</a></li>
                                <li><a class="tab-menu" @click="activeTab = 'variations'"
                                        :class="{ active: activeTab === 'variations' }">Additional info</a></li>
                                <li><a class="tab-menu" @click="activeTab = 'reviews'"
                                        :class="{ active: activeTab === 'reviews' }">Reviews</a></li>
                            </ul>
                        </div>
                        <div class="empty-space col-xs-b30 col-sm-b60"></div>

                        <div class="tab-entry" :class="{ 'visible': activeTab === 'description' }">
                            <div class="row">
                                <div class="col-sm-12 col-xs-b30 col-sm-b0">
                                    <div class="h5">Product Description</div>
                                    <div class="empty-space col-xs-b20"></div>
                                    <div class="simple-article size-2">
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

                                    <div class="description">

                                    </div>
                                </div>
                            </div>

                            <div class="empty-space col-xs-b35 col-md-b70"></div>
                        </div>

                        <div class="tab-entry" :class="{ 'visible': activeTab === 'variations' }">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                @foreach ($variations as $key => $attribute)
                                                    <tr class="stand-up">
                                                        <th>{{ $attribute->name }} :</th>
                                                        <td style="text-align: left;">
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
                            </div>
                            <div class="empty-space col-xs-b30 col-sm-b60"></div>
                        </div>

                        <div class="tab-entry" :class="{ 'visible': activeTab === 'reviews' }">
                            <div class="testimonial-entry" v-for="review in reviews.data" :key="review.id" v-if="review.status == 1">
                                <div class="row col-xs-b20">
                                    <div class="col-xs-8">
                                        <div class="avatar">
                                            <div class="avatar__letters">
                                                <span style="font-size: 31px;">@{{ review.name.match(/\b(\w)/g).join('') }}</span>
                                            </div>
                                        </div>
                                        {{-- <img class="preview" src="{{ asset('frontend/electrobag/img/author-1.png') }}" alt="" /> --}}
                                        <div class="heading-description" style="margin-left: 85px !important;">
                                            <div class="h6 col-xs-b5" style="margin-top: -54px;">@{{ review.name }}</div>
                                            {{-- <div class="rate-wrapper align-inline">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star-o" aria-hidden="true"></i>
                                            </div> --}}
                                            <star-rating :show-rating="false" :star-size="18"
                                                v-model="review.rating" :read-only="true" :increment="0.5">
                                            </star-rating>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 text-right">
                                        <div class="simple-article size-1 grey">@{{ review.created_at | humanReadableTime }}
                                        </div>
                                    </div>
                                </div>
                                <div class="simple-article size-2 col-xs-b15">@{{ review.comment }}</div>
                            </div>
                            <form class="form-contact comment_form" method="post"
                                @submit.prevent="updateReview({{ $info->id }})"
                                action="{{ url('/make-review', $info->id) }}" id="commentForm">
                                @csrf
                                <div class="empty-space col-xs-b20"></div>
                                <div class="h5">{{ __('Leave Your Review') }}</div>
                                <div class="empty-space col-xs-b20"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <validation-errors :errors="reviewValidationErrors" v-if="reviewValidationErrors"></validation-errors>
                                    </div>
                                </div>
                                <div class="row m10">
                                    <div class="col-sm-12">
                                        <textarea class="simple-input" v-model="form.comment" name="comment" id="comment" cols="30" maxlength="200"
                                            rows="9" placeholder="Write Comment"></textarea>
                                        <div class="empty-space col-xs-b20"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="align-inline">
                                            <div class="empty-space col-xs-b5"></div>
                                            <div class="simple-article size-3">{{ __('Your Rating') }}</div>
                                            <div class="empty-space col-xs-b5"></div>
                                        </div>
                                        <div class="rate-wrapper set align-inline">
                                            <star-rating :star-size="16" :increment="0.5"
                                                v-model="form.rating">
                                            </star-rating>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <div class="button size-2 style-3">
                                            @if (Auth::guard('customer')->check())
                                                <span class="button-wrapper">
                                                    <span class="icon"><img
                                                            src="{{ asset('frontend/electrobag/img/author-1.png') }}"
                                                            alt=""></span>
                                                    <span class="text">{{ __('Send Review') }}</span>
                                                </span>
                                                <input type="submit" value="">
                                            @else
                                                <a href="{{ url('/user/login') }}" class="btn btn-info review_btn">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                    {{ __('Please Login') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                            {{-- <form>
                                <div class="row m10">
                                    <div class="col-sm-6">
                                        <input class="simple-input" type="text" value="" placeholder="Your name" />
                                        <div class="empty-space col-xs-b20"></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="simple-input" type="text" value="" placeholder="Your name" />
                                        <div class="empty-space col-xs-b20"></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input class="simple-input" type="text" value="" placeholder="Describe the pros" />
                                        <div class="empty-space col-xs-b20"></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input class="simple-input" type="text" value="" placeholder="Describe cons" />
                                        <div class="empty-space col-xs-b20"></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <textarea class="simple-input" placeholder="Your comment"></textarea>
                                        <div class="empty-space col-xs-b20"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="align-inline">
                                            <div class="empty-space col-xs-b5"></div>
                                            <div class="simple-article size-3">Rating&nbsp;&nbsp;&nbsp;</div>
                                            <div class="empty-space col-xs-b5"></div>
                                        </div>
                                        <div class="rate-wrapper set align-inline">
                                            <i class="fa fa-star-o" aria-hidden="true"></i>
                                            <i class="fa fa-star-o" aria-hidden="true"></i>
                                            <i class="fa fa-star-o" aria-hidden="true"></i>
                                            <i class="fa fa-star-o" aria-hidden="true"></i>
                                            <i class="fa fa-star-o" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <div class="button size-2 style-3">
                                            <span class="button-wrapper">
                                                <span class="icon"><img src="img/icon-4.png" alt=""></span>
                                                <span class="text">submit</span>
                                            </span>
                                            <input type="submit" value="">
                                        </div>
                                    </div>
                                </div>
                            </form> --}}
                        </div>
                    </div>
                   
                    <div class="empty-space col-xs-b30 col-sm-b50"></div>

                    <div class="empty-space col-xs-b35 col-md-b70"></div>
                    <input type="hidden" id="max_qty"
                        @if ($info->stock->stock_manage == 1) value="{{ $info->stock->stock_qty }}"
                @else
                value="999" @endif>
                    <input type="hidden" id="term" value="{{ $info->id }}">
                </div>
            </div>
        </div>
    </product-show>
@endsection
@push('js')
@endpush
