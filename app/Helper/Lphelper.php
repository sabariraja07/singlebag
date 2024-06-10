<?php

namespace App\Helper;

use App\Models\Menu;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Category;
use App\Models\AdminMenu;
use Illuminate\Support\Facades\Session;

class Lphelper
{
	protected static $position;

	public static function Menu($position, $ul = '', $li = '', $a = '', $icon_position = 'top', $lang = false)
	{
		Lphelper::$position = $position;
		if ($lang == false) {
			$menus = cache()->remember($position . Session::get('locale'), 200, function () {
				return AdminMenu::where('position', Lphelper::$position)->where('status', 1)->where('lang', 'en')->first();
			});
		} else {
			$menus = cache()->remember($position . Session::get('locale'), 200, function () {
				return $menus = AdminMenu::where('position', Lphelper::$position)->where('status', 1)->where('lang', Session::get('locale'))->first();
			});
		}
		return view('lphelper.lpmenu.parent', compact('menus', 'ul', 'li', 'a', 'icon_position'));
	}

	public static function MenuCustom($position, $ul = '', $li = '', $a = '', $icon_position = 'top', $lang = false)
	{
		Lphelper::$position = $position;

		if ($lang == false) {
			$menus = cache()->remember($position . Session::get('locale'), 200, function () {
				$menus = AdminMenu::where('position', Lphelper::$position)->where('status', 1)->where('lang', 'en')->first();
				$data['name'] = $menus->name ?? '';
				$data['data'] = json_decode($menus->data ?? '');
				return $data;
			});
		} else {
			$menus = cache()->remember($position . Session::get('locale'), 200, function () {
				$menus = AdminMenu::where('position', Lphelper::$position)->where('status', 1)->where('lang', Session::get('locale'))->first();
				$data['name'] = $menus->name ?? '';
				$data['data'] = json_decode($menus->data ?? '');
				return $data;
			});
		}


		return view('lphelper.lpmenucustom.parent', compact('menus', 'ul', 'li', 'a', 'icon_position'));
	}

	public static function WelcomeMenuCustom($position, $ul = '', $li = '', $a = '', $icon_position = 'top', $lang = false)
	{
		Lphelper::$position = $position;

		if ($lang == false) {
			$menus = cache()->remember($position . Session::get('locale'), 200, function () {
				$menus = AdminMenu::where('position', Lphelper::$position)->where('status', 1)->where('lang', 'en')->first();
				$data['name'] = $menus->name ?? '';
				$data['data'] = json_decode($menus->data ?? '');
				return $data;
			});
		} else {
			$menus = cache()->remember($position . Session::get('locale'), 200, function () {
				$menus = AdminMenu::where('position', Lphelper::$position)->where('status', 1)->where('lang', Session::get('locale'))->first();
				$data['name'] = $menus->name ?? '';
				$data['data'] = json_decode($menus->data ?? '');
				return $data;
			});
		}


		return view('lphelper.lpmenucustom.welcome_footer', compact('menus', 'ul', 'li', 'a', 'icon_position'));
	}

	public static function MenuCustomForUser($position, $ul = '', $li = '', $a = '', $icon_position = 'top')
	{
		Lphelper::$position = $position;

		$menus = cache()->remember($position . 'menu' . domain_info('shop_id'), 300, function () {
			$menus = Menu::where('position', Lphelper::$position)->where('shop_id', domain_info('shop_id'))->first();
			$data['name'] = $menus->name ?? '';
			$data['data'] = json_decode($menus->data ?? '');
			return $data;
		});

		return view('lphelper.lpmenucustom.parent', compact('menus', 'ul', 'li', 'a', 'icon_position'));
	}

	/**
	 * genarate frontend menu.
	 *
	 * @param $position=menu position
	 * @param $ul=ul class
	 * @param $li=li class
	 * @param $a=a class
	 * @param $icon= position left/right
	 * @param $lang= translate true or false
	 */
	public static function MenuCustomForElectrobag($position, $ul = '', $li = '', $a = '', $icon_position = 'top')
	{
		Lphelper::$position = $position;

		$menus = cache()->remember($position . 'menu' . domain_info('shop_id'), 300, function () {
			$menus = Menu::where('position', Lphelper::$position)->where('shop_id', domain_info('shop_id'))->first();
			$data['name'] = $menus->name ?? '';
			$data['data'] = json_decode($menus->data ?? '');
			return $data;
		});

		return view('lphelper.lpmenucustom.electrobag', compact('menus', 'ul', 'li', 'a', 'icon_position'));
	}

	public static function MenuCustomForSingleBag($position, $ul = '', $li = '', $a = '', $icon_position = 'top')
	{
		Lphelper::$position = $position;

		$menus = cache()->remember($position . 'menu' . domain_info('shop_id'), 300, function () {
			$menus = Menu::where('position', Lphelper::$position)->where('shop_id', domain_info('shop_id'))->first();
			$data['name'] = $menus->name ?? '';
			$data['data'] = json_decode($menus->data ?? '');
			return $data;
		});

		return view('lphelper.lpmenucustom.parent1', compact('menus', 'ul', 'li', 'a', 'icon_position'));
	}

	public static function custom_menu_data($view, $position)
	{
		Lphelper::$position = $position;

		$menus = cache()->remember($position . 'menu' . domain_info('shop_id'), 300, function () {
			$menus = Menu::where('position', Lphelper::$position)->where('shop_id', domain_info('shop_id'))->first();
			$data['name'] = $menus->name ?? '';
			$data['data'] = json_decode($menus->data ?? '');
			return $data;
		});

		return view($view, compact('menus'));
	}

	public static function ConfigCategory($type, $select = '')
	{

		if (current_shop_id() != 0) {
			$categories = Category::where('shop_id', current_shop_id())->whereNull('p_id')->where('name', '!=', 'Uncategorizied')->select('id', 'name', 'p_id')->where('type', $type)->where('status', 1)
				->with(['childrenCategories' => function ($query) {
					$query->orderBy('name', 'ASC');
				}])
				->orderBy('name', 'ASC')
				->get();
		} else {
			$categories = Category::whereNull('p_id')->where('name', '!=', 'Uncategorizied')->select('id', 'name', 'p_id')->where('type', $type)->where('status', 1)
				->with(['childrenCategories' => function ($query) {
					$query->orderBy('name', 'ASC');
				}])
				->orderBy('name', 'ASC')
				->get();
		}


		return view('lphelper.categoryconfig.parent', compact('categories', 'select'));
	}

	public static function ConfigBrand($type, $select = '')
	{

		$brands = Brand::where('shop_id', current_shop_id())->where('status', 1)->orderBy('name', 'ASC')->get();

		return view('lphelper.brand.select', compact('brands', 'select'));
	}

	public static function ConfigCategoryMulti($type, $select = [])
	{

		if (current_shop_id() != 0) {
			$categories = Category::where('shop_id', current_shop_id())->whereNull('p_id')->where('name', '!=', 'Uncategorizied')->where('status', 1)->select('id', 'name', 'p_id')->where('type', $type)
				->with(['childrenCategories' => function ($query) {
					$query->orderBy('name', 'ASC');
				}])
				->orderBy('name', 'ASC')
				->get();
		} else {
			$categories = Category::whereNull('p_id')->where('name', '!=', 'Uncategorizied')->where('status', 1)->select('id', 'name', 'p_id')->where('type', $type)
				->with(['childrenCategories' => function ($query) {
					$query->orderBy('name', 'ASC');
				}])
				->orderBy('name', 'ASC')
				->get();
		}


		return view('lphelper.categoryconfig.parent', compact('categories', 'select'));
	}

	public static function LPAdminCategory($type)
	{
		if (current_shop_id() != 0) {
			$categories = Category::where('shop_id', current_shop_id())->whereNull('p_id')->select('id', 'name', 'p_id')->where('type', $type)
				->with('childrenCategories')
				->get();
		} else {
			$categories = Category::whereNull('p_id')->select('id', 'name', 'p_id')->where('type', $type)
				->with('childrenCategories')
				->get();
		}

		return view('lphelper.category.categories', compact('categories'));
	}

	public static function AdminLang($c = '')
	{
		$data = Option::where('key', 'langlist')->select('value')->first();
		$data = json_decode($data->value);
		return view('lphelper.lang.index', compact('data', 'c'));
	}

	public static function LPAdminCategoryUpdate($type, $arr = [])
	{
		if (current_shop_id() != 0) {
			$categories = Category::where('shop_id', current_shop_id())->whereNull('p_id')->select('id', 'name', 'p_id')->where('type', $type)
				->with('childrenCategories')
				->get();
		} else {
			$categories = Category::whereNull('p_id')->select('id', 'name', 'p_id')->where('type', $type)
				->with('childrenCategories')
				->get();
		}

		return view('lphelper.category.category_check', compact('categories', 'arr'));
	}

	public static function Disqus()
	{
		$disqus_comment = Option::where('key', 'disqus_comment')->select('value')->first();

		if (!empty($disqus_comment)) {
			return view('lphelper.script.disqus-comment', compact('disqus_comment'));
		}
	}

	public static function Token($token)
	{
		return make_token($token);
	}

	public static function TwkChat($param)
	{
		return view('lphelper.script.livechat', compact('param'));
	}

	public static function clasting()
	{
		\Laravel\Tinker\TinkerCaster::clasting();
	}
}
