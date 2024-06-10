@extends('layouts.app')

@section('content')
<div class="">
	<div class="row justify-content-center">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="float-right">
						<form>
							<div class="input-group mb-2">

								<input type="text" id="src" class="form-control" placeholder="Search..." required="" name="src" autocomplete="off" value="{{ $request->src ?? '' }}">
								<select class="form-control selectric" name="term" id="term">
									<option value="plans.name">{{ __('Search By Plan Name') }}</option>
									<option value="users.email">{{ __('Search By User Mail') }}</option>
                                    <option value="users.first_name">{{ __('Search By User First Name') }}</option>
                                    <option value="users.last_name">{{ __('Search By User Last Name') }}</option>
								</select>
								<div class="input-group-append">                                            
									<button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
								</div>
							</div>
						</form>
					</div>


						<div class="table-responsive">
							<table class="table table-hover table-nowrap card-table text-center">
								<thead>
									<tr>
                                        <th>{{ __('Partner') }}</th>
										<th>{{ __('Plan') }}</th>
										<th class="text-right">{{ __('Amount') }}</th>
										<th class="text-right">{{ __('Tax') }}</th>
										<th class="text-right">{{ __('Discount') }}</th>
                                        <th class="text-right">{{ __('License') }}</th>
										{{-- <th class="text-right">{{ __('Commission') }}</th> --}}
										{{-- <th class="text-right">{{ __('Action') }}</th> --}}
									</tr>
								</thead>
								<tbody class="list font-size-base rowlink" data-link="row">
									@foreach($posts ?? [] as $key => $row)
									<tr>
                                        <th>{{ $row->fullname }}</th>
                                        <th>{{ $row->plan_name }}</th>
										<td class="text-right">{{ amount_format($row->amount) }}</td>
										<td class="text-right">{{ amount_format($row->tax ?? 0) }}</td>
                                        <td class="text-right">{{ amount_format($row->discount ?? 0) }}</td>
                                        <td class="text-right">{{ $row->subscription_count ?? 0 }}</td>
										{{-- <td>{{ amount_format($row->commission ?? 0) }}</td> --}}
										{{-- <td> 
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('Action') }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item has-icon" href="{{ route('admin.order.edit',$row->id) }}"><i class="far fa-edit"></i> {{ __('Edit') }}</a>
                                                    <a class="dropdown-item has-icon" href="{{ route('admin.order.show',$row->id) }}"><i class="far fa-eye"></i> {{ __('View') }}</a>
                                                    <a class="dropdown-item has-icon" href="{{ route('admin.order.invoice',$row->id) }}"><i class="fa fa-file-invoice"></i> {{ __('Download Invoice') }}</a>

                                                </div>
                                            </div>
                                        </td> --}}
									</tr>	
									@endforeach
								</tbody>
							</table>

						</div>
					</div>
				<div class="card-footer d-flex justify-content-between">
					{{ $posts->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
				</div>
			</div>
		</div>
	</div>   
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush