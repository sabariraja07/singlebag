<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Category::withCount('posts')->where('type', 'category');

        if ($request->src) {
            $posts = $posts->where($request->type, $request->src);
        }
        $posts = $posts->withCount('posts')->latest()->paginate(40);

        return view('admin.category.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
            'name' => 'required|unique:categories|max:100',
            'file' => 'image|max:1000',
        ]);
        $slug = Str::slug($request->name);
        if (empty($slug)) {
            $slug = Category::max('id') + 1;
        }

        $post = new Category();
        $post->name = $request->name;
        $post->slug = $slug;
        if ($request->file) {
            $imageName = date('dmy') . time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move('uploads/admin/' . date('y/m'), $imageName);
            $post->avatar = 'uploads/admin/' . date('y/m') . '/' . $imageName;
        }

        $post->type = $request->type;
        if ($request->p_id) {
            $post->p_id = $request->p_id;
        }

        $post->featured = $request->featured;
        $post->save();

        return response()->json([$request->type . ' Created']);
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
        $info = Category::with('map')->find($id);
        return view('admin.category.edit', compact('info'));
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
            'name' => 'required|max:100',
            'slug' => 'required|max:100',
            'file' => 'image|max:1000',
        ]);

        $slug = Str::slug($request->name);
        if (empty($slug)) {
            $slug = Category::max('id') + 1;
        }

        $post = Category::find($id);
        $post->name = $request->name;
        $post->slug = $slug;
        if ($request->file) {
            $imageName = date('dmy') . time() . '.' . request()->file->getClientOriginalExtension();
            request()->file->move('uploads/' . date('y/m'), $imageName);
            if (file_exists($post->avatar)) {
                unlink($post->avatar);
            }
            $post->avatar = 'uploads/' . date('y/m') . '/' . $imageName;
        }

        $post->p_id = $request->p_id;
        $post->featured = $request->featured;
        $post->save();

        return response()->json([$post->type . ' Updated']);
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
