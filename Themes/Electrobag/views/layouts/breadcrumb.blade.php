@hasSection('breadcrumb')
    <div class="breadcrumbs">
        <a href="{{ url('/') }}">{{ __('Home') }}</a>
        @yield('breadcrumb')
    </div>  
@endif