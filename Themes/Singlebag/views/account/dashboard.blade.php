@extends('singlebag::account.layout.app')
@section('user_content')
<div class="tab-pane fade active show" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Hello {{ Auth::guard('customer')->user()->first_name }}!</h3>
    </div>
    <div class="card-body">
      <p>{{ __('Hello') }} <strong>{{ Auth::guard('customer')->user()->fullname }} ( {!! __('not :full_name ?' , ["full_name" => "<strong>" . Auth::guard('customer')->user()->fullname  ."</strong>"]) !!}
          <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>)</p>

      <p>{!! __('From your account dashboard you can view your :recent_orders and :edit_password_details .', ['recent_orders' => "<a href='" .  url('/user/orders')  . "'>" .  trans('recent orders')  . "</a>" , 'edit_password_details' => "<a href='" . url('/user/settings') . "'>". trans('edit your password and account details') . "</a>" ]) !!}</p>
    </div>
  </div>
</div>

@endsection