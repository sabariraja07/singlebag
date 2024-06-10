<?php

namespace App\Http\Controllers\Seller;

use Image;
use App\Models\Media;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductMediaController extends Controller
{
    protected $filename;
    protected $ext;
    protected $fullname;
    protected $path;

    public function __construct()
    {
        $this->middleware('auth');;
    }

    public function bulk_upload()
    {
        if (!Auth()->user()->can('media.upload')) {
            abort(401);
        }
        return view('admin.media.create');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth()->user()->can('media.list')) {
            abort(401);
        }

        $medias = Media::latest()->paginate(30);
        $src = $request->src ?? '';
        return view('admin.media.index', compact('medias', 'src'));
    }

    public function json(Request $request)
    {

        $row = Media::latest()->select('id', 'name', 'url')->paginate(12);
        return response()->json($row);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->has('file')) {
            request()->validate([
                'file' => 'required|image',
                'product_id' => 'required',
            ]);
        } else {
            request()->validate([
                'media.*' => 'required|image',
                'product_id' => 'required',
            ]);
        }

       $product = Product::findorFail($request->product_id);

       if ($request->hasfile('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size - $product->getFileSize('image')))) {
                return response()->json(trans('Maximum storage limit exceeded'), 401);
            }

            $product->deleteFile('image');
            $product->uploadFile($request->file, $product->shop_id  . '/product/'. $product->id . '/image', 'image');
            $response = (array) $product->getFile('image');
            $response['message'] = trans('Feature Image updated successfully');
            return response()->json($response);
        }

        if ($request->hasfile('media')) {
            $size = 0;
            foreach ($request->file('media') as $image) {
                $size += $image->getSize();
            }
            if (user_plan_limit('storage', storageToMB($size))) {
                return response()->json(trans('Maximum storage limit exceeded'), 401);
            }
            
            foreach ($request->file('media') as $image) {
                $product->uploadFile($image, $product->shop_id  . '/product/'. $product->id . '/images', 'images');

                return response($product->getFiles('images'));
            }

            return response();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $media = Media::find($id);
        return response()->json($media);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if ($request->m_id) {
            $id = base64_decode($request->m_id);
            mediaRemove($id);
        }
        return response()->json(trans('Delete Success'));
    }










    private function create($image, $name, $type, $size, $c_type, $level)
    {
        $im_name = $this->fullname;
        $path = $this->path;
        $im_output = $path . $im_name;
        $im_ex = explode('.', $im_output); // get file extension

        // create image
        if ($type == 'image/jpeg') {
            $im = imagecreatefromjpeg($image); // create image from jpeg
        } elseif ($type == 'image/gif') {
            $im = imagecreatefromgif($image);
        } elseif ($type == 'image/png') {
            $im = imagecreatefrompng($image);
        } else {
            $im = imagecreatefromjpeg($image);
        }

        // compree image
        if ($c_type) {
            $im_name = str_replace(end($im_ex), 'jpg', $im_name);
            $im_name = str_replace(end($im_ex), 'png', $im_name);
            $im_name = str_replace(end($im_ex), 'gif', $im_name);
            $im_name = str_replace(end($im_ex), 'jpeg', $im_name); // replace file extension
            $im_output = str_replace(end($im_ex), 'webp', $im_output); // replace file extension

            if (!empty($level)) {
                imagewebp($im, $im_output, 60); // if level = 2 then quality = 80%
            } else {
                imagewebp($im, $im_output, 100); // default quality = 100% (no compression)
            }
            $im_type = 'image/webp';
            // image destroy
            imagedestroy($im);
        } else {
        }



        // output original image & compressed image
        $im_size = filesize($im_output);
        $info = array(
            'name' => $im_name,
            'image' => $im_output,
            'type' => $im_type,
            'size' => $im_size
        );
        return $info;
    }

    private function check_transparent($im)
    {

        $width = imagesx($im); // Get the width of the image
        $height = imagesy($im); // Get the height of the image

        // We run the image pixel by pixel and as soon as we find a transparent pixel we stop and return true.
        for ($i = 0; $i < $width; $i++) {
            for ($j = 0; $j < $height; $j++) {
                $rgba = imagecolorat($im, $i, $j);
                if (($rgba & 0x7F000000) >> 24) {
                    return true;
                }
            }
        }

        // If we dont find any pixel the function will return false.
        return false;
    }

    function run($image, $c_type, $level = 0)
    {

        // get file info
        $im_info = getImageSize($image);
        $im_name = basename($image);
        $im_type = $im_info['mime'];
        $im_size = filesize($image);

        // result
        $result = array();

        // cek & ricek
        if (in_array($c_type, array('jpeg', 'jpg', 'JPG', 'JPEG', 'gif', 'GIF', 'png', 'PNG'))) { // jpeg, png, gif only

            $result['data'] = $this->create($image, $im_name, $im_type, $im_size, $c_type, $level);

            return $result;
        }
    }
}
