@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Shop Category'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-sm-8">
          </div>

          <div class="col-sm-4 text-right">
            @can('shop.create')
              <a href="{{ route('admin.shop-categories.create') }}" class="btn btn-success">{{ __('Create Shop Category') }}</a>
            @endcan
          </div>
        </div>
        <form method="post" action="{{ route('admin.shops.destroy') }}" class="basicform_with_reload">
          @csrf
          <div class="table-responsive">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                  <th>{{ __('Title') }}</th>
                  <th>{{ __('Description') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categories as $row)
                <tr>
                  <td>{{ $row->name }}</td>
                  <td>{{ $row->description ?? "" }}</td>
                  <td>
                    @if ($row->status == 1)
                      <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($row->status == 0)
                      <span class="badge badge-danger">{{ __('Inactive') }}</span>
                    @endif
                  </td>
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Action') }}
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item has-icon" href="{{ route('admin.shop-categories.edit',$row->id) }}"><i class="fas fa-user-edit"></i> {{ __('Edit') }}</a>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                 <th>{{ __('Title') }}</th>
                 <th>{{ __('Description') }}</th>
                 <th>{{ __('Status') }}</th>
                 <th>{{ __('Action') }}</th>
               </tr>
             </tfoot>
           </table>
           
         </div>
     </div>
   </div>
 </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush