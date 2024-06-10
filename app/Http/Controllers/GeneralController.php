<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function search_cities(Request $request)
    {
        $posts = DB::table('cities')
            ->where('name', 'LIKE', '%' . $request->q . '%')
            ->where('status', 1)
            ->paginate(10);


        $cities = [];


        $count_posts = DB::table('cities')
            ->where('name', 'LIKE', '%' . $request->q . '%')
            ->where('status', 1)
            ->get();

        $get_count =   count($count_posts);
        if (count(['$posts']) > 0) {

            foreach ($posts as $city) {
                $cities[] = array(
                    "id" => $city->id,
                    "text" => $city->name,
                );
            }
        }

        // return response()->json($cities, 200);
        return response()->json(['cities' => $cities, 'count' => $get_count], 200);
    }
}
