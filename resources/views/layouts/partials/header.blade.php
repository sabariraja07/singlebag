 <nav class="navbar navbar-expand-lg main-navbar">
     <form class="form-inline mr-auto">
         <ul class="navbar-nav mr-3">
             <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
             <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                         class="fas fa-search"></i></a></li>

         </ul>
     </form>
    @if(current_shop_id() != 0)
        @php
            $shop_type = current_shop_type();
        @endphp
        @if ($shop_type == 'seller' || $shop_type == 'reseller')
            <div class="custom-control custom-switch">
                @php
                    $shop_mode_info = \App\Models\ShopOption::where('shop_id', current_shop_id())
                        ->where('key', 'shop_mode')
                        ->first();
                    $shop_mode = $shop_mode_info->value ?? '';
                @endphp
                    <input type="hidden" id="shop_id_for_mode" value="{{ current_shop_id() }}">
                    <input id="shop_mode" class="custom-control-input" @if($shop_mode == 'online' || $shop_mode == '') checked @endif name="status" type="checkbox">
                    <label class="custom-control-label" for="shop_mode" id="shop_mode_status">
                        @if($shop_mode == 'online' || $shop_mode == '') 
                            Online 
                        @else
                            Offline
                        @endif
                    </label>
            </div>
        @endif
    @endif
     <ul class="navbar-nav navbar-right">
         @if (current_shop_id() != 0)
             @php
                 $all_language = active_lang_list(); //Cache::get(domain_info('shop_id').'languages');
                 $locale = app()->getLocale();
                 $languages = \App\Models\ShopOption::where('shop_id', current_shop_id())
                     ->where('key', 'languages')
                     ->first();
                     
                 $languages = $languages ? json_decode($languages->value) ?? '' : '';
             @endphp
             @if (!empty($languages))
                 <li class="dropdown" style="text-transform: uppercase;">
                     <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg">
                         @foreach ($languages as $lang_key => $language)
                             @if ($locale == $language)
                                 <div class="d-sm-none d-lg-inline-block">{{ $language }}</div>
                             @endif
                         @endforeach
                     </a>
                     <div class="dropdown-menu dropdown-menu-right">
                         @foreach ($languages as $lang_key => $language)
                             <a href="{{ url('/language/' . $language) }}" class="dropdown-item has-icon">
                                 @if ($locale != $language)
                                     <i class="far fa-circle"></i>
                                 @else
                                     <i class="far fa-dot-circle" style="color: green;"></i>
                                 @endif
                                 {{ $language }}
                             </a>
                         @endforeach
                     </div>
                 </li>
             @else
                 <li class="dropdown">
                     <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg">
                         @foreach ($all_language as $lang_key => $language)
                             @if ($locale == $language['code'])
                                 <div class="d-sm-none d-lg-inline-block">{{ $language['nativeName'] }}</div>
                             @endif
                         @endforeach
                     </a>
                     <div class="dropdown-menu dropdown-menu-right">
                         @foreach ($all_language as $lang_key => $language)
                             <a href="{{ url('/language/' . $language['code']) }}" class="dropdown-item has-icon">
                                 @if ($locale != $language['code'])
                                     <i class="far fa-circle"></i>
                                 @else
                                     <i class="far fa-dot-circle" style="color: green;"></i>
                                 @endif
                                 {{ $language['nativeName'] }}
                             </a>
                         @endforeach
                     </div>
                 </li>
             @endif
         @endif
         @if (Auth::user()->isPartner())
             @php
                 $languages = active_lang_list(); //Cache::get(domain_info('shop_id').'languages');
                 $locale = app()->getLocale();
             @endphp
             <li class="dropdown">
                 <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg">
                     @foreach ($languages as $lang_key => $language)
                         @if ($locale == $language['code'])
                             <div class="d-sm-none d-lg-inline-block">{{ $language['nativeName'] }}</div>
                         @endif
                     @endforeach
                 </a>
                 <div class="dropdown-menu dropdown-menu-right">
                     @foreach ($languages as $lang_key => $language)
                         <a href="{{ url('/language/' . $language['code']) }}" class="dropdown-item has-icon">
                             @if ($locale != $language['code'])
                                 <i class="far fa-circle"></i>
                             @else
                                 <i class="far fa-dot-circle" style="color: green;"></i>
                             @endif
                             {{ $language['nativeName'] }}
                         </a>
                     @endforeach
                 </div>
             </li>
         @endif

         <li class="dropdown"><a href="#" data-toggle="dropdown"
                 class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                 <img alt="image" src="https://ui-avatars.com/api/?name={{ Auth::user()->fullname }}"
                     class="rounded-circle mr-1">
                 <div class="d-sm-none d-lg-inline-block">{{ __('Hi') }}, {{ Auth::user()->fullname }}</div>
             </a>
             <div class="dropdown-menu dropdown-menu-right">

                 @if (Auth::user()->isPartner())
                     <a href="{{ route('profile.settings') }}" class="dropdown-item has-icon">
                         <i class="far fa-user"></i> {{ __('Profile Settings') }}
                     </a>
                     <a href="https://community.singlebag.com/" class="dropdown-item has-icon" target="_blank">
                         <i class="far fa-comment"></i> {{ __('Singlebag Community') }}
                     </a>
                 @elseif(current_shop_id() != 0)
                     <a href="{{ route('seller.settings') }}" class="dropdown-item has-icon">
                         <i class="far fa-user"></i> {{ __('Profile Settings') }}
                     </a>
                     <a href="https://community.singlebag.com/" class="dropdown-item has-icon" target="_blank">
                         <i class="far fa-comment"></i> {{ __('Singlebag Community') }}
                     </a>
                 @endif
                 @if (Auth::user()->hasRole('superadmin'))
                     <a href="{{ route('admin.profile.settings') }}" class="dropdown-item has-icon">
                         <i class="far fa-user"></i> {{ __('Profile Settings') }}
                     </a>
                 @endif



                 <div class="dropdown-divider"></div>
                 <a href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();" class="dropdown-item has-icon text-danger">
                     <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                 </a>



                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="none">
                     @csrf
                 </form>
             </div>
         </li>
     </ul>
 </nav>
     <!-- Modal -->
     <div class="modal fade" id="shop_mode_duration_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Go online after') }}</h5>


                    <button type="button" class="close" data-dismiss="modal" id="modal_close_button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="seller/shop-mode-duration" method="POST" id="shop_mode_duration_frm">
                    @csrf
                    <div class="modal-body">
                        <input type="radio" id="1hour" class="shop_mode_duration_checkbox" name="shop_mode_duration" value="1">
                        <label for="1hour">1 hour</label><br>
                        <input type="radio" id="2hours" class="shop_mode_duration_checkbox" name="shop_mode_duration" value="2">
                        <label for="2hours">2 hours</label><br>
                        <input type="radio" id="4hours" class="shop_mode_duration_checkbox" name="shop_mode_duration" value="4">
                        <label for="4hours">4 hours</label><br>
                        <input type="radio" id="tomorrow" class="shop_mode_duration_checkbox" name="shop_mode_duration" value="24">
                        <label for="tomorrow">Tomorrow, at same time</label><br>
                        <input type="radio" id="manual" class="shop_mode_duration_checkbox" name="shop_mode_duration" value="" checked>
                        <label for="manual">I will go online myself</label><br>
                    </div>
                    <div class="modal-footer">


                        <div class="import_area">

                            <div>
                                <button type="submit" class="btn btn-success basicbtn">{{ __('Submit') }}</button>
                            </div>

                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>