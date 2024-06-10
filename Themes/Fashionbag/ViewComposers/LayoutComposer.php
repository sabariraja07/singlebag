<?php

namespace Themes\Fashionbag\ViewComposers;

use App\Http\Controllers\Frontend\FrontendController;

class LayoutComposer
{
    /**
     * @var \Modules\Compare\Compare
     */

    /**
     * Create a new view composer instance.
     *
     * @param \Modules\Compare\Compare $compare
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    public function compose($view)
    {
        $fontend = new FrontendController();
        $view->with([
            'menu_categories' => $fontend->get_menu_category(),
            'sliders' => $fontend->get_slider(),
            'categories' => $fontend->get_category(),
            'latest_products' => $fontend->get_latest_products(),
            'bump_adds' => $fontend->get_bump_adds(),
            'banner_adds' => $fontend->get_banner_adds(),
            'featured_categories' => $fontend->get_featured_category(),
            'featured_brands' => $fontend->get_featured_brand(),
            // 'category_with_products' => $fontend->get_category_with_product(),
            // 'brand_with_products' => $fontend->get_brand_with_product(),
            // 'random_products' => $fontend->get_random_products(),
            'offerable_products' => $fontend->get_offerable_products(),
            'trending_products' => $fontend->get_trending_products(),
            'best_selling_products' => $fontend->get_best_selling_product(),
            'top_rated_products' => $fontend->get_top_rated_products(),
            'brands' => $fontend->get_brand(),
        ]);
    }
}
