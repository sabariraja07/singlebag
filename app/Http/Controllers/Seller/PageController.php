<?php

namespace App\Http\Controllers\Seller;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Page::where('shop_id', current_shop_id())->paginate(20);
        $post_limit = true;
        return view('seller.store.page.index', compact('posts', 'post_limit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $posts_count = Page::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('product_limit', $posts_count)) {
            Session::flash('error', trans('Maximum posts limit exceeded'));
            return back();
        }
        return view('seller.store.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $page = new Page();
        $page->title = $request->title;
        $page->slug = Str::slug($request->title);
        $page->content = $request->content ?? '';
        $page->user_id = auth()->id();
        $page->shop_id = current_shop_id();
        $page->status = 1;
        $page->type = 'page';
        $meta =  [
            'meta_description' => $request->short_description,
        ];
        $page->meta = $meta;
        $page->keywords = $request->keywords ?? null;
        $page->save();

        return response()->json([trans('Pages Created')]);
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
        $info = Page::where('shop_id', current_shop_id())->findorFail($id);
        return view('seller.store.page.edit', compact('info'));
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
        $page = Page::where('shop_id', current_shop_id())->findorFail($id);
        $page->title = $request->title;
        $page->slug = Str::slug($request->title);
        $page->content = $request->content ?? '';
        $page->user_id = auth()->id();
        $page->shop_id = current_shop_id();
        $page->status = 1;
        $page->type = 'page';
        $meta['meta_description'] = $request->short_description;
        $page->meta = $meta;
        $page->keywords = $request->keywords ?? null;
        $page->save();

        Session::flash('success', trans('Pages Updated !!'));
        return redirect('seller/setting/page');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->method == 'delete') {
            foreach ($request->ids as $key => $id) {
                $post = Page::where('shop_id', current_shop_id())->findorFail($id);
                $post->delete();
            }

            return response()->json([trans('Page Deleted')]);
        }
    }

    public function seo(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->seo()->updateOrCreate([
            'page_id' => $page->id
        ], [
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
            'keywords' => $request->keywords ?? null,
            'page' => 'page',
        ]);
        return response()->json([trans('Seo Updated')]);
    }
}
