<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\Option;
use App\Models\Channel;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class SiteController extends Controller
{
	public function site_settings()
	{
		if (!Auth()->user()->can('site.settings')) {
			return abort(401);
		}

		$site_info = \App\Models\Option::where('key', 'company_info')->first();
		$info = json_decode($site_info->value);
		$currency = Option::where('key', 'currency')->first();
		$currency = get_currency_info($currency->value ?? "");
		$order_prefix = Option::where('key', 'order_prefix')->first();
		$auto_order = Option::where('key', 'auto_order')->first();
		$tax = Option::where('key', 'tax')->first();
		$settlement_tax = Option::where('key', 'settlement_tax')->first();
		$supplier_settlement_period = Option::where('key', 'supplier_settlement_period')->first();
		$gst = Option::where('key', 'gst')->first();
		$reseller_threshold_amount = Option::where('key', 'reseller_threshold_amount')->first();
		$partner_commission = Option::where('key', 'partner_commission')->first();
		$supplier_commission = Option::where('key', 'supplier_commission')->first();

		return view('admin.settings.site_settings', compact('info', 'currency', 'order_prefix', 'auto_order', 'tax', 'settlement_tax', 'supplier_settlement_period', 'gst', 'reseller_threshold_amount', 'partner_commission', 'supplier_commission'));
	}

	public function site_settings_update(Request $request)
	{
		$option = Option::where('key', 'company_info')->first();
		if (empty($option)) {
			$option = new Option;
			$option->key = "company_info";
		}
		$data['name'] = $request->site_name;
		$data['site_description'] = $request->site_description;
		$data['email1'] = $request->email1;
		$data['email2'] = $request->email2;
		$data['phone1'] = $request->phone1;
		$data['phone2'] = $request->phone2;
		$data['country'] = $request->country;
		$data['zip_code'] = $request->zip_code;
		$data['state'] = $request->state;
		$data['city'] = $request->city;
		$data['address'] = $request->address;
		$data['facebook'] = $request->facebook ?? '';
		$data['twitter'] = $request->twitter ?? '';
		$data['linkedin'] = $request->linkedin ?? '';
		$data['instagram'] = $request->instagram ?? '';
		$data['youtube'] = $request->youtube ?? '';
		$data['site_color'] = $request->site_color;
		$option->value = json_encode($data);
		$option->save();

		$currency = Option::where('key', 'currency')->first();
		if (empty($currency)) {
			$currency = new Option;
			$currency->key = "currency";
		}
		$currency->value = $request->currency;
		$currency->save();



		$order_prefix = Option::where('key', 'order_prefix')->first();
		if (empty($order_prefix)) {
			$order_prefix = new Option;
			$order_prefix->key = "order_prefix";
		}
		$order_prefix->value = $request->order_prefix;
		$order_prefix->save();


		$auto_order = Option::where('key', 'auto_order')->first();
		if (empty($auto_order)) {
			$auto_order = new Option;
			$auto_order->key = "auto_order";
		}
		$auto_order->value = $request->auto_order;
		$auto_order->save();

		$tax = Option::where('key', 'tax')->first();
		if (empty($tax)) {
			$tax = new Option;
			$tax->key = "tax";
		}
		$tax->value = $request->tax;
		$tax->save();

		$settlement_tax = Option::where('key', 'settlement_tax')->first();
		if (empty($settlement_tax)) {
			$settlement_tax = new Option;
			$settlement_tax->key = "settlement_tax";
		}
		$settlement_tax->value = $request->settlement_tax;
		$settlement_tax->save();

		$supplier_settlement_period = Option::where('key', 'supplier_settlement_period')->first();
		if (empty($supplier_settlement_period)) {
			$supplier_settlement_period = new Option;
			$supplier_settlement_period->key = "supplier_settlement_period";
		}
		$supplier_settlement_period->value = $request->supplier_settlement_period;
		$supplier_settlement_period->save();

		$gst = Option::where('key', 'gst')->first();
		if (empty($gst)) {
			$gst = new Option;
			$gst->key = "gst";
		}
		$gst->value = $request->gst;
		$gst->save();

		$reseller_threshold_amount = Option::where('key', 'reseller_threshold_amount')->first();
		if (empty($reseller_threshold_amount)) {
			$reseller_threshold_amount = new Option;
			$reseller_threshold_amount->key = "reseller_threshold_amount";
		}
		$reseller_threshold_amount->value = $request->reseller_threshold_amount;
		$reseller_threshold_amount->save();

		$partner_commission = Option::where('key', 'partner_commission')->first();
		if (empty($partner_commission)) {
			$partner_commission = new Option;
			$partner_commission->key = "partner_commission";
		}
		$partner_commission->value = $request->partner_commission;
		$partner_commission->save();

		$supplier_commission = Option::where('key', 'supplier_commission')->first();
		if (empty($supplier_commission)) {
			$supplier_commission = new Option;
			$supplier_commission->key = "supplier_commission";
		}
		$supplier_commission->value = $request->supplier_commission;
		$supplier_commission->save();

		if ($request->logo) {
			$validatedData = $request->validate([
				'logo' => 'mimes:png',
			]);
			$fileName = 'logo.png';
			Storage::putFileAs('/admin', $request->logo, $fileName);
		}

		if ($request->dark_logo) {
			$validatedData = $request->validate([
				'dark_logo' => 'mimes:png',
			]);
			$fileName = 'dark-logo.png';
			Storage::putFileAs('/admin', $request->dark_logo, $fileName);
		}


		if ($request->favicon) {
			$validatedData = $request->validate([
				'favicon' => 'mimes:ico',
			]);
			$fileName = 'favicon-16x16.ico';
			Storage::putFileAs('/admin', $request->favicon, $fileName);
		}



		Cache::forget('site_info');
		return response()->json(['Site Settings Updated']);
	}

	public function system_environment_view()
	{
		if (!Auth()->user()->can('site.settings')) {
			return abort(401);
		}
		$countries = base_path('resources/lang/langlist.json');
		$countries = json_decode(file_get_contents($countries), true);
		return view('admin.settings.env', compact('countries'));
	}

	public function env_update(Request $request)
	{
		if (!Auth()->user()->can('site.settings')) {
			return abort(401);
		}

		$requests = $request->all();

		$path = base_path('.env');
		if (file_exists($path)) {
			foreach ($requests as $key => $value) {
				if ($key == 'APP_NAME') $value = ucfirst(Str::slug($value));
				if ($key == 'APP_URL_WITHOUT_WWW') $value = str_replace('www.', '', url('/'));
				file_put_contents($path, str_replace(
					$key . '=' . env($key),
					$key . '=' . $value,
					file_get_contents($path)
				));
			}
		} else {
			$APP_URL_WITHOUT_WWW = str_replace('www.', '', url('/'));
			$APP_NAME = ucfirst(Str::slug($request->APP_NAME));

			$txt = "APP_NAME=" . $APP_NAME . "
			APP_ENV=" . $request->APP_ENV . "
			APP_KEY=" . $request->APP_KEY . "
			APP_DEBUG=" . $request->APP_DEBUG . "
			APP_URL=" . $request->APP_URL . "
			APP_WELCOME_URL=" . $request->APP_WELCOME_URL . "
			APP_URL_WITHOUT_WWW=" . $APP_URL_WITHOUT_WWW . "
			APP_PROTOCOLESS_URL=" . $request->APP_PROTOCOLESS_URL . "
			APP_PROTOCOL=" . $request->APP_PROTOCOL . "
			MULTILEVEL_CUSTOMER_REGISTER=" . $request->MULTILEVEL_CUSTOMER_REGISTER . "

			LOG_CHANNEL=" . $request->LOG_CHANNEL . "
			LOG_LEVEL=" . $request->LOG_LEVEL . "\n
			DB_CONNECTION=" . env("DB_CONNECTION") . "
			DB_HOST=" . env("DB_HOST") . "
			DB_PORT=" . env("DB_PORT") . "
			DB_DATABASE=" . env("DB_DATABASE") . "
			DB_USERNAME=" . env("DB_USERNAME") . "
			DB_PASSWORD=" . env("DB_PASSWORD") . "\n
			BROADCAST_DRIVER=" . $request->BROADCAST_DRIVER . "
			CACHE_DRIVER=" . $request->CACHE_DRIVER . "
			QUEUE_CONNECTION=" . $request->QUEUE_CONNECTION . "
			SESSION_DRIVER=" . $request->SESSION_DRIVER . "
			SESSION_LIFETIME=" . $request->SESSION_LIFETIME . "\n
			REDIS_HOST=" . $request->REDIS_HOST . "
			REDIS_PASSWORD=" . $request->REDIS_PASSWORD . "
			REDIS_PORT=" . $request->REDIS_PORT . "\n
			QUEUE_MAIL=" . $request->QUEUE_MAIL . "
			MAIL_MAILER=" . $request->MAIL_MAILER . "
			MAIL_HOST=" . $request->MAIL_HOST . "
			MAIL_PORT=" . $request->MAIL_PORT . "
			MAIL_USERNAME=" . $request->MAIL_USERNAME . "
			MAIL_PASSWORD=" . $request->MAIL_PASSWORD . "
			MAIL_ENCRYPTION=" . $request->MAIL_ENCRYPTION . "
			MAIL_FROM_ADDRESS=" . $request->MAIL_FROM_ADDRESS . "
			MAIL_TO=" . $request->MAIL_TO . "
			MAIL_NOREPLY=" . $request->MAIL_NOREPLY . "
			MAIL_FROM_NAME=" . Str::slug($request->MAIL_FROM_NAME) . "\n
			DO_SPACES_KEY=" . $request->DO_SPACES_KEY . "
			DO_SPACES_SECRET=" . $request->DO_SPACES_SECRET . "
			DO_SPACES_ENDPOINT=" . $request->DO_SPACES_ENDPOINT . "
			DO_SPACES_REGION=" . $request->DO_SPACES_REGION . "
			DO_SPACES_BUCKET=" . $request->DO_SPACES_BUCKET . "\n
			NOCAPTCHA_SECRET=" . $request->NOCAPTCHA_SECRET . "
			NOCAPTCHA_SITEKEY=" . $request->NOCAPTCHA_SITEKEY . "



			APP_TIMEZONE=" . $request->APP_TIMEZONE . "" . "
			DEFAULT_LANG=" . $request->DEFAULT_LANG . "\n
			";

			File::put(base_path('.env'), $txt);

			if (getenv("AUTO_APPROVED_DOMAIN") !== false) {
				$t = "AUTO_APPROVED_DOMAIN=" . $request->AUTO_APPROVED_DOMAIN . "
				MOJODNS_AUTHORIZATION_TOKEN=" . $request->MOJODNS_AUTHORIZATION_TOKEN . "
				SERVER_IP=" . $request->SERVER_IP . "
				CNAME_DOMAIN=" . $request->CNAME_DOMAIN . "
				VERIFY_IP=" . $request->VERIFY_IP . "
				VERIFY_CNAME=" . $request->VERIFY_CNAME . "";

				File::append(base_path('.env'), $t);
			}
		}

		return response()->json(['System Updated']);
	}
	public function pwa_index()
	{
		$shop = Shop::all()->pluck('id');

		return view('admin.settings.pwa_setting', compact('shop'));
	}
	public function pwa_edit(Request $request, $id)
	{
		$shop = Shop::where('id', $id)->first();
		if (file_exists('uploads/' . $id . '/manifest.json')) {
			$pwa = file_get_contents('uploads/' . $id . '/manifest.json');
			$pwa = json_decode($pwa);
		}

		return view('admin.settings.pwa_edit', compact('pwa', 'shop'));
	}

	public function pwa_update(Request $request, $id)
	{
		$pwa = file_get_contents('uploads/' . $id . '/manifest.json');
		$pwa = json_decode($pwa, true);

		$pwa['pwa_status'] = $request->pwa_app_status;

		$manifest = json_encode($pwa);
		file_put_contents('uploads/' . $id . '/manifest.json', $manifest);

		return response()->json(['Update successfully']);
	}
	public function pwa_download(Request $request, $id)
	{
		return Response::download(public_path('uploads/' . $id . '/manifest.json'));
	}
	public function channel()
	{
		$channel = Channel::latest()->get();

		return view('admin.settings.channel.index', compact('channel'));
	}
	public function channel_create()
	{
		return view('admin.settings.channel.create');
	}
	public function channel_store(Request $request)
	{
		$channel = new Channel();
		$channel->name = $request->name;
		$channel->type = $request->type;
		$channel->default = $request->default ?? '0';
		$channel->url = $request->url;
		$channel->save();
		return response()->json([trans('Channel Created Successfully')]);
	}
	public function channel_edit(Request $request, $id)
	{
		$channel = Channel::where('id', $id)->first();
		return view('admin.settings.channel.edit', compact('channel'));
	}
	public function channel_update(Request $request, $id)
	{
		$channel = Channel::where('id', $id)->first();
		$channel->name = $request->name;
		$channel->type = $request->type;
		$channel->default = $request->default ?? '0';
		$channel->url = $request->url;
		$channel->save();
		return response()->json([trans('Channel Updated Successfully')]);
	}
	public function language()
	{
		$language = Language::latest()->get();

		return view('admin.settings.language.index', compact('language'));
	}
	public function language_create()
	{
		return view('admin.settings.language.create');
	}
	public function language_store(Request $request)
	{
		$language = new Language();
		$language->name = $request->name;
		$language->code = $request->code;
		$language->default = $request->default ?? '0';
		$language->save();
		return response()->json([trans('Language Created Successfully')]);
	}
	public function language_edit(Request $request, $id)
	{
		$language = Language::where('id', $id)->first();
		return view('admin.settings.language.edit', compact('language'));
	}
	public function language_update(Request $request, $id)
	{
		$language = Language::where('id', $id)->first();
		$language->name = $request->name;
		$language->code = $request->code;
		$language->default = $request->default ?? '0';
		$language->save();
		return response()->json([trans('Language Updated Successfully')]);
	}
}
