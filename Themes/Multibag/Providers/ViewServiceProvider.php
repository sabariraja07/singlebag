<?php

namespace Themes\Multibag\Providers;

use App\Models\Post;
use App\View\Composers\PostComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Themes\Multibag\ViewComposers\LayoutComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('multibag::layouts.header', LayoutComposer::class);
        View::composer('multibag::index', LayoutComposer::class);
    }
}