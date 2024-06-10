@extends('layouts.seller')
@section('title', 'Suppliers')

@section('content')
<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">{{ __('Supplier List') }}</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Supplier List') }}
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
        <div class="card">
            <div class="card-body">
                <br>
                <div class="float-right">
                    <form>
                        <div class="input-group mb-2">

                            <input type="text" id="src" class="form-control" placeholder="Search..." required="" name="src"
                                autocomplete="off" value="{{ $src ?? '' }}">
                            <select class="form-control selectric d-none" name="type" id="type">
                                <option value="title">{{ __('Search By Name') }}</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="ph-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($shops as $shop)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex flex-row align-items-center">
                                <div class="avatar me-1">
                                    <img src="{{ get_shop_logo_url($shop->id) }}" onerror="this.src='./images/shop1.png'" alt="Avatar" width="90" height="90">
                                </div>
                                <div class="user-info">
                                    <h5 class="mb-0">{{ ucfirst($shop->name) }}</h5>
                                    <span class="mb-0">{{ get_shop_category($shop->id) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-end mt-1 pt-25">
                            <a href="{{ route('seller.supplier.products', ['supplier' => $shop->id]) }}" class="role-edit-modal">
                                <small class="fw-bolder btn btn-primary">{{ __('View Products') }}</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
	</div>
</div>
@endsection
