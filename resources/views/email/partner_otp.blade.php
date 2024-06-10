@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
@php
$image = admin_logo_url();
@endphp
@if (file_exists($image))
<img src="{{ admin_logo_url() }}" height="50" width="200">
@else
{{ config('app.name') }}
@endif
@endcomponent
@endslot

# Dear {{ $data['name'] }},

Your one-time password for Singlebag partner registration is 
<center><h4><b>{{ $data['otp'] }}</b></h4></center>


--------

If you have any trouble during login, please contact our technical team <p><a href="mailto:support@singlebag.com">support@singlebag.com</a></p>


Thanks,<br>
{{ config('app.name') }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent


