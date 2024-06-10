@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Stores'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                 
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Email') }}</th>
                  <th>{{ __('Plan') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Order at') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">
                
                  <td><a href="{{ route('admin.shop.show',$row->id) }}">{{ $row->name ?? '' }}</a></td>
                  <td><a href="mailto:{{ $row->user->email }}">{{ $row->user->email }}</a></td>
                 
                  <td>{{ $row->subscription->plan_info->name ?? '' }}</td>
                  <td>
                    
                    @if($row->subscription->status==1) <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($row->subscription->status==0) <span class="badge badge-danger">{{ __('Trash') }}</span>
                    @elseif($row->subscription->status==2) <span class="badge badge-warning">{{ __('Suspended') }}</span>
                    @elseif($row->subscription->status==3) <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @endif
                  </td>
                  <td>{{ $row->subscription->created_at->format('d-F-Y')  }}</td>
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                      </button>
                      <div class="dropdown-menu">
                       
                         @can('customer.edit')
                        <a class="dropdown-item has-icon" href="{{ route('admin.customer.edit',$row->id) }}"><i class="far fa-edit"></i> Edit</a>
                         @endcan
                          @can('customer.view')
                        <a class="dropdown-item has-icon" href="{{ route('admin.customer.show',$row->id) }}"><i class="far fa-eye"></i>View</a>
                         @endcan

                         <a class="dropdown-item has-icon" href="{{ route('admin.order.create','email='.$row->email) }}"><i class="fas fa-cart-arrow-down"></i>Make Order</a>

                         <a class="dropdown-item has-icon" href="{{ route('admin.customer.show',$row->id) }}"><i class="far fa-envelope"></i>Send Email</a>
                      </div>
                    </div>
                   

                  </td>
                </tr>
                @endforeach
              </tbody>
              
           </table>
           
         </div>
      
        {{ $posts->links('vendor.pagination.bootstrap-4') }}
     </div>
   </div>
 </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush