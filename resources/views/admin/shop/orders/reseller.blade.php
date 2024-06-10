@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Shops Order'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-sm-8">
            <a href="{{ route(Route::currentRouteName()) }}" class="mr-2 btn btn-outline-primary">{{ __('All Order') }} ({{ $all }}) </a>

            <a href="{{ route(Route::currentRouteName(),'type=processing') }}" class="mr-2 btn btn-outline-success">{{ __('Processing Order') }}  ({{ $processing }})</a>

            <a href="{{ route(Route::currentRouteName(),'type=completed') }}" class="mr-2 btn btn-outline-warning">{{ __('Completed Order') }} ({{ $completed }}) </a>

             <a href="{{ route(Route::currentRouteName(),'type=delivered') }}" class="mr-2 btn btn-outline-info" >{{ __('Delivered Order') }} ({{ $deliverd }}) </a>

          </div>

        </div>

        <div class="float-right">
          <form>
           
            <div class="input-group mb-2" style="margin-top: -43px !important;">

              <input type="text" id="src" class="form-control" placeholder="Search..." required="" name="src" autocomplete="off" value="{{ $request->src ?? '' }}">
              <select class="form-control selectric" name="term" id="term">
                <option value="order_id">{{ __('Search By Order') }}</option>
              </select>
              <div class="input-group-append">                                            
                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>

        <form method="post" action="{{ route('admin.shops.destroy') }}" class="basicform_with_reload">
          @csrf
          {{-- <div class="float-left mb-1">
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
          </div> --}}
          

          <div class="table-responsive">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                  <th>{{ __('Order ID') }}</th>
                  <th>{{ __('Date') }}</th>
                  <th>{{ __('Shop') }}</th>
                  <th>{{ __('Reseller') }}</th>
                  @if( Route::currentRouteName() == 'admin.shop_order.index')
                  <th>{{ __('Store Type') }}</th>
                  @endif
                  <th>{{ __('Amount') }}</th>
                  <th class="text-right">{{ __('Tax') }}</th>
                  <th class="text-right">{{ __('Commission') }}</th>
                  <th class="text-right">{{ __('Settlement Amount') }}</th>
                  <th>{{ __('Method') }}</th>
                  <th>{{ __('Payment Status') }}</th>
                  <th>{{ __('Fulfillment') }}</th>
                  {{-- <th>{{ __('Action') }}</th> --}}
                </tr>
              </thead>
               <tbody>
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">
                  <td><a href="{{ route('admin.shop_order.show',$row->id) }}">{{ $row->order_no ?? '' }}</a></td></td>
                  <td>{{ $row->created_at->format('d-M-Y')  }}</td>
                  @php
                  $find_order = App\Models\OrderSettlement::where('order_id', $row->id)->whereNotNull('settlement_id')->first();
                  if(!empty($find_order)){
                    $settlement = App\Models\Settlement::where('id', $find_order->settlement_id)->first();
                  }
                  else{
                    $settlement = '';
                  }
                  @endphp
                  @php
                  $group_order = \App\Models\Order::where('id', $row->group_order_id)->first();
                  if($group_order)
                  $get_shop = \App\Models\Shop::where('id', $group_order->shop_id)->first();
                  @endphp
                  <td>
                    @if(isset($row->shop))
                      {{ $row->shop->name }}
                    @else
                      {{ "--" }}
                    @endif
                  </td>
                  <td>
                    {{ $get_shop->name ?? ''}}
                  </td>
                  @if( Route::currentRouteName() == 'admin.shop_order.index')
                  <td>{{ ucfirst($row->shop->shop_type ?? '') }}</td>
                  @endif
                  <td>{{ amount_format($row->total) }}</td>
                  <td>{{ amount_format($row->tax) }}</td>
                  <td>{{ amount_format($row->commission) }}</td>
                  <td>{{ amount_format($row->order_settlement_sum_total) }}</td>
                  <td>{{ strtoupper($row->payment_method ?? '')  }}</td>
                  <td>
                    @if($row->payment_status==1)
                    <span class="badge badge-success">{{ __('Paid') }}</span>
                    @elseif($row->payment_status == 2)
                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @else
                    <span class="badge badge-danger">{{ __('Fail') }}</span>
                    @endif
                  </td>
                  <td>
                    @if($row->status== 'completed') <span class="badge badge-success">{{ __('Completed') }}</span>
                    @elseif($row->status== 'delivered') <span class="badge badge-primary">{{ __('Delivered') }}</span>
                    @elseif($row->status== 'pending') <span class="badge bg-info text-dark">{{ __('Pending') }}</span>
                    @elseif($row->status== 'processing') <span class="badge badge-warning">{{ __('Processing') }}</span>
                    @elseif($row->status== 'ready-for-pickup') <span class="badge badge-light">{{ __('Ready for pickup') }}</span>
                    @elseif($row->status== 'picked_up') <span class="badge badge-warning">{{ __('picked_up') }}</span>
                    @elseif($row->status== 'archived') <span class="badge badge-success">{{ __('Archived') }}</span>
                    @elseif($row->status== 'canceled') <span class="badge badge-danger">{{ __('Cancelled') }}</span>
                    @endif
                  </td>
                  {{-- <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Action') }}
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item has-icon" href="{{ route('admin.shop_order.show',$row->id) }}"><i class="far fa-eye"></i>{{ __('View') }}</a>
                        
                      </div>
                    </div>
                  </td> --}}
                </tr>
                @endforeach
              </tbody> 
              <tfoot>
                <tr>
                  <th>{{ __('Order ID') }}</th>
                  <th>{{ __('Date') }}</th>
                  <th>{{ __('Shop') }}</th>
                  <th>{{ __('Reseller') }}</th>
                    @if( Route::currentRouteName() == 'admin.shop_order.index')
                    <th>{{ __('Store Type') }}</th>
                    @endif
                    <th>{{ __('Amount') }}</th>
                    <th class="text-right">{{ __('Tax') }}</th>
                    <th class="text-right">{{ __('Commission') }}</th>
                    <th class="text-right">{{ __('Settlement Amount') }}</th>
                    <th>{{ __('Method') }}</th>
                    <th>{{ __('Payment Status') }}</th>
                    <th>{{ __('Fulfillment') }}</th>
                    {{-- <th>{{ __('Action') }}</th> --}}
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