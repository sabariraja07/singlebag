<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Domain;
use App\Models\Subscription;
use App\Models\RequestDomain;
use App\Models\Option;
use App\Models\Shop;
use App\Models\ShopOption;
use Carbon\Carbon;
use Http;


class DomainController extends Controller
{
  public function index()
  {

    abort_if(getenv("AUTO_APPROVED_DOMAIN") == false, 404);

    $info = user_limit();
    $request = RequestDomain::where('shop_id', current_shop_id())->first();

    $dns = Option::where('key', 'instruction')->first();
    $dns = json_decode($dns->value ?? '');
    return view('seller.domain.config', compact('info', 'dns', 'request'));
  }

  public function store(Request $request)
  {
    $checkisvalid = $this->is_valid_domain_name($request->domain);
    if ($checkisvalid == false) {
      $error['errors']['domain'] = trans('Please enter valid domain....!!');
      return response()->json($error, 422);
    }


    $check_before = RequestDomain::where([['shop_id', current_shop_id()]])->first();
    if (!empty($check_before)) {
      $error['errors']['domain'] = trans('Oops you already customdomain created....!!');
      return response()->json($error, 422);
    }



    if (user_plan_access('custom_domain') == true) {
      $validatedData = $request->validate([
        'domain' => 'required|string|max:50',
      ]);

      $domain = strtolower($request->domain);
      $input = trim($domain, '/');
      if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
      }
      $urlParts = parse_url($input);
      $domain = preg_replace('/^www\./', '', $urlParts['host'] ?? $urlParts['path']);

      $checkArecord = $this->dnscheckRecordA($domain);
      $checkCNAMErecord = $this->dnscheckRecordCNAME($domain);
      if ($checkArecord != true) {
        $error['errors']['domain'] = trans('A record entered incorrectly.');
        return response()->json($error, 422);
      }

      if ($checkCNAMErecord != true) {
        $error['errors']['domain'] = trans('CNAME record entered incorrectly.');
        return response()->json($error, 422);
      }

      $check = Domain::where('domain', $domain)->first();
      if (!empty($check)) {
        $error['errors']['domain'] = trans('Oops domain name already taken....!!');
        return response()->json($error, 422);
      }
      $check = RequestDomain::where('domain', $domain)->first();
      if (!empty($check)) {
        $error['errors']['domain'] = trans('Oops domain name already requested....!!');
        return response()->json($error, 422);
      }

      $shop = Shop::with('domain')->findorFail(current_shop_id());

      $subdomain = new RequestDomain;
      $subdomain->domain = $domain;
      $subdomain->shop_id = $shop->id;
      $subdomain->status = 2;
      $subdomain->domain_id = $shop->domain->id;
      $subdomain->save();

      return response()->json(trans('Custom Domain Created Successfully...!!'));
    } else {

      $error['errors']['domain'] = trans('Sorry custom domain modules not support in your plan....!!');
      return response()->json($error, 422);
    }
    $error['errors']['domain'] = trans('Opps something wrong...!!');
    return response()->json($error, 422);
  }


  public function update(Request $request, $id)
  {
    $checkisvalid = $this->is_valid_domain_name($request->domain);
    if ($checkisvalid == false) {
      $error['errors']['domain'] = trans('Please enter valid domain....!!');
      return response()->json($error, 422);
    }

    if (user_plan_access('custom_domain') == true) {
      $validatedData = $request->validate([
        'domain' => 'required|string|max:50',
      ]);

      $domain = strtolower($request->domain);
      $input = trim($domain, '/');
      if (!preg_match('#^http(s)?://#', $input)) {
        $input = 'http://' . $input;
      }
      $urlParts = parse_url($input);
      $domain = preg_replace('/^www\./', '', $urlParts['host'] ?? $urlParts['path']);


      $check = RequestDomain::where('domain', $domain)->where('id', '!=', $id)->first();
      if (!empty($check)) {
        $error['errors']['domain'] = trans('Oops domain name already taken....!!');
        return response()->json($error, 422);
      }
      $check = RequestDomain::where('domain', $domain)->first();
      if (!empty($check)) {
        $error['errors']['domain'] = trans('Oops domain name already requested....!!');
        return response()->json($error, 422);
      }

      $custom_domain = RequestDomain::where('shop_id', current_shop_id())->findorFail($id);

      if ($custom_domain->domain != $domain) {
        $checkArecord = $this->dnscheckRecordA($domain);
        $checkCNAMErecord = $this->dnscheckRecordCNAME($domain);
        if ($checkArecord != true) {
          $error['errors']['domain'] = trans('A record entered incorrectly.');
          return response()->json($error, 422);
        }

        if ($checkCNAMErecord != true) {
          $error['errors']['domain'] = trans('CNAME record entered incorrectly.');
          return response()->json($error, 422);
        }
      }

      $custom_domain->domain = $domain;
      $custom_domain->status = 2;
      $custom_domain->save();

      return response()->json(trans('Custom Domain Request Updated Successfully...!!'));
    } else {
      $error['errors']['domain'] = trans('Sorry subdomain modules not support in your plan....!!');
      return response()->json($error, 422);
    }

    $error['errors']['domain'] = trans('Opps something wrong...!!');
    return response()->json($error, 422);
  }

  //check is valid domain name
  public function is_valid_domain_name($domain_name)
  {
    if (filter_var(gethostbyname($domain_name), FILTER_VALIDATE_IP)) {
      return TRUE;
    }
    return false;
  }

  //check A record
  public function dnscheckRecordA($domain)
  {
    if (env('MOJODNS_AUTHORIZATION_TOKEN') != null  && env('VERIFY_IP') == true) {
      try {
        $response = Http::withHeaders(['Authorization' => env('MOJODNS_AUTHORIZATION_TOKEN')])->acceptJson()->get('https://api.mojodns.com/api/dns/' . $domain . '/A');
        $ip = $response['answerResourceRecords'][0]['ipAddress'];

        if ($ip == env('SERVER_IP')) {
          $ip = true;
        } else {
          $ip = false;
        }
      } catch (\Exception $e) {
        $ip = false;
      }

      return $ip;
    }

    return true;
  }


  //check crecord name
  public function dnscheckRecordCNAME($domain)
  {
    if (env('MOJODNS_AUTHORIZATION_TOKEN') != null) {
      if (env('VERIFY_CNAME') === true) {
        try {
          $response = Http::withHeaders(['Authorization' => env('MOJODNS_AUTHORIZATION_TOKEN')])->acceptJson()->get('https://api.mojodns.com/api/dns/' . $domain . '/CNAME');
          if ($response->successful()) {
            $cname = $response['reportingNameServer'];

            if ($cname === env('CNAME_DOMAIN')) {
              $cname = true;
            } else {
              $cname = false;
            }
          } else {
            $cname = false;
          }
        } catch (\Exception $e) {
          $cname = false;
        }


        return $cname;
      }
    }

    return true;
  }

  public function shopmode(Request $request)
  {
    $domain = request()->getHost();
    $domain = str_replace('www.', '', $domain);

    $shop_mode_info = ShopOption::where('shop_id', $request->shop_id)->where('key', 'shop_mode')->first();
    $shop_mode_duration_info = ShopOption::where('shop_id', $request->shop_id)->where('key', 'shop_mode_duration')->first();
    
    if($shop_mode_duration_info) {
      $shop_mode_duration = ShopOption::where('shop_id', $request->shop_id)->where('key', 'shop_mode_duration')->update(["value" => NULL]);
    } else {
      $shop_mode_duration = new ShopOption();
      $shop_mode_duration->key = 'shop_mode_duration';
      $shop_mode_duration->value = NULL;
      $shop_mode_duration->shop_id = $request->shop_id;
      $shop_mode_duration->save();     
    }

    if($shop_mode_info) {
      if ($shop_mode_info->value != 'online') {
      $shop_mode_update = ShopOption::where('shop_id', $request->shop_id)->where('key', 'shop_mode')->update(["value" => $request->shop_mode]);
      Cache::forget($domain);

      if($request->shop_mode == 'online') {
        return response()->json(['success'=>'Successfully updated shop status.']); 
      }
    }
    }
    else {
      $shop_mode = new ShopOption();
      $shop_mode->key = 'shop_mode';
      $shop_mode->value = $request->shop_mode;
      $shop_mode->shop_id = $request->shop_id;
      $shop_mode->save();
      Cache::forget($domain);

    if($request->shop_mode == 'online') {
      return response()->json(['success'=>'Successfully updated shop status.']);  
    }
    }
  }
  public function shopmodeduration(Request $request) {

    $domain = request()->getHost();
    $domain = str_replace('www.', '', $domain);
    
    $shop_mode_info = ShopOption::where('shop_id', current_shop_id())->where('key', 'shop_mode')->first();
    if($shop_mode_info) {
      $shop_mode_update = ShopOption::where('shop_id', current_shop_id())->where('key', 'shop_mode')->update(["value" => 'offline']);
      Cache::forget($domain);
    }
    else {
      $shop_mode = new ShopOption();
      $shop_mode->key = 'shop_mode';
      $shop_mode->value = 'offline';
      $shop_mode->shop_id = current_shop_id();
      $shop_mode->save();
      Cache::forget($domain);
    }
    if($request->shop_mode_duration != "") {
      $shop_mode_duration_time = Carbon::now()->addHours($request->shop_mode_duration); 
    }
    else {
      $shop_mode_duration_time = NULL;
    }
    $shop_mode_duration_info = ShopOption::where('shop_id', current_shop_id())->where('key', 'shop_mode_duration')->first();
    if($shop_mode_duration_info) {
      $shop_mode_duration = ShopOption::where('shop_id', current_shop_id())->where('key', 'shop_mode_duration')->update(["value" => $shop_mode_duration_time]);
      return response()->json(['success'=>'Successfully updated shop status.']);
    }else{
      $shop_mode_duration = new ShopOption();
      $shop_mode_duration->key = 'shop_mode_duration';
      $shop_mode_duration->value = $shop_mode_duration_time;
      $shop_mode_duration->shop_id = current_shop_id();
      $shop_mode_duration->save();
      return response()->json(['success'=>'Successfully updated shop status.']);
    }
  }
}
