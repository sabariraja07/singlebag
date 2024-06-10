<?php

namespace App\Payments;

use App\Models\Order;

abstract class AbstractGateway implements GatewayInterface
{
    /**
     * The instance of the order.
     *
     * @var \App\Models\Order
     */
    protected ?Order $order = null;

    /**
     * Any config for this payment provider.
     */
    protected array $config = [];


    /**
     * Data storage.
     */
    protected array $data = [];

    /**
     * {@inheritDoc}
     */
    public function order(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }
}
