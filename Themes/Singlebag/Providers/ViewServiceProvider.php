<?php

namespace Themes\Singlebag\Providers;

use App\Models\Post;
use App\View\Composers\PostComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Themes\Singlebag\ViewComposers\LayoutComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('singlebag::layouts.header', LayoutComposer::class);
        View::composer('singlebag::index', LayoutComposer::class);
    }
}