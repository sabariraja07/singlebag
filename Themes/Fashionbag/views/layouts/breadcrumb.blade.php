@hasSection('breadcrumb')
<div class="section">
    <div class="breadcrumb-area bg-light">
        <div class="container-fluid">
            <div class="breadcrumb-content text-center">
                <h1 class="title">{{ __($title ?? 'Home') }}</h1>
                <ul>
                    <li>
                        <a href="{{ url('/') }}">{{ __('Home') }}</a>
                    </li>
                    @yield('breadcrumb')
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
