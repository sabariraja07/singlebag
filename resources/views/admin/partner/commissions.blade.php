@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'License Commission'])
@endsection
@section('content')
<div class="row">
  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('License Commission') }}</h4>
                
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('admin.partner.commission.update') }}" class="basicform_with_reload">
          @csrf
          
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Default Commission') }}</label>
            <div class="col-sm-12 col-md-7">
              <input type="number" class="form-control" required="" name="default_commission" value="{{ $default_commission }}">
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Commissions') }} </label>
            <div class="col-sm-12 col-md-7">
              <div class="table-responsive">
                <table class="table table-striped table-hover text-center table-borderless">
                  <thead>
                    <tr>
                      <th>{{ __('From') }} ({{ $currency ? $currency->symbol : ''}})</th>
                      <th>{{ __('To') }} ({{ $currency ? $currency->symbol : '' }})</th>
                      <th>{{ __('Commission (%)') }}</th>
                      <th>{{ __('Action') }}</th>
                    </tr>
                  </thead>
                  <tbody id="partner-commission">
                    @foreach($commissions as $key => $commission)
                    <tr>
                      <td>
                        <div class="form-group mt-4">
                          <input type="number" class="form-control" required="" min="0" name="commissions[{{ $key }}][from]" value="{{ $commission['from']}}">
                        </div>
                      </td>
                      <td>
                        <div class="form-group mt-4">
                          <input type="number" class="form-control" required="" min="0" name="commissions[{{ $key }}][to]" value="{{ $commission['to']}}">
                        </div>
                      </td>
                      <td>
                        <div class="form-group mt-4">
                          <input type="number" class="form-control" required="" min="0" max="100" name="commissions[{{ $key }}][commission]" value="{{ $commission['commission']}}">
                        </div>
                      </td>
                      <td>
                        <button type="button" class="btn btn-danger float-right removebtn" onclick="removeColumn()">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4">
                        <button type="button" class="btn btn-info float-right" onclick="addColumn()">Add</button>
                      </th>
                    </tr>
                  </tfoot>
               </table>
               
             </div>
            </div>
          </div>

          <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
            <div class="col-sm-12 col-md-7">
              <button class="btn btn-success basicbtn" type="submit">{{ __('Save') }}</button>
            </div>
          </div>
         
       </form>
     </div>
   </div>
 </div>
</div>
@endsection

@push('js')
<script>

$("#partner-commission").on("click", ".removebtn", function() {
   $(this).closest("tr").remove();
});
  
function getRowHtml(id){
  return `<tr>
                      <td>
                        <div class="form-group mt-4">
                          <input type="number" class="form-control" required="" min="0" name="commissions[` + id +`][from]">
                        </div>
                      </td>
                      <td>
                        <div class="form-group mt-4">
                          <input type="number" class="form-control" required="" min="0" name="commissions[` + id +`][to]">
                        </div>
                      </td>
                      <td>
                        <div class="form-group mt-4">
                          <input type="number" class="form-control" required="" min="0" max="100" name="commissions[` + id +`][commission]">
                        </div>
                      </td>
                      <td>
                        <button type="button" class="btn btn-danger float-right removebtn" onclick="removeColumn()">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>`;
}

    function addColumn() {
      $('#partner-commission').append(getRowHtml($('#partner-commission tr').length));
    }
                    
</script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush