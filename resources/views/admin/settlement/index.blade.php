@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Partner Settlements'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">

        <form method="post" action="" class="basicform_with_reload">
          @csrf


          <div class="table-responsive">
            <table class="table table-striped table-hover text-center table-borderless">
              <thead>
                <tr>
                  <th><input type="checkbox" class="checkAll"></th>

                  <th>{{ __('Partner Name') }}</th>
                  <th>{{ __('Invoice No') }}</th>
                  <th>{{ __('Earned Amount') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Paid at') }}</th>
                  <th>{{ __('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($posts as $row)
                <tr id="row{{ $row->id }}">
                  <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                  <td>{{ $row->user->fullname ?? '' }}</td>
                  <td>{{ $row->invoice_no }}</td>
                  <td>{{ amount_format($row->amount) ?? '' }}</td>
                  <td>
                    @if($row->status== 'paid') <span class="badge badge-success">{{ __('Paid') }}</span>
                    @elseif($row->status== 'unpaid') <span class="badge badge-danger">{{ __('Unpaid') }}</span>
                    @endif
                  </td>
                  <td>{{ $row->paid_at ? $row->paid_at->format('d-F-Y') : ''  }}</td>
                  <td>
                    <div class="dropdown d-inline">
                      <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Action') }}
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item has-icon" href="{{ route('admin.settlement.show',$row->id) }}"><i class="far fa-eye"></i>{{ __('View') }}</a>
                      </div>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th><input type="checkbox" class="checkAll"></th>

                  <th>{{ __('Partner Name') }}</th>
                  <th>{{ __('Invoice No') }}</th>
                  <th>{{ __('Earned Amount') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Paid at') }}</th>
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