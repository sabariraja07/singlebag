@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Plans'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
              @if (Session::has('error'))
                  <ul class="alert alert-danger">
                      <li>{{ Session::get('error') }}</li>
                  </ul>
              @endif
              @if (Session::has('success'))
                  <ul class="alert alert-success">
                      <li>{{ Session::get('success') }}</li>
                  </ul>
              @endif
          </div>
        </div>
          <form method="post" action="" class="basicform_with_reload">
            @csrf
            <div class="float-right">
              @can('plan.create')
              <a href="{{ route('admin.plan.create') }}" class="btn btn-success">{{ __('Create Plan') }}</a>
              @endcan
            </div> 
          <div class="table-responsive" style="padding-top: 12px !important;">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                  <th>{{ __('S.No') }}</th>
                  <th>{{ __('Shop Type') }}</th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Price') }}</th>
                  <th>{{ __('Duration') }}</th>
                  <th>{{ __('Users') }}</th>
                  <th>{{ __('Featured') }}</th>
                  <th>{{ __('Is Trial') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Created at') }}</th>
                  <th width="15%">{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @php $i=1; @endphp
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">

                  <td>{{ $i++ }}</td>
                  <td>{{ $row->shop_type ? : 'Seller'}}</td>
                  <td>{{ $row->name  }}</td>
                  <td>{{ $row->price  }}</td>
                  <td>@if($row->days == 36500) {{ __('Unlimited') }} @elseif($row->days == 365) {{ __('Yearly') }} @elseif($row->days == 30) {{ __('Monthly') }} @else {{ $row->days }}  {{ __('Days') }} @endif</td>
                  <td>{{ $row->active_users_count  }}</td>
                  <td>@if($row->featured==1) <span class="badge badge-success  badge-sm">{{ __('Yes') }}</span> @else <span class="badge badge-danger  badge-sm">{{ __('No') }}</span> @endif</td>
                  <td>@if($row->is_trial==1) <span class="badge badge-success  badge-sm">{{ __('Yes') }}</span> @else <span class="badge badge-danger  badge-sm">{{ __('No') }}</span> @endif</td>
                  <td>@if($row->status==1) <span class="badge badge-success  badge-sm">{{ __('Active') }}</span> @elseif($row->status==0) <span class="badge badge-danger  badge-sm">{{ __('Inactive') }}</span> @endif</td>
                  <td>{{ $row->created_at->diffforHumans()  }}</td>
                  <td>
                    @can('plan.edit')
                    <a href="{{ route('admin.plan.edit',$row->id) }}" class="btn btn-info btn-sm text-center"><i class="far fa-edit"></i></a>
                    @endcan
                    @can('plan.show')
                    <a href="{{ route('admin.plan.show',$row->id) }}" class="btn btn-success btn-sm text-center"><i class="far fa-eye"></i></a>
                    @endcan
                    <a href="{{ route('admin.plans.destroy',$row->id) }}" class="btn btn-success btn-sm text-center"><i class="far fa-trash-alt"></i></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                 <th>{{ __('S.No') }}</th>
                  <th>{{ __('Shop Type') }}</th>
                  <th>{{ __('Name') }}</th>
                 <th>{{ __('Price') }}</th>
                 <th>{{ __('Duration') }}</th>
                 <th>{{ __('Users') }}</th>
                 <th>{{ __('Featured') }}</th>
                 <th>{{ __('Is Trail') }}</th>
                 <th>{{ __('Status') }}</th>
                 <th>{{ __('Created at') }}</th>
                 <th>{{ __('Action') }}</th>
               </tr>
             </tfoot>
           </table>
         </div>
       </form>
     </div>
   </div>
 </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush