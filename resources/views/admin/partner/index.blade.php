@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Partners'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-sm-8">
            <a href="{{ route('admin.partner.index') }}" class="mr-2 btn btn-outline-primary @if($type==="all") active @endif">{{ __('All') }} ({{ $all }})</a>

            <a href="{{ route('admin.partner.index','type=1') }}" class="mr-2 btn btn-outline-success @if($type==1) active @endif">{{ __('Active') }} ({{ $actives }})</a>

            <a href="{{ route('admin.partner.index','type=2') }}" class="mr-2 btn btn-outline-warning @if($type==2) active @endif">{{ __('Inactive') }} ({{ $inactives }})</a>

             <a href="{{ route('admin.partner.index','type=0') }}" class="mr-2 btn btn-outline-warning @if($type==0) active @endif">{{ __('Pending') }} ({{ $pendings }})</a>


            <a href="{{ route('admin.partner.index','type=3') }}" class="mr-2 btn btn-outline-danger @if($type === 3) active @endif">{{ __('Banned') }} ({{ $banneds }})</a>
          </div>
        </div>

        <div class="float-right">
          <form>
            {{-- <input type="hidden" name="type" value="@if($type === 0) trash @else {{ $type }} @endif"> --}}
            <div class="input-group mb-2">

              <input type="text" id="src" class="form-control" placeholder="Search..." required="" name="src" autocomplete="off" value="{{ $request->src ?? '' }}">
              <select class="form-control selectric" name="term" id="term">
                <option value="first_name">{{ __('Search By Partner First Name') }}</option>
                <option value="last_name">{{ __('Search By Partner Last Name') }}</option>
                {{-- <option value="id">{{ __('Search By Partner Id') }}</option> --}}
                <option value="email">{{ __('Search By User Mail') }}</option>

              </select>
              <div class="input-group-append">                                            
                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>

        <form method="post" action="{{ route('admin.customers.destroy') }}" class="basicform_with_reload">
          @csrf
          <div class="float-left mb-1">
            @can('partner.delete')
            <div class="input-group">
              <select class="form-control selectric" name="method">
                <option value="" >{{ __('Select Action') }}</option>
                <option value="1" >{{ __('Move To Active') }}</option>
                <option value="2" >{{ __('Move To Inactive') }}</option>
                <option value="pending">{{ __('Move To Pending') }}</option>
                <option value="3" >{{ __('Move To Banned') }}</option>
                 {{-- @if($type !== "trash")
                <option value="trash" >{{ __('Move To Trash') }}</option>
                @endif
                @if($type=="trash")
                <option value="delete" >{{ __('Delete Permanently') }}</option>
                @endif --}}
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
                  <th>{{ __('Email') }}</th>
                  <th>{{ __('Commission') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('OTP Status') }}</th>
                  <th>{{ __('Join at') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">
                  <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                  <td>{{ $row->fullname }}</td>
                  <td><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                  <td>{{ $row->partner->commission ?? '' }}</td>
                  {{-- <td>
                    @if($row->status==1) <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($row->status==0) <span class="badge badge-danger">{{ __('Trash') }}</span>
                    @elseif($row->status==2) <span class="badge badge-warning">{{ __('Suspended') }}</span>
                    @elseif($row->status==3) <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @endif
                  </td> --}}
                  <td>
                    @if($row->partner->status==0) <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @elseif($row->partner->status==1) <span class="badge badge-success">{{ __('Active') }}</span>
                    @elseif($row->partner->status==2) <span class="badge badge-info">{{ __('Inactive') }}</span>
                    @elseif($row->partner->status==3) <span class="badge badge-danger">{{ __('Banned') }}</span>
                    @endif
                  </td>
                  <td>
                    @if($row->email_verified_at == '')
                    {{-- <a href="{{ route('admin.partner.otp',$row->id) }}" target="_blank">
                    <span class="badge badge-danger">{{ __('Not Confirmed') }}</span></a> --}}
                    <span class="badge badge-danger">{{ __('Not Confirmed') }}</span>
                    @else
                    <span class="badge badge-success">{{ __('Confirmed') }}</span>
                    @endif
                  </td>
                  <td>{{ $row->created_at->format('d-F-Y')  }}</td>
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Action') }}
                      </button>
                      <div class="dropdown-menu">
                       
                      @can('partner.edit')
                      <a class="dropdown-item has-icon" href="{{ route('admin.partner.edit',$row->id) }}"><i class="fas fa-user-edit"></i> {{ __('Edit') }}</a>
                      @endcan
                      @can('partner.view')
                      <a class="dropdown-item has-icon" href="{{ route('admin.partner.show',$row->id) }}"><i class="far fa-eye"></i>{{ __('View') }}</a>
                      @endcan
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
                 <th>{{ __('Email') }}</th>
                 <th>{{ __('Commission') }}</th>
                 <th>{{ __('Status') }}</th>
                 <th>{{ __('OTP Status') }}</th>
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