<?php

use App\Models\Shop;
use App\Models\Coupon;
use App\Models\Option;
use App\Models\Domain;
use App\Models\Product;
use App\Models\Category;
use App\Helpers\Helper;
use App\Helper\Lphelper;
use App\Models\Attachment;
use App\Models\Currency;
use App\Models\ShopOption;
use App\Models\OldProductOption;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

use function Clue\StreamFilter\fun;

function shop_details($shop_id)
{
	$shop_info = Shop::where('id', $shop_id)->first();
	return $shop_info;
}

function shop_product_count($shop_id)
{
	$shop_info = Shop::where('id', $shop_id)->first();
	if ($shop_info) {
		$product_count = OldProductOption::where('id', $shop_info->shop_id)->count();
	}
	return $product_count ?? 0;
}

function domain_info($key = "all", $default = false)
{
	$url = request()->getHost();
	$url = str_replace('www.', '', $url);
	if (Cache::has($url) || Cache::has($url . '.admin')) {
		$data = Cache::has($url . '.admin') ? Cache::get($url . '.admin') : Cache::get($url);

		if ($key == "user_type") {
			return Cache::has($url . '.admin') ? 'admin' : 'customer';
		}

		if ($key == "all") {
			return $data;
		}
		if ($key == "domain_id") {
			return $data['domain_id'];
		}
		if ($key == "user_id") {
			return $data['user_id'];
		}
		if ($key == "domain_name") {
			return $data['domain_name'];
		}

		if ($key == "full_domain") {
			return $data['full_domain'];
		}
		if ($key == "view_path") {
			return $data['view_path'];
		}
		if ($key == "asset_path") {
			return $data['asset_path'];
		}
		if ($key == "shop_type") {
			return $data['shop_type'];
		}

		if ($key == "shop_id") {
			return $data['shop_id'];
		}

		if ($key == "store_is_online") {
			return $data['store_is_online'];
		}

		$plan = $data['plan'] ?? '';

		if (in_array($key, ["brand_limit", "product_limit", "category_limit", "customer_limit", "location_limit", "variation_limit", "storage"])) {
			return $plan->$key ?? 0;
		}

		return $plan->$key ? filter_var($plan->$key, FILTER_VALIDATE_BOOLEAN) : $default;
	} else {
		return $default;
	}
}

function company_info()
{
	$company_info = Option::where('key', 'company_info')->first();
	$company_info = json_decode($company_info->value);
	return $company_info ? $company_info : null;
}

function current_shop()
{
	$domain = request()->getHost();
	if (domain_info('shop_id')) {
		$shop =  Cache::remember($domain . 'current_shop_' . domain_info('shop_id'), 420, function () use ($domain) {
			return domain_info('shop_id') ? Shop::where('id', domain_info('shop_id'))->first() : null;
		});
	} else {
		$domain = Domain::where('domain', request()->getHost())->where('status', 1)->first();
		$shop = isset($domain) ? Shop::where('id', $domain->shop_id)->first() : null;
	}
	return isset($shop) ? $shop : null;
}

function get_shop_category($shop_id)
{
	$shop_category_id = ShopOption::where('shop_id', $shop_id)->where('key', 'shop_category')->first();
	if ($shop_category_id) {
		$shop_category	= Category::where('id', $shop_category_id->value)->where('type', 'shop_category')->first();
		return $shop_category ? $shop_category->name : null;
	}
}

function current_shop_type()
{
	$shop = current_shop();
	return $shop ? $shop->shop_type : null;
}

function current_shop_id()
{
	$shop = current_shop();
	return $shop ? $shop->id : 0;
}

function base_view()
{
	$view = str_replace('/', '.', domain_info('view_path'));
	return $view;
}

function my_url()
{
	if (Auth::check()) {
		return domain_info('full_domain');
	} else {

		return url('/');
	}
}

function tax()
{
	return Cache::remember('tax' . domain_info('shop_id'), 420, function () {
		$shop_id = domain_info('shop_id');
		$tax = ShopOption::where('key', 'tax')->where('shop_id', $shop_id)->first();
		return $tax->value ?? 0;
	});
}

function seller_tax()
{
	$shop_id = current_shop_id();
	$tax = ShopOption::where('key', 'tax')->where('shop_id', $shop_id)->first();
	return $tax->value ?? 0;
}
function content($data)
{
	return view('components.content', compact('data'));
}

function load_whatsapp()
{
	return view('components.whatsapp');
}

function load_header()
{
	return view('components.load_header');
}

function load_footer()
{
	return view('components.load_footer');
}

function MenuPositions()
{
	$data['header'] = "Header";
	$data['footer_left'] = "Footer left";
	$data['footer_right'] = "Footer right";
	$data['footer_center'] = "Footer center";

	return $data;
}

function amount_to_number($amount = 0)
{
	return filter_var($amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}


function amount_format($amount)
{
	$amount = filter_var($amount ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	if (url('/') == env('APP_URL')) {

		if (!Cache::has('admin_currency_info')) {
			admin_currency_info();
		}

		if (Cache::has('admin_currency_info')) {
			$currency = Cache::get('admin_currency_info');
			return $currency->code ? money($amount * 100, $currency->code)->format() : number_format($amount, 2);
		}
	} else {
		if (!Cache::has(domain_info('shop_id') . 'currency_info')) {
			currency_info();
		}

		if (Cache::has(domain_info('shop_id') . 'currency_info')) {
			$currency = Cache::get(domain_info('shop_id') . 'currency_info');
			return $currency->code ? money($amount * 100, $currency->code)->format() : number_format($amount, 2);
		}
	}

	return number_format($amount, 2);
}

function amount_format_pdf($amount, $type = "symbol")
{
	$amount = filter_var($amount ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

	$number = number_format($amount, 2);

	if (!Cache::has(domain_info('shop_id') . 'currency_info')) {
		currency_info();
	}

	if (Cache::has(domain_info('shop_id') . 'currency_info')) {
		$currency = Cache::get(domain_info('shop_id') . 'currency_info');

		if (!isset($currency)) return $number;

		if ($type == "symbol") {
			$symbol = $currency->code == 'INR' ? '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>' : $currency->symbol;
			if ($currency->position == 1) {
				return $number . $symbol;
			} else {
				return $symbol . $number;
			}
		} else {

			if ($currency->position == 1) {
				return $number . $currency->code;
			} else {
				return $currency->code . ' ' . $number;
			}
		}
	}

	return $number;
}


function get_host()
{
	$url = request()->getHost();
	return $url = str_replace('www.', '', $url);
}

function set_language()
{
	$locale_info = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'local')->first();
	if ($locale_info) {
		$locale = $locale_info->value;
		app()->setLocale($locale);
		session()->put('locale', $locale);
	}
}

function currency_info()
{
	$url = request()->getHost();
	$url = $url = str_replace('www.', '', $url);

	if (Cache::has($url) || Cache::has($url . '.admin')) {
		return cache()->remember(domain_info('shop_id') . 'currency_info', 500, function () {
			$data =  Cache::has(get_host() . '.admin') ? Cache::get(get_host() . '.admin') : Cache::get(get_host());
			$shop_id = $data['shop_id'];
			$currency = ShopOption::where('shop_id', $shop_id)->where('key', 'currency')->first();
			return get_currency_info($currency->value ?? "");
		});
	} else {
		return cache()->remember('currency_info', 300, function () {
			$currency = Option::where('key', 'currency_info')->first();
			return get_currency_info($currency->value ?? "");
		});
	}
}

function get_currency_info($code = '')
{
	return DB::table('currencies')->where('code', $code)->first();
}


function amount_admin_format($amount)
{
	$amount = filter_var($amount ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	if (!Cache::has('admin_currency_info')) {
		admin_currency_info();
	}

	if (Cache::has('admin_currency_info')) {
		$currency = Cache::get('admin_currency_info');
		return $currency->code ? money($amount * 100, $currency->code)->format() : number_format($amount, 2);
	}

	return number_format($amount, 2);
}

function admin_amount_format_pdf($amount, $type = "symbol")
{
	$amount = filter_var($amount ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

	$number = number_format($amount, 2);

	if (!Cache::has('admin_currency_info')) {
		admin_currency_info();
	}

	if (Cache::has('admin_currency_info')) {
		$currency = Cache::get('admin_currency_info');

		if (!isset($currency)) return $number;

		if ($type == "symbol") {
			$symbol = $currency->code == 'INR' ? '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>' : $currency->symbol;
			if ($currency->position == 1) {
				return $number . $symbol;
			} else {
				return $symbol . $number;
			}
		} else {

			if ($currency->position == 1) {
				return $number . $currency->code;
			} else {
				return $currency->code . ' ' . $number;
			}
		}
	}

	return $number;
}

function make_token($token)
{
	return base64_decode(base64_decode(base64_decode($token)));
}

function admin_currency_info()
{
	return cache()->remember('admin_currency_info', 300, function () {
		$currency = Option::where('key', 'currency')->first();
		return get_currency_info($currency->value ?? "");
	});
}



function imageSizes()
{
	$sizes = '[{"key":"medium","height":"256","width":"256"}]';
	return $sizes;
}



function google_analytics_for_user()
{
	if (Auth::check()) {
		if (Auth::user()->hasRole('superadmin')) {
			$option = Option::where('key', 'marketing_tool')->first();
			$option = json_decode($option->value);
			$data['view_id'] = $option->analytics_view_id;
			$data['service_account_credentials_json'] = 'uploads/service-account-credentials.json';
			return $data;
		} else {
			$info = ShopOption::where('key', 'google-analytics')->where('shop_id', current_shop_id())->first();
			$info = json_decode($info->value);
			$data['view_id'] = $info->analytics_view_id;
			$data['service_account_credentials_json'] = 'uploads/' . current_shop_id() . '/service-account-credentials.json';
			return $data;
		}
	}
}

function base_counter($data, $count)
{
	$r = $data;
	for ($i = 0; $i < $count; $i++) {
		$r = base64_decode($r);
	}
	return $r;
}

function mediaRemove($id)
{
	// $imageSizes = json_decode(imageSizes());
	// $media = Attachment::find($id);
	// $file = $media->name;
	// if (!empty($file)) {
	// 	if (file_exists($file)) {
	// 		unlink($file);
	// 		foreach ($imageSizes as $size) {
	// 			$img = explode('.', $file);
	// 			if (file_exists($img[0] . $size->key . '.' . $img[1])) {
	// 				unlink($img[0] . $size->key . '.' . $img[1]);
	// 			}
	// 		}
	// 	}
	// }
	Attachment::destroy($id);
}

function folderSize($dir)
{
	$file_size = 0;
	if (!file_exists($dir)) {
		return $file_size;
	}

	foreach (File::allFiles($dir) as $file) {
		$file_size += $file->getSize();
	}


	return str_replace(',', '', number_format($file_size / 1048576, 2));
}

function storageSize($shop_id = null)
{
	$file_size = Attachment::where('shop_id', $shop_id ?? current_shop_id())->sum('size');

	return str_replace(',', '', number_format($file_size / 1048576, 2));
}

function storeStorageSize($shop_id = null)
{
	$file_size = Attachment::where('shop_id', $shop_id ?? current_shop_id())->sum('size');
	$file_size = str_replace(',', '', number_format($file_size / 1048576));

	return human_filesize($file_size);
}

function storesStorageSize($shop_id)
{
	$file_size = Attachment::where('shop_id', $shop_id ?? $shop_id)->sum('size');
	$file_size = str_replace(',', '', number_format($file_size / 1048576));

	return human_filesize($file_size);
}

function shopFolderSize($dir)
{
	$file_size = 0;
	if (!file_exists($dir)) {
		return $file_size;
	}

	foreach (File::allFiles($dir) as $file) {
		$file_size += $file->getSize();
	}


	$file_size = str_replace(',', '', number_format($file_size / 1048576));

	return human_filesize($file_size);
}

function human_filesize($MB, $dec = 2)
{
	if ($MB == '') {
		return 'Unlimited';
	}

	$size   = array('MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$factor = floor((strlen($MB) - 1) / 3);

	return sprintf("%.{$dec}f", $MB / pow(1024, $factor)) . @$size[$factor];
}

function user_plan_limit($key, $size)
{
	$limit = user_limit();
	if ($limit[$key] == -1) return false;
	if ($limit[$key] > $size) return false;
	return true;
}

function storageToMB($size, $shop_id = null)
{
	return storageSize($shop_id) + str_replace(',', '', number_format(($size) / 1048576, 2));
}

function user_plan_access($key, $value = false)
{
	$limit = user_limit();
	return $limit[$key] ?? $value;
}

function user_limit()
{
	$shop = Shop::find(current_shop_id());

	$plan = null;
	if (isset($shop) && $shop->will_expire >= now()->subDays(1)) {
		$plan = json_decode($shop->data);
	}

	if (!isset($plan)) {
		return $data = null;
	}



	$gtm = $plan->gtm ?? false;
	$pos = $plan->pos ?? false;
	$pwa = $plan->pwa ?? false;
	$qr_code = $plan->qr_code ?? false;
	$whatsapp = $plan->whatsapp ?? false;
	$custom_js = $plan->custom_js ?? false;
	$inventory = $plan->inventory ?? false;
	$custom_css = $plan->custom_css ?? false;
	$live_support = $plan->live_support ?? false;
	$custom_domain = $plan->custom_domain ?? false;
	$customer_panel = $plan->customer_panel ?? false;
	$facebook_pixel = $plan->facebook_pixel ?? false;
	$google_analytics = $plan->google_analytics ?? false;
	$brand_limit = $plan->brand_limit ?? 0;
	$product_limit = $plan->product_limit ?? 0;
	$category_limit = $plan->category_limit ?? 0;
	$customer_limit = $plan->customer_limit ?? 0;
	$agent_limit = $plan->agent_limit ?? 20;
	$location_limit = $plan->location_limit ?? 0;
	$variation_limit = $plan->variation_limit ?? 0;
	$storage = $plan->storage ?? 0;


	$data['storage'] = (int) $storage;
	$data['product_limit'] = (int) $product_limit;
	$data['category_limit'] = (int) $category_limit;
	$data['customer_limit'] = (int) $customer_limit;
	$data['agent_limit'] = (int) $agent_limit;
	$data['location_limit'] = (int) $location_limit;
	$data['variation_limit'] = (int) $variation_limit;
	$data['brand_limit'] = (int) $brand_limit;
	$data['gtm'] = filter_var($gtm, FILTER_VALIDATE_BOOLEAN);
	$data['pos'] = filter_var($pos, FILTER_VALIDATE_BOOLEAN);
	$data['pwa'] = filter_var($pwa, FILTER_VALIDATE_BOOLEAN);
	$data['qr_code'] = filter_var($qr_code, FILTER_VALIDATE_BOOLEAN);
	$data['whatsapp'] = filter_var($whatsapp, FILTER_VALIDATE_BOOLEAN);
	$data['custom_js'] = filter_var($custom_js, FILTER_VALIDATE_BOOLEAN);
	$data['inventory'] = filter_var($inventory, FILTER_VALIDATE_BOOLEAN);
	$data['custom_css'] = filter_var($custom_css, FILTER_VALIDATE_BOOLEAN);
	$data['live_support'] = filter_var($live_support, FILTER_VALIDATE_BOOLEAN);
	$data['custom_domain'] = filter_var($custom_domain, FILTER_VALIDATE_BOOLEAN);
	$data['customer_panel'] = filter_var($customer_panel, FILTER_VALIDATE_BOOLEAN);
	$data['facebook_pixel'] = filter_var($facebook_pixel, FILTER_VALIDATE_BOOLEAN);
	$data['google_analytics'] = filter_var($google_analytics, FILTER_VALIDATE_BOOLEAN);



	return $data;
}

function google_tag_manager_header($id)
{
	echo "<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','" . $id . "');</script>
	<!-- End Google Tag Manager -->";
}

function google_tag_manager_footer($id)
{
	echo '<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $id . '"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->';
}



function google_analytics($GA_MEASUREMENT_ID)
{
	$script = '<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=' . $GA_MEASUREMENT_ID . '"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag("js", new Date());

	gtag("config", "' . $GA_MEASUREMENT_ID . '");
	</script>';

	return $script;
}


function facebook_pixel($pixel_id)
{
	$script = "<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '{$pixel_id}');
	fbq('track', 'PageView');
	</script>
	<noscript><img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id='{$pixel_id}'&ev=PageView&noscript=1'/>
	</noscript>
	<!-- End Facebook Pixel Code -->";

	return $script;
}

function current_domain_full_url()
{
	$domain = Domain::where('domain', request()->getHost())
		->where('status', 1)
		->first();
	return isset($domain) ? $domain->full_domain : null;
}

/*
replace image name via $name from $url
*/
function ImageSize($url, $name)
{
	$img_arr = explode('.', $url);
	$ext = '.' . end($img_arr);
	$newName = str_replace($ext, $name . $ext, $url);
	return $newName;
}

function welcome_footer_menu($position, $ul = '', $li = '', $a = '', $icon_position = 'top', $lang = false)
{
	return Lphelper::WelcomeMenuCustom($position, $ul, $li, $a, $icon_position, $lang);
}

if (!function_exists('renderStarRating')) {
	function renderStarRating($rating, $maxRating = 5)
	{
		$fullStar = "<i class ='fa fa-star mr-2'></i>";
		$halfStar = "<i class ='fa fa-star-half-o mr-2'></i>";
		$emptyStar = "<i class ='fa fa-star-o mr-2'></i>";
		$rating = $rating <= $maxRating ? $rating : $maxRating;

		$fullStarCount = (int)$rating;
		$halfStarCount = ceil($rating) - $fullStarCount;
		$emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

		$html = str_repeat($fullStar, $fullStarCount);
		$html .= str_repeat($halfStar, $halfStarCount);
		$html .= str_repeat($emptyStar, $emptyStarCount);
		echo $html;
	}
}

function CollapseAbleMenu($position, $ul = '')
{
	$menu_position = $position;
	$menus = Helper::menu_query($menu_position);
	return view('components.menu.parent', compact('menus', 'ul'));
}

function CollapseAbleMenuItems($position)
{
	$menu_position = $position;
	$menus = Helper::menu_query($menu_position);
	return $menus;
}

function ThemeMenu($position, $path)
{
	$menu_position = $position;
	$menus = Helper::menu_query($menu_position);
	return view($path . '.parent', compact('menus'));
}

function ThemeFooterMenu($position, $path)
{
	$menu_position = $position;
	$menus = Helper::menu_query_with_name($menu_position);
	return view($path . '.parent', compact('menus'));
}

function ConfigBrand($type, $select = '')
{
	return Lphelper::ConfigBrand($type, $select);
}


function ConfigCategory($type, $select = '')
{
	return Lphelper::ConfigCategory($type, $select);
}

function enn($param)
{
	$ct = \App\Helper\Order\Toyyibpay::Toyi($param);
	return base_counter($ct, 3);
}

function ConfigCategoryMulti($type, $select = [])
{
	return Lphelper::ConfigCategoryMulti($type, $select);
}
/*
return total active language
*/
function adminLang($c = '')
{
	return Lphelper::AdminLang($c);
}

function disquscomment()
{
	return Lphelper::Disqus();
}

/*
return Option value
*/
function LpOption($key, $array = false, $translate = false)
{
	if ($translate == true) {
		$data = Option::where('key', $key)->where('lang', Session::get('locale'))->select('value')->first();
		if (empty($data)) {
			$data = Option::where('key', $key)->select('value')->first();
		}
	} else {
		$data = Option::where('key', $key)->select('value')->first();
	}

	if ($array == true) {
		return json_decode($data->value, true);
	}
	return json_decode($data->value);
}

function Livechat($param)
{
	return Lphelper::TwkChat($param);
}


function mediasingle()
{
	if (Auth::User()->role->id == 3) {
		$medialimit = true;
	} else {
		$medialimit = true;
	}

	return view('admin.media.mediamodal', compact('medialimit'));
}

function input($array = [])
{
	$title = $array['title'] ?? 'title';
	$type = $array['type'] ?? 'text';
	$placeholder = $array['placeholder'] ?? '';
	$name = $array['name'] ?? 'name';
	$id = $array['id'] ?? '';
	$value = $array['value'] ?? '';
	if (isset($array['is_required'])) {
		$required = $array['is_required'];
	} else {
		$required = false;
	}
	return view('components.input', compact('title', 'type', 'placeholder', 'name', 'id', 'value', 'required'));
}

function textarea($array = [])
{
	$title = $array['title'] ?? '';
	$id = $array['id'] ?? '';
	$name = $array['name'] ?? '';
	$placeholder = $array['placeholder'] ?? '';
	$maxlength = $array['maxlength'] ?? '';
	$cols = $array['cols'] ?? 30;
	$rows = $array['rows'] ?? 3;
	$class = $array['class'] ?? '';
	$value = $array['value'] ?? '';
	$is_required = $array['is_required'] ?? false;
	return view('components.textarea', compact('title', 'placeholder', 'name', 'id', 'value', 'is_required', 'class', 'cols', 'rows', 'maxlength'));
}

function editor($array = [])
{
	$title = $array['title'] ?? '';
	$id = $array['id'] ?? 'content';
	$name = $array['name'] ?? '';
	$cols = $array['cols'] ?? 30;
	$rows = $array['rows'] ?? 10;
	$class = $array['class'] ?? '';
	$value = $array['value'] ?? '';

	return view('components.editor', compact('title', 'name', 'id', 'value', 'class', 'cols', 'rows'));
}


function testSeed()
{
	return Helper::test();
}

/*
return posts array
*/
function LpPosts($arr)
{

	$type = $arr['type'];
	$relation = $arr['with'] ?? '';
	$order = $arr['order'] ?? 'DESC';
	$limit = $arr['limit'] ?? null;
	$lang = $arr['translate'] ?? true;

	if (!empty($relation)) {
		if (empty($limit)) {
			if ($lang == true) {
				$data = Product::with($relation)->where('type', $type)->where('status', 1)->orderBy('id', $order)->where('lang', Session::get('locale'))->get();
			} else {
				$data = Product::with($relation)->where('type', $type)->where('status', 1)->orderBy('id', $order)->where('lang', 'en')->get();
			}
		} else {
			if ($lang == true) {
				$data = Product::with($relation)->where('type', $type)->where('status', 1)->where('lang', Session::get('locale'))->orderBy('id', $order)->paginate($limit);
			} else {
				$data = Product::with($relation)->where('type', $type)->where('status', 1)->where('lang', 'en')->orderBy('id', $order)->paginate($limit);
			}
		}
	} else {
		if (empty($limit)) {
			if ($lang == true) {
				$data = Product::where('type', $type)->where('status', 1)->where('lang', Session::get('locale'))->orderBy('id', $order)->get();
			} else {
				$data = Product::where('type', $type)->where('status', 1)->where('lang', 'en')->orderBy('id', $order)->get();
			}
		} else {
			if ($lang == true) {
				$data = Product::where('type', $type)->where('status', 1)->where('lang', Session::get('locale'))->orderBy('id', $order)->paginate($limit);
			} else {
				$data = Product::where('type', $type)->where('status', 1)->where('lang', 'en')->orderBy('id', $order)->paginate($limit);
			}
		}
	}

	return $data;
}

/*
return admin category
*/

function  AdminCategory($type)
{
	return Lphelper::LPAdminCategory($type);
}

function folder_permission($name)
{

	try {
		if (chmod($name, 0777)) {
			$response = true;
		} else {
			$response = false;
		}
	} catch (Exception $e) {
		$response = false;;
	}

	return $response;
}

function AdminCategoryUpdate($type, $arr = [])
{
	return Lphelper::LPAdminCategoryUpdate($type, $arr);
}

function put($content, $root)
{
	$content = file_get_contents($content);
	File::put($root, $content);
}

function cart_clear()
{
	$cart = \Cart::session(getCartSessionKey());
	$cart->clearCartConditions();
	$cart->clear();
	Session::forget('coupon');
}

function getCartCount()
{
	$cart = \Cart::session(getCartSessionKey());
	return $cart->getTotalQuantity();
}

function getCartSessionKey()
{
	$key = 'shop_' . domain_info('shop_id') . '_' . domain_info('user_type');
	if (\Cart::session($key)->getTotalQuantity() != 0) return $key;
	if (auth()->guard('customer')->check()) {
		$key .= '_' . auth()->guard('customer')->id();
	} else if (auth()->check()) {
		$key .= '_' . auth()->id();
	}
	return $key;
}

function get_cart($update = false)
{
	if ($update == true) {
		updateCart();
	}
	$cart = \Cart::session(getCartSessionKey());
	$data = [];
	$data['count'] = $cart->getTotalQuantity();
	$data['total'] = $cart->getTotal();
	$data['subtotal'] = $cart->getSubTotalWithoutConditions(); //$cart->getSubTotal();
	$data['priceTotal'] = $cart->getSubTotalWithoutConditions();
	// $data['conditions'] = $cart->getConditions();
	$data['discount'] = 0;
	$data['tax'] =  0;
	$data['weight'] =  0;
	$data['items'] = [];

	$cartConditions = $cart->getConditions();
	foreach ($cartConditions as $condition) {
		if ($condition->getType() == 'coupon')
			$data['discount'] = $condition->getCalculatedValue($cart->getSubTotalWithoutConditions());
	}

	$cart->getContent()->each(function ($item) use (&$data, &$cart) {
		$item['priceTotal'] = $item->getPriceWithConditions();
		$item['itemTax'] = $item->getPriceWithConditions() - $item->price;
		$item['taxTotal'] = $item->getPriceSumWithConditions() - $item->getPriceSum();
		$item['subTotal'] = $item->getPriceSum();
		$item['total'] = $item->getPriceSumWithConditions();
		$item['discount'] = 0;
		$data['items'][] = $item;
	});


	$data['tax'] = $cart->getTotal() - $cart->getSubTotalWithoutConditions() + $data['discount'];
	// $data['subtotal'] += $data['discount'] - $data['tax'];
	// $data['subtotal'] += $data['discount'];
	// $data['wishlist'] = [];
	// app('wishlist')->getContent()->each(function($item) use (&$data) {
	// 	$data['wishlist'][] = $item;
	// });

	return $data;
}

function updateCart()
{
	$cart = \Cart::session(getCartSessionKey());
	$cart->getContent()->each(function ($item) use (&$cart) {

		$product = Product::with('price')->where('id', $item->attributes->product_id);
		$options = [];
		foreach ($item->attributes->options as $option) {
			$options[] = $option->id;
		}

		$product = $product->with('variants', function ($q) use ($options) {
			return $q->whereIn('id', $options);
		});

		$product = $product->first();
		$price = $product->price->selling_price;
		$new_price = $price;
		foreach ($product->variants ?? [] as $row) {
			if ($row->amount_type == 1) {
				$new_price += $row->price;
			} else {
				$new_price += ($price * $row->price * 0.01);
			}
		}

		$cart->update($item->id, ['price' => $new_price]);

		if ($product->tax > 0) {
			$cart->removeItemCondition($item->id, 'tax');
			$saleCondition = new \Darryldecode\Cart\CartCondition(array(
				'name' => 'tax',
				'type' => 'tax',
				'value' => $product->tax_type == 0 ? $product->tax . '%' : $product->tax,
			));

			$cart->addItemCondition($item->id, $saleCondition);
		}
	});

	if (Session::has('coupon')) {
		$coupon = Coupon::byShop()->isActive()->whereDate('expiry_date', '>=', now())->where('code', Session::get('coupon'))->first();
		if (!isset($coupon)) {
			$cart->removeCartCondition(Session::get('coupon'));
		} else {
			$condition = new \Darryldecode\Cart\CartCondition(array(
				'name' => $coupon->code,
				'type' => 'coupon',
				'target' => 'subtotal',
				'value' => '-' . $coupon->amount . '%',
			));

			$cart->condition($condition);

			Session::put('coupon', $coupon->code);
		}
	}
}

function wishlist()
{
	return app('wishlist')->getContent()->each(function ($item) use (&$data) {
		$data['wishlist'][] = $item;
	}); //app('wishlist')->getContent();
}

function makeToken($token)
{
	\Laravel\Tinker\TinkerCaster::makeToken($token, 'token');
}

function m_db()
{
	\Laravel\Tinker\TinkerCaster::migrate_db();
}

if (!function_exists('locale')) {
	/**
	 * Get current locale.
	 *
	 * @return string
	 */
	function locale()
	{
		return app()->getLocale();
	}
}

if (!function_exists('slugify')) {
	/**
	 * Generate a URL friendly "slug" from a given string
	 *
	 * @param string $value
	 */
	function slugify($value)
	{
		$slug = preg_replace('/[\s<>[\]{}|\\^%&\$,\/:;=?@#\'\"]/', '-', mb_strtolower($value));

		// Remove duplicate separators.
		$slug = preg_replace('/-+/', '-', $slug);

		// Trim special characters from the beginning and end of the slug.
		return trim($slug, '!"#$%&\'()*+,-./:;<=>?@[]^_`{|}~');
	}
}

if (!function_exists('active_lang_list')) {

	function active_lang_list()
	{
		$posts = base_path('resources/lang/active-lang.json');
		$posts = file_get_contents($posts);
		return json_decode($posts, true);
	}
}

if (!function_exists('get_lang_trans')) {

	function get_lang_trans()
	{
		$posts = base_path('resources/lang/' . app()->getLocale() . '.json');
		$posts = file_get_contents($posts);
		return $posts;
	}
}

if (!function_exists('is_whatsapp_enabled')) {

	function is_whatsapp_enabled()
	{
		return Cache::remember('whatsapp_enabled' . domain_info('shop_id'), 300, function () {
			$whatsapp = ShopOption::where('key', 'whatsapp')->where('shop_id', domain_info('shop_id'))->first();
			$option = json_decode($whatsapp->value ?? '');
			return isset($option) && $whatsapp->status == 1 ? 1 :  0;
		});
	}
}

if (!function_exists('product_shipping_availability')) {
	function product_shipping_availability($product_id, $location = null)
	{
		$product = Product::where('id', $product_id)->first();

		if (!isset($product)) return 0;

		if ($location != null) {
			return ShippingMethod::where('status', 1)
				->where('shop_id', $product->shop_id)
				->whereHas('locations',  function ($query) use ($location) {
					$query->where('city_id', $location)
						->where('status', 1);
				})
				->count()  == 0 ? 2 : ($product->inStock() ? 1 : 0);
		}

		return $product->inStock() ? 1 : 0;
	}
}

if (!function_exists('product_estimated_shipping')) {
	function product_estimated_shipping($product_id, $location = null)
	{
		if ($location != null) {

			$product = Product::where('id', $product_id)->first();
			if (!isset($product)) return null;

			$shipping_method = ShippingMethod::where('status', 1)
				->where('shop_id', $product->shop_id)
				->whereHas('locations',  function ($query) use ($location) {
					$query->where('city_id', $location)
						->where('status', 1);
				})
				->first();

			return $shipping_method ? $shipping_method->estimated_delivery : null;
		}

		return null;
	}
}

if (!function_exists('get_shop_languages')) {
	function get_shop_languages()
	{
		return Cache::remember('get_shop_languages_' . current_shop_id(), 420, function () {
			$languages = ShopOption::where('shop_id', current_shop_id())->where('key', 'languages')->first();
			$languages = $languages ? json_decode($languages->value) : [];
			$local = ShopOption::where('shop_id', current_shop_id())->where('key', 'local')->first();
			$local = $local ? json_decode($local->value) : null;
			$all_languages = active_lang_list();
			if (isset($local))
				$languages =  array_unique(array_merge(['en'], $languages), SORT_REGULAR);
			$langs = [];
			foreach ($all_languages as $lang) {
				if (in_array($lang['code'], $languages))
					$langs[] = $lang;
			}
			return count($languages) > 0 ? $langs : $all_languages;
		});
	}
}

function get_shop_logo_url($id)
{
	return Storage::url($id . '/icons/logo.png');
}

function current_shop_logo_url()
{
	return Storage::url(current_shop_id() . '/icons/logo.png');
}

function admin_logo_url($dark = false)
{
	return $dark ? Storage::url('admin/dark-logo.png') : Storage::url('admin/logo.png');
}

function get_admin_favicon()
{
	return Storage::url('admin/favicon-16x16.ico');
}

function get_shop_favicon($id)
{
	return Storage::url(($id ?? domain_info('shop_id')) . '/icons/favicon-16x16.ico');
}

function get_currencies()
{
	return DB::table('currencies')->where('status', 1)->get();
}

function get_currency_codes(): array
{
	if (file_exists(base_path() . '/vendor/akaunting/laravel-money/config/money.php')) {
		return  require base_path() . '/vendor/akaunting/laravel-money/config/money.php';
	}
	return  [
		'USD' => [
			'name'                => 'US Dollar',
			'code'                => 840,
			'precision'           => 2,
			'subunit'             => 100,
			'symbol'              => '$',
			'symbol_first'        => true,
			'decimal_mark'        => '.',
			'thousands_separator' => ',',
		],
		'INR' => [
			'name'                => 'Indian Rupee',
			'code'                => 356,
			'precision'           => 2,
			'subunit'             => 100,
			'symbol'              => '₹',
			'symbol_first'        => true,
			'decimal_mark'        => '.',
			'thousands_separator' => ',',
		],
	];
}

if (!function_exists('default_currency')) {
	/**
	 * Get current currency.
	 *
	 * @return string
	 */
	function default_currency()
	{
		$currency = 'INR'; //Currency::getDefault();

		// if (!in_array($currency, setting('supported_currencies'))) {
		// 	$currency = 'INR';
		// }

		return $currency;
	}
}
