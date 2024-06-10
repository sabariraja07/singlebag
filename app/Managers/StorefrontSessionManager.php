<?php

namespace App\Managers;

use App\Models\Currency;
use Illuminate\Session\SessionManager;
use App\Base\StorefrontSessionInterface;

class StorefrontSessionManager implements StorefrontSessionInterface
{
    /**
     * The current currency
     *
     * @var Currency
     */
    protected ?Currency $currency = null;

    /**
     * Initialise the manager
     *
     * @param protected SessionManager
     */
    public function __construct(
        protected SessionManager $sessionManager
    ) {
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
    public function getSessionKey(): string
    {
        return 'storefront';
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrency(): Currency
    {
        return $this->currency ?: Currency::getDefault();
    }
}
