@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection', ['title' => 'Partner'])
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('Edit Partner') }}</h4>

            </div>
            <div class="card-body">

                <form class="basicform" action="{{ route('admin.partner.update', $info->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('First Name') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" required="" name="first_name" value="{{ $info->first_name }}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Last Name') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" required="" name="last_name" value="{{ $info->last_name }}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Partner Email') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="email" class="form-control" required="" name="email" value="{{ $info->email }}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Password') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="password">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Mobile Number') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" required="" name="mobile_number" id="mobile_number" maxlength="10" onkeyup="mobile_validation()" value="{{ $info->partner->mobile_number ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Commission') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="commission" value="{{ $info->partner->commission ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Threshold Value') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="threshold_value" id="threshold_value" value="{{ $info->partner->settlement_amount ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Bank Name') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="bank_details[bank_name]" value="{{ $info->partner->bank_details['bank_name'] ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Bank Account No') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="bank_details[account_no]" value="{{ $info->partner->bank_details['account_no'] ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('IFSC Code') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="bank_details[ifsc_code]" value="{{ $info->partner->bank_details['ifsc_code'] ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('KYC') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" class="form-control" name="bank_details[kyc]" value="{{ $info->partner->bank_details['kyc'] ?? '' }}">
                        </div>
                    </div>


                    {{-- <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                    <div class="col-sm-12 col-md-7">
                        <select class="form-control" name="status">
                            <option value="1" @if ($info->status == 1) selected="" @endif>{{ __('Active') }}</option>
                            <option value="0" @if ($info->status == 0) selected="" @endif>{{ __('Trash') }}</option>
                            <option value="2" @if ($info->status == 2) selected="" @endif>{{ __('Suspended') }}</option>
                            <option value="3" @if ($info->status == 3) selected="" @endif>{{ __('Request') }}</option>
                        </select>
                    </div>
            </div> --}}
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                <div class="col-sm-12 col-md-7">
                    <select class="form-control" name="partner_status" id="partner_status">
                        <option value="0" @if ($info->partner && $info->partner->status == 0) selected="" @endif>
                            {{ __('Pending') }}
                        </option>
                        <option value="1" @if ($info->partner && $info->partner->status == 1) selected="" @endif>
                            {{ __('Active') }}
                        </option>
                        <option value="2" @if ($info->partner && $info->partner->status == 2) selected="" @endif>
                            {{ __('Inactive') }}
                        </option>
                        <option value="3" @if ($info->partner && $info->partner->status == 3) selected="" @endif>
                            {{ __('Banned') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                <div class="col-sm-12 col-md-7">
                    <button class="btn btn-success basicbtn" id="submit" type="submit">{{ __('Save') }}</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script>
    function mobile_validation() {
        var mobile_number = $('#mobile_number').val();
        var number = /^[0-9]+$/;
        if (mobile_number != "") {
            if (mobile_number.match(number)) {

            } else {
                $('#mobile_number').focus();
                alert('Please Enter Valid Mobile Number Only');
                $('#mobile_number').val('');
                return false;

            }
        }
    }
    $('#submit').on('click', function() {
        var threshold_value = $('#threshold_value').val();
        var partner_status = $('#partner_status option:selected').val();
        if (partner_status == 1 && threshold_value == '') {
            $('#threshold_value').focus();
            Sweet('error', '{{ __('
                Please Enter Threshold Value ') }}');
            return false;
        }

    });
</script>
@endpush