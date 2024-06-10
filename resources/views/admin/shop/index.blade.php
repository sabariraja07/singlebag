@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Shops'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-sm-8">
            <a href="{{ route(Route::currentRouteName()) }}" class="mr-2 btn btn-outline-primary @if($type==="all") active @endif">{{ __('All') }} ({{ $all }})</a>

            <a href="{{ route(Route::currentRouteName(),'type=active') }}" class="mr-2 btn btn-outline-success @if($type== 'active') active @endif">{{ __('Active') }} ({{ $actives }})</a>

            <a href="{{ route(Route::currentRouteName(),'type=suspended') }}" class="mr-2 btn btn-outline-warning @if($type== 'suspended') active @endif">{{ __('Suspended') }} ({{ $suspened }})</a>

             <a href="{{ route(Route::currentRouteName(),'type=pending') }}" class="mr-2 btn btn-outline-info @if($type== 'pending') active @endif">{{ __('Pending') }} ({{ $pendings }})</a>


            <a href="{{ route(Route::currentRouteName(),'type=trash') }}" class="mr-2 btn btn-outline-danger @if($type == 'trash') active @endif">{{ __('Trash') }} ({{ $trash }})</a>
            <a href="{{ route(Route::currentRouteName(),'type=expired') }}" class="mr-2 btn btn-outline-warning @if($type == 'expired') active @endif">{{ __('Expired') }} ({{ $expired }})</a>
          </div>

          <div class="col-sm-4 text-right">
            @can('shop.create')
            <a href="{{ route('admin.shop.create') }}" class="btn btn-success">{{ __('Create Shop') }}</a>
            @endcan
          </div>
        </div>

        <div class="float-right">
          <form>
            <input type="hidden" name="type" value="@if($type === 0) trash @else {{ $type }} @endif">
            <div class="input-group mb-2">

              <input type="text" id="src" class="form-control" placeholder="Search..." required="" name="src" autocomplete="off" value="{{ $request->src ?? '' }}">
              <select class="form-control selectric" name="term" id="term">
                <option value="domain">{{ __('Search By Domain') }}</option>
                <option value="shop">{{ __('Search By Shop') }}</option>
                <option value="customer">{{ __('Search By Customer') }}</option>
              </select>
              <div class="input-group-append">                                            
                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>

        <form method="post" action="{{ route('admin.shops.destroy') }}" class="basicform_with_reload">
          @csrf
          <div class="float-left mb-1">
            @can('shop.delete')
            <div class="input-group">
              <select class="form-control selectric" name="method">
                <option value="" >{{ __('Select Action') }}</option>
                <option value="active" >{{ __('Publish') }}</option>
                <option value="suspended" >{{ __('Suspend') }}</option>
                <option value="pending" >{{ __('Move To Pending') }}</option>
                 @if($type !== "trash")
                <option value="trash" >{{ __('Move To Trash') }}</option>
                @endif
                @if($type=="trash")
                <option value="delete" >{{ __('Delete Permanently') }}</option>
                @endif
              </select>
              <div class="input-group-append">                                            
                <button class="btn btn-success basicbtn" type="submit">{{ __('Submit') }}</button>
              </div>
            </div>
            @endcan
          </div>
          

          <div class="table-responsive">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                  <th><input type="checkbox" class="checkAll"></th>

                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Admin') }}</th>
                  <th>{{ __('Domain') }}</th>
                  @if( Route::currentRouteName() == 'admin.shop.index')
                  <th>{{ __('Shop Type') }}</th>
                  @endif
                  <th>{{ __('Storage Used') }}</th>
                  <th>{{ __('Mobile Number') }}</th>
                  <th>{{ __('Plan') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Join at') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">
                  <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                  <td>{{ $row->name }}</td>
                  <td>{{ $row->user->fullname }}</td>
                  <td>{{ $row->domain->domain ?? '' }}</td>
                  @if( Route::currentRouteName() == 'admin.shop.index')
                  <td>{{ ucfirst($row->shop_type) ?? '' }}</td>
                  @endif
                  <td>@php $var = json_decode($row->subscription->plan->data ?? '' ) @endphp {{ storesStorageSize($row->id) }} / {{  isset($var->storage) && $var->storage == -1 ? __('Unlimited') : human_filesize($var->storage ?? 0) }}</td>
                  <td>{{ $row->mobilenumber ?? ''}}</td>
                  <td>{{ $row->subscription->plan->name ?? '' }}</td>
                  <td>
                    @if($row->status== 'active') <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($row->status== 'trash') <span class="badge badge-danger">{{ __('Trash') }}</span>
                    @elseif($row->status== 'suspended') <span class="badge badge-warning">{{ __('Suspended') }}</span>
                    @elseif($row->status== 'pending') <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @endif
                  </td>
                  <td>{{ $row->created_at->format('d-M-Y')  }}</td>
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Action') }}
                      </button>
                      <div class="dropdown-menu">
                        @can('shop.edit')
                        <a class="dropdown-item has-icon" href="{{ route('admin.shop.edit',$row->id) }}"><i class="fas fa-user-edit"></i> {{ __('Edit') }}</a>
                        <a class="dropdown-item has-icon" href="{{ route('admin.shop.planedit',$row->id) }}"><i class="far fa-edit"></i> {{ __('Edit Plan Info') }}</a>
                        @endcan
                        @can('shop.view')
                        <a class="dropdown-item has-icon" href="{{ route('admin.shop.show',$row->id) }}"><i class="far fa-eye"></i>{{ __('View') }}</a>
                        @endcan
                        <a class="dropdown-item has-icon" href="{{ route('admin.order.plans',$row->id) }}"><i class="fas fa-cart-arrow-down"></i>{{ __('Make Order') }}</a>
                        <a class="dropdown-item has-icon" href="{{ route('admin.shop.show',$row->id) }}"><i class="far fa-envelope"></i>{{ __('Send Email') }}</a>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                 <th><input type="checkbox" class="checkAll"></th>
                 <th>{{ __('Name') }}</th>
                 <th>{{ __('Admin') }}</th>
                 <th>{{ __('Domain') }}</th>
                 @if( Route::currentRouteName() == 'admin.shop.index')
                 <th>{{ __('Shop Type') }}</th>
                 @endif
                 <th>{{ __('Storage Used') }}</th>
                 <th>{{ __('Mobile Number') }}</th>
                 <th>{{ __('Plan') }}</th>
                 <th>{{ __('Status') }}</th>
                 <th>{{ __('Join at') }}</th>
                 <th>{{ __('Action') }}</th>
               </tr>
             </tfoot>
           </table>
           
         </div>
       </form>
        {{ $posts->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
     </div>
   </div>
 </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush