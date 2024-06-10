@extends('layouts.seller')

@section('title', 'Shops')
@section('content')
<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">{{ __('Shops') }}</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('My Shops') }}
                            </li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
		</div>
	</div>
	<div class="content-body">
		<!-- Basic table -->
        <div class="row">
            @foreach($shops as $shop)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex flex-row align-items-center">
                                <div class="avatar me-1">
                                    <img src="{{ current_shop_logo_url() }}" onerror="this.src='./images/shop1.png'" alt="Avatar" width="90" height="90">
                                </div>
                                <div class="user-info">
                                    <h5 class="mb-0">{{ $shop->name }}</h5>
                                    <small class="text-muted" style="word-break: break-all;">{{ $shop->email }}</small>
                                    {{-- <div class="d-flex justify-content-between">
                                        <span>Total 4 users</span>
                                    </div> --}}
                                </div>
                            </div>
                        </div> @php $shop = App\Models\Shop::where('will_expire', '>=',  \Carbon\Carbon::now()->format('Y-m-d'))->where('id', $shop->domain->shop_id)->first(); @endphp
                        <div class="d-flex justify-content-end align-items-end mt-1 pt-25">
                            @if(empty($shop))
                                <small class="fw-bolder">{{ __('Store Suspended') }}</small>
                            @else
                            <a href="/seller/switch-store?url={{ $shop->domain->full_domain }}" class="role-edit-modal">
                                <small class="fw-bolder">{{ __('Switch Store') }}</small>
                            @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card d-flex flex-column">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end justify-content-center h-100">
                                <img src="./images/shop2.png" class="img-fluid mt-2" alt="Image" width="110">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0 mb-1">
                                <a href="{{ route('seller.shop.create') }}" class="stretched-link text-nowrap add-new-role">
                                    <span class="btn btn-primary mb-1 waves-effect waves-float waves-light">{{ __('Create Shop') }}</span>
                                </a>
                                <p class="mb-0">{{ __('Add or manage your own store') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
