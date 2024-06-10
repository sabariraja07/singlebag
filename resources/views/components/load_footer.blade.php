@if(filter_var(domain_info('custom_js'),FILTER_VALIDATE_BOOLEAN) == true)
<script src="{{ asset(domain_info('shop_id').'/js/additional.js') }}"></script>
@endif




@if(filter_var(domain_info('google_analytics'),FILTER_VALIDATE_BOOLEAN) == true)
@if(Cache::has(domain_info('shop_id').'google-analytics'))
@php
$google_analytics=json_decode(Cache::get(domain_info('shop_id').'google-analytics'));
@endphp
{!! google_analytics($google_analytics->ga_measurement_id ?? 1234) !!}
@endif

@endif

@if(filter_var(domain_info('gtm'),FILTER_VALIDATE_BOOLEAN) == true)
@if(Cache::has(domain_info('shop_id').'tag_manager'))
@php
$tag_manager=Cache::get(domain_info('shop_id').'tag_manager');
@endphp
{!! google_tag_manager_footer($tag_manager ?? 1234) !!}
@endif
@endif


@if(filter_var(domain_info('pwa'),FILTER_VALIDATE_BOOLEAN) == true)
@if(file_exists('uploads/'.domain_info('shop_id').'/manifest.json'))
<script type="text/javascript">
        window.onload = () => {
              'use strict';
              if ('serviceWorker' in navigator) {
                 navigator.serviceWorker.register('/sw.js?v=1.0');
               
            }
        }

</script>
@endif
@endif