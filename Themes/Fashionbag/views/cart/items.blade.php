<table class="table table-bordered">
    <thead>
        <tr>
            <th class="pro-thumbnail">{{ __('Image') }}</th>
            <th class="pro-title">{{ __('Product') }}</th>
            {{-- <th class="th-text-center">{{ __('Price') }}</th> --}}
            <th class="pro-quantity">{{ __('Quantity') }}</th>
            <th class="pro-price">{{ __('Total') }}</th>
            <th class="pro-remove">{{ __('Remove') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="product in cart.items" :key="product.id">
            <td class="pro-thumbnail">
                <a href="javascript::void(0)">
                    <img class="img-fluid" v-if="product.attributes" style="max-width:80px;" :src="product.attributes.image" alt="" />
                </a>
            </td>
            <td class="pro-title">
                <a href="javascript::void(0)"> 
                    @{{ product.name }} <br> 
                    <span v-for="( option,index ) in product.attributes.options" :key="option.id">@{{ option.name }} @{{ product.attributes.options.length != index + 1 ? ', ' : '' }}</span>
                </a>
            </td>
            {{-- <td class="pro-price"><span>$95.00</span></td> --}}
            <td class="pro-quantity">
                @{{product.quantity}}
                {{-- <div class="quantity">
                    <div class="cart-plus-minus">
                        <input class="cart-plus-minus-box" value="0" type="text">
                        <div class="dec qtybutton">-</div>
                        <div class="inc qtybutton">+</div>
                        <div class="dec qtybutton"><i class="fa fa-minus"></i></div>
                        <div class="inc qtybutton"><i class="fa fa-plus"></i></div>
                    </div>
                </div> --}}
            </td>
            <td class="pro-subtotal"><span>@{{ $formatPrice(product.price) }}</span></td>
            <td class="pro-remove"><a href="javascript::void(0)" @click="remove(product)"><i class="pe-7s-trash"></i></a></td>
        </tr>
    </tbody>
</table>