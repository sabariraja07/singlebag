@extends('multibag::layouts.app')
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
{{-- @section('breadcrumb')
@php
$title = $info->title;
@endphp
<li><a href="{{ url('/shop') }}">{{ __('Products') }}</a></li>
<li><span> > </span></li>
<li class="active">{{ $info->title }}</li>
@endsection --}}
@section('content')
    <product-show :product="{{ $info }}" :avg-rating="{{ $info->avg_rating ?? 0 }}"
        :review-count="{{ $info->reviews_count ?? 0 }}" inline-template>
        <div class="slider-mt-1">
            <div class="product-details-area product-details-bg slider-mt-7">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="product-details-tab-wrap">
                                <div class="product-details-tab-large tab-content pt-40 text-center">
                                    @foreach ($gallery as $index => $row)
                                        <div class="tab-pane {{$index == 0 ? 'active' : ''}}" id="{{ 'pro-details-' . $index }}">
                                            <div class="product-details-2-img ">
                                                <img src="{{ asset($row->url) }}" alt="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="product-details-tab-small nav">
                                    @foreach ($gallery as $index => $row)
                                    <a class="{{$index == 0 ? 'active' : ''}}" href="#pro-details-{{$index}}">
                                        <img src="{{ asset($row->url) }}" alt="">
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="product-details-content main-product-details-content">
                                @if (!$info->inStock())
                                <span class="stock-status out-stock"> {{ __('Out of stock') }} </span>
                                @endif
                                <h2>{{ $info->title }}</h2>
                                <div class="product-ratting-review-wrap">
                                    <div class="product-ratting-digit-wrap">
                                        <star-rating :show-rating="true" :star-size="18" v-model="avgRating"
                                        :read-only="true" :increment="0.5"></star-rating>
                                        {{-- <div class="product-digit">
                                            <span>@{{ avgRating }}</span>
                                        </div> --}}
                                    </div>
                                    <div class="product-review-order">
                                        <span>{{ $info->reviews_count }} {{ __('reviews') }}</span>
                                    </div>
                                </div>
                                <p>{{ $info->short_description }}</p>
                                <div class="pro-details-price">
                                    <span class="current-price" data-price="{{ $info->price->selling_price }}" data-regular-price="{{ $info->price->regular_price }}">{{ amount_format($info->price->selling_price) }}</span>
                                    @if ($info->offer_discount > 0)
                                    <span class="save-price font-md color3 ml-15">
                                        {{ $info->offer_discount }}
                                        % Off</span>
                                    <span class="old-price">{{ amount_format($info->price->regular_price) }}</span>
                                    @endif
                                </div>
    
                                @if (count($info->options) > 0)
                                <div class="pb-20"></div>
                                @endif
                                <form id="add-to-cart-form" @submit.prevent="addCart()" novalidate>
                                @foreach ($info->options as $key => $option)
                                <div class="pro-details-color-wrap rating-form-style option-check mb-20">
                                    <span @if ($option->is_required != 1) style="display: inline-block;" @endif>
                                        <label style="float: left;margin-right:5px;">{{ $option->name }}  : </label>
                                        @if ($option->is_required == 1) <span class="text-danger" style="font-size: 16px;">*</span> @endif
                                    </span>
                                    @foreach ($option->variants as $row)
                                        @if ($option->select_type == 0)
                                        <div class="product-options">
                                            <div class="product-option">
                                                <input class="input-radio options" 
                                                type="radio"
                                                name="option[{{ $option->id }}]"
                                                id="option_{{ $row->id }}"
                                                @if ($option->is_required == 1) required @endif
                                                value="{{ $row->id }}"
                                                data-amount="{{ $row->price ?? $row->amount }}"
                                                data-amount-type="{{ $row->amount_type }}"
                                                @click.stop="syncCustomRadioTypeOptionValue({{ $option->id }}, {{ $row->id }})">
                                                <label for="option_{{ $row->id }}">{{ $row->name }}</label>
                                            </div>
                                        </div>
                                        @else
                                        <div class="checkbox-option">
                                            <input class="option-input options" 
                                            type="checkbox"
                                            id="option_{{ $row->id }}"                                                      
                                            name="option[{{ $option->id }}][]"
                                            id="option_{{ $row->id }}"
                                            value="{{ $row->id }}"
                                            @if ($option->is_required == 1) required @endif
                                            data-amount="{{ $row->price ?? $row->amount }}"
                                            data-amount-type="{{ $row->amount_type }}"
                                            @change="updateCheckboxTypeOptionValue({{ $option->id }}, $event)">
                                            <span class="option-span">{{ $row->name }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endforeach
                                @if (count($info->options) > 0)
                                    <div class="pb-20"></div>
                                @endif
                                @if ($info->inStock())
                                <div class="pro-details-quality pb-20">
                                    <span>Quantity:</span>
                                    <div class="cart-plus-minus">
                                        <div class="dec qtybutton" @click="updateQuantity(qty-1)">-</div>
                                        <input class="cart-plus-minus-box" type="number" name="qtybutton" v-model="qty" disabled>
                                        <div class="inc qtybutton" @click="updateQuantity(qty+1)">+</div>
                                    </div>
                                </div>
                                @endif
                                <div class="product-details-meta">
                                    <ul>
                                        @if (!empty($info->stock->sku))
                                            @if ($info->stock->stock_manage == 1 || isset($info->stock->sku))
                                                <li><span>SKU:</span><a href="javascript:void(0)">{{ $info->stock->sku }}</a></li>
                                            @endif
                                        @endif
                                        @if (current_shop_type() == 'reseller')
                                            @if (!empty($info->category))
                                                <li><span>{{ __('Category') }} :</span>
                                                    <a href="{{ url('/category/' . $info->category->slug . '/' . $info->category->id) }}"
                                                        rel="tag">{{ $info->category->name }}</a>
                                                </li>
                                            @endif
                                        @else
                                        @if (count($info->categories) > 0)
                                            @if (!empty($info->categories))
                                                <li><span>{{ __('Category') }} :</span>
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
                                            <li><span>{{ __('Brand') }} :</span>
                                                    <a href="{{ url('/brand/' . $info->brand->slug . '/' . $info->brand->id) }}"
                                                        rel="tag">{{ $info->brand->name }}</a>
                                            </li>
                                        @endif
                                        @if(domain_info('shop_type') == 'seller')
                                            @if ($info->inStock() && $info->stock->stock_manage == 1)
                                                <li><span>Stock:</span><a href="javascript:void(0)">{{ $info->stock->stock_qty }}
                                                        Items In Stock</a>
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                                <div class="pro-details-action-wrap">
                                    @if ($info->inStock())
                                    <div class="pro-details-buy-now">
                                        <a @click="addCart()"> 
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                v-if="addingToCart" aria-hidden="true">
                                            </span>
                                            {{ __('Add to cart') }}
                                        </a>
                                    </div>
                                    @endif
                                    <div class="pro-details-action">
                                        {{-- @if ($info->inStock())
                                        <a title="Add to Cart" v-if="addingToCart" href="javascript:void(0)"><i class="icon-basket"></i></a>
                                        @endif --}}
                                        <a title="Add to Wishlist" @click="syncWishlist">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                v-if="loadingWishlist" aria-hidden="true"></span>
                                                <i v-else-if="inWishlist" class="icon-heart" style="color:red;"></i>
                                                <i v-else class="icon-heart"></i>
                                        </a>
                                        {{-- <a class="social" title="Social" href="javascript:void(0)"><i class="icon-share"></i></a>
                                        <div class="product-dec-social">
                                            <a class="facebook" title="Facebook" href="javascript:void(0)"><i
                                                    class="icon-social-facebook-square"></i></a>
                                            <a class="twitter" title="Twitter" href="javascript:void(0)"><i
                                                    class="icon-social-twitter"></i></a>
                                            <a class="instagram" title="Instagram" href="javascript:void(0)"><i
                                                    class="icon-social-instagram"></i></a>
                                            <a class="pinterest" title="Pinterest" href="javascript:void(0)"><i
                                                    class="icon-social-pinterest"></i></a>
                                        </div> --}}
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="description-review-wrapper pt-160 pb-155">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="dec-review-topbar nav mb-65">
                                <a data-bs-toggle="tab" href="#tab-description" @click="activeTab = 'description'"
                                :class="{ active: activeTab === 'description' }">{{ 'Description' }}</a>
                                <a data-bs-toggle="tab" href="#tab-variations" @click="activeTab = 'variations'"
                                :class="{ active: activeTab === 'variations' }">{{ __('Additional info') }}</a>
                                <a data-bs-toggle="tab" href="#tab-reviews" @click="activeTab = 'reviews'"
                                :class="{ active: activeTab === 'reviews' }">{{ __('Review') }}</a>
                            </div>
                            <div class="tab-content dec-review-bottom">
                                <div id="tab-description" class="tab-pane" :class="{ 'active': activeTab === 'description' }">
                                    <div class="description-wrap">
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
                                <div id="tab-variations" class="tab-pane" :class="{ 'active': activeTab === 'variations' }">
                                    <div class="specification-wrap table-responsive">
                                        <table>
                                            @foreach ($variations as $key => $attribute)
                                                        <tr>
                                                            <td class="width1">{{ $attribute->name }}</td>
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
                                {{-- <div id="des-details3" class="tab-pane" :class="{ 'active': activeTab === 'details3' }">
                                    <div class="specification-wrap table-responsive">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="width1">Top</td>
                                                    <td>Cotton Digital Print Chain Stitch Embroidery Work</td>
                                                </tr>
                                                <tr>
                                                    <td>Bottom</td>
                                                    <td>Cotton Cambric</td>
                                                </tr>
                                                <tr>
                                                    <td class="width1">Dupatta</td>
                                                    <td>Digital Printed Cotton Malmal With Chain Stitch</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> --}}
                                <div id="tab-reviews" class="tab-pane" :class="{ 'active': activeTab === 'reviews' }">
                                    <div class="review-wrapper">
                                        {{-- <h2>{{ __('Customer reviews') }}</h2> --}}
                                        <div class="single-review"  v-for="review in reviews.data" :key="review.id" v-if="review.status == 1">
                                            <div class="review-img">
                                                <div class="avatar">
                                                    <div class="avatar__letters">
                                                        <span style="font-size: 31px;">@{{ review.name.match(/\b(\w)/g).join('') }}</span>
                                                    </div>
                                                </div>
                                                {{-- <img src="assets/images/product-details/client-1.png" alt=""> --}}
                                            </div>
                                            <div class="review-content">
                                                <div class="review-top-wrap">
                                                    <div class="review-name">
                                                        <h5><span>@{{ review.name }}</span> - @{{ review.created_at | humanReadableTime }}</h5>
                                                    </div>

                                        
                                                    {{-- <div class="review-rating">
                                                        <i class="yellow icon-rating"></i>
                                                        <i class="yellow icon-rating"></i>
                                                        <i class="yellow icon-rating"></i>
                                                        <i class="yellow icon-rating"></i>
                                                        <i class="yellow icon-rating"></i>
                                                    </div> --}}
                                                </div>
                                                    <star-rating :show-rating="true"
                                                                                :star-size="18"
                                                                                v-model="review.rating"
                                                                                :read-only="true"
                                                                                :increment="0.5"></star-rating>
                                                <p>@{{ review.comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ratting-form-wrapper">
                                        <span>{{ __('Leave Your Review') }}</span>
                                        {{-- <p>Your email address will not be published. Required fields are marked
                                            <span>*</span></p> --}}
                                        <div class="ratting-form">
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
                                                    {{-- <div class="col-lg-3 col-md-6">
                                                        <div class="rating-form-style mb-20">
                                                            <label>Name <span>*</span></label>
                                                            <input type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="rating-form-style mb-20">
                                                            <label>Email <span>*</span></label>
                                                            <input type="email">
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-lg-12">
                                                        <div class="rating-form-style mb-20">
                                                            <label>{{ __('Your Rating') }} <span>*</span></label>
                                                            <star-rating :star-size="26"
                                                                :increment="1" v-model="form.rating">
                                                            </star-rating>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="rating-form-style mb-20">
                                                            <label>{{ __('Your review') }} <span>*</span></label>
                                                            <textarea  v-model="form.comment" name="comment" id="comment" cols="30"
                                                                            maxlength="200" rows="9" placeholder="Write Comment"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-submit">
                                                            @if (Auth::guard('customer')->check())
                                                            <button type="submit">{{ __('Send Review') }}</button>
                                                            @else
                                                                <a href="{{ url('/user/login') }}" class="default-btn btn-md">
                                                                    <i class="fas fa-sign-in-alt"></i>
                                                                    {{ __('Please Login') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (count($related) > 0)
            <div class="product-area pb-155">
                <div class="container">
                    <div class="section-title-8 mb-65">
                        <h2>{{ __('Related products') }}</h2>
                    </div>
                    <div class="product-slider-active-4">
                        @foreach ($related as $product)
                        <div class="product-wrap-plr-1" :key="{{ $product->id }}">
                            <product-card 
                                :product="{{ $product }}"></product-card>
                        </div>
                        @endforeach
                        {{-- <div class="product-wrap-plr-1">
                            <div class="product-wrap">
                                <div class="product-img product-img-zoom mb-25">
                                    <a href="product-details.html">
                                        <img src="assets/images/product/product-151.jpg" alt="">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h4><a href="product-details.html">Product Title</a></h4>
                                    <div class="product-price">
                                        <span>$ 124</span>
                                        <span class="old-price">$ 130</span>
                                    </div>
                                </div>
                                <div class="product-action-position-1 text-center">
                                    <div class="product-content">
                                        <h4><a href="product-details.html">Product Title</a></h4>
                                        <div class="product-price">
                                            <span>$ 124</span>
                                            <span class="old-price">$ 130</span>
                                        </div>
                                    </div>
                                    <div class="product-action-wrap">
                                        <div class="product-action-cart">
                                            <button title="Add to Cart">Add to cart</button>
                                        </div>
                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                class="icon-zoom"></i></button>
                                        <button title="Add to Compare"><i class="icon-compare"></i></button>
                                        <button title="Add to Wishlist"><i class="icon-heart-empty"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-wrap-plr-1">
                            <div class="product-wrap">
                                <div class="product-img product-img-zoom mb-25">
                                    <a href="product-details.html">
                                        <img src="assets/images/product/product-152.jpg" alt="">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h4><a href="product-details.html">Product Title</a></h4>
                                    <div class="product-price">
                                        <span>$ 124</span>
                                        <span class="old-price">$ 130</span>
                                    </div>
                                </div>
                                <div class="product-action-position-1 text-center">
                                    <div class="product-content">
                                        <h4><a href="product-details.html">Product Title</a></h4>
                                        <div class="product-price">
                                            <span>$ 124</span>
                                            <span class="old-price">$ 130</span>
                                        </div>
                                    </div>
                                    <div class="product-action-wrap">
                                        <div class="product-action-cart">
                                            <button title="Add to Cart">Add to cart</button>
                                        </div>
                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                class="icon-zoom"></i></button>
                                        <button title="Add to Compare"><i class="icon-compare"></i></button>
                                        <button title="Add to Wishlist"><i class="icon-heart-empty"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-wrap-plr-1">
                            <div class="product-wrap">
                                <div class="product-img product-img-zoom mb-25">
                                    <a href="product-details.html">
                                        <img src="assets/images/product/product-153.jpg" alt="">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h4><a href="product-details.html">Product Title</a></h4>
                                    <div class="product-price">
                                        <span>$ 124</span>
                                        <span class="old-price">$ 130</span>
                                    </div>
                                </div>
                                <div class="product-action-position-1 text-center">
                                    <div class="product-content">
                                        <h4><a href="product-details.html">Product Title</a></h4>
                                        <div class="product-price">
                                            <span>$ 124</span>
                                            <span class="old-price">$ 130</span>
                                        </div>
                                    </div>
                                    <div class="product-action-wrap">
                                        <div class="product-action-cart">
                                            <button title="Add to Cart">Add to cart</button>
                                        </div>
                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                class="icon-zoom"></i></button>
                                        <button title="Add to Compare"><i class="icon-compare"></i></button>
                                        <button title="Add to Wishlist"><i class="icon-heart-empty"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-wrap-plr-1">
                            <div class="product-wrap">
                                <div class="product-img product-img-zoom mb-25">
                                    <a href="product-details.html">
                                        <img src="assets/images/product/product-154.jpg" alt="">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h4><a href="product-details.html">Product Title</a></h4>
                                    <div class="product-price">
                                        <span>$ 124</span>
                                        <span class="old-price">$ 130</span>
                                    </div>
                                </div>
                                <div class="product-action-position-1 text-center">
                                    <div class="product-content">
                                        <h4><a href="product-details.html">Product Title</a></h4>
                                        <div class="product-price">
                                            <span>$ 124</span>
                                            <span class="old-price">$ 130</span>
                                        </div>
                                    </div>
                                    <div class="product-action-wrap">
                                        <div class="product-action-cart">
                                            <button title="Add to Cart">Add to cart</button>
                                        </div>
                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                class="icon-zoom"></i></button>
                                        <button title="Add to Compare"><i class="icon-compare"></i></button>
                                        <button title="Add to Wishlist"><i class="icon-heart-empty"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-wrap-plr-1">
                            <div class="product-wrap">
                                <div class="product-img product-img-zoom mb-25">
                                    <a href="product-details.html">
                                        <img src="assets/images/product/product-152.jpg" alt="">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h4><a href="product-details.html">Product Title</a></h4>
                                    <div class="product-price">
                                        <span>$ 124</span>
                                        <span class="old-price">$ 130</span>
                                    </div>
                                </div>
                                <div class="product-action-position-1 text-center">
                                    <div class="product-content">
                                        <h4><a href="product-details.html">Product Title</a></h4>
                                        <div class="product-price">
                                            <span>$ 124</span>
                                            <span class="old-price">$ 130</span>
                                        </div>
                                    </div>
                                    <div class="product-action-wrap">
                                        <div class="product-action-cart">
                                            <button title="Add to Cart">Add to cart</button>
                                        </div>
                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                                class="icon-zoom"></i></button>
                                        <button title="Add to Compare"><i class="icon-compare"></i></button>
                                        <button title="Add to Wishlist"><i class="icon-heart-empty"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            @endif
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
    $('.product-details-tab-small').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        dots: false,
        loop: true,
        fade: false,
        arrows: false,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            }
        ]
    });
    $('.product-slider-active-4').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        loop: true,
        dots: false,
        arrows: false,
        responsive: [{
                breakpoint: 1500,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    autoplay: false,
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 575,
                settings: {
                    autoplay: false,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
    });

</script>
@endpush
