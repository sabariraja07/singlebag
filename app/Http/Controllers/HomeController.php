<?php

namespace App\Http\Controllers;

use App\Facades\StorefrontSession;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Product;
use App\Models\OldProductOption;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function permutate()
    {
        return StorefrontSession::getSessionKey();
        $num_str1 = "3";
        $num_str2 = "11.222";
        $res = bcmul($num_str1, 2);

        return (int) $res;
        $Variant = new OldProductOption();
        return  $Variant->getTable();
        $options = OldProductOption::where('product_id', 2126)->with('Variants')->where('type', 1)->get()->toArray();
        $Variants = OldProductOption::where('product_id', 2126)->where('type', 0)->get();
        return Arr::permutate(
            $Variants
                ->groupBy('p_id')
                ->mapWithKeys(function ($values) {
                    $optionId = $values->first()->p_id;

                    return [$optionId => $values->map(function ($value) {
                        return $value->id;
                    })];
                })->toArray()
            // ProductValueValue::findMany($this->optionValues)
            //     ->groupBy('product_option_id')
            //     ->mapWithKeys(function ($values) {
            //         $optionId = $values->first()->product_option_id;

            //         return [$optionId => $values->map(function ($value) {
            //             return $value->id;
            //         })];
            //     })->toArray()
        );
    }
}
