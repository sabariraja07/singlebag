  <!-- BEGIN: Header-->
  <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
      <div class="bookmark-wrapper d-flex align-items-center">
          <ul class="nav navbar-nav d-xl-none">
              <li class="nav-item">
                <a class="nav-link menu-toggle" href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu ficon"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </a>
              </li>
          </ul>
      </div>
      <ul class="nav navbar-nav align-items-center ms-auto">
       
        @if (current_shop_id() != 0)
          @php
              $all_language = [];
              $languages = get_shop_languages();
              $locale = collect($languages)->filter(function($item) {
                            return $item['code'] == app()->getLocale();
                    })->first();
          @endphp
          @if (!empty($languages))
          <li class="nav-item dropdown dropdown-language">
            @if ($locale)
            <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="flag-icon flag-icon-{{ $locale['countryCode'] }}"></i>
              <span class="selected-language">{{ $locale['nativeName'] }}</span>
            </a>
            @else
            <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="flag-icon flag-icon-{{ $languages[0]['countryCode'] }}"></i>
              <span class="selected-language">{{ $languages[0]['nativeName'] }}</span>
            </a>
            @endif
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
              @foreach ($languages as $language)
              <a class="dropdown-item" href="{{ url('/language/' . $language['code']) }}" data-language="{{ $language['code'] }}">
                <i class="flag-icon flag-icon-{{ $language['countryCode'] }}"></i> {{ $language['nativeName'] }}
              </a>
              @endforeach
            </div>
          </li>
          @endif
        @endif
        {{-- <li class="nav-item d-none d-lg-block">
          <a class="nav-link nav-link-style">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon ficon">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
          </a>
        </li> --}}
        
        {{-- <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#" data-bs-toggle="dropdown"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell ficon">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg><span class="badge rounded-pill bg-danger badge-up">5</span></a>
          <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
            <li class="dropdown-menu-header">
              <div class="dropdown-header d-flex">
                <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                <div class="badge rounded-pill badge-light-primary">6 New</div>
              </div>
            </li>
            <li class="scrollable-container media-list ps"><a class="d-flex" href="#">
              <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
              </div>
              <div class="ps__rail-y" style="top: 0px; right: 0px;">
                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
              </div>
            </li>
            <li class="dropdown-menu-footer"><a class="btn btn-primary w-100 waves-effect waves-float waves-light" href="#">Read all notifications</a></li>
          </ul>
        </li> --}}
        <li class="nav-item dropdown dropdown-user">
          <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="user-nav d-sm-flex d-none">
              <span class="user-name fw-bolder">{{ auth()->user()->fullname  ?? '' }}</span>
              <span class="user-status">{{ ucfirst(current_shop_type()) }}</span>
            </div>
            <span class="avatar">
              <img class="round" src="{{ asset('uploads/'.current_shop_id().'/favicon.ico') }}" onerror="this.src='./images/shop1.png'" alt="avatar" height="40" width="40">
              {{-- <span class="avatar-status-online"></span> --}}
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user" style="width: 15rem;">
      
            {{-- @if(current_shop_id() != 0)
            <a class="dropdown-item" href="{{ route('seller.settings') }}">
              <i class="ph-gear-bold"></i>
              Profile Settings
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="https://community.singlebag.com/">
              <i class="ph-chat-circle-bold"></i>
              Singlebag Community
            </a>
            @endif --}}
            <a class="dropdown-item" href="{{ route('agent_logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              <i data-feather="power"></i>
              Logout
            </a>
            <form id="logout-form" action="{{ route('agent_logout') }}" method="POST" class="none">
                     @csrf
            </form>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <!-- END: Header-->

