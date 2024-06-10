@extends('layouts.app')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}">
@endpush
@section('head')
    @include('layouts.partials.headersection', ['title' => 'Site Settings'])
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Site Settings') }}</h4><br>

                </div>
                <div class="card-body">
                    <form class="basicform" action="{{ route('admin.site_settings.update') }}" method="post">
                        @csrf

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Site Name') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="site_name" class="form-control" value="{{ $info->name ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Site Description') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <textarea name="site_description" class="form-control"
                                    placeholder="short description" maxlength="500" row="6">
                                    {{ $info->site_description ?? '' }}
                                </textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Contact Mail 1') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" name="email1" class="form-control" value="{{ $info->email1 ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Contact Mail 2') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" name="email2" class="form-control"
                                    value="{{ $info->email2 ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Contact Phone 1') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="phone1" class="form-control"
                                    value="{{ $info->phone1 ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Contact Phone 2') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="phone2" class="form-control"
                                    value="{{ $info->phone2 ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Country') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="country" class="form-control"
                                    value="{{ $info->country ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Zip Code') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" name="zip_code" class="form-control"
                                    value="{{ $info->zip_code ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('State') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="state" class="form-control" value="{{ $info->state ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('city') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="city" class="form-control" value="{{ $info->city ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('address') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="address" class="form-control"
                                    value="{{ $info->address ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Currency') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control" name="currency" required="">
                                    @foreach(get_currencies() as $item)
                                        @if ($item->code != 'INR')
                                        @continue
                                        @endif
                                        <option value="{{ $item->code }}"
                                            @if (isset($currency) && $currency->code == $item->code) selected="" @endif>
                                            {{ currency($item->code) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Order Prefix') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="order_prefix" class="form-control"
                                    value="{{ $order_prefix->value ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('facebook url') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="facebook" class="form-control"
                                    value="{{ $info->facebook ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('twitter url') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="twitter" class="form-control"
                                    value="{{ $info->twitter ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('linkedin url') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="linkedin" class="form-control"
                                    value="{{ $info->linkedin ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('instagram url') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="instagram" class="form-control"
                                    value="{{ $info->instagram ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('youtube url') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="youtube" class="form-control"
                                    value="{{ $info->youtube ?? '' }}">
                            </div>
                        </div>



                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                              {{ __('Logo') }}
                            </label>
                            <div class="col-sm-12 col-md-7">
                                <input type="file" name="logo" class="form-control" accept=".png">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                              {{ __('Dark Logo') }}
                            </label>
                            <div class="col-sm-12 col-md-7">
                                <input type="file" name="dark_logo" class="form-control" accept=".png">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                              {{ __('Favicon') }}
                            </label>
                            <div class="col-sm-12 col-md-7">
                                <input type="file" name="favicon" accept=".ico" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                              {{ __('Site Color') }}
                            </label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="site_color" class="form-control colorpickerinput"
                                    value="{{ $info->site_color ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                              {{ __('Automatic Order Approved After Payment Success') }}
                            </label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control" name="auto_order">
                                    <option value="yes" @if ($auto_order->value == 'yes') selected @endif>
                                        {{ __('Yes') }}</option>
                                    <option value="no" @if ($auto_order->value == 'no') selected @endif>
                                        {{ __('No') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">GST</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="gst" class="form-control" min="0" required=""
                                    placeholder="GST" value="{{ $gst->value ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Subscription Tax') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" step="any" name="tax" class="form-control" min="0"
                                    max="100" value="{{ $tax->value ?? 0 }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Settlement Tax') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" step="any" name="settlement_tax" class="form-control"
                                    min="0" max="100" value="{{ $settlement_tax->value ?? 0 }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                {{ __('Supplier Settlement Period') }} (In Days)</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" step="any" name="supplier_settlement_period"
                                    class="form-control" min="0" max="100"
                                    value="{{ $supplier_settlement_period->value ?? 7 }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Reseller settlement threshold amount</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="reseller_threshold_amount" class="form-control"
                                    min="0" required="" placeholder="Reseller settlement threshold amount"
                                    value="{{ $reseller_threshold_amount->value ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Partner Commission</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="partner_commission" class="form-control" min="0"
                                    required="" placeholder="partner commission"
                                    value="{{ $partner_commission->value ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Supplier Commission (In Percentage)</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="supplier_commission" class="form-control" min="0"
                                    required="" value="{{ $supplier_commission->value ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button class="btn btn-success basicbtn" type="submit">{{ __('Save') }}</button><br>
                                <small>{{ __('Note:') }} </small> <small
                                    class="text-danger mt-4">{{ __('After You Update Settings The Action Will Work After 5 Minutes') }}</small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
