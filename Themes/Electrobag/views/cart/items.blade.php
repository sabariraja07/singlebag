<table class="cart-table">
    <thead>
        <tr>
        <th></th>
        <th colspan="2" style="width: 95px;">{{ __('Product') }}</th>
        <th style="width: 150px;">{{ __('Option') }}</th>
        <th style="width: 260px;">{{ __('Price') }}</th>
        <th style="width: 70px;">{{ __('Quantity') }}</th>
        <th style="width: 150px;">{{ __('Total') }}</th>
        <th style="width: 70px;">{{ __('Remove') }}</th>
        <th></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="product in cart.items" :key="product.id">
            <td><a class="cart-entry-thumbnail" :href="'/product-view/' + product.id"><img v-if="product.attributes" :src="product.attributes.image" width="85" height="85" alt=""></a></td>
            <td colspan="2"><h6 class="h6"> 
                    <a class="cart-entry-thumbnail" :href="'/product-view/' + product.id">@{{ product.name }}</a>
                </h6>
            </td>
            <td data-title="option"><p v-for="option in product.attributes.options" :key="option.id">@{{ option.name }}</p></td>
            <td data-title="price"><h4 class="text-body">@{{ $formatPrice(product.price) }}</h4></td>
            <td data-title="Quantity">@{{ product.quantity }}</td>
            <td class="price" data-title="Price"><h4 class="text-brand">@{{ $formatPrice(product.total) }}</h4></td>
            <td data-title=""><a @click="remove(product)" class="text-body remove-cart-btn"><div class="button-close"></div></a></td>
            <td></td>
        </tr>
        <tr v-if="cart.items.length == 0">
            <td></td>
            <td colspan="7" style="text-align: center;padding:30px;">
                <h6 style="color:red;">{{ __('No Product found in your cart') }}</h6>
            </td>
            <td></td>
        </tr>
    </tbody>    
</table>
<div class="empty-space col-xs-b35"></div>
            
   




        
        

