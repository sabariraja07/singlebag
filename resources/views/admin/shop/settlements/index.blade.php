@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Shop Settlements'])
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
                  <th>{{ __('Shop Name') }}</th>
                  <th>{{ __('Shop Type') }}</th>
                  <th>{{ __('Invoice No') }}</th>
                  <th>{{ __('Commission') }}</th>
                  <th>{{ __('Amount') }}</th>
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
                  <td>{{ $row->shop->shop_type ?? '' }}</td>
                  <td>{{ $row->invoice_no }}</td>
                  <td>{{ $row->charge ? amount_format($row->charge) : '' }}</td>
                  <td>{{ $row->amount ? amount_format($row->amount) : '' }}</td>
                  <td>
                    @if($row->status== 'paid') <span class="badge badge-success">{{ __('Paid') }}</span>
                    @elseif($row->status== 'unpaid') <span class="badge badge-danger">{{ __('Unpaid') }}</span>
                    @endif
                  </td>
                  <td>{{ $row->paid_at ? $row->paid_at->format('d-F-Y') : ''  }}</td>
                  <td>
                    <a href="{{ route('admin.shop.settlements.show',$row->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th><input type="checkbox" class="checkAll"></th>
                  <th>{{ __('Shop Name') }}</th>
                  <th>{{ __('Shop Type') }}</th>
                  <th>{{ __('Invoice No') }}</th>
                  <th>{{ __('Commission') }}</th>
                  <th>{{ __('Amount') }}</th>
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