  @php
  $shop_info = \App\Models\Shop::where('id', current_shop_id())->first();
  $image = current_shop_logo_url();
  @endphp
  <div class="main-sidebar">
      <aside id="sidebar-wrapper">
          @if (current_shop_id() != 0)
          @if (file_exists($image))
          <div class="sidebar-brand-logo">
              <a id="logo" href="#">
                  <img src="{{ current_shop_logo_url() }}" alt="logo" style="padding: 20px;width: 200px;" />
              </a>
          </div>
          @else
          <div class="sidebar-brand">
              <a href="#">{{ $shop_info->name ?? '' }}</a>
          </div>
          @endif
          @else
          <div class="sidebar-brand">
              <a href="#">{{ env('APP_NAME') }}</a>
          </div>
          @endif

          <div class="sidebar-brand sidebar-brand-sm">
              <a href="#">{{ Str::limit(env('APP_NAME'), $limit = 1) }}</a>
          </div>
          <ul class="sidebar-menu">
              @hasrole('superadmin')
              @can('dashboard')
              <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.dashboard') }}">
                      <i class="flaticon-dashboard"></i> <span>{{ __('Dashboard') }}</span>
                  </a>
              </li>
              @endcan

              @can('order.list')
              <li class="{{ Request::is('admin/order*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.order.index') }}">
                      <i class="flaticon-note"></i> <span>{{ __('Orders') }}</span>
                  </a>
              </li>
              @endcan
              @can('order.list')
              <li class="{{ Request::is('admin/orders/licences') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.order.licences') }}">
                      <i class="flaticon-note"></i> <span>{{ __('License') }}</span>
                  </a>
              </li>
              @endcan

              @php
              $plan = false;
              @endphp
              @can('plan.create')
              @php
              $plan = true;
              @endphp
              @endcan
              @can('plan.list')
              @php
              $plan = true;
              @endphp
              @endcan
              @if ($plan == true)
              <li class="dropdown {{ Request::is('admin/plan*') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-pricing"></i> <span>{{ __('Plans') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('plan.create')
                      <li><a class="nav-link {{ Request::is('admin/plan/create') ? 'active' : '' }}" href="{{ route('admin.plan.create') }}">{{ __('Create') }}</a></li>
                      @endcan
                      @can('plan.list')
                      <li><a class="nav-link {{ Request::is('admin/plan') ? 'active' : '' }}" href="{{ route('admin.plan.index') }}">{{ __('All Plans') }}</a></li>
                      @endcan
                  </ul>
              </li>
              @endif
              @can('report.view')
              <li class="{{ Request::is('admin/report*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('admin.report') }}">
                      <i class="flaticon-dashboard-1"></i> <span>{{ __('Reports') }}</span>
                  </a>
              </li>
              @endcan

              @can('shop.create', 'shop.list', 'shop.request', 'shop.list')
              <li class="dropdown {{ Request::is('admin/shop') || Request::is('admin/shops') ? 'active' : '' }}">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-shop"></i>
                      <span>{{ __('Shops') }}</span></a>
                  <ul class="dropdown-menu">
                      @can('shop.create')
                      <li><a class="nav-link {{ request()->routeIs('admin.shop.create') ? 'active' : '' }}" href="{{ route('admin.shop.create') }}">{{ __('Create Shop') }}</a></li>
                      @endcan
                      @can('shop.list')
                      <li><a class="nav-link {{ request()->routeIs('admin.shop.index') ? 'active' : '' }}" href="{{ route('admin.shop.index') }}">{{ __('All Shops') }}</a>
                      </li>
                      @endcan
                      <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.create') ? 'active' : '' }}" href="{{ route('admin.shop-categories.create') }}">{{ __('Create Shop Categories') }}</a></li>
                      <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.index') ? 'active' : '' }}" href="{{ route('admin.shop-categories.index') }}">{{ __('All Shop Categories') }}</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('admin.shop.settlements') ? 'active' : '' }}" href="{{ route('admin.shop.settlements') }}">{{ __('Settlements') }}</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('admin.shop_order.index') ? 'active' : '' }}" href="{{ route('admin.shop_order.index') }}">{{ __('Shop Orders') }}</a>
                      </li>
                      {{-- @can('shop.request')
          <li><a class="nav-link" href="{{ route('admin.shop.index','type=3') }}">{{ __('Shop Request') }}</a>
              </li>
              @endcan
              @can('shop.list')
              <li><a class="nav-link" href="{{ route('admin.shop.index','type=2') }}">{{ __('Suspended Shops') }}</a></li>
              @endcan --}}
          </ul>
          </li>
          <li class="dropdown {{ Request::is('admin/shops/seller*') || Request::is('admin/shops/seller*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-shop"></i>
                <span>{{ __('Seller') }}</span></a>
            <ul class="dropdown-menu">
                {{-- @can('shop.create')
                <li><a class="nav-link {{ request()->routeIs('admin.shop.create') ? 'active' : '' }}" href="{{ route('admin.shop.create') }}">{{ __('Create Shop') }}</a></li>
                @endcan --}}
                @can('shop.list')
                <li><a class="nav-link {{ request()->routeIs('admin.shop.seller') ? 'active' : '' }}" href="{{ route('admin.shop.seller') }}">{{ __('All Shops') }}</a>
                </li>
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shoporder.seller') ? 'active' : '' }}" href="{{ route('admin.shoporder.seller') }}">{{ __('Orders') }}</a>
                </li> --}}
                @endcan
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.create') ? 'active' : '' }}" href="{{ route('admin.shop-categories.create') }}">{{ __('Create Shop Categories') }}</a></li>
                <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.index') ? 'active' : '' }}" href="{{ route('admin.shop-categories.index') }}">{{ __('All Shop Categories') }}</a>
                </li> --}}
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shop.settlements') ? 'active' : '' }}" href="{{ route('admin.shop.settlements') }}">{{ __('Settlements') }}</a>
                </li>
                <li><a class="nav-link {{ request()->routeIs('admin.shop_order.index') ? 'active' : '' }}" href="{{ route('admin.shop_order.index') }}">{{ __('Shop Orders') }}</a>
                </li> --}}
            </ul>
        </li>
        <li class="dropdown {{ Request::is('admin/shops/supplier*') || Request::is('admin/shops/supplier*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-shop"></i>
                <span>{{ __('Supplier') }}</span></a>
            <ul class="dropdown-menu">
                {{-- @can('shop.create')
                <li><a class="nav-link {{ request()->routeIs('admin.shop.create') ? 'active' : '' }}" href="{{ route('admin.shop.create') }}">{{ __('Create Shop') }}</a></li>
                @endcan --}}
                @can('shop.list')
                <li><a class="nav-link {{ request()->routeIs('admin.shop.supplier') ? 'active' : '' }}" href="{{ route('admin.shop.supplier') }}">{{ __('All Shops') }}</a>
                </li>
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shoporder.supplier') ? 'active' : '' }}" href="{{ route('admin.shoporder.supplier') }}">{{ __('Orders') }}</a>
                </li> --}}
                @endcan
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.create') ? 'active' : '' }}" href="{{ route('admin.shop-categories.create') }}">{{ __('Create Shop Categories') }}</a></li>
                <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.index') ? 'active' : '' }}" href="{{ route('admin.shop-categories.index') }}">{{ __('All Shop Categories') }}</a>
                </li> --}}
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shop.settlements') ? 'active' : '' }}" href="{{ route('admin.shop.settlements') }}">{{ __('Settlements') }}</a>
                </li>
                <li><a class="nav-link {{ request()->routeIs('admin.shop_order.index') ? 'active' : '' }}" href="{{ route('admin.shop_order.index') }}">{{ __('Shop Orders') }}</a>
                </li> --}}
            </ul>
        </li>
        <li class="dropdown {{ Request::is('admin/shops/reseller*') || Request::is('admin/shops/reseller*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-shop"></i>
                <span>{{ __('Reseller') }}</span></a>
            <ul class="dropdown-menu">
                {{-- @can('shop.create')
                <li><a class="nav-link {{ request()->routeIs('admin.shop.create') ? 'active' : '' }}" href="{{ route('admin.shop.create') }}">{{ __('Create Shop') }}</a></li>
                @endcan --}}
                @can('shop.list')
                <li><a class="nav-link {{ request()->routeIs('admin.shop.reseller') ? 'active' : '' }}" href="{{ route('admin.shop.reseller') }}">{{ __('All Shops') }}</a>
                </li>
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shoporder.reseller') ? 'active' : '' }}" href="{{ route('admin.shoporder.reseller') }}">{{ __('Orders') }}</a>
                </li> --}}
                @endcan
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.create') ? 'active' : '' }}" href="{{ route('admin.shop-categories.create') }}">{{ __('Create Shop Categories') }}</a></li>
                <li><a class="nav-link {{ request()->routeIs('admin.shop-categories.index') ? 'active' : '' }}" href="{{ route('admin.shop-categories.index') }}">{{ __('All Shop Categories') }}</a>
                </li> --}}
                {{-- <li><a class="nav-link {{ request()->routeIs('admin.shop.settlements') ? 'active' : '' }}" href="{{ route('admin.shop.settlements') }}">{{ __('Settlements') }}</a>
                </li>
                <li><a class="nav-link {{ request()->routeIs('admin.shop_order.index') ? 'active' : '' }}" href="{{ route('admin.shop_order.index') }}">{{ __('Shop Orders') }}</a>
                </li> --}}
            </ul>
        </li>
          @endcan
          @can('partner.create', 'partner.list', 'partner.settlements')
          <li class="dropdown {{ request()->routeIs('admin.partner.index') ? 'active' : '' }}
        {{ request()->routeIs('admin.partners.settlements') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-customer"></i> <span>{{ __('Partners') }}</span></a>
              <ul class="dropdown-menu">
                  @can('partner.list')
                  <li><a class="nav-link {{ request()->routeIs('admin.partner.index') ? 'active' : '' }}" href="{{ route('admin.partner.index') }}">{{ __('All Partner') }}</a></li>
                  @endcan
                  @can('partner.settlements')
                  <li><a class="nav-link {{ request()->routeIs('admin.partners.settlements') ? 'active' : '' }}" href="{{ route('admin.partners.settlements') }}">{{ __('Settlements') }}</a></li>
                  @endcan
                  @can('partner.settlements')
                  <li><a class="nav-link {{ request()->routeIs('admin.license.commission.view') ? 'active' : '' }}" href="{{ route('admin.license.commission.view') }}">{{ __('License Settings') }}</a>
                  </li>
                  @endcan
              </ul>
          </li>
          @endcan
          {{-- @can('customer.create', 'customer.list', 'customer.request', 'customer.list')
        <li class="dropdown {{ Request::is('admin/customer*') ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-customer"></i> <span>Customers</span></a>
          <ul class="dropdown-menu">
              @can('customer.create')
              <li><a class="nav-link {{ request()->routeIs('admin.customer.create') ? 'active' : '' }}" href="{{ route('admin.customer.create') }}">{{ __('Create Customer') }}</a></li>
              @endcan
              @can('customer.list')
              <li><a class="nav-link {{ request()->routeIs('admin.customer.index') ? 'active' : '' }}" href="{{ route('admin.customer.index') }}">{{ __('All Customers') }}</a></li>
              @endcan
              @can('customer.request')
              <li><a class="nav-link" href="{{ route('admin.customer.index','type=3') }}">{{ __('Customer Request') }}</a></li>
              @endcan
              @can('customer.list')
              <li><a class="nav-link" href="{{ route('admin.customer.index','type=2') }}">{{ __('Suspended Customers') }}</a></li>
              @endcan
          </ul>
          </li>
          @endcan --}}
          @can('domain.create', 'domain.list')
          <li class="dropdown {{ Request::routeIs('admin.domain.create') ? 'active' : '' }}
         {{ Request::routeIs('admin.domain.index') ? 'active' : '' }}
         {{ Request::routeIs('admin.customdomain.*') ? 'active' : '' }}
         ">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-www"></i>
                  <span>{{ __('Domains') }}</span></a>
              <ul class="dropdown-menu">
                  @can('domain.create')
                  <li><a class="nav-link {{ Request::routeIs('admin.domain.create') ? 'active' : '' }}" href="{{ route('admin.domain.create') }}">{{ __('Create Domain') }}</a></li>
                  @endcan
                  @can('domain.list')
                  <li>
                      <a class="nav-link {{ Request::routeIs('admin.domain.index') ? 'active' : '' }}" href="{{ route('admin.domain.index') }}">
                          {{ __('All Domains') }}
                      </a>
                  </li>
                  <li>
                      <a class="nav-link {{ Request::routeIs('admin.customdomain.*') ? 'active' : '' }}" href="{{ route('admin.customdomain.index') }}">
                          {{ __('Domains Requests') }}
                      </a>
                  </li>
                  @endcan
              </ul>
          </li>
          @endcan

          @can('cron_job')
          <li class="{{ Request::is('admin/cron') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.cron.index') }}">
                  <i class="flaticon-task"></i> <span>{{ __('Cron Jobs') }}</span>
              </a>
          </li>
          @endcan
          <li class="{{ Request::is('admin/cities*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.cities.index') }}">
                  <i class="flaticon-dashboard-1"></i> <span>{{ __('Cities') }}</span>
              </a>
          </li>
          <li class="{{ Request::is('admin/currencies*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.currencies.index') }}">
                  <i class="flaticon-pricing"></i> <span>{{ __('Currencies') }}</span>
              </a>
          </li>
          @can('payment_gateway.config')
          <li class="{{ Request::is('admin/payment-gateway*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.payment-gateway.index') }}">
                  <i class="flaticon-credit-card"></i> <span>{{ __('Payment Gateways') }}</span>
              </a>
          </li>
          @endcan
          @can('template.list')
          <li class="{{ Request::is('admin/template') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.template.index') }}">
                  <i class="flaticon-template"></i> <span>{{ __('Templates') }}</span>
              </a>
          </li>
          @endcan

          {{-- @can('language_edit')
        <li class="dropdown {{ request()->routeIs('admin.language.create') ? 'active' : '' }}
          {{ request()->routeIs('admin.language.index') ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-translation"></i> <span>{{ __('Language') }}</span></a>
          <ul class="dropdown-menu">
              <li><a class="nav-link {{ request()->routeIs('admin.language.create') ? 'active' : '' }}" href="{{ route('admin.language.create') }}">{{ __('Create language') }}</a></li>
              <li><a class="nav-link {{ request()->routeIs('admin.language.index') ? 'active' : '' }}" href="{{ route('admin.language.index') }}">{{ __('Manage language') }}</a></li>
          </ul>
          </li>
          @endcan --}}

          @can('site.settings')
          <li class="dropdown {{ request()->routeIs('admin.menu.index') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                <i class="flaticon-settings"></i> <span>{{ __('Appearance') }}</span>
            </a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ request()->routeIs('admin.menu.index') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}">{{ __('Menu') }}</a></li>
              </ul>
          </li>
          @endcan

          @can('marketing.tools')
          <li class="{{ request()->routeIs('admin.marketing.index') ? 'active' : '' }}">
              <a class="nav-link {{ request()->routeIs('admin.marketing.index') ? 'active' : '' }}" href="{{ route('admin.marketing.index') }}">
                  <i class="flaticon-megaphone"></i> <span>{{ __('Marketing Tools') }}</span>
              </a>
          </li>
          @endcan

          @can('site.settings', 'environment.settings')
          <li class="dropdown {{ request()->routeIs('admin.site.settings') ? 'active' : '' }}
         {{ request()->routeIs('admin.site.environment') ? 'active' : '' }} 
         {{ request()->routeIs('admin.site.channel') ? 'active' : '' }} 
         {{ request()->routeIs('admin.site.language') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-settings"></i> <span>{{ __('Settings') }}</span></a>
              <ul class="dropdown-menu">
                  @can('site.settings')
                  <li><a class="nav-link {{ request()->routeIs('admin.site.settings') ? 'active' : '' }}" href="{{ route('admin.site.settings') }}">{{ __('Site Settings') }}</a></li>
                  @endcan
                  @can('environment.settings')
                  <li><a class="nav-link {{ request()->routeIs('admin.site.environment') ? 'active' : '' }}" href="{{ route('admin.site.environment') }}">{{ __('System Environment') }}</a>
                  </li>
                  @endcan
                  <li><a class="nav-link {{ request()->routeIs('admin.site.channel') ? 'active' : '' }}" href="{{ route('admin.site.channel') }}">{{ __('Channel') }}</a>
                  </li>
                  <li><a class="nav-link {{ request()->routeIs('admin.site.language') ? 'active' : '' }}" href="{{ route('admin.site.language') }}">{{ __('Language') }}</a>
                  </li>
                  <li><a class="nav-link {{ request()->routeIs('admin.pwa.index') ? 'active' : '' }}" href="{{ route('admin.pwa.index') }}">{{ __('PWA Request') }}</a>
                  </li>

              </ul>
          </li>
          @endcan

          @can('admin.list', 'role.list')
          <li class="dropdown {{ request()->routeIs('admin.role.index') ? 'active' : '' }}
         {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-member"></i> <span>{{ __('Admins & Roles') }}</span></a>
              <ul class="dropdown-menu">
                  @can('role.list')
                  <li><a class="nav-link {{ request()->routeIs('admin.role.index') ? 'active' : '' }}" href="{{ route('admin.role.index') }}">{{ __('Roles') }}</a></li>
                  @endcan
                  @can('admin.list')
                  <li><a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">{{ __('Admins') }}</a></li>
                  @endcan
              </ul>
          </li>
          @endcan

          @endhasrole

          @if (current_shop_id() != 0)

          @php
          $plan_limit = user_limit();

          @endphp
          <li class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
              <a class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">
                  <i class="flaticon-dashboard"></i> <span>{{ __('Dashboard') }}</span>
              </a>
          </li>

          <li class="dropdown {{ Request::is('seller/orders/all') ? 'active' : '' }}
        {{ Request::is('seller/orders/canceled') ? 'active' : '' }}
        {{ request()->routeIs('seller.transaction.index') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-note"></i> <span>{{ __('Orders') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ Request::is('seller/orders/all') ? 'active' : '' }}" href="{{ url('/seller/orders/all') }}">{{ __('All Orders') }}</a></li>
                  <li><a class="nav-link {{ Request::is('seller/orders/canceled') ? 'active' : '' }}" href="{{ url('/seller/orders/canceled') }}">{{ __('Cancelled') }}</a></li>
                  <li>
                      <a class="nav-link {{ request()->routeIs('seller.transaction.index') ? 'active' : '' }}" href="{{ route('seller.transaction.index') }}">
                          {{ __('Transactions') }}</span>
                      </a>
                  </li>


              </ul>
          </li>

          <li class="dropdown {{ request()->routeIs('seller.products.index') ? 'active' : '' }}
        {{ request()->routeIs('seller.inventory.index') ? 'active' : '' }}
        {{ request()->routeIs('seller.category.index') ? 'active' : '' }}
        {{ request()->routeIs('seller.attribute.index') ? 'active' : '' }}
        {{ request()->routeIs('seller.brand.index') ? 'active' : '' }}
        {{ request()->routeIs('seller.coupon.index') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-box"></i> <span>{{ __('Products') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ request()->routeIs('seller.products.index') ? 'active' : '' }}" href="{{ route('seller.products.index') }}">{{ __('All Products') }}</a></li>
                  @if (isset($plan_limit))
                  <li>
                      <a class="nav-link {{ request()->routeIs('seller.inventory.index') ? 'active' : '' }}" @if (filter_var($plan_limit['inventory'])==true) href="{{ route('seller.inventory.index') }}" @endif>{{ __('Inventory') }}
                          @if (filter_var($plan_limit['inventory']) != true)
                          <i class="fa fa-lock text-danger"></i>
                          @endif
                      </a>
                  </li>
                  @endif
                  <li><a class="nav-link {{ request()->routeIs('seller.category.index') ? 'active' : '' }}" href="{{ route('seller.category.index') }}">{{ __('Categories') }}</a></li>
                  <li><a class="nav-link {{ request()->routeIs('seller.attribute.index') ? 'active' : '' }}" href="{{ route('seller.attribute.index') }}">{{ __('Attributes') }}</a></li>
                  <li><a class="nav-link {{ request()->routeIs('seller.brand.index') ? 'active' : '' }}" href="{{ route('seller.brand.index') }}">{{ __('Brands') }}</a></li>
                  <li><a class="nav-link {{ request()->routeIs('seller.coupon.index') ? 'active' : '' }}" href="{{ route('seller.coupon.index') }}">{{ __('Coupons') }}</a></li>
              </ul>
          </li>
          <li class="dropdown {{ Request::is('seller/customer*') ? 'active' : '' }} {{ Request::is('seller/review*') ? 'active' : '' }} }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-customer"></i> <span>{{ __('Customers') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ Request::is('seller/customer*') ? 'active' : '' }}" href="{{ route('seller.customer.index') }}">{{ __('All Customers') }}</a>
                  </li>
                  <li><a class="nav-link {{ Request::is('seller/review*') ? 'active' : '' }}" href="{{ route('seller.review.index') }}">{{ __('Review & Ratings') }}</a>
                  </li>
              </ul>
          </li>
          <li class="dropdown {{ Request::is('seller/delivery*') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-motorcycle"></i> <span>{{ __('Delivery Agents') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ Request::is('seller/delivery*') ? 'active' : '' }}" href="{{ route('seller.delivery.index') }}">{{ __('All Agents') }}</a></li>
              </ul>
          </li>


          <li class="{{ Request::is('seller/report*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('seller.report.index') }}">
                  <i class="flaticon-dashboard-1"></i> <span>{{ __('Analytics') }}</span>
              </a>
          </li>



          {{-- <li class="dropdown {{ Request::routeIs('seller.location.index') ? 'active' : '' }} {{ Request::routeIs('seller.theme.index') ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-delivery"></i> <span>{{ __('Shipping') }}</span></a>
          <ul class="dropdown-menu">
              <li><a class="nav-link" href="{{ route('seller.location.index') }}">{{ __('Locations') }}</a></li>
              <li><a class="nav-link" href="{{ route('seller.shipping.index') }}">{{ __('Shipping Price') }}</a></li>
          </ul>
          </li>


          <li class="dropdown {{ Request::is('seller/ads*') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-megaphone"></i> <span>{{ __('Offer & Ads') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link" href="{{ route('seller.ads.index') }}">{{ __('Bump Ads') }}</a></li>
                  <li><a class="nav-link" href="{{ route('seller.ads.show','banner') }}">{{ __('Banner Ads') }}</a></li>
              </ul>
          </li> --}}
          <li class="dropdown {{ Request::is('seller/settings/shop-settings') ? 'active' : '' }}
        {{ Request::is('seller/settings/payment') ? 'active' : '' }}
        {{ Request::routeIs('seller.location.index') ? 'active' : '' }}
        {{ Request::routeIs('seller.shipping.index') ? 'active' : '' }}
        {{ Request::is('seller/settings/plan') ? 'active' : '' }}
        {{ Request::routeIs('seller.domain.index') ? 'active' : '' }}
        {{-- {{ Request::routeIs('seller.users*') ? 'active' : '' }} --}}
          ">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-settings"></i> <span>{{ __('Settings') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ Request::is('seller/settings/shop-settings') ? 'active' : '' }}" href="{{ route('seller.settings.show', 'shop-settings') }}">{{ __('Shop Settings') }}</a>
                  </li>
                  @if ($shop_info->shop_type == 'seller')
                  {{-- <li><a class="nav-link {{ Request::is('seller/users*') ? 'active' : '' }}"
                  href="{{ route('seller.users.index') }}">{{ __('Users') }}</a>
          </li> --}}
          <li><a class="nav-link {{ Request::is('seller/settings/payment') ? 'active' : '' }}" href="{{ route('seller.settings.show', 'payment') }}">{{ __('Payment Options') }}</a>
          </li>
          @endif
          <li><a class="nav-link {{ Request::routeIs('seller.location.index') ? 'active' : '' }}" href="{{ route('seller.location.index') }}">{{ __('Locations') }}</a></li>
          <li><a class="nav-link {{ Request::routeIs('seller.shipping.index') ? 'active' : '' }}" href="{{ route('seller.shipping.index') }}">{{ __('Shipping Price') }}</a>
          </li>
          <li><a class="nav-link {{ Request::is('seller/settings/plan') ? 'active' : '' }}" href="{{ route('seller.settings.show', 'plan') }}">{{ __('Plans') }}</a></li>
          @if (domain_info('custom_domain') == true && $shop_info->shop_type == 'seller')
          <li><a class="nav-link {{ Request::routeIs('seller.domain.index') ? 'active' : '' }}" href="{{ route('seller.domain.index') }}">{{ __('Domain Settings') }}</a>
          </li>
          @endif
          <li><a class="nav-link {{ Request::is('seller.user.term') ? 'active' : '' }}" href="{{ route('seller.user.term') }}">{{ __('User Terms') }}</a></li>
          </ul>
          </li>
          @if ($shop_info->shop_type == 'seller')
          @if (domain_info('google_analytics') == true ||
          domain_info('whatsapp') == true ||
          domain_info('facebook_pixel') == true ||
          domain_info('gtm') == true)
          <li class="dropdown {{ Request::is('seller/marketing*') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-megaphone"></i> <span>{{ __('Marketing Tools') }}</span></a>
              <ul class="dropdown-menu">
                  @if (domain_info('google_analytics') == true)
                  <li><a class="nav-link {{ Request::is('seller/marketing/google-analytics') ? 'active' : '' }}" href="{{ route('seller.marketing.show', 'google-analytics') }}">{{ __('Google Analytics') }}</a>
                  </li>
                  @endif
                  @if (domain_info('gtm') == true)
                  <li><a class="nav-link {{ Request::is('seller/marketing/tag-manager') ? 'active' : '' }}" href="{{ route('seller.marketing.show', 'tag-manager') }}">{{ __('Google Tag Manager') }}</a>
                  </li>
                  @endif
                  @if (domain_info('facebook_pixel') == true)
                  <li><a class="nav-link {{ Request::is('seller/marketing/facebook-pixel') ? 'active' : '' }}" href="{{ route('seller.marketing.show', 'facebook-pixel') }}">{{ __('Facebook Pixel') }}</a>
                  </li>
                  @endif
                  @if (domain_info('whatsapp') == true)
                  <li><a class="nav-link {{ Request::is('seller/marketing/whatsapp') ? 'active' : '' }}" href="{{ route('seller.marketing.show', 'whatsapp') }}">{{ __('Whatsapp API') }}</a>
                  </li>
                  @endif
              </ul>
          </li>
          @endif
          {{-- <li class="{{ Request::routeIs('seller.support') ? 'active' : '' }}">
          @if (isset($plan_limit))
          <a class="nav-link {{ Request::routeIs('seller.support') ? 'active' : '' }}" @if (filter_var($plan_limit['live_support'])==true) href="{{ route('seller.support') }}" @endif>
              @if (filter_var($plan_limit['live_support']) != true) <i class="fa fa-lock text-danger"></i> @else <i class="fa fa-user"></i> @endif <span>{{ __('Support') }} </span>
          </a>
          @endif
          </li> --}}

          <li class="menu-header">{{ __('SALES CHANNELS') }}</li>
          <li class="dropdown {{ Request::routeIs('seller.theme.index') ? 'active' : '' }}
        {{ Request::routeIs('seller.menu.index') ? 'active' : '' }}
        {{ Request::routeIs('seller.page.index') ? 'active' : '' }}
        {{ Request::routeIs('seller.slider.index') ? 'active' : '' }}
        {{ Request::routeIs('seller.seo.index') ? 'active' : '' }}
        {{ Request::routeIs('seller.bump-ads*') ? 'active' : '' }}
        {{ Request::routeIs('seller.banner-ads*') ? 'active' : '' }}
        ">
              <a href="#" class="nav-link has-dropdown"><i class="flaticon-shop"></i>
                  <span>{{ __('Online store') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ Request::routeIs('seller.theme.index') ? 'active' : '' }}" href="{{ route('seller.theme.index') }}">{{ __('Themes') }}</a></li>
                  <li><a class="nav-link {{ Request::routeIs('seller.menu.index') ? 'active' : '' }}" href="{{ route('seller.menu.index') }}">{{ __('Menus') }}</a></li>
                  <li><a class="nav-link {{ Request::routeIs('seller.page.index') ? 'active' : '' }}" href="{{ route('seller.page.index') }}">{{ __('Pages') }}</a></li>
                  <li><a class="nav-link {{ Request::routeIs('seller.slider.index') ? 'active' : '' }}" href="{{ route('seller.slider.index') }}">{{ __('Sliders') }}</a></li>
                  <li><a class="nav-link {{ Request::routeIs('seller.seo.index') ? 'active' : '' }}" href="{{ route('seller.seo.index') }}">{{ __('SEO') }}</a></li>
                  <li><a class="nav-link {{ Request::routeIs('seller.bump-ads.index') ? 'active' : '' }}" href="{{ route('seller.bump-ads.index') }}">{{ __('Bump Ads') }}</a></li>
                  <li><a class="nav-link {{ Request::routeIs('seller.banner-ads.index') ? 'active' : '' }}" href="{{ route('seller.banner-ads.index') }}">{{ __('Banner Ads') }}</a>
                  </li>
              </ul>
          </li>
          <li class="dropdown {{ Request::routeIs('seller/users*') ? 'active' : '' }}
                  {{ Request::routeIs('seller.users*') ? 'active' : '' }}
                  {{ Request::routeIs('seller.products.pos*') ? 'active' : '' }}">

              <a href="#" class="nav-link has-dropdown"><i class="fa fa-building"></i> <span>{{ __('Point Of Sales') }}</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ Request::routeIs('seller/users*') ? 'active' : '' }}" href="{{ route('seller.users.index') }}">{{ __('Users') }}</a></li>
                  <li><a class="nav-link {{ request()->routeIs('seller.products.pos') ? 'active' : '' }}" href="{{ route('seller.products.pos') }}">{{ __('POS Products') }}</a></li>
              </ul>
          </li>

          <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
              <a href="{{ url('/') }}" class="btn btn-success btn-lg btn-block btn-icon-split">
                  <i class="fas fa-external-link-alt"></i>{{ __('Your Website') }}
              </a>
          </div>
          @endif
          @endif
          @if (Auth::user()->isPartner())
          <li class="{{ Request::routeIs('partner.dashboard') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('partner.dashboard') }}">
                  <i class="flaticon-dashboard"></i> <span>{{ __('Dashboard') }}</span>
              </a>
          </li>
          <li class="dropdown {{ request()->routeIs('partner.shop.create') ? 'active' : '' }}
        {{ request()->routeIs('partner.shop.index') ? 'active' : '' }}">
              <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="flaticon-shop"></i> <span>Stores</span></a>
              <ul class="dropdown-menu">
                  <li><a class="nav-link {{ request()->routeIs('partner.shop.create') ? 'active' : '' }}" href="{{ route('partner.shop.create') }}">{{ __('Create Store') }}</a></li>
                  <li><a class="nav-link {{ request()->routeIs('partner.shop.index') ? 'active' : '' }}" href="{{ route('partner.shop.index') }}">{{ __('All Store') }}</a></li>
              </ul>
          </li>
          <li class="{{ request()->routeIs('partner.license.plans') ? 'active' : '' }}">
              <a class="nav-link {{ request()->routeIs('partner.license.plans') ? 'active' : '' }}" href="{{ route('partner.license.plans') }}">
                  <i class="fas fa-user-tag" style="font-size: 20px;"></i>
                  <span>{{ __('License') }}</span>
              </a>
          </li>
          <li class="{{ request()->routeIs('partner.order.index') ? 'active' : '' }}">
              <a class="nav-link {{ request()->routeIs('partner.order.index') ? 'active' : '' }}" href="{{ route('partner.order.index') }}">
                  <i class="flaticon-pricing"></i> <span>{{ __('Subscriptions') }}</span>
              </a>
          </li>
          <li class="{{ request()->routeIs('partner.settlement.tool') ? 'active' : '' }}">
              <a class="nav-link {{ request()->routeIs('partner.settlement.index') ? 'active' : '' }}" href="{{ route('partner.settlement.index') }}">
                  <i class="flaticon-credit-card"></i> <span>{{ __('Settlements') }}</span>
              </a>
          </li>
          <li>
              <a class="nav-link" href="/assets/pdf/partner_toolkit.pdf" target=_blank}>
                  <i class="flaticon-task"></i> <span>{{ __('Download setup toolkit') }}</span>
              </a>
          </li>
          @endif
          @if (Auth::user()->isInActivePartner())
          <li class="{{ Request::routeIs('partner.dashboard') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('partner.dashboard') }}">
                  <i class="flaticon-dashboard"></i> <span>{{ __('Dashboard') }}</span>
              </a>
          </li>
          @endif
      </aside>
  </div>