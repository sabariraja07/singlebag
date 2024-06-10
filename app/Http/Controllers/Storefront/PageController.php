<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Seo;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLdMulti;

class PageController extends Controller
{
    public function show(Request $request, $slug)
    {
        $page = Page::where()->where()->first();
        $id = request()->route()->parameter('id');
        $page = Page::where('shop_id', domain_info('shop_id'))->findorFail($id);
        $seo = $page->seo;
        JsonLdMulti::setDescription($page->excerpt->value ?? null);
        JsonLdMulti::addImage(get_shop_logo_url(domain_info('shop_id')));

        SEOMeta::setTitle($page->title ?? env('APP_NAME'));
        SEOMeta::setDescription($page->excerpt->value ?? null);

        SEOTools::setTitle($page->title ?? env('APP_NAME'));
        SEOTools::setDescription($page->excerpt->value ?? null);
        SEOTools::setCanonical(url('/'));
        SEOTools::opengraph()->addProperty('image', get_shop_logo_url(domain_info('shop_id')));
        SEOTools::twitter()->setTitle($page->title ?? env('APP_NAME'));
        SEOTools::twitter()->setSite($page->title ?? null);
        SEOTools::jsonLd()->addImage(get_shop_logo_url(domain_info('shop_id')));

        OpenGraph::setDescription($seo->description ?? $page->short_description ?? null);
        OpenGraph::setTitle($seo->title ?? $page->title);
        OpenGraph::addProperty('type', 'product');

        return view(base_view() . '::page', compact('page'));
    }
}
