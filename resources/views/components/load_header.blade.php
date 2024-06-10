@if(filter_var(domain_info('custom_css'),FILTER_VALIDATE_BOOLEAN) == true)
<script src="{{ asset(domain_info('shop_id').'/css/additional.css') }}"></script>
@endif

@if(filter_var(domain_info('gtm'),FILTER_VALIDATE_BOOLEAN) == true)
@if(Cache::has(domain_info('shop_id').'tag_manager'))
@php
$tag_manager=Cache::get(domain_info('shop_id').'tag_manager');
@endphp
{!! google_tag_manager_header($tag_manager ?? 1234) !!}
@endif
@endif

@if(filter_var(domain_info('facebook_pixel'),FILTER_VALIDATE_BOOLEAN) == true)
@if(Cache::has(domain_info('shop_id').'fb_pixel'))
@php
$facebook_pixel=Cache::get(domain_info('shop_id').'fb_pixel');
@endphp
{!! facebook_pixel($fb_pixel ?? 1234) !!}
@endif
@endif

@if(filter_var(domain_info('pwa'),FILTER_VALIDATE_BOOLEAN) == true)
<link rel="manifest"  href="{{ asset(domain_info('shop_id').'/pwa/manifest.json') }}">
@endif