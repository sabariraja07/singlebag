<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Shop
{
    protected $config;
    protected $shop;
    protected $current;

    public function __construct($config)
    {
        $this->setConfig($config);
    }

    public function all()
    {
        return $this->shop;
    }

    public function default()
    {
        return $this->shop->first();
    }

    public function hasMultiple()
    {
        return $this->shop->count() > 1;
    }

    public function get($handle)
    {
        return $this->shop->get($handle);
    }

    public function findByUrl($url)
    {
        $url = Str::before($url, '?');
        $url = Str::ensureRight($url, '/');

        return collect($this->shop)->filter(function ($site) use ($url) {
            return Str::startsWith($url, Str::ensureRight($site->absoluteUrl(), '/'));
        })->sortByDesc->url()->first();
    }

    public function current()
    {
        return $this->current
            ?? $this->findByUrl(request()->getUri())
            ?? $this->default();
    }

    public function setCurrent($site)
    {
        $this->current = $this->get($site);
    }

    public function selected()
    {
        return $this->get(session('statamic.cp.selected-site', $this->default()->handle()));
    }

    public function setConfig($key, $value = null)
    {
        // If no value is provided, then the key must've been the entire config.
        // Otherwise, we should just replace the specific key in the config.
        if (is_null($value)) {
            $this->config = $key;
        } else {
            Arr::set($this->config, $key, $value);
        }

        $this->shop = $this->toArray($this->config['shop']);
    }

    protected function toArray($config)
    {
        return collect($config)->map(function ($site, $handle) {
            return new Site($handle, $site);
        });
    }
}
