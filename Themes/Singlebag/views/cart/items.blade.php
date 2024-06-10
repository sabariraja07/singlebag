<div class="table-responsive shopping-summery">
    <table class="table table-wishlist">
        <thead>
            <tr class="main-heading">
                <th class="custome-checkbox start pl-30">
                </th>
                <th class="col" colspan="2">{{ __('Product') }}</th>
                <th class="col">{{ __('Option') }}</th>
                <th class="col">{{ __('Price') }}</th>
                <th class="col">{{ __('Quantity') }}</th>
                <th class="col">{{ __('Total') }}</th>
                <th scope="col" class="end">{{ __('Remove') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom: 2px solid #edecec;" class="pt-30" v-for="product in cart.items"
                :key="product.id">
                <td class="custome-checkbox pl-30">
                </td>
                <td class="image product-thumbnail pt-40">
                    <img v-if="product.attributes" :src="product.attributes.image" alt="#">
                </td>
                <td class="product-des product-name">
                    <h6 class="mb-5">
                        <a class="product-name mb-10 text-heading">
                            @{{ product.name }}
                        </a>
                    </h6>
                </td>
                <td>
                    <p v-for="option in product.attributes.options" :key="option.id">@{{ option.name }}</p>
                </td>
                <td class="price" data-title="Price">
                    <h4 class="text-body">@{{ $formatPrice(product.price) }}</h4>
                </td>
                <td class="price">
                    <h4 class="text-body">@{{ product.quantity }}</h4>
                </td>
                <td class="price" data-title="Price">
                    <h4 class="text-brand">@{{ $formatPrice(product.total) }}</h4>
                </td>
                <td class="action text-center" data-title="Remove">
                    <a @click="remove(product)" class="text-body remove-cart-btn"><i
                            class="fi-rs-trash"></i></a>
                </td>
            </tr>
            <tr v-if="cart.items.length == 0">
                <td colspan="8" style="text-align: center;padding:30px;">
                    <h6 style="color:red;">{{ __('No Product found in your cart') }}</h6>
                </td>
            </tr>
        </tbody>
    </table>
</div>
