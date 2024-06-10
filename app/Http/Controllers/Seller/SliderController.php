<?php

namespace App\Http\Controllers\Seller;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $posts = Slider::where('shop_id', current_shop_id())->latest()->paginate(30);

        return view('seller.store.sliders', compact('posts'));
    }

    public function create()
    {
        return view('seller.store.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        // [
        //     'title.max' => 'Title field must not exceed 20 characters',
        // ]);
        $posts_count = Slider::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('product_limit', $posts_count)) {
            Session::flash('error', trans('Maximum posts limit exceeded'));
            $error['errors']['error'] = trans('Maximum posts limit exceeded');
            return response()->json($error, 401);
        }

        if ($request->hasFile('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size))) {
                Session::flash('error', trans('Maximum storage limit exceeded'));
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }
        }

        $request->validate([
            'url' => 'required|max:50',
            'description' => 'max:100',
            'title' => 'max:100',
            'btn_text' => 'max:100',
            'file' => 'required|max:1000|image',
            'text_color' => 'required',
        ]);

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->url = $request->url;
        $slider->btn_text = $request->btn_text;
        $slider->description = $request->description;
        $slider->position = $request->position;
        $slider->shop_id = current_shop_id();
        $slider->user_id = auth()->id();
        $slider->status = $request->status ?? 1;
        $slider->text_color = $request->text_color;
        $slider->save();

        if ($request->hasFile('file')) {
            $slider->deleteFile('image');
            $slider->uploadFile($request->file, $slider->shop_id  . '/offer/'. $slider->id . '/image', 'image');
        }


        return response()->json([trans('Slider Created')]);
        // Session::flash('success', trans('Slider Created !!'));
        // return redirect('seller/setting/slider');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slider = Slider::where('shop_id', current_shop_id())->findorFail($id);
        if (file_exists($slider->name)) {
            unlink($slider->name);
        }
        $slider->deleteFile('image');
        $slider->delete();

        // return back();
        Session::flash('success', trans('Slider Deleted !!'));
        return redirect('seller/setting/slider');
    }

    #####  slider edit function #####
    public function edit($id)
    {
        $info = Slider::where('shop_id', current_shop_id())->findOrFail($id);
        return view('seller.store.edit', compact('info'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);
        // [
        //     'title.max' => 'Title field must not exceed 20 characters',
        // ]);
        
        $slider = Slider::where('shop_id', current_shop_id())->findOrFail($id);
        $slider->title = $request->title;
        $slider->url = $request->url;
        $slider->btn_text = $request->btn_text;
        $slider->description = $request->description;
        $slider->position = $request->position;
        $slider->shop_id = current_shop_id();
        $slider->status = $request->status ?? 1;
        $slider->text_color = $request->text_color;
        $slider->save();

        if ($request->hasFile('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size - $slider->getFileSize('image')))) {
                Session::flash('error', trans('Maximum storage limit exceeded'));
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }
            $slider->deleteFile('image');
            $slider->uploadFile($request->file, $slider->shop_id  . '/offer/'. $slider->id . '/image', 'image');
        }

        return response()->json([trans('Slider Updated')]);
        // Session::flash('success', trans('Slider Updated !!'));
        // return redirect('seller/setting/slider');

    }
}
