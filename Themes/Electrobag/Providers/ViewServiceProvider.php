<?php

namespace Themes\Electrobag\Providers;

use App\Models\Post;
use App\View\Composers\PostComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Themes\Electrobag\ViewComposers\LayoutComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('electrobag::layouts.header', LayoutComposer::class);
        View::composer('electrobag::index', LayoutComposer::class);
    }
}