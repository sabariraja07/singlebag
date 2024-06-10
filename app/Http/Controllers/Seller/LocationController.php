<?php

namespace App\Http\Controllers\Seller;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Location::where('shop_id', current_shop_id())->latest()->paginate(20);
        return view('seller.location.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop_id = current_shop_id();

        $count = Location::where('shop_id', $shop_id)->count();
        if (user_plan_limit('location_limit', $count)) {
            $msg = 'Maximum Location Exceeded Please Update Your Plan';
            Session::flash('error', trans('Maximum Location Exceeded Please Update Your Plan'));
            return back();
        }

        $request->validate([
            'title' => 'required|max:50',
        ]);

        $post = new Location;
        $post->name = $request->title;
        $post->user_id = Auth::id();
        $post->shop_id = $shop_id;
        $post->slug = Str::slug($request->title);
        $post->save();

        Session::flash('success', trans('Shipping Location Created Successfully'));
        return back();
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Location::where('shop_id', current_shop_id())->findorFail($id);
        return view('seller.location.edit', compact('info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:50',
        ]);

        $shop_id = current_shop_id();
        $post = Location::where('shop_id', $shop_id)->findorFail($id);
        $post->name = $request->title;
        $post->save();

        Session::flash('success', trans('Location Updated Successfully'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $auth_id = Auth::id();
        $shop_id = current_shop_id();
        if ($request->method == 'delete') {
            foreach ($request->ids as $key => $id) {
                $post = Location::where('shop_id', $shop_id)->findorFail($id);
                $post->delete();
            }
        }

        return response()->json([trans('Success')]);
    }
}
