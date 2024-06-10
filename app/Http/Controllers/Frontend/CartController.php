<?php

namespace App\Http\Controllers\Frontend;

use Cart;
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Darryldecode\Cart\CartCondition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function cart()
    {
        return response()->json(get_cart());
    }

    public function add_to_cart(Request $request)
    {
        $id = $request->id;
        $option = $request->option ?? [];
        $option = collect($option)->flatten()->sort()->values()->all();
        $product = Product::byShop()->isActive()->with('price')->where('id', $id);
        
        $product = $product->with('variants', function ($q) use ($option) {
            return $q->whereIn('id', $option);
        });
        // $variation = $request->variation;
        // if ($request->variation) {
        //     $product = $product->with('attributes', function ($q) use ($variation) {
        //         if (count($variation) > 0) {
        //             return $q->whereIn('id', $variation);
        //         } else {
        //             return $q;
        //         }
        //     });
        // }
        $product = $product->first();
        $rowId = md5($product->id.serialize($option));
        $cart = \Cart::session(getCartSessionKey());
        $cartItem = $cart->get($rowId);

        $cqty = $request->qty ?? 1;
        if(isset($cartItem)){
            $cqty += $cartItem->quantity;
        }

        if(empty($product)){
            return response()->json(['message' => trans('Sorry, product not found.')], 422);
        }

        if(!$product->isStockAvailable($cqty)){
            return response()->json(['message' => $request->qty == 1 ? trans('Sorry, product is out of stock.') : trans('Sorry, product stock limit is exceeded.')], 422);
        }
        
        if (isset($product)) {
            $price = $product->price->selling_price;
            $new_price = $price;
            if ($request->option != null) {
                foreach ($product->variants ?? [] as $row) {
                    if ($row->amount_type == 1) {
                        $new_price += $row->price;
                    } else {
                        $new_price += ($price * $row->price * 0.01);
                    }
                }
                $options = $product->variants;
            } else {
                $options = [];
            }
            // if ($request->variation != null) {
            //     $attributes = $product->attributes ?? [];
            // } else {
            //     $attributes = [];
            // }
            $qty = $request->qty ?? 1;

            $price = round($new_price, 2);

            $data = [
                'product_id' => $product->id, 
                // 'attribute' => $attributes, 
                'options' => $options, 
                'image' => $product->image ?? asset('uploads/default.png'),
            ];
            // Cart::add($product->id, $product->title, $qty, $price, 0, $data);
            
            $cart->add(array(
                'id' => $rowId,
                'name' => $product->title,
                'price' => $price,
                'quantity' => $qty,
                'attributes' => $data,
                'associatedModel' => $product
            ));

            if($product->tax > 0) {
                $saleCondition = new CartCondition(array(
                    'name' => 'tax',
                    'type' => 'tax',
                    'value' => $product->tax_type == 0 ? $product->tax .'%' : $product->tax,
                ));

                $cart->addItemCondition($rowId, $saleCondition);
            }
        }
        // $this->update_coupon();

        return $this->cart();
    }

    public function add_to_wishlist(Request $request, $id)
    {
        $id = request()->route()->parameter('id');
        $shop_id = domain_info('shop_id');
        $option = $request->option;
        $product = Product::byShop()->isActive()->with('price')->where('id', $id);
        if ($request->option != null) {
            $product = $product->with('options', function ($q) use ($option) {
                if (count($option) > 0) {
                    return $q->whereIn('id', $option);
                } else {
                    return $q;
                }
            });
        }
        $variation = $request->variation;
        if ($request->variation) {
            $product = $product->with('attributes', function ($q) use ($variation) {
                if (count($variation) > 0) {
                    return $q->whereIn('id', $variation);
                } else {
                    return $q;
                }
            });
        }
        $product = $product->first();
        if (!empty($product)) {
            $price = $product->price->price;
            if ($request->option != null) {
                foreach ($product->options ?? [] as $row) {
                    if ($row->amount_type == 1) {
                        $price = $price + $row->price;
                    } else {
                        $percent = $price * $row->price * 0.01;
                        $price = $price + $percent;
                    }
                }
                $options = $product->options;
            } else {
                $options = [];
            }

            if ($request->variation != null) {
                $attributes = $product->attributes ?? [];
            } else {
                $attributes = [];
            }
            $qty = $request->qty ?? 1;

            $price = $price * $qty;

            $data = [
                'product_id' => $product->id, 
                'attribute' => $attributes, 
                'options' => $options, 
                'image' => $product->image ?? asset('uploads/default.png'),
            ];
            app('wishlist')->add($product->id, $product->title, $price, $qty, $data);
        }
        return response()->json(['data' => app('wishlist')->getContent(), 'message' => trans('Product added to your wishlist.')]);
    }

    public function wishlist_remove()
    {
        $id = request()->route()->parameter('id');
        app('wishlist')->remove($id);
        // if($request->ajax()){
        //     return app('wishlist')->getContent();
        // }
        return response()->json(['data' => app('wishlist')->getContent(), 'message' => trans('Product removed from your wishlist.')]);
    }

    public function cart_clear()
    {
        cart_clear();
        return back();
    }

    public function remove_cart(Request $request)
    {
        $cart = \Cart::session(getCartSessionKey());
        $cart->remove($request->id);
        // $this->update_coupon();
        return $this->cart();
    }

    public function cart_remove($id)
    {
        $id = request()->route()->parameter('id');
        $cart = \Cart::session(getCartSessionKey());
        $cart->remove($id);
        $this->update_coupon();
        if(request()->ajax()){
            return response()->json(['message'=> trans('Cart cleared')]);
        }
    
        return back();
    }

    public function apply_coupon(Request $request)
    {
        $coupon = Coupon::byShop()->where('code', $request->code)->first();

        if (empty($coupon)) {
            return response()->json(['message' => trans('Coupon Code Not Found.')], 400);
        }

        $coupon = Coupon::byShop()->isActive()->where('code', $request->code)->first();
        $mydate = Carbon::now()->toDateString();

        if(!empty($coupon)){
            if ($coupon->expiry_date >= $mydate) {
                $cart = \Cart::session(getCartSessionKey());
                if(Session::has('coupon')) {
                    $cart->removeCartCondition(Session::get('coupon'));
                    Session::forget($coupon->code);
                }
                $condition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => $coupon->code,
                    'type' => 'coupon',
                    'target' => 'subtotal',
                    'value' => '-'. $coupon->amount .'%',// . ($coupon->discount_type == 0 ? $coupon->amount .'%' : $coupon->amount),
                ));
                
                $cart->condition($condition);
    
                Session::put('coupon', $coupon->code);
    
                return response()->json(['message' => trans('Coupon Applied')]);
            }
        }
        else{
            return response()->json(['message' => trans('Coupon is Inactive')],400);
        }
        

        return response()->json(['message' => trans('Sorry, this coupon is expired')], 400);
    }

    public function update_coupon()
    {
        $cart = \Cart::session(getCartSessionKey());
        if(Session::has('coupon') && $cart->getTotalQuantity() != 0){
            $coupon = Coupon::byShop()->isActive()->where('code', Session::get('coupon'))->first();
            if (!empty($coupon)) {
                $mydate = Carbon::now()->toDateString();
                if ($coupon->expiry_date >= $mydate) {
                    $condition = new \Darryldecode\Cart\CartCondition(array(
                        'name' => $coupon->code,
                        'type' => 'coupon',
                        'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                        'value' => '-' . $coupon->discount_type == 0 ? $coupon->amount .'%' : $coupon->amount,
                    ));
    
                    $cart->condition($condition);
                }
            }
        }else if($cart->getTotalQuantity() == 0){
            cart_clear();
        }
    }

    public function express(Request $request)
    {
        $id = $request->id;
        $option = $request->option ?? [];
        $product = Product::byShop()->isActive()->with('price')->where('id', $id);

        
        
        if ($request->option != null) {
            $product = $product->with('options', function ($q) use ($option) {
                if (count($option) > 0) {
                    return $q->whereIn('id', $option);
                } else {
                    return $q;
                }
            });
        }
        if ($request->variation != null) {

            $variation = [];
            foreach ($request->variation as $key => $row) {
                array_push($variation, $row);
            }


            $product = $product->with('attributes', function ($q) use ($variation) {
                if (count($variation) > 0) {
                    return $q->whereIn('variation_id', $variation);
                } else {
                    return $q;
                }
            });
        }
        $product = $product->first();

        // $qty = $request->qty ?? 1;

        // if(!$product->isStockAvailable($qty)){
        //     return response()->json(['message' => $qty == 1 ? trans('Sorry, product is out of stock.') : trans('Sorry, product stock limit is exceeded.')], 422);
        // }

        if (!empty($product)) {
            $price = $product->price->price;

            if ($request->option != null) {
                foreach ($product->options ?? [] as $row) {
                    if ($row->amount_type == 1) {
                        $price = $price + $row->price;
                    } else {
                        $percent = $price * $row->price * 0.01;
                        $price = $price + $percent;
                    }
                }
                $options = $product->options;
            } else {
                $options = [];
            }

            if ($request->variation != null) {
                $attributes = $product->attributes ?? [];
            } else {
                $attributes = [];
            }


            $price = $price;
            $cart = \Cart::session(getCartSessionKey());
            $cart->add(
                $product->id, 
                $product->title, 
                $request->qty, 
                $price, 
                0, 
                ['attribute' => $attributes, 'options' => $options, 'image' => $product->image ?? asset('uploads/default.png')]
            );
        }


        return redirect('/checkout');
    }
}
