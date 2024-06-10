<?php

namespace App\Base;

use App\Models\Currency;

interface StorefrontSessionInterface
{
    /**
     * Return the session key for carts.
     *
     * @return string
     */
    public function getSessionKey(): string;

    /**
     * Set the cart session channel.
     *
     * @param  string  $channel
     * @return self
     */
    public function setChannel(string $channel): self;

    /**
     * Set the cart session currency.
     *
     * @param  \App\Models\Currency  $currency
     * @return self
     */
    public function setCurrency(Currency $currency): self;

    /**
     * Return the current currency.
     *
     * @return \App\Models\Currency
     */
    public function getCurrency(): Currency;

    /**
     * Return the current channel.
     *
     * @return string
     */
    public function getChannel(): string;
}
