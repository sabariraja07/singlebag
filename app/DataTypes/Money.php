<?php

namespace App\DataTypes;

use NumberFormatter;
use JsonSerializable;
use App\Models\Currency;
use InvalidArgumentException;
use App\Exceptions\InvalidDataTypeValueException;

class Money implements JsonSerializable
{
    private $amount;
    private $currency;

    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function inDefaultCurrency($amount)
    {
        return new self($amount, default_currency());
    }

    public static function inCurrentCurrency($amount)
    {
        return new self($amount, default_currency());
    }

    private function newInstance($amount)
    {
        return new self($amount, $this->currency);
    }

    public function amount()
    {
        return $this->amount;
    }

    public function subunit()
    {
        $fraction = 10 ** Currency::subunit($this->currency);

        return (int) round($this->amount * $fraction);
    }

    public function default_currency()
    {
        return $this->currency;
    }

    public function isZero()
    {
        return $this->amount == 0;
    }

    public function add($addend)
    {
        $addend = $this->convertToSameCurrency($addend);

        return $this->newInstance($this->amount + $addend->amount);
    }

    public function subtract($subtrahend)
    {
        $subtrahend = $this->convertToSameCurrency($subtrahend);

        return $this->newInstance($this->amount - $subtrahend->amount);
    }

    public function multiply($multiplier)
    {
        return $this->newInstance($this->amount * $multiplier);
    }

    public function divide($divisor)
    {
        return $this->newInstance($this->amount / $divisor);
    }

    public function lessThan($other)
    {
        return $this->amount < $other->amount;
    }

    public function lessThanOrEqual($other)
    {
        return $this->amount <= $other->amount;
    }

    public function greaterThan($other)
    {
        return $this->amount > $other->amount;
    }

    public function greaterThanOrEqual($other)
    {
        return $this->amount >= $other->amount;
    }

    private function convertToSameCurrency($other)
    {
        if ($this->isNotSameCurrency($other)) {
            $other = $other->convertToDefaultCurrency();
        }

        $this->assertSameCurrency($other);

        return $other;
    }

    public function isSameCurrency($other)
    {
        return $this->currency === $other->currency;
    }

    public function isNotSameCurrency($other)
    {
        return !$this->isSameCurrency($other);
    }

    private function assertSameCurrency($other)
    {
        if ($this->isNotSameCurrency($other)) {
            throw new InvalidArgumentException('Mismatch money currency.');
        }
    }

    public function convertToDefaultCurrency()
    {
        $exchangeRate = Currency::for($this->currency);

        if (is_null($exchangeRate)) {
            throw new InvalidArgumentException('Cannot convert the money to the default currency.');
        }

        return new self($this->amount / $exchangeRate, default_currency());
    }

    public function convertToCurrentCurrency($exchangeRate = null)
    {
        return $this->convert(default_currency(), $exchangeRate);
    }

    public function convert($currency, $exchangeRate = null)
    {
        $exchangeRate = $exchangeRate ?: Currency::for($currency);

        if (is_null($exchangeRate)) {
            throw new InvalidArgumentException("Cannot convert the money to currency [$currency].");
        }

        return new self($this->amount * $exchangeRate, $currency);
    }

    public function round($precision = null, $mode = null)
    {
        if (is_null($precision)) {
            $precision = Currency::subunit($this->currency);
        }

        $amount = round($this->amount, $precision, $mode);

        return $this->newInstance($amount);
    }

    public function ceil()
    {
        return $this->newInstance(ceil($this->amount));
    }

    public function floor()
    {
        return $this->newInstance(floor($this->amount));
    }

    public function format($currency = null, $locale = null)
    {
        $currency = $currency ?: default_currency();
        $locale = $locale ?: locale();

        $numberFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        $amount = $numberFormatter->formatCurrency($this->amount, $currency);

        if (default_currency() === 'HUF') {
            $amount = str_replace(',00', '', $amount);
        }

        return $amount;
    }

    public function toArray()
    {
        return [
            'amount' => $this->amount,
            'formatted' => $this->format(),
            'currency' => $this->currency,
        ];
    }

    public function jsonSerialize()
    {
        return array_merge($this->toArray(), [
            'inCurrentCurrency' => $this->convertToCurrentCurrency()->toArray(),
        ]);
    }

    public function __toString()
    {
        return (string) $this->amount;
    }
}
