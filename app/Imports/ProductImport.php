<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Attribute;
use App\Models\Stock;
use App\Models\Meta;
use App\Models\Media;
use App\Models\PostMedia;
use App\Models\Price;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\PostCategory;
use App\Models\OldProductOption;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;





class ProductImport implements ToCollection, WithHeadingRow, WithStartRow
{
  protected $user_id;
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function startRow(): int
  {
    return 3;
  }
  public function collection(Collection $rows)
  {

    if (current_shop_type() == 'suppier') {
      Validator::make($rows->toArray(), [
        '*.title' => 'required',
        '*.status' => 'required',
        '*.regular_price' => 'required',
        '*.stock_manage' => 'required',
        '*.stock_qty' => 'required',
        '*.stock_status' => 'required'
      ])->validate();
    }

    if (current_shop_type() == 'seller') {
      Validator::make($rows->toArray(), [
        '*.title' => 'required',
        '*.featured' => 'required',
        '*.status' => 'required',
        '*.regular_price' => 'required',
        '*.stock_manage' => 'required',
        '*.stock_qty' => 'required',
        '*.stock_status' => 'required'
      ])->validate();
    }




    ini_set('max_execution_time', '0');
    $auth_id = Auth::id();
    $posts_count = Product::where('shop_id', current_shop_id())->where('type', 'product')->where('status', '!=', 4)->count();

    $this->user_id = $auth_id;
    foreach ($rows as $key => $row) {

      if (user_plan_limit('product_limit', $posts_count)) {

        \Session::flash('error', 'Maximum posts limit exceeded');
        return back();
        break;
      }
      $posts_count++;


      // $term = new Product;
      // $term->title = $row['title'];
      // $term->slug = Str::slug($row['title']);
      // $term->featured = $row['featured'];
      // $term->status = $row['status'];
      // $term->type = 'product';
      // $term->user_id = $auth_id;
      // $term->shop_id = current_shop_id();
      // $term->save();

      $term = new Product;
      $term->title = $row['title'];
      $term->slug = Str::slug($row['title']);
      $term->status = $row['status'] ?? 2;
      $term->featured = $row['featured'] ?? 0;
      $term->short_description = $row['product_short_description'] ?? null;
      $term->type = 'product';
      $term->affiliate = null;
      $term->user_id = $auth_id;
      $term->shop_id = current_shop_id();
      $seo['meta_title'] = $row['title'];
      $seo['meta_description'] = '';
      $seo['meta_keyword'] = '';
      $term->seo = $seo;
      $term->save();

      if ($row['offer_price_start_date'] != null && $row['offer_price_start_date']  <= Carbon::now()->format('Y-m-d') && $row['selling_price'] != null) {
        if (!$row->has($row['offer_price_end_date']) || Carbon::parse($row['offer_price_end_date'])->endOfDay() >  Carbon::now()->format('Y-m-d')) {
          // if ($row['price_type'] == 1) {
          //   $price = $row['regular_price'] - $row['selling_price'];
          // } else {
          //   $percent = $row['regular_price'] * $row['selling_price'] * 0.01;
          //   $price = $row['regular_price'] - $percent;
          //   $price = str_replace(',', '', number_format($price, 2));
          // }
          $price = $row['selling_price'];
          $diff =  $row['regular_price'] - $row['selling_price'];
          $price = str_replace(',', '', number_format($price, 2));
        } else {
          $price =  $row['regular_price'];
        }
      } else {
        $price =  $row['regular_price'];
      }

      $new_price = new Price;
      $new_price->product_id = $term->id;
      $new_price->price = $price;
      $new_price->regular_price = $row['regular_price'];
      $new_price->special_price = $diff ?? null;
      $new_price->price_type = $row['price_type'] ?? 1;

      if (!empty($row['offer_price_start_date'])) {
        $new_price->starting_date = Carbon::parse($row['offer_price_start_date'])->toDateString();
      }
      if (!empty($row['offer_price_end_date'])) {
        $new_price->ending_date = Carbon::parse($row['offer_price_end_date'])->toDateString();
      }
      $new_price->save();


      $stc = new Stock;
      $stc->sku = $row['sku'];
      $stc->stock_manage = $row['stock_manage'];
      if ($row['stock_manage'] == 1 && $row['stock_status'] == 1) {
        if ($row['stock_qty'] > 0) {
          $stc->stock_qty = $row['stock_qty'];
          $stc->stock_status = $row['stock_status'];
        } else {
          $stc->stock_qty = 0;
          $stc->stock_status = 0;
        }
      }
      if ($row['stock_manage'] == 1 && $row['stock_status'] == 0) {
        $stc->stock_qty = 0;
        $stc->stock_status = 0;
      }
      $stc->product_id = $term->id;
      $stc->save();


      // $dta['content'] = $row['product_short_description'];
      $dta['excerpt'] = $row['product_short_description'];

      $meta = new Meta;
      $meta->product_id = $term->id;
      $meta->key = 'content';
      $meta->value = json_encode($dta);
      $meta->save();

      $data = '{"meta_title":"' . $row['meta_title'] . '","meta_description":"' . $row['meta_keyword'] . '","meta_keyword":"' . $row['meta_description'] . '"}';
      $meta = new Meta;
      $meta->product_id = $term->id;
      $meta->key = 'seo';
      $meta->value = $data;
      $meta->save();

      $check_brand = $row['brand'] ?? '';
      $categories = Brand::where('name', $row['brand'])->where('shop_id', current_shop_id())->first();
      if (!empty($check_brand)) {
        if (empty($categories)) {
          $brand = new Brand;
          $brand->name = $check_brand;
          $brand->slug = Str::slug($check_brand);
          $brand->featured = 0;
          $brand->status = 1;
          $brand->user_id = $auth_id;
          $brand->shop_id = current_shop_id();
          $brand->save();


          $post_category_brand = Product::where('id', $term->id)->first();
          $post_category_brand->brand_id = $brand->id ?? '';
          $post_category_brand->save();
        }
        if (!empty($categories)) {
          $post_category_brand = Product::where('id', $term->id)->first();
          $post_category_brand->brand_id = $categories->id ?? '';
          $post_category_brand->save();
        }
      }
      $str = $row['categories'] ?? '';
      $count = substr_count($str, ",");
      // if($count > 0){
      $explode = explode(",", $str);
      for ($i = 0; $i <= $count; $i++) {
        $query_category = Category::where('type', 'category')->where('name', $explode[$i])->where('shop_id', current_shop_id())->first();
        if (!empty($explode[$i])) {
          if (empty($query_category)) {
            $category = new Category;
            $category->name = $explode[$i];
            $category->slug = Str::slug($explode[$i]);
            $category->type = 'category';
            $category->user_id = $auth_id;
            $category->shop_id = current_shop_id();
            $category->save();

            $post_category = new ProductCategory;
            $post_category->category_id = $category->id ?? '';
            $post_category->product_id = $term->id ?? '';
            $post_category->save();
          }

          if (!empty($query_category)) {
            $post_category = new ProductCategory;
            $post_category->category_id = $query_category->id ?? '';
            $post_category->product_id = $term->id ?? '';
            $post_category->save();
          }
        }
      }
      // }

      //first option
      if (isset($row['option_title_a']) && !empty($row['option_title_a'])) {
        $option_one = new OldProductOption;
        $option_one->name = $row['option_title_a'] ?? '';
        $option_one->type = 1;
        $option_one->is_required =  $row['is_required_a'] ?? 0;
        $option_one->select_type = $row['select_type_a'] ?? 0;
        $option_one->product_id = $term->id;
        $option_one->shop_id = current_shop_id();
        $option_one->save();
      }


      //first option-> sub one
      if (isset($row['option_title_a']) && !empty($row['option_title_a']) && isset($row['row_a_name_one']) && !empty($row['row_a_name_one'])) {
        $option_sub_one = new OldProductOption();
        $option_sub_one->name = $row['row_a_name_one'] ?? '';
        $option_sub_one->amount = $row['row_a_price_one'] ?? 0.00;
        $option_sub_one->amount_type = $row['row_a_price_type_one'] ?? 1;
        $option_sub_one->type = 0;
        $option_sub_one->p_id = $option_one->id;
        $option_sub_one->product_id = $term->id;
        $option_sub_one->shop_id = current_shop_id();
        $option_sub_one->save();
      }


      //first option->sub two
      if (isset($row['option_title_a']) && !empty($row['option_title_a']) && isset($row['row_a_name_two']) && !empty($row['row_a_name_two'])) {
        $option_sub_two = new OldProductOption();
        $option_sub_two->name = $row['row_a_name_two'] ?? '';
        $option_sub_two->amount = $row['row_a_price_two'] ?? 0.00;
        $option_sub_two->amount_type = $row['row_a_price_type_two'] ?? 1;
        $option_sub_two->type = 0;
        $option_sub_two->p_id = $option_one->id;
        $option_sub_two->product_id = $term->id;
        $option_sub_two->shop_id = current_shop_id();
        $option_sub_two->save();
      }

      //Second option
      if (isset($row['option_title_b']) && !empty($row['option_title_b'])) {
        $option_two = new OldProductOption;
        $option_two->name = $row['option_title_b'] ?? '';
        $option_two->type = 1;
        $option_two->is_required =  $row['is_required_b'] ?? 0;
        $option_two->select_type = $row['select_type_b'] ?? 0;
        $option_two->product_id = $term->id;
        $option_two->shop_id = current_shop_id();
        $option_two->save();
      }

      //second option-> sub one
      if (isset($row['option_title_b']) && !empty($row['option_title_b']) && isset($row['row_b_name_one']) && !empty($row['row_b_name_one'])) {
        $option_two_sub_one = new OldProductOption();
        $option_two_sub_one->name = $row['row_b_name_one'] ?? '';
        $option_two_sub_one->amount = $row['row_b_price_one'] ?? 0.00;
        $option_two_sub_one->amount_type = $row['row_b_price_type_one'] ?? 1;
        $option_two_sub_one->type = 0;
        $option_two_sub_one->p_id = $option_two->id;
        $option_two_sub_one->product_id = $term->id;
        $option_two_sub_one->shop_id = current_shop_id();
        $option_two_sub_one->save();
      }


      //second option->sub two
      if (isset($row['option_title_b']) && !empty($row['option_title_b']) && isset($row['row_b_name_two']) && !empty($row['row_b_name_two'])) {
        $option_two_sub_two = new OldProductOption();
        $option_two_sub_two->name = $row['row_b_name_two'] ?? '';
        $option_two_sub_two->amount = $row['row_b_price_two'] ?? 0.00;
        $option_two_sub_two->amount_type = $row['row_b_price_type_two'] ?? 1;
        $option_two_sub_two->type = 0;
        $option_two_sub_two->p_id = $option_two->id;
        $option_two_sub_two->product_id = $term->id;
        $option_two_sub_two->shop_id = current_shop_id();
        $option_two_sub_two->save();
      }


      //first Attribute Creation 
      $check_attribute = $row['attribute_title_b'] ?? '';
      $check_child_attribute = $row['attribute_varients_a'] ?? '';
      $count_child = substr_count($check_child_attribute, ",");
      $explode_chlid = explode(",", $check_child_attribute);
      if (!empty($check_attribute)) {
        $attribute_one = new Attribute;
        $attribute_one->name = $check_attribute;
        $attribute_one->featured = $row['attribute_featured_a'] ?? 1;
        $attribute_one->slug = Str::slug($check_attribute);
        $attribute_one->user_id = Auth::id();
        $attribute_one->shop_id = current_shop_id();
        $attribute_one->status = 1;
        $attribute_one->save();
      }
      if (!empty($check_attribute)) {
        for ($i = 0; $i <= $count_child; $i++) {
          if (!empty($explode_chlid[$i])) {
            $attribute_sub_one = new Attribute;
            $attribute_sub_one->name = $explode_chlid[$i];
            $attribute_sub_one->featured = 1;
            $attribute_sub_one->slug = Str::slug($explode_chlid[$i]);
            $attribute_sub_one->parent_id = $attribute_one->id;
            $attribute_sub_one->user_id = Auth::id();
            $attribute_sub_one->shop_id = current_shop_id();
            $attribute_sub_one->save();

            $attribute_add = new ProductAttribute;
            $attribute_add->attribute_id = $attribute_sub_one->id;
            $attribute_add->product_id = $term->id;
            $attribute_add->save();
          }
        }
      }

      //second Attribute Creation 
      $check_attribute_two = $row['attribute_title_b'] ?? '';
      $check_child_attribute_two = $row['attribute_varients_b'] ?? '';
      $count_child_two = substr_count($check_child_attribute_two, ",");
      $explode_chlid_two = explode(",", $check_child_attribute_two);
      if (!empty($check_attribute_two)) {
        $attribute_two = new Attribute;
        $attribute_two->name = $check_attribute_two;
        $attribute_two->featured = $row['attribute_featured_b'] ?? 1;
        $attribute_two->slug = Str::slug($check_attribute_two);
        $attribute_two->user_id = Auth::id();
        $attribute_two->shop_id = current_shop_id();
        $attribute_two->status = 1;
        $attribute_two->save();
      }
      if (!empty($check_attribute_two)) {
        for ($i = 0; $i <= $count_child_two; $i++) {
          if (!empty($explode_chlid_two[$i])) {
            $attribute_sub_two = new Attribute;
            $attribute_sub_two->name = $explode_chlid_two[$i];
            $attribute_sub_two->featured = 1;
            $attribute_sub_two->slug = Str::slug($explode_chlid_two[$i]);
            $attribute_sub_two->parent_id = $attribute_two->id;
            $attribute_sub_two->user_id = Auth::id();
            $attribute_sub_two->shop_id = current_shop_id();
            $attribute_sub_two->save();

            $attribute_add_two = new ProductAttribute;
            $attribute_add_two->attribute_id = $attribute_sub_two->id;
            $attribute_add_two->product_id = $term->id;
            $attribute_add_two->save();
          }
        }
      }
    }
  }

  public function media($url, $id)
  {
    $imageSizes = json_decode(imageSizes());
    if (!empty($url)) {

      $data = explode('/', $url);
      $name = end($data);

      $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd', 'webp'];

      $explodeImage = explode('.', $name);
      $extension = end($explodeImage);

      if (!in_array(strtolower($extension), $imageExtensions)) {
        return false;
      }

      $file = file_get_contents($url);
      $path = 'uploads/' . $this->user_id . date('/y/') . date('m') . '/' . rand(20, 60) . $name;
      $filename = 'uploads/' . $this->user_id . date('/y/') . date('m') . '/';

      $pathArr = explode('/', $path);
      $name = end($pathArr);
      if (!\File::exists($filename)) {
        mkdir($filename, 0777, true);
      }
      file_put_contents($path, $file);
      $imgArr = explode('.', $name);

      foreach ($imageSizes as $size) {
        $img = \Image::make($path);
        $img->fit($size->width, $size->height);
        $img->save($filename . $imgArr[0] . $size->key . '.' . $imgArr[1]);
      }


      $schemeurl = parse_url(url('/'));
      if ($schemeurl['scheme'] == 'https') {
        $url = substr(url('/'), 6);
      } else {
        $url = substr(url('/'), 5);
      }

      $url = $url . '/' . $path;

      $media = new Media;
      $media->name = $path;
      $media->user_id = $this->user_id;
      $media->url = $url;
      $media->save();

      $post = new PostMedia;
      $post->media_id = $media->id;
      $post->product_id = $id;
      $post->save();
    }
  }
}
