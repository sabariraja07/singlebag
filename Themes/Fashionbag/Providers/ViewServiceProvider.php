<?php

namespace Themes\Fashionbag\Providers;

use App\Models\Post;
use App\View\Composers\PostComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Themes\Fashionbag\ViewComposers\LayoutComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('fashionbag::layouts.header', LayoutComposer::class);
        View::composer('fashionbag::index', LayoutComposer::class);
    }
}