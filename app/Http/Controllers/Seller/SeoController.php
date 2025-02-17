<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopOption;
use Illuminate\Support\Facades\Auth;
use samdark\sitemap\Sitemap;
use samdark\sitemap\Index;
class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $info=ShopOption::where('shop_id',current_shop_id())->where('key','seo')->first();
        if (empty($info)) {
            $info=new ShopOption;
            $info->shop_id=current_shop_id();
            $info->key='seo';
            $data['title']='';
            $data['twitterTitle']='';
            $data['canonical']='';
            $data['tags']='';
            $data['description']='';
            $info->value=json_encode($data);
            $info->save();
            
        }
        $id=$info->id;
        $info=json_decode($info->value);
       
        return view('seller.store.seo',compact('info','id'));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth_id=Auth::id();
        if (!file_exists('uploads/'. current_shop_id() .'/sitemap.xml')) {
            file_put_contents('uploads/'. current_shop_id() .'/sitemap.xml', '');
        }
        $url=my_url();

        $products=\App\Models\Product::where('shop_id',current_shop_id())->where('type','product')->get();
        $pages=\App\Models\Product::where('shop_id',current_shop_id())->where('type','page')->get();
        $categories=\App\Models\Category::where('shop_id',current_shop_id())->where('type','page')->get();
        $brands=\App\Models\Category::where('shop_id',current_shop_id())->where('type','brands')->get();

        $index = new Index('uploads/'. current_shop_id() .'/sitemap.xml');
        $index->addSitemap($url.'/');
        $index->addSitemap($url.'/shop');
        $index->addSitemap($url.'/contact');


        foreach ($products as $key => $row) {
             $index->addSitemap($url.'/product/'.$row->slug.'/'.$row->id);
        }

        foreach ($categories as $key => $row) {
             $index->addSitemap($url.'/category/'.$row->slug.'/'.$row->id);
        }

        foreach ($pages as $key => $row) {
             $index->addSitemap($url.'/page/'.$row->slug.'/'.$row->id);
        }

         foreach ($brands as $key => $row) {
             $index->addSitemap($url.'/brand/'.$row->slug.'/'.$row->id);
        }
        $check= $index->write();


      return response()->json([trans('New Sitemap Generated')]);
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
       $info=ShopOption::where('shop_id',current_shop_id())->where('key','seo')->findorFail($id);
       $data['title']=$request->title;
       $data['twitterTitle']=$request->twitterTitle;
       $data['canonical']=$request->canonical;
       $data['tags']=$request->tags;
       $data['description']=$request->description;
       $info->value=json_encode($data);
       $info->save();

       return response()->json([trans('Site Seo Content Updated')]);
       
    }

   
}
