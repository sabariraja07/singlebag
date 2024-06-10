<?php

namespace App\Http\Controllers\Seller;

use App\Models\File;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|max:255',
        ]);

        $product = Product::where('shop_id', current_shop_id())->findorFail($request->term);

        $file = new File();
        $file->product_id = $product->id;
        $file->url = $request->url;
        $file->save();

        return response()->json([trans('File Created Successfully')]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'url' => 'required|max:255',
        ]);

        $product = Product::where('shop_id', current_shop_id())->findorFail($request->term);
        $id = $request->id;
        $file = File::find($id);
        $file->product_id = $product->id;
        $file->url = $request->url;
        $file->save();

        return response()->json([trans('File Updated Successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $requesr)
    {
        $id = base64_decode($requesr->a_id);
        File::destroy($id);
        return response()->json(trans('File Removed'));
    }
}
