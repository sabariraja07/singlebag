<div class="row my-2">
    <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
        <div class="d-flex align-items-center justify-content-center">
            @foreach ($gallery as $index => $row)
            @if($index == 0)
            <img src="{{ asset($row->url) }}" class="img-fluid product-img" alt="product image">
            @endif
            @endforeach
        </div>
    </div>
    <div class="col-12 col-md-7">
        <h4>{{ $product->title }}</h4>
        @if($product->brand)
        <span class="card-text item-company">By <a href="javascript::void(0)" class="company-name">{{ $product->brand->name }}</a></span>
        @endif
        <div class="ecommerce-details-price d-flex flex-wrap mt-1">
            <h4 class="item-price me-1">{{ $product->price->price }}</h4>
            <ul class="unstyled-list list-inline ps-1 border-start">
                @php $rating = $product->avg_rating ?? 0; @endphp  
                @foreach(range(1,5) as $i)
                    <li class="ratings-list-item">
                        @if($rating >0)
                            @if($rating >0.5)
                            <i class="ph-star-fill" style="color: #ff9f43;"></i>
                            @else
                            <i class="ph-star-half-fill" style="color: #ff9f43;"></i>
                            @endif
                        @else
                        <i class="ph-star-thin"></i>
                        @endif
                        @php $rating--; @endphp
                    </li>
                @endforeach
                <li class="ratings-list-item">({{ $product->avg_rating ?? 0 }})</li>
            </ul>
        </div>
        <p class="card-text">
            {{ __('Available') }}  - 
            @if (!$product->inStock())
            <span class="text-danger">{{ __('Out of stock') }}</span>
            @else
            <span class="text-success">{{ __('In Stock') }}</span>
            @endif
        </p>
        @if (!empty($product->stock->sku))
        @if ($product->stock->stock_manage == 1 || isset($product->stock->sku))
        <p class="card-text">
            {{ __('SKU') }} :
            <strong>{{ $product->stock->sku }}</strong>
        </p>
        @endif
        @endif
        <p class="card-text">{{ $product->short_description }}</p>
        <hr>
        @if (count($product->options) > 0)
            @foreach ($product->options as $key => $option)
                <div class="product-color-options">
                    <h6>
                        {{ $option->name }} 
                        ({{ $option->select_type == 1 ? __('Multiple Select') : __('Single Select')}})
                        @if ($option->is_required == 1)
                        <span class="text-danger">*</span>
                        @endif :
                    </h6>
                    <ul class="list-group list-group-circle text-start fw-bold">
                        @foreach ($option->variants as $row)
                            <li class="list-group-item">
                                {{ $row->name }} (+{{ $row->amount }}{{ $row->amount_type == 0 ? '%' : ''}})
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                </div>
            @endforeach
        @endif
        @if (count($product->categories) > 0)
        <div class="product-color-options">
            <h6>{{ __('Category') }} :</h6>
            <ul class="list-unstyled mb-0">
                @foreach ($product->categories as $row)
                    <li class="d-inline-block">
                        <div class="color-option b-info">{{ $row->name }}</div>
                    </li>
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            </ul>
        </div>
        <hr>
        @endif
        <div class="d-flex flex-column flex-sm-row pt-1">
            <a href="{{ route('seller.reseller.add_product', $product->id) }}" class="btn btn-primary btn-cart me-0 me-sm-1 mb-1 mb-sm-0 waves-effect waves-float waves-light">
                <i class="ph-plus-bold"></i>
                <span class="add-to-cart">{{ __('Add Products') }}</span>
            </a>
        </div>
    </div>
    @if(!empty($product->description))
    <div class="col-12">
        <hr>
        <div class="card-text">
            <h6>{{ __('Description') }}</h6>
            {!! $product->description !!}
        </div>
    </div>
    @endif
</div>