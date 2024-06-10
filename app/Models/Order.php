<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\Mail\LowInventoryMail;
use Illuminate\Support\Facades\Mail;

class Order extends Model
{
    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($order) {
            $order->order_no = $order->generateOrderNo();
            $order->save();
        });
    }

    private function generateOrderNo()
    {
        $orderNo = static::where('shop_id', $this->shop_id)->latest('id')->skip(1)->value('order_no');
        $prefix = ShopOption::where('shop_id', $this->shop_id)->where('key', 'order_prefix')->first();
        $prefix = $prefix->value ?? "#ORD";
        $orderNo = preg_replace('/[^0-9]/', '', $orderNo);
        $orderNo = $orderNo ? $orderNo : '0';
        if (isset($orderNo[-1]) && is_numeric($orderNo[-1])) {
            $orderNo = preg_replace_callback('/(\d+)$/', function ($mathces) {
                return str_pad($mathces[1] + 1, 6, '0', STR_PAD_LEFT);;
            }, $orderNo);
        }
        return $prefix . $orderNo;
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function gateway()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function PaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'slug');
    }

    public function order_item()
    {
        return $this->hasMany(OrderItem::class)->with('term');
    }

    public function scopeShopFinder($query)
    {
        if (current_shop_type() == 'reseller') {
            return $query->whereHas('group_order', function ($q) {
                $q->where('shop_id', current_shop_id());
            });
        } else {
            return $query->where('shop_id', current_shop_id());
        }
    }

    public function group_order()
    {
        return $this->belongsTo(Order::class, 'group_order_id');
    }

    public function order_item_with_file()
    {
        return $this->hasMany(OrderItem::class)->with('term', 'file');
    }

    public function order_item_with_stock()
    {
        return $this->hasMany(OrderItem::class)->with('stock', 'order_stock');
    }

    public function files()
    {
        return $this->hasMany(OrderItem::class)->with('file');
    }

    public function order_content()
    {
        return $this->hasOne(OrderMeta::class)->where('key', 'content');
    }

    public function shipping_info()
    {
        return $this->hasOne(OrderShipping::class)->with('city', 'method');
    }

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id')->with('method');
    }

    public function scopeDateBetween($q, $start, $end)
    {
        return $q->where(function ($q) use ($start, $end) {
            $q->whereDate('created_at', '<=', $end)->whereDate('created_at', '>=', $start);
        });
    }

    public function update_stock()
    {
        $meta = OrderMeta::where('order_id', $this->id)->where('key', 'stock')->first();
        if (!empty($meta)) {

            $stocks =  json_decode($meta->value, true);
            foreach ($stocks as $key => $stock) {
                if ($stock['stock_reduced'] == 1 && $this->status == 'canceled') {
                    $item = Stock::where('stock_manage', 1)->where('product_id', $stock['product_id'])->first();
                    if (isset($item)) {
                        $item->stock_qty = $item->stock_qty + $stock['stock_qty'];
                        if ($item->stock_qty == 0) {
                            $item->stock_status = 0;
                        } else {
                            $item->stock_status = 1;
                        }
                        $item->save();
                    }
                    $stocks[$key]['stock_reduced'] = 0;
                } else if ($stock['stock_reduced'] == 0 && in_array($this->status, ['pending', 'processing', 'ready-for-pickup', 'picked_up', 'delivered', 'completed'])) {
                    $item = Stock::where('stock_manage', 1)->where('product_id', $stock['product_id'])->first();
                    if (isset($item) && $item->stock_qty >= $stock['stock_qty']) {
                        $item->stock_qty = $item->stock_qty - $stock['stock_qty'];
                        if ($item->stock_qty == 0) {
                            $item->stock_status = 0;
                        } else {
                            $item->stock_status = 1;
                        }
                        $item->save();
                    } else {
                        return false;
                    }
                    $stocks[$key]['stock_reduced'] = 1;
                }
            }

            $meta->value = json_encode($stocks);
            $meta->save();
        }
        return true;
    }

    public function revert_stock()
    {
        $meta = OrderMeta::where('order_id', $this->id)->where('key', 'stock')->first();
        if (!empty($meta)) {

            $stocks =  json_decode($meta->value, true);
            foreach ($stocks as $stock) {
                $item = Stock::where('stock_manage', 1)->where('product_id', $stock['product_id'])->first();
                if (isset($item)) {
                    $item->stock_qty = $item->stock_qty + $stock['stock_qty'];
                    $item->save();
                }
            }

            $meta->value = json_encode(['product_id' => $item->product_id, 'stock_qty' => $stock['stock_qty'], 'flag' => false]);
            $meta->save();
        } else {

            $stocks = OrderItem::where('order_id', $this->id)->first();
            $item = Stock::where('stock_manage', 1)->where('product_id', $stocks['product_id'])->first();
            if (isset($item)) {
                $item->stock_qty = $item->stock_qty + $stocks['qty'];
                $item->save();
            }
        }
    }

    public function reduce_stock()
    {
        $items = $this->order_item()->get();
        $stocks = [];
        foreach ($items as $item) {
            $stock = Stock::where('stock_manage', 1)->where('product_id', $item->product_id)->first();
            if (isset($stock) && $stock->stock_qty >= $item->qty) {
                $stock->stock_qty = $stock->stock_qty - $item->qty;
                if ($stock->stock_qty == 0) {
                    $stock->stock_status = 0;
                } else {
                    $stock->stock_status = 1;
                }
                $stock->save();
                if ($stock->stock_qty <= 5) {
                    $data['stock'] = $stock;
                    $product = Product::where('id', $stock->product_id)->first();
                    $shop_id = $product->shop_id;
                    $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
                    $shop_name = Shop::where('id', $shop_id)->first();
                    $data['product'] = $product;
                    $data['store_email'] = $store_email->value ?? '';
                    $data['full_domain'] = $shop_name->domain->full_domain;
                    $data['shop_name'] = $shop_name->name ?? '';
                    Mail::to($data['store_email'])->send(new LowInventoryMail($data));
                }
                $stocks[] = ['product_id' => $item->product_id, 'stock_qty' => $item->qty, 'stock_reduced' => 1];
            }
            if (empty($stock)) {
                $stock = Stock::where('stock_manage', 0)->where('product_id', $item->product_id)->first();
            }
        }

        $meta = new OrderMeta();
        $meta->order_id = $this->id;
        $meta->key = 'stock';
        $meta->value = json_encode($stocks);
        $meta->save();
    }

    public function agent_details()
    {
        return $this->belongsTo(Customer::class, 'agent_id', 'id');
    }

    public function agent_avathar_details()
    {
        return $this->belongsTo(DeliveryAgent::class, 'agent_id', 'customer_id');
    }

    public function scopeAgentCurrentOrders($q)
    {
        return $q->whereIn('status', ['ready-for-pickup', 'picked_up']);
    }
    public function order_stock_meta()
    {
        return $this->hasOne(OrderMeta::class)->where('key', 'stock');
    }

    public function Shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function order_settlement()
    {
        return $this->hasOne(OrderSettlement::class, 'order_id');
    }

    public function order_settlements()
    {
        return $this->hasMany(OrderSettlement::class, 'order_id');
    }

    public function supplier_settlement()
    {
        return $this->hasOne(OrderSettlement::class, 'order_id')->where('type', 0);
    }

    public function reseller_settlement()
    {
        return $this->hasOne(OrderSettlement::class, 'order_id')->where('type', 1);
    }

    public function update_settlement()
    {
        try {
            $items = OrderItem::where('order_id', $this->id)->get();
            $amount = 0;
            $reseller_price = 0;

            foreach ($items as $item) {
                $info = json_decode($item->info, true);
                $amount += isset($info['supplier']) ? $info['supplier']['subTotal'] : 0;
                $reseller_price += $info['reseller_price'] ?? 0;
            }

            OrderSettlement::updateOrCreate([
                'shop_id' => $this->shop_id,
                'order_id' => $this->id
            ], [
                'group_order_id' => $this->group_order_id,
                'settlement_id' => null,
                'amount' => $amount + $this->shipping,
                'total' => $amount + $this->shipping + $this->tax,
                'commission' => 0,
                'status' => 2
            ]);

            $reseller_order = Order::where('id', $this->group_order_id)->first();

            if (isset($reseller_order)) {
                OrderSettlement::updateOrCreate([
                    'shop_id' => $reseller_order->shop_id,
                    'order_id' => $this->id,
                    'group_order_id' => $this->group_order_id
                ], [
                    'settlement_id' => null,
                    'amount' => $reseller_price,
                    'total' => $reseller_price,
                    'commission' => 0,
                    'type' => 1,
                    'status' => 2
                ]);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * Return the shipping address relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id')->whereType('shipping');
    }
}
