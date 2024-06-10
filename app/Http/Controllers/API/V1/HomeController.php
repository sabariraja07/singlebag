<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use ApiResponser;

    public function verify_store_name(Request $request)
    {
        $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->shop_name ?? "");
        $domain = str_replace("'", '', $domain);

        $shop = Shop::where('name', $domain)->first();
        if (isset($shop)) {
            $domain = env('APP_PROTOCOL') . Str::lower($shop->sub_domain) . '.' . env('APP_PROTOCOLESS_URL');
            $data = [];
            $data['id'] = $shop->id;
            $data['name'] = $shop->name;
            $data['logo'] = get_shop_logo_url($shop->id);
            $data['domain'] = $domain;
            return $this->success($data, 'Shop name is available.', 200);
        }

        return $this->error('Shop name is not available', 400);
    }
}
