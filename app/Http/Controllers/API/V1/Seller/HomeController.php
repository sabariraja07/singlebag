<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\City;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Channel;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\State;

class HomeController extends Controller
{
    use ApiResponser;

    public function channels(Request $request)
    {
        $channels = Channel::get();

        return $this->success($channels);
    }

    public function categories(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $skip = ($page - 1) * $limit;
        $categories = Category::where('shop_id', current_shop_id())->where('type', 'category')->where('status', '!=', 5);
        if ($request->term) {
            $categories = $categories->where('name', 'LIKE', '%' . $request->term . '%');
        }
        $categories = $categories->whereNull('p_id')->with('childrens')->limit($limit)->skip($skip)->get();

        return $this->success($categories);
    }

    public function brands(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $skip = ($page - 1) * $limit;
        $brands = Brand::where('shop_id', current_shop_id())->where('status', '!=', 5);
        if ($request->term) {
            $brands = $brands->where('name', 'LIKE', '%' . $request->term . '%');
        }
        $brands = $brands->latest()->get();

        return $this->success($brands);
    }

    public function attributes(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $skip = ($page - 1) * $limit;
        $attributes = Attribute::where('shop_id', current_shop_id())
            ->whereNull('parent_id')
            ->with('children')
            ->withCount('featured_with_product_count');

        if ($request->term) {
            $attributes = $attributes->where('name', 'LIKE', '%' . $request->term . '%');
        }
        $attributes = $attributes->limit($limit)->skip($skip)->get();

        return $this->success($attributes);
    }

    public function Cities(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $skip = ($page - 1) * $limit;
        $cities = City::where('status', 1);
        if ($request->term) {
            $cities = $cities->where('name', 'LIKE', '%' . $request->term . '%');
        }
        if ($request->state) {
            $cities = $cities->where('state_id', $request->state);
        }
        $cities = $cities->limit($limit)->skip($skip)->get();

        return $this->success($cities);
    }

    public function States(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $skip = ($page - 1) * $limit;
        $states = State::where('status', 1);
        if ($request->term) {
            $states = $states->where('name', 'LIKE', '%' . $request->term . '%');
        }
        if ($request->country) {
            $states = $states->where('country_id', $request->country);
        }
        $states = $states->limit($limit)->skip($skip)->get();

        return $this->success($states);
    }

    public function Countries(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $skip = ($page - 1) * $limit;
        $countries = Country::where('status', 1);
        if ($request->term) {
            $countries = $countries->where('name', 'LIKE', '%' . $request->term . '%');
        }
        $countries = $countries->limit($limit)->skip($skip)->get();

        return $this->success($countries);
    }

    public function Currencies(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 20;
        $skip = ($page - 1) * $limit;
        $countries = Currency::where('status', 1);
        if ($request->term) {
            $countries = $countries->where('name', 'LIKE', '%' . $request->term . '%')
                ->orWhere('code', 'LIKE', '%' . $request->term . '%');
        }
        $countries = $countries->limit($limit)->skip($skip)->get();

        return $this->success($countries);
    }
}
