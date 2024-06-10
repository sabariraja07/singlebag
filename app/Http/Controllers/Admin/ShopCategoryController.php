<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::where('type', 'shop_category');

        if ($request->src) {
            $categories = $categories->where($request->type, $request->src);
        }
        $categories = $categories->latest()->paginate(40);

        return view('admin.shop.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shop.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'image|max:1000',
        ]);
        $slug = Str::slug($request->title);
        if (empty($slug)) {
            $slug = Category::max('id') + 1;
        }

        $category = new Category();
        $category->name = $request->title;
        $category->description = $request->description ?? null;
        $category->slug = $slug;
        $category->status = $request->status;
        $category->type = $request->type;
        $category->save();

        if ($request->has('file')) {
            $category->deleteFile('image');
            $category->uploadFile($request->file, $category->shop_id  . '/category/' . $category->id . '/image', 'image');
        }

        return response()->json([' Shop Category Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Category::find($id);
        return view('admin.shop.categories.edit', compact('info'));
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

        $category = Category::find($id);
        $category->name = $request->title;
        $category->description = $request->description ?? null;
        $category->status = $request->status;
        $category->save();

        if ($request->has('file')) {
            $category->deleteFile('image');
            $category->uploadFile($request->file, $category->shop_id  . '/category/' . $category->id . '/image', 'image');
        }

        return response()->json([' Shop Category Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if ($request->type == "delete") {
            foreach ($request->ids as $row) {
                $category = Category::with('preview')->find($row);
                if (!empty($category->preview->content)) {
                    if (file_exists($category->preview->content)) {
                        unlink($category->preview->content);
                    }
                }

                $category->delete();
            }
        }

        return response()->json(['Success']);
    }
}
