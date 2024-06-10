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

<center><h4><b>Test Mail</b></h4></center>

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


