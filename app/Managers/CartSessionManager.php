<?php

namespace App\Managers;

use App\Models\Cart;
use App\Models\Channel;
use App\Models\Currency;
use Illuminate\Auth\AuthManager;
// use App\Facades\ShippingManifest;
use App\Base\CartSessionInterface;
use Illuminate\Session\SessionManager;
use Illuminate\Contracts\Auth\Authenticatable;

class CartSessionManager implements CartSessionInterface
{
    public function __construct(
        protected SessionManager $sessionManager,
        protected AuthManager $authManager,
        protected $channel = null,
        protected $currency = null,
        public $cart = null
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->fetchOrCreate(true);
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $this->sessionManager->forget(
            $this->getSessionKey()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function manager()
    {
        if (!$this->cart) {
            $this->fetchOrCreate(create: true);
        }

        return $this->cart;
    }

    /**
     * {@inheritDoc}
     */
    public function associate(Cart $cart, Authenticatable $user, $policy)
    {
        $this->use(
            $cart->associate($user, $policy)
        );
    }

    /**
     * Set the cart to be used for the session.
     *
     * @return \App\Models\Cart
     */
    public function use(Cart $cart)
    {
        $this->sessionManager->put(
            $this->getSessionKey(),
            $cart->id
        );

        return $this->cart = $cart;
    }

    /**
     * Fetches a cart and optionally creates one if it doesn't exist.
     *
     * @param  bool  $create
     * @return \App\Models\Cart|null
     */
    private function fetchOrCreate($create = false)
    {
        $cartId = $this->sessionManager->get(
            $this->getSessionKey()
        );

        if (!$cartId) {
            return $create ? $this->cart = $this->createNewCart() : null;
        }
        $this->cart = Cart::with('currency')->whereShopId(current_shop_id())->find($cartId);

        if (auth()->check()) {
            $this->cart = Cart::with('currency')->whereShopId(current_shop_id())
                ->where('user_type', get_class(auth()->user()))
                ->where('user_id', auth()->id())->find($cartId);
        } else {
            $this->cart = Cart::with('currency')->whereShopId(current_shop_id())->find($cartId);
        }

        if (!$this->cart) {
            if (!$create) {
                return null;
            }

            return $this->createNewCart();
        }

        return $this->cart->calculate();
    }

    /**
     * Get the cart session key.
     */
    public function getSessionKey()
    {
        return 'singlebag';
    }

    /**
     * Set the current channel.
     *
     * @return void
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;

        if ($this->current() && $this->current()->channel_id != $channel->id) {
            $this->cart->update([
                'channel_id' => $channel->id,
            ]);
        }
    }

    /**
     * Set the current currency.
     *
     * @return void
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;

        if ($this->current() && $this->current()->currency_code != $currency->code) {
            $this->cart->update([
                'currency_code' => $currency->id,
            ]);
        }
    }

    /**
     * Return the current currency.
     */
    public function getCurrency(): Currency
    {
        return $this->currency ?: Currency::getDefault();
    }

    /**
     * Return the current channel.
     */
    public function getChannel(): Channel
    {
        return $this->channel ?: Channel::getDefault();
    }

    /**
     * Return available shipping options for the current cart.
     *
     * @return \Illuminate\Support\Collection
     */
    // public function getShippingOptions()
    // {
    //     return ShippingManifest::getOptions(
    //         $this->current()
    //     );
    // }

    /**
     * Create an order from a cart instance.
     *
     * @param  bool  $forget
     * @return \App\Models\Order
     */
    public function createOrder($forget = true)
    {
        if ($forget) {
            $this->forget();
        }

        return $this->manager()->createOrder();
    }

    /**
     * Create a new cart instance.
     *
     * @return \App\Models\Cart
     */
    protected function createNewCart()
    {
        $cart = Cart::create([
            'currency_code' => $this->getCurrency()->code,
            'channel_id' => $this->getChannel()->id,
            'user_type' => $this->authManager->user() ? get_class($this->authManager->user()) : null,
            'user_id' => $this->authManager->user()?->id,
            'shop_id' => current_shop_id(),
        ]);

        return $this->use($cart);
    }

    public function __call($method, $args)
    {
        if (!$this->cart) {
            $this->cart = $this->fetchOrCreate(true);
        }

        return $this->cart->{$method}(...$args);
    }
}
