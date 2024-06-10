
<div class="cart-table-content">
    <div class="table-content table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="padding-left: 121px;">{{ __('Product') }}</th>
                    {{-- <th class="th-text-center">{{ __('Price') }}</th> --}}
                    <th class="th-text-center">{{ __('Quantity') }}</th>
                    <th class="th-text-center">{{ __('Total') }}</th>
                    <th class="th-text-center">{{ __('Remove') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in cart.items" :key="product.id">
                    <td class="cart-product" style="width: 580px !important;">
                        <div class="product-img-info-wrap">
                            <div class="product-img">
                                <a href="javascript::void(0)">
                                    <img v-if="product.attributes" :src="product.attributes.image" alt="">
                                </a>
                            </div>
                            <div class="product-info">
                                <h4><a href="javascript::void(0)"> @{{ product.name }}</a></h4>
                                <span v-for="( option, index ) in product.attributes.options" :key="option.id">@{{ option.name }} @{{ product.attributes.options.length != index + 1 ? ', ' : '' }}</span>
                                {{-- <span>Color : Black</span>
                                <span>Size : SL</span> --}}
                            </div>
                        </div>
                    </td>
                    {{-- <td class="product-price"><span class="amount">@{{ $formatPrice(product.priceTotal) }}</span></td> --}}
                    <td class="cart-quality">
                        {{-- <div class="pro-details-quality">
                            <div class="cart-plus-minus">
                                <div class="dec qtybutton">-</div>
                                <input class="cart-plus-minus-box plus-minus-width-inc" type="text"
                                    name="qtybutton" v-model="product.quantity">
                                <div class="inc qtybutton">+</div>
                            </div>
                        </div> --}}
                        <span>@{{product.quantity}}</span>
                    </td>
                    <td class="product-total"><span>@{{ $formatPrice(product.total) }}</span></td>
                    <td class="product-remove">
                        <a href="javascript::void(0)" @click="remove(product)">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="11.314" height="11.314" viewBox="0 0 11.314 11.314"
                                class="injected-svg inject-me">
                                <g id="Group_1415" data-name="Group 1415"
                                    transform="translate(-1251.843 -711.843)">
                                    <line id="Line_10" data-name="Line 10" x2="14"
                                        transform="translate(1252.55 712.55) rotate(45)" fill="none"
                                        stroke="#cbcbcb" stroke-width="2"></line>
                                    <line id="Line_11" data-name="Line 11" x2="14"
                                        transform="translate(1252.55 722.45) rotate(-45)" fill="none"
                                        stroke="#cbcbcb" stroke-width="2"></line>
                                </g>
                            </svg>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="cart-shiping-update-wrapper">
        <a href="{{ url('/shop') }}">{{ __('Continue Shopping') }}</a>
        {{-- <a href="javascript::void(0)">{{ __('Update Cart') }}</a> --}}
        <a href="javascript::void(0)" @click="clearCart">{{ __('Clear Cart') }}</a>
    </div>
</div>
