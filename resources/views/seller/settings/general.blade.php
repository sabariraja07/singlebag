  @extends('layouts.seller')
  @section('title', 'Settings')
  @section('page-style')
    <style>
        @media screen and (max-width: 900px) {
            .save_mobile_view {
                display: block;
                margin: auto;
                text-align: center;
            }
        }
        @media screen and (min-width: 992px) {
            .save_desktop_view{
                float: right;
            }
            
        }
    </style>
  @endsection
  @section('content')
      <!-- BEGIN: Content-->
          <div class="content-wrapper container-xxl p-0">
              <div class="content-header row">
                  @if (Session::has('success'))
                      <div class="alert alert-success">
                          <ul style="margin-top: 1rem;">
                              <li>{{ Session::get('success') }}</li>
                          </ul>
                      </div>
                  @endif
                  @if ($errors->any())
                  @php
                  $page_type = "pwa";
                  @endphp
                      <div class="alert alert-danger">
                          <ul style="margin-top: 1rem;">
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
                  <div class="content-header-left col-md-9 col-12 mb-2">
                      <div class="row breadcrumbs-top">
                          <div class="col-12">
                              <h2 class="content-header-title float-start mb-0">{{ __('Store Settings') }}</h2>
                              <div class="breadcrumb-wrapper">
                                  <ol class="breadcrumb">
                                      <li class="breadcrumb-item"><a
                                              href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                      </li>
                                      <li class="breadcrumb-item">{{ __('Settings') }}
                                      </li>
                                      <li class="breadcrumb-item active">{{ __('Store Settings') }}
                                      </li>
                                  </ol>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="content-body">
                  <div class="row">
                      <!-- User Sidebar -->
                      <div class="col-xl-3 col-lg-5 col-md-5 order-1 order-md-0">
                          <!-- User Card -->
                          <div class="card">
                              <div class="card-body">
                                  <div class="user-avatar-section">
                                      <div class="d-flex align-items-center flex-column">
                                          <img src="{{ current_shop_logo_url() }}" class="img-fluid rounded mt-3 mb-2"
                                              style="border-radius: 10% !important;" onerror="this.src='./images/shop1.png'"
                                              alt="Avatar" width="110" height="110">
                                          <div class="user-info text-center">
                                              <h4></h4>
                                              <span class="badge bg-light-secondary">{{ __('Shop') }}</span>
                                          </div>
                                      </div>
                                  </div><br>
                                  <h4 class="fw-bolder border-bottom pb-50 mb-1">{{ __('Shop Details') }}</h4>
                                  <div class="info-container">
                                      <ul class="list-unstyled">
                                          <li class="mb-75">
                                              <span class="fw-bolder me-25">{{ __('Store Name') }}:</span>
                                              <span>{{ $shop_name->value ?? '' }}</span>
                                          </li>
                                          <li class="mb-75">
                                              <span class="fw-bolder me-25">{{ __('Email') }}:</span>
                                              <span>{{ $store_email->value ?? '' }}</span>
                                          </li>
                                          <li class="mb-75">
                                              <span class="fw-bolder me-25">{{ __('Mobile Number') }}:</span>
                                              <span>{{ $store_mobile_number->value ?? '' }}</span>
                                          </li>
                                          <li class="mb-75">
                                              <span class="fw-bolder me-25">{{ __('Address') }}:</span>
                                              @if ($location)
                                                  <span>{{ ucfirst($location->address) ?? '' }},
                                                      {{ ucfirst($location->city) ?? '' }},
                                                      {{ ucfirst($location->state) ?? '' }},
                                                      {{ $location->zip_code ?? '' }}</span>
                                              @endif
                                          </li>
                                      </ul>

                                  </div>
                              </div>
                          </div>
                          <!-- /User Card -->
                          <!-- Plan Card -->
                          <div class="card border-primary">
                              <div class="card-body">
                                  <div class="align-items-start">
                                      <span
                                          class="badge bg-light-primary text-wrap">{{ ucfirst($data['plan_name']) }}</span>
                                      &nbsp;&nbsp;
                                      @if (current_shop_type() != 'supplier')
                                          <div class="d-flex justify-content-center">
                                              <sup
                                                  class="h5 pricing-currency text-primary mt-1 mb-0">{{ $currency->symbol ?? '' }}</sup>
                                              <span class="fw-bolder mb-0 text-primary"
                                                  style=" font-size: 25px; ">{{ $data['amount'] }}</span>
                                              <!-- <sub class="pricing-duration font-small-4 ms-25 mt-auto mb-2">/ {{ $data['duration'] }} Days</sub> -->
                                          </div>
                                      @endif
                                  </div><br>
                                  <ul class="ps-1 mb-2">
                                      <li class="mb-50">
                                          @if ($data['product_limit'] == -1)
                                          {{ __('Unlimited') }} 
                                          @else
                                              {{ $data['product_limit'] }}
                                          @endif  {{ __('Products') }}
                                      </li>
                                      <li class="mb-50">
                                          @if ($data['customer_limit'] == -1)
                                          {{ __('Unlimited') }} 
                                          @else
                                              {{ $data['customer_limit'] }}
                                          @endif  {{ __('Customers') }}
                                      </li>
                                      <li class="mb-50">
                                          @if ($data['storage'] == -1)
                                          {{ __('Unlimited') }}
                                          @else
                                          {{ __('Up to') }}  {{ $data['storage'] }} MB
                                          @endif  {{ __('storage') }}
                                      </li>
                                  </ul>
                                  @if (current_shop_type() != 'supplier')
                                      <div>
                                          <div class="d-flex justify-content-between align-items-center fw-bolder mb-50">
                                              <span>{{ __('Days') }}</span>
                                              <span>{{ $data['duration'] -\Carbon\Carbon::parse(current_shop()->will_expire)->endOfDay()->diffInDays() }}
                                                  of {{ $data['duration'] }} {{ __('Days') }}</span>
                                          </div>
                                          <div class="progress mb-50" style="height: 8px">
                                              <div class="progress-bar" role="progressbar" style="width: 80%"
                                                  aria-valuenow="65" aria-valuemax="100" aria-valuemin="80"></div>
                                          </div>
                                          <span>{{ \Carbon\Carbon::parse(current_shop()->will_expire)->endOfDay()->diffInDays() }}
                                            {{ __('days remaining') }}</span>
                                      </div>
                                      <div class="d-grid w-100 mt-2">
                                          <a href="{{ route('seller.settings.show', 'plan') }}">
                                              <button class="btn btn-primary save_mobile_view">{{ __('Upgrade Plan') }}</button>
                                          </a>
                                      </div>
                                  @endif
                              </div>
                          </div>
                          <!-- /Plan Card -->
                      </div>
                      <!--/ User Sidebar -->
                      {{-- @php
                      if($page_type == 'general'){
                        $page_type = 'pwa';
                      }
                      @endphp --}}
                      <div class="col-xl-9 col-lg-7 col-md-7 order-0 order-md-1">
                          <nav>
                              <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                  <a class="nav-item nav-link {{ $page_type == 'general' ? 'active' : 'unactive' }}"
                                      id="nav-home-tab" data-toggle="tab" href="#nav-general" role="tab"
                                      aria-controls="nav-home" aria-selected="true">{{ __('General') }}</a>
                                  <a class="nav-item nav-link {{ $page_type == 'location' ? 'active' : 'unactive' }}" id="nav-profile-tab" data-toggle="tab" href="#nav-location"
                                      role="tab" aria-controls="nav-profile"
                                      aria-selected="false">{{ __('Location') }}</a>
                                  @if (current_shop_type() != 'supplier')
                                      <a class="nav-item nav-link {{ $page_type == 'pwa' ? 'active' : 'unactive' }}" id="nav-contact-tab" data-toggle="tab" href="#nav-pwa"
                                          role="tab" aria-controls="nav-contact"
                                          aria-selected="false">{{ __('PWA Settings') }}</a>
                                  @endif
                                  <a class="nav-item nav-link {{ $page_type == 'logo' ? 'active' : 'unactive' }}"
                                      id="nav-others-tab" data-toggle="tab" href="#nav-others" role="tab"
                                      aria-controls="nav-others" aria-selected="false">{{ __('Others') }}</a>
                              </div>
                          </nav>
                          <div class="card tab-content py-3 px-3 px-sm-0" id="nav-tabContent"
                              style="padding: 15px !important;">
                              <div class="tab-pane fade {{ $page_type == 'general' ? 'active show' : 'unactive unshow' }}"
                                  id="nav-general" role="tabpanel" aria-labelledby="nav-home-tab">
                                  <form method="post" action="{{ route('seller.settings.store') }}" class="basicform">
                                      @csrf
                                      <div class="row">
                                          <div class="col-md-12 mb-1">
                                              <label class="form-label" for="username">{{ __('Store Name') }}</label>
                                              <input type="hidden" name="type" value="general">
                                              <input type="text" name="shop_name" id="shop_name" class="form-control"
                                                  required="" minlength="4" maxlength="15"
                                                  value="{{ $shop_name->value ?? '' }}">
                                          </div>
                                      </div>
                                      @if (current_shop_type() != 'supplier')
                                          <div class="row">
                                              <div class="col-12 mb-1">
                                                  <label class="form-label"
                                                      for="shop_description">{{ __('Store Description') }}</label>
                                                  <textarea class="form-control" required="" name="shop_description">{{ $shop_description->value ?? '' }}</textarea>
                                              </div>
                                          </div>
                                      @endif
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="store_email">{{ __('Notification & Reply-to Email') }}</label>
                                              <input type="email" name="store_email" class="form-control"
                                                  required="" placeholder="reply@example.com"
                                                  value="{{ $store_email->value ?? '' }}">
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label" for="username">{{ __('Mobile Number') }}</label>
                                              <input type="text" name="store_mobile_number" class="form-control"
                                                  required="" placeholder="9876543210"
                                                  value="{{ $store_mobile_number->value ?? '' }}" maxlength="10"
                                                  minlength="10">
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="username">{{ __('I will sale (shop type)') }}</label>
                                              @php
                                                  $shop = \App\Models\Shop::where('user_id', Auth::id())->first();
                                                  $shop_type = $shop->shop_type ?? null;
                                              @endphp
                                              <select class="form-control" name="shop_type" disabled>
                                                  <option value="normal"
                                                      @if ($shop_type == 'normal') selected="selected" @endif>
                                                      {{ __('Seller') }}</option>
                                                  <option value="reseller" disabled
                                                      @if ($shop_type == 'reseller') selected="selected" @endif>
                                                      {{ __('Reseller') }}</option>
                                                  <option value="supplier" disabled
                                                      @if ($shop_type == 'supplier') selected="selected" @endif>
                                                      {{ __('Wholesaler') }}</option>
                                              </select>
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="email">{{ __('Order Receive Method') }}</label>
                                              <select class="form-control form-select" name="order_receive_method">

                                                  @if (current_shop_type() == 'seller')
                                                      @if (domain_info('whatsapp') == true)
                                                          <option value="whatsapp"
                                                              @if (is_whatsapp_enabled() == 0) disabled @endif
                                                              @if ($order_receive_method == 'whatsapp') selected="selected" @endif>
                                                              {{ __('I will Receive My Order Via Whatsapp') }}
                                                              {{ Cache::get(domain_info('shop_id') . 'whatsapp') }}
                                                          </option>
                                                      @endif
                                                  @endif
                                                  <option value="email"
                                                      @if ($order_receive_method == 'email') selected="selected" @endif>
                                                      {{ __('I will Receive My Order Via Email') }}</option>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label" for="username">{{ __('Languages') }}</label>
                                              <select class="select2 form-select" id="select2-multiple" name="lanugage[]"
                                                  multiple required>
                                                  @foreach ($def_languages ?? [] as $key => $lang)
                                                      <option value="{{ $lang['code'] }}"
                                                          @if (in_array($lang['code'], $my_languages)) selected="" @endif>
                                                          {{ $lang['name'] }}
                                                      </option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="email">{{ __('Default Language') }}</label>
                                              <select class="form-control col-sm-12" name="local">
                                                  @foreach ($def_languages ?? [] as $key => $row)
                                                      <option value="{{ $row['code'] }}"
                                                          @if ($row['code'] === $local) selected @endif>
                                                          {{ $row['name'] }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          @if (current_shop_type() != 'reseller')
                                              <div class="col-md-6 mb-1">
                                                  <label class="form-label" for="username">{{ __('Tax') }}</label>
                                                  <input type="number" name="tax" class="form-control"
                                                      min="0" required="" placeholder="0.00"
                                                      value="{{ $tax->value ?? '' }}">
                                              </div>
                                          @endif
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label" for="gst">{{ __('GST') }}</label>
                                              <input type="text" name="gst" class="form-control" min="0"
                                                 placeholder="GST" value="{{ $gst->value ?? '' }}">
                                          </div>
                                          @if (current_shop_type() != 'seller')
                                          <div class="col-md-6 mb-1">
                                                <label class="form-label" for="pan">{{ __('PAN') }}</label>
                                                <input type="text" name="pan" class="form-control"
                                                    min="0" required="" placeholder="PAN"
                                                    value="{{ $pan->value ?? '' }}">
                                          </div>
                                          @endif
                                          @if (current_shop_type() != 'seller')
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="aadhar">{{ __('Aadhar') }}</label>
                                                <input type="text" name="aadhar" class="form-control"
                                                    min="0" required="" placeholder="412345678901" pattern="[0-9]{12}"
                                                    value="{{ $aadhar->value ?? '' }}" maxlength="12" minlength="12">
                                            </div>
                                          @endif
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label" for="currency">{{ __('Currency') }}</label>
                                              <select class="form-control" name="currency" required="">
                                                  @foreach(get_currencies() as $item)
                                                    @if (current_shop_type() != 'seller' && $item->code != 'INR')
                                                    @continue
                                                    @endif
                                                    <option value="{{ $item->code }}"
                                                        @if (isset($currency) && $currency->code == $item->code) selected="" @endif>
                                                        {{ currency($item->code) }}
                                                    </option>
                                                  @endforeach
                                              </select>
                                              @if(current_shop_type() == 'seller')
                                                <small class="form-text text-muted">
                                                        {{ __('Choose payment gateway supported currency. Payment gateway use this currency.') }}
                                                        <a href="{{ route('seller.settings.show', 'payment') }}">{{ __('Payment Options') }}</a>
                                                </small>
                                              @endif
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="email">{{ __('Order ID Format (Prefix)') }}</label>
                                              <input type="text" name="order_prefix" id="order_prefix"
                                                  class="form-control" required="" placeholder="#ABC"
                                                  value="{{ $order_prefix->value ?? '' }}">
                                          </div>
                                          @if (current_shop_type() != 'seller')
                                              <div class="col-md-12 mb-1">
                                                  <b><label class="form-label">{{ __('Bank Details') }}</label></b>
                                                  <hr>
                                              </div>
                                          @endif
                                      </div>
                                      @if (current_shop_type() != 'seller')
                                          <div class="row">
                                              <div class="col-md-6 mb-1">
                                                  <label class="form-label">{{ __('Account Holder Name') }}</label>
                                                  <input type="text" name="account_holder_name" class="form-control"
                                                      required="" placeholder="John"
                                                      value="{{ $bank_details->account_holder_name ?? '' }}">
                                              </div>
                                              <div class="col-md-6 mb-1">
                                                  <label class="form-label">{{ __('IFSC Code') }}</label>
                                                  <input type="text" name="ifsc_code" class="form-control ifsc_code"
                                                      required="" placeholder="IAFC0123456" maxlength="11" minlength="11"
                                                      value="{{ $bank_details->ifsc_code ?? '' }}">
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-12 mb-1">
                                                  <label class="form-label">{{ __('Account No.') }}</label>
                                                  <input type="text" name="account_no" class="form-control account_no"
                                                      required="true" placeholder="238576252"
                                                      value="{{ $bank_details->account_no ?? '' }}">
                                              </div>
                                          </div>
                                      @endif
                                      <div style="margin-top: 25px;">
                                          <button class="btn btn-primary save_mobile_view save_desktop_view" type="submit">{{ __('Save') }}</button>
                                      </div>
                                  </form>
                              </div>
                              <div class="tab-pane fade {{ $page_type == 'location' ? 'active show' : 'unactive unshow' }}" id="nav-location" role="tabpanel"
                                  aria-labelledby="nav-profile-tab">
                                  <form method="post" action="{{ route('seller.settings.store') }}" class="basicform">
                                      @csrf
                                      <input type="hidden" name="type" value="location">
                                      <div class="row">
                                          <div class="col-md-12 mb-1">
                                              <label class="form-label"
                                                  for="company_name">{{ __('Company Name') }}</label>
                                              <input class="form-control" name="company_name"
                                                  value="{{ $location->company_name ?? '' }}" type="text"
                                                  required="">
                                          </div>
                                      </div>
                                      @if (current_shop_type() != 'reseller')
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label" for="email">{{ __('Email') }}</label>
                                              <input class="form-control" name="email" type="email"
                                                  value="{{ $location->email ?? '' }}" required="">
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label" for="phone">{{ __('Phone') }}</label>
                                              <input class="form-control" name="phone" id="phone" type="text"
                                                  maxlength="10" minlength="10" value="{{ $location->phone ?? '' }}"
                                                  required="">
                                          </div>
                                      </div>
                                      @endif
                                      <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="address">{{ __('Address') }}</label>
                                                <input class="form-control" name="address"
                                                    value="{{ $location->address ?? '' }}" type="text" value=""
                                                    required="">
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="city">{{ __('City') }}</label>
                                                <input class="form-control" name="city"
                                                    value="{{ $location->city ?? '' }}" type="text" value=""
                                                    required="">
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label" for="company_name">{{ __('State') }}</label>
                                                <input class="form-control" name="state"
                                                    value="{{ $location->state ?? '' }}" type="text" required="">
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label"
                                                    for="email">{{ __('Postal / Zip Code') }}</label>
                                                <input class="form-control" name="zip_code"
                                                    value="{{ $location->zip_code ?? '' }}" type="text" required=""
                                                    placeholder="1234">
                                            </div>
                                      </div>
                                      @if (current_shop_type() != 'reseller')
                                          <div class="row">
                                              <div class="col-md-12 mb-1">
                                                  <label>{{ __('Invoice Description') }}</label>
                                                  <textarea class="form-control" name="invoice_description">{{ $location->invoice_description ?? '' }}</textarea>
                                              </div>
                                          </div>
                                      @endif
                                      <div style="margin-top: 25px; float: right;">
                                          <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                      </div>
                                  </form>
                              </div>
                              <div class="tab-pane fade {{ $page_type == 'pwa' ? 'active show' : 'unactive unshow' }}" id="nav-pwa" role="tabpanel"
                                  aria-labelledby="nav-contact-tab">
                                  <form method="post" action="{{ route('seller.settings.store') }}"
                                      enctype="multipart/form-data" class="basicform">
                                      @csrf
                                      <input type="hidden" name="type" value="pwa_settings">
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="pwa_app_title">{{ __('APP Title') }}</label>
                                              <input class="form-control" name="pwa_app_title"
                                                  value="{{ $pwa->name ?? '' }}" type="text" value=""
                                                  required="">
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="pwa_app_name">{{ __('App Name (Short Name)') }}</label>
                                              <input class="form-control" name="pwa_app_name"
                                                  value="{{ $pwa->short_name ?? '' }}" type="text" value=""
                                                  required="">
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="pwa_app_background_color">{{ __('APP Background Color (Dont use color code)') }}</label>
                                              <input class="form-control" name="pwa_app_background_color"
                                                  value="{{ $pwa->background_color ?? '' }}" type="text"
                                                  required="">
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="pwa_app_theme_color">{{ __('APP Theme Color') }}</label>
                                              <input class="form-control" name="pwa_app_theme_color"
                                                  value="{{ $pwa->theme_color ?? '' }}" type="text" required="">
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="app_lang">{{ __('APP Main Language') }}</label>
                                              <input class="form-control" name="app_lang"
                                                  value="{{ $pwa->lang ?? '' }}" type="text" required=""
                                                  placeholder="en-US">
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="app_icon_128x128">{{ __('App Icon 128x128') }}</label>
                                              <input class="form-control" name="app_icon_128x128" type="file"
                                                  required="" accept="image/.png">
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="app_icon_144x144">{{ __('App Icon 144x144') }}</label>
                                              <input class="form-control" name="app_icon_144x144" type="file"
                                                  required="" accept="image/.png">
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="app_icon_152x152">{{ __('App Icon 152x152') }}</label>
                                              <input class="form-control" name="app_icon_152x152" type="file"
                                                  required="" accept="image/.png">
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="app_icon_192x192">{{ __('App Icon 192x192') }}</label>
                                              <input class="form-control" name="app_icon_192x192" type="file"
                                                  required="" accept="image/.png">
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="app_icon_256x256">{{ __('App Icon 256x256') }}</label>
                                              <input class="form-control" name="app_icon_256x256" type="file"
                                                  required="" accept="image/.png">
                                          </div>
                                          <div class="col-md-6 mb-1">
                                              <label class="form-label"
                                                  for="app_icon_512x512">{{ __('App Icon 512x512') }}</label>
                                              <input class="form-control" name="app_icon_512x512" type="file"
                                                  required="" accept="image/.png">
                                          </div>
                                      </div>
                                      <input type="hidden" name="pwa_app_status" value="Pending">
                                      @if (!empty($pwa->pwa_status))
                                          <div class="form-group">
                                              <label>{{ __('Status') }}</label>
                                              <input class="form-control" name="pwa_app_status"
                                                  value="{{ $pwa->pwa_status ?? 'Pending' }}" type="text" readonly>
                                          </div>
                                      @endif
                                      <div style="margin-top: 25px; float: right;">
                                          <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                      </div>
                                  </form>
                              </div>
                              <div class="tab-pane fade {{ $page_type == 'logo' ? 'active show' : 'unactive unshow' }}"
                                  id="nav-others" role="tabpanel" aria-labelledby="nav-others-tab">
                                  <form method="post" action="{{ route('seller.settings.store') }}"
                                      enctype="multipart/form-data" class="basicform">
                                      @csrf
                                      <input type="hidden" name="type" value="theme_settings">
                                      @php
                                          $default_colur = '#212060';
                                      @endphp
                                      @csrf
                                      @if (current_shop_type() != 'supplier')
                                          <div class="form-group mb-1">
                                              <label>{{ __('Theme Color') }}</label>
                                              <input type="text" name="theme_color" class="form-control rgcolorpicker"
                                                  required="" value="{{ $theme_color->value ?? $default_colur }}">
                                          </div>
                                      @endif
                                      <div class="form-group mb-1">
                                          <label>{{ __('Logo') }} <span style="color:red;">(</span> 250 X 120 mm <span
                                                  style="color:red;">)</span> </label><br>
                                           <img id="shoplogo" src="" class="d-none" alt="shoplogo" width=100 height=50/>
                                           <img src="{{ current_shop_logo_url() }}" alt="" srcset="" class="shop_logo" width=100 height=50 onerror="this.onerror=null; this.remove();">
                                          <input type="file" name="logo" id="logo" accept="image/*"
                                              class="form-control alertbox" onchange="readURL(this);"><span id="file_error"></span>
                                              
                                      </div>
                                      <div class="form-group mb-1">
                                          <label>{{ __('Favicon') }}<span style="color:red;">(</span> 64 X 64 mm <span
                                                  style="color:red;">)</span></label><br>
                                           <img id="shopfav" src="" class="d-none" alt="shopfav" width=32 height=32/>
                                           <img src="{{ get_shop_favicon(domain_info('shop_id')) }}" class="shop_fav" alt="" srcset="" width=32 height=32 onerror="this.onerror=null; this.remove();">
                                          <input type="file" name="favicon" id="favicon" accept="image/*"
                                              class="form-control alertboxx" onchange="readURLFav(this);">
                                          <span id="fav_error"></span>
                                      </div>
                                      @if (current_shop_type() != 'supplier')
                                          <label>{{ __('Social Links') }}</label>
                                          <table class="table table-bordered table-striped" id="user_table">
                                              <thead>
                                                  <tr>
                                                      <th width="35%" style=" border: 1px transparent; ">{{ __('Url') }}</th>
                                                      <!-- <th width="35%">Icon Class (<a href="https://fontawesome.com/" target="_blank">fontawesome</a>)</th> -->
                                                      <th width="35%" style=" border: 1px transparent; ">{{ __('Name') }}
                                                          <br /><small style="text-transform: lowercase;">(facebook,
                                                              instagram,
                                                              twitter, pinterest, youtube)</small>
                                                          <a href="#" data-bs-toggle="modal"
                                                              data-bs-target="#exampleModal">{{ __('Click Here') }}</a>
                                                      </th>
                                                      <th width="30%" style=" border: 1px transparent; "><button
                                                              type="button" name="add" id="add"
                                                              class="btn btn-success btn-sm">{{ __('Add New') }}</button>
                                                      </th>
                                                  </tr>
                                              </thead>
                                              <tbody>

                                                  @foreach ($socials ?? [] as $key => $row)
                                                      <tr>
                                                          <td style=" border: 1px transparent; "><input type="text"
                                                                  name="url[]" class="form-control" required
                                                                  value="{{ $row->url }}" /></td>
                                                          <td style=" border: 1px transparent; "><input type="text"
                                                                  name="icon[]" class="form-control"
                                                                  placeholder="fa fa-facebook" required
                                                                  value="{{ $row->icon }}" /></td>
                                                          <td style=" border: 1px transparent; "><button type="button"
                                                                  name="remove" id=""
                                                                  class="btn btn-danger remove">{{ __('Remove') }}</button></td>
                                                      </tr>
                                                  @endforeach

                                              </tbody>

                                          </table>
                                      @endif
                                      <div style="margin-top: 25px; float: right;">
                                          <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" style="color: blue;" id="exampleModalLabel">
                          {{ __('Fontawesome icon list') }}</h5>
                      <style>
                          table,
                          th,
                          td {
                              border: 1px solid black;
                              border-collapse: collapse;
                          }
                      </style>
                  </div>


                  <div class="modal-body">

                      <div>

                          <table style="width:100%;">
                              <tr>
                                  <th>Description</th>
                                  <th>Name</th>

                              </tr>
                              <tr>
                                  <td>facebook</td>
                                  <td>fa fa-facebook</td>
                              </tr>
                              <tr>
                                  <td>instagram</td>
                                  <td>fa fa-instagram</td>
                              </tr>
                              <tr>
                                  <td>twitter</td>
                                  <td>fa fa-twitter</td>
                              </tr>
                              <tr>
                                  <td>linkedin</td>
                                  <td>fa fa-linkedin</td>
                              </tr>
                              <tr>
                                  <td>telegram</td>
                                  <td>fa fa-telegram</td>
                              </tr>
                              <tr>
                                  <td>whatsapp</td>
                                  <td>fa fa-whatsapp</td>
                              </tr>
                              <tr>
                                  <td>youtube</td>
                                  <td>fa fa-youtube</td>
                              </tr>
                              <tr>
                                  <td>paypal</td>
                                  <td>fa fa-paypal</td>
                              </tr>
                              <tr>
                                  <td>internet-explorer</td>
                                  <td>fa fa-internet-explorer</td>
                              </tr>
                          </table>
                      </div>


                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger"
                          data-bs-dismiss="modal">{{ __('Close') }}</button>
                  </div>
              </div>
          </div>
      </div>
      <!-- END: Content-->
  @endsection
  @section('vendor-script')
      <script src="./admin/js/scripts/forms/form-select2.js"></script>
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  @endsection
  @section('page-script')
      <script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
      <script src="{{ asset('assets/js/color.js') }}"></script>
      <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
      <script>
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#shoplogo')
                    .attr('src', e.target.result);
                    $('#shoplogo').removeClass('d-none');
                    $('.shop_logo').remove();
                    $('#shoplogo').addClass('d-block');
            };

            reader.readAsDataURL(input.files[0]);
        }
        }
        function readURLFav(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#shopfav')
                    .attr('src', e.target.result);
                    $('#shopfav').removeClass('d-none');
                    $('.shop_fav').remove();
                    $('#shopfav').addClass('d-block');
            };

            reader.readAsDataURL(input.files[0]);
        }
        }
        $('.ifsc_code').change(function () {      
            var inputvalues = $(this).val();
            var reg = /[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/;
                            if (inputvalues.match(reg)) {    
                                return true;  
                            }    
                            else {    
                                $('.ifsc_code').val("");   
                                alert("You entered invalid IFSC code");       
                                return false;    
                            }    
            });  
            $('.account_no').change(function () {      
            var inputvalues = $(this).val();
            var reg = '^[a-zA-Z0-9]{6,20}$';
                            if (inputvalues.match(reg)) {    
                                return true;  
                            }    
                            else {    
                                $('.account_no').val("");   
                                alert("Account No. must be atleast 6 to 20 characters.");       
                                return false;
                            }   
            });
          $('#logo').bind('change', function() {
              $("#file_error").html("");
              $(".alertbox").css("border-color", "#F0F0F0");
              var file_size = $('#logo')[0].files[0].size;
              if (file_size >= 1048576) {
                  $("#file_error").html(" Recommended file size is less than 1 mb");
                  $("#file_error").css("color", "red");
                  $(".alertbox").css("border-color", "#FF0000");
                  $('#submit').hide();
                  return false;
              } else {
                  $('#submit').show();
              }
              return true;
          });
          $(function() {
              $('#shop_name').bind('input', function() {
                  $(this).val(function(_, v) {
                      return v.replace(/\s+/g, '');
                  });
              });
          });
          $('#shop_name').keyup(function() {
              var value = $(this).val();
              var value = value.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
              $(this).val(value);
          });
          $('#favicon').bind('change', function() {
              $("#fav_error").html("");
              $(".alertboxx").css("border-color", "#F0F0F0");
              var file_size = $('#favicon')[0].files[0].size;
              if (file_size >= 1048576) {
                  $("#fav_error").html(" Recommended file size is less than 1 mb");
                  $("#fav_error").css("color", "red");
                  $(".alertboxx").css("border-color", "#FF0000");
                  $('#submit').hide();
                  return false;
              } else {
                  $('#submit').show();
              }
              return true;
          });
          $(function() {
              $('#order_prefix').bind('change', function() {
                  var order_prefix = $('#order_prefix').val();
                  var letters = /^[A-Za-z!@#$%^&*?]+$/;
                  if (order_prefix != "") {
                      if (order_prefix.match(letters)) {} else {
                          $('#order_prefix').focus();
                          Sweet('error', '{{ __('Numbers Not Allowed') }}');
                          $('#order_prefix').val('');
                          return false;
                      }
                  }
              });
          });

          $('#phone').bind('change', function() {
              var mobile_number = $('#phone').val();
              var number = /^[0-9]+$/;
              if (mobile_number != "") {
                  if (mobile_number.match(number)) {
                      if (mobile_number.length != 10) {
                          $('#mobile_number').focus();
                          Sweet('error', '{{ __('Please Enter Mobile Number 10 Digit only') }}');
                          return false;
                      }
                  } else {
                      $('#mobile_number').focus();
                      Sweet('error', '{{ __('Please Enter Valid Mobile Number Only') }}');
                      $('#phone').val('');
                      return false;
                  }
              }
          });
      </script>
  @endsection
