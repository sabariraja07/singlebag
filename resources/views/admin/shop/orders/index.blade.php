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
            <a href="{{ route('admin.shop_order.index') }}" class="mr-2 btn btn-outline-primary">{{ __('All Order') }} ({{ $all }}) </a>

            <a href="{{ route('admin.shop_order.index','type=processing') }}" class="mr-2 btn btn-outline-success">{{ __('Processing Order') }}  ({{ $processing }})</a>

            <a href="{{ route('admin.shop_order.index','type=completed') }}" class="mr-2 btn btn-outline-warning">{{ __('Completed Order') }} ({{ $completed }}) </a>

             <a href="{{ route('admin.shop_order.index','type=delivered') }}" class="mr-2 btn btn-outline-info" >{{ __('Delivered Order') }} ({{ $deliverd }}) </a>

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
                  <th>{{ __('Invoice Number') }}</th>
                  <th>{{ __('Store Name') }}</th>
                  <th>{{ __('Store Type') }}</th>
                  <th>{{ __('Amount') }}</th>
                  <th>{{ __('Date') }}</th>
                  <th>{{ __('Payment Method') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
               <tbody>
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">
                  <td>{{ $row->order_no ?? '' }}</td>
                  @php
                  $find_order = App\Models\OrderSettlement::where('order_id', $row->id)->whereNotNull('settlement_id')->first();
                  if(!empty($find_order)){
                    $settlement = App\Models\Settlement::where('id', $find_order->settlement_id)->first();
                  }
                  else{
                    $settlement = '';
                  }
                  @endphp
                  @if($settlement == '')
                  <td></td>
                  @else
                  <td>{{  $settlement->invoice_no ?? '' }}</td>
                  @endif
                  <td>{{ ucfirst($row->shop->name ?? '') }}</td>
                  <td>{{ ucfirst($row->shop->shop_type ?? '') }}</td>
                  <td>{{ amount_format($row->total) }}</td>
                  <td>{{ $row->created_at->format('d-M-Y')  }}</td>
                  <td>{{ strtoupper($row->payment_method ?? '')  }}</td>
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
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Action') }}
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item has-icon" href="{{ route('admin.shop_order.show',$row->id) }}"><i class="far fa-eye"></i>{{ __('View') }}</a>
                        
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody> 
              <tfoot>
                <tr>
                    <th>{{ __('Order ID') }}</th>
                    <th>{{ __('Store Name') }}</th>
                    <th>{{ __('Store Type') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Payment Method') }}</th>
                    <th>{{ __('Status') }}</th>
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