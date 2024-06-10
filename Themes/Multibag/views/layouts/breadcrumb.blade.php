@hasSection('breadcrumb')
<div class="breadcrumb-area slider-mt-1 breadcrumb-ptb-2 breadcrumb-pm">
    <div class="container">
        <div class="breadcrumb-content @if(isset($type) && $type == 1) text-center @endif">
            <h2>{{ __($title ?? 'No Title') }}</h2>
            <ul>
                <li>
                    <a href="{{ url('/') }}">{{ __('Home') }}</a>
                </li>
                <li><span> > </span></li>
                @yield('breadcrumb')
            </ul>
        </div>
    </div>
</div>
@endif
