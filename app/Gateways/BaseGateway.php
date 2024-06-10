<?php

namespace App\Gateways;

use Illuminate\Http\Request;

abstract class BaseGateway
{
    protected $gateway;
    protected $type;
    public    $config;

    public function __construct($gateway, $type = null)
    {
        $this->gateway = $gateway;
        $this->type = $type;
    }

    public function isActive()
    {
        return $this->config;
    }

    public function process(Request $request, $order)
    {
    }

    public function cancelPayment(Request $request)
    {
    }

    public function confirmPayment(Request $request)
    {
    }

    public function getConfig()
    {
        return [];
    }

    public function view()
    {
    }

    public function getReturnUrl()
    {
        return url('/payment/payment-success');
    }

    public function getCancelUrl()
    {
        return url('/payment/payment-fail');
    }
}
