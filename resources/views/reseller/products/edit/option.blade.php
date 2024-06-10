@extends('layouts.seller')
@section('title', 'Edit Options')
@section('page-style')
<style>
.select2-container--classic .select2-selection--single .select2-selection__arrow b, .select2-container--default .select2-selection--single .select2-selection__arrow b {
	padding-right: 0px !important;
}
.detail-amt{
	float: right !important;margin-top: -17px !important;
}
</style>
@endsection
@section('content')
<div class="content-header row">
    @if (Session::has('success'))
    <div class="alert alert-success">
        <ul style="margin-top: 1rem;">
            <li>{{ Session::get('success') }}</li>
        </ul>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin-top: 1rem;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="content-header row">
	<div class="content-header-left col-md-9 col-12 mb-2">
		<div class="row breadcrumbs-top">
			<div class="col-12">
				<h2 class="content-header-title float-start mb-0">{{ __('Options') }}</h2>
				<div class="breadcrumb-wrapper">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
						</li>
						<li class="breadcrumb-item"><a href="{{ url('/seller/product') }}">{{ __('Products') }}</a>
						</li>   
						<li class="breadcrumb-item active">{{ __('Options') }}
						</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="row">
	<div class="col-lg-12">      
		
		<div class="card">
			<div class="card-body">

				<div class="row">
					<div class="col-sm-3">
						@include('reseller.products.edit.tab')
					</div>
               @if(current_shop_type() != 'reseller')
					<div class="col-sm-9">
                  <form class="basicform" method="post" action="{{ route('seller.products.option_update',$info->id) }}">
                     @csrf
                            <div class="row">
                             
                                <div class="col-12 col-md-12 col-lg-12">
                                    <!-- <button type="button" data-toggle="modal" data-target="#add_option" class="btn btn-success float-right">{{ __('Add New Option') }}</button>	 -->
                                    @if(current_shop_type() != 'reseller')   
                                       <a href="javascript:void(0)" data-bs-target="#add_option" data-bs-toggle="modal" style="float: right">
                                             <span class="btn btn-primary mb-1">{{ __('Add New Option') }}</span>
                                       </a>
                                    @endif
                                </div>   
                                <div class="col-12 col-md-12 col-lg-12 mt-3">
                                    <div id="accordion">
                                      @foreach ($info->options as $key=> $row)
                                          
                                     
                                        <div class="accordion option{{ $row->id }} mb-1">
                                          <div class="accordion-header h-50" role="button" data-bs-toggle="collapse" data-bs-target="#panel-body-{{ $key }}">
                                             <div class="float-left">
                                                <h6><span id="option_name{{ $row->id }}">{{ $row->name }}</span> @if($row->is_required == 1) <span class="text-danger">*</span> @endif</h6>
                                             </div>
                                             <div class="float-right"> 
                                             @if(current_shop_type() != 'reseller')                                         
                                                <a class="btn btn-success btn-sm text-white row_edit" data-bs-target="#editform" data-bs-toggle="modal"  data-target="#editform"  data-selecttype="{{ $row->select_type }}" data-name="{{ $row->name }}" data-required="{{ $row->is_required }}"  data-id="{{ $row->id }}"><i class="ph-pencil-bold"></i></a>
                                             
                                                <a class="btn btn-danger btn-sm text-white option_delete" data-id="{{ $row->id }}"><i class="ph-trash"></i></a> 
                                             @endif
                                             </div>                                           
                                          </div>
                                          <div class="accordion-body collapse" id="panel-body-{{ $key }}" data-parent="#accordion">
                                            <div class="panel-body">                                              
                                                <div class="option-values clearfix" id="option-2-values">
                                                   <div class="option-select ">
                                                      <div class="mb-1">
                                                         <table class="options table table-bordered table-striped">
                                                            <thead>
                                                               <tr>
                                                                 
                                                                  <th>Label</th>
                                                                  <th>Price</th>
                                                                  <th>Price Type</th>
                                                                  @if(current_shop_type() != 'reseller')
                                                                  <th></th>
                                                                  @endif
                                                               </tr>
                                                            </thead>
                                                            <tbody >
                                                              
                                                               @foreach ($row->childrenCategories ?? [] as $item)
                                                               <tr class="option{{ $item->id }}" >
                                                                 
                                                                  <td>                                                                     
                                                                     <input type="text" name="options[{{ $row->id }}][values][{{ $item->id }}][label]"  class="form-control" value="{{ $item->name }}">
                                                                  </td>
                                                                  <td>
                                                                     <input type="number" name="options[{{ $row->id }}][values][{{ $item->id }}][price]" class="form-control" value="{{ $item->amount }}" step="any" min="0">
                                                                  </td>
                                                                  <td>
                                                                     <select name="options[{{ $row->id }}][values][{{ $item->id }}][price_type]" class="form-select select2 custom-select-black">
                                                                        <option value="1" @if($item->amount_type == 1) selected="" @endif>
                                                                           {{ __('Fixed') }}
                                                                        </option>
                                                                        <option value="0" @if($item->amount_type == 0) selected="" @endif>
                                                                           {{ __('Percent') }}
                                                                        </option>
                                                                     </select>
                                                                  </td>
                                                                  @if(current_shop_type() != 'reseller')  
                                                                     <td class="text-center">
                                                                        <button type="button" class="btn btn-white delete-row bg-white option_delete text-danger" data-id="{{ $item->id }}">
                                                                        <i class="ph-trash"></i>
                                                                        </button>
                                                                     </td>
                                                                  @endif
                                                               </tr>
                                                               @endforeach
                                                            </tbody>
                                                         </table>
                                                      </div>
                                                   
                                                   @if(current_shop_type() != 'reseller')
                                                      <button  type="button" data-bs-toggle="modal" data-bs-target="#new_row_modal" class="btn btn-primary add_new_row" data-id="{{ $row->id }}">
                                                          {{ __('Add New Row') }}
                                                      </button>
                                                   @endif
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        @endforeach
                                       
                                      </div>
                                          @if(count($info->options) > 0)
                                          <button type="submit" class="btn btn-primary basicbtn mt-1">{{ __('Save Changes') }}</button>	
                                          @endif
                                 </div>
                                
                                
                              
                              </div>                      
                           </form>
						

						
					</div>
               @else
               <div class="col-sm-9">
                  <form class="basicform" method="post" action="{{ route('seller.products.option_update',$info->id) }}">
                     @csrf
                            <div class="row">
                             
                                <div class="col-12 col-md-12 col-lg-12">
                                    <!-- <button type="button" data-toggle="modal" data-target="#add_option" class="btn btn-success float-right">{{ __('Add New Option') }}</button>	 -->
                                    @if(current_shop_type() != 'reseller')   
                                       <a href="javascript:void(0)" data-bs-target="#add_option" data-bs-toggle="modal" style="float: right">
                                             <span class="btn btn-primary mb-1">{{ __('Add New Option') }}</span>
                                       </a>
                                    @endif
                                </div>   
                                <div class="col-12 col-md-12 col-lg-12 mt-3">
                                    <div id="accordion">
                                      @foreach ($info->options as $key=> $row)
                                          
                                     
                                        <div class="accordion option{{ $row->id }} mb-1">
                                          <div class="accordion-header h-50" role="button" data-bs-toggle="collapse" data-bs-target="#panel-body-{{ $key }}">
                                             <div class="float-left">
                                                <h6><span id="option_name{{ $row->id }}">{{ $row->name }}</span> @if($row->is_required == 1) <span class="text-danger">*</span> @endif</h6>
                                             </div>
                                             <div class="float-right"> 
                                             @if(current_shop_type() != 'reseller')                                         
                                                <a class="btn btn-success btn-sm text-white row_edit" data-bs-target="#editform" data-bs-toggle="modal"  data-target="#editform"  data-selecttype="{{ $row->select_type }}" data-name="{{ $row->name }}" data-required="{{ $row->is_required }}"  data-id="{{ $row->id }}"><i class="ph-pencil-bold"></i></a>
                                             
                                                <a class="btn btn-danger btn-sm text-white option_delete" data-id="{{ $row->id }}"><i class="ph-trash"></i></a> 
                                             @endif
                                             </div>                                           
                                          </div>
                                          {{-- <div class="accordion-body collapse" id="panel-body-{{ $key }}" data-parent="#accordion"> --}}
                                            <div class="panel-body">                                              
                                                <div class="option-values clearfix" id="option-2-values">
                                                   <div class="option-select ">
                                                      <div class="mb-1">
                                                         <table class="options table table-bordered table-striped">
                                                            <thead>
                                                               <tr>
                                                                 
                                                                  <th>Label</th>
                                                                  <th>Price</th>
                                                                  <th>Price Type</th>
                                                                  @if(current_shop_type() != 'reseller')
                                                                  <th></th>
                                                                  @endif
                                                               </tr>
                                                            </thead>
                                                            <tbody >
                                                              
                                                               @foreach ($row->childrenCategories ?? [] as $item)
                                                               <tr class="option{{ $item->id }}" >
                                                                 
                                                                  <td>                                                                     
                                                                     <input type="text" name="options[{{ $row->id }}][values][{{ $item->id }}][label]"  class="form-control" value="{{ $item->name }}" disabled>
                                                                  </td>
                                                                  <td>
                                                                     <input type="number" name="options[{{ $row->id }}][values][{{ $item->id }}][price]" class="form-control" value="{{ $item->amount }}" step="any" min="0" disabled>
                                                                  </td>
                                                                  <td>
                                                                     <select name="options[{{ $row->id }}][values][{{ $item->id }}][price_type]" class="form-control custom-select-black" disabled>
                                                                        <option value="1" @if($item->amount_type == 1) selected="" @endif>
                                                                           {{ __('Fixed') }}
                                                                        </option>
                                                                        <option value="0" @if($item->amount_type == 0) selected="" @endif>
                                                                           {{ __('Percent') }}
                                                                        </option>
                                                                     </select>
                                                                  </td>
                                                                  @if(current_shop_type() != 'reseller')  
                                                                     <td class="text-center">
                                                                        <button type="button" class="btn btn-white delete-row bg-white option_delete text-danger" data-id="{{ $item->id }}">
                                                                        <i class="ph-trash"></i>
                                                                        </button>
                                                                     </td>
                                                                  @endif
                                                               </tr>
                                                               @endforeach
                                                            </tbody>
                                                         </table>
                                                      </div>
                                                   
                                                   @if(current_shop_type() != 'reseller')
                                                      <button  type="button" data-bs-toggle="modal" data-bs-target="#new_row_modal" class="btn btn-primary add_new_row" data-id="{{ $row->id }}">
                                                          {{ __('Add New Row') }}
                                                      </button>
                                                   @endif
                                                   </div>
                                                </div>
                                            </div>
                                        {{-- </div> --}}
                                        </div>
                                        @endforeach
                                       
                                      </div>
                                         @if(current_shop_type() != 'reseller')
                                          @if(count($info->options) > 0)
                                          <button type="submit" class="btn btn-primary basicbtn mt-1">{{ __('Save Changes') }}</button>	
                                          @endif
                                          @endif
                                 </div>
                                
                                
                              
                              </div>                      
                           </form>
						

						
					</div>
              @endif
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<div class="modal fade" id="add_option" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('seller.products.store_group',$info->id) }}" class="basicform_with_reload" method="post">
           
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Add New Option') }}</h5>
        </div>
        <div class="modal-body">
         <div class="form-group mb-1">
             <label for="name">{{ __('Option Name') }}</label>
             <input type="text" name="name" required class="form-control">
         </div>
         <div class="form-group mb-1 mb-1">
            <label >{{ __('Select Type') }}</label>
           
            <select name="select_type" class="form-control">
               <option value="1">{{ __('Multiple Select') }}</option>
               <option selected value="0">{{ __('Single Select') }}</option>
            </select>
        </div>
         <label for="is_required"><input type="checkbox" name="is_required" value="1" id="is_required"> {{ __('Required') }}</label>
          
        </div>
        <div class="modal-footer">
         <button type="reset" class="btn btn-outline-secondary"
            data-bs-dismiss="modal" aria-label="Close">
            Close
         </button>
          <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
        </div>
      </div>
    </form>
    </div>
  </div>


<!-- Modal -->
<div class="modal fade" id="new_row_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
        <form action="{{ route('seller.products.add_row') }}" class="basicform_with_reload" method="post">
         @csrf
      
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLabel">{{ __('Add New Row') }}</h5>
       </div>
       <div class="modal-body">
         <div class="form-group mb-1">
            <label for="add_row">{{ __('Row Name') }}</label>
            <input type="text" id="add_row" class="form-control" name="name" required>
            <input type="hidden" id="row_id" name="row_id">
         </div>
         <div class="form-group mb-1">
            <label for="price">{{ __('Price') }}</label>
            <input type="number" step="any"  id="price" class="form-control" name="price" required>
         </div>
         <div class="form-group mb-1">
            <label for="price_type">{{ __('Price Type') }}</label>
            <select name="amount_type" id="price_type" class="form-control">
               <option value="1">{{ __('Fixed') }}</option>
               <option value="0">{{ __('Percentage') }}</option>
            </select>
         </div>
       </div>
       <div class="modal-footer">
         <button type="reset" class="btn btn-outline-secondary"
            data-bs-dismiss="modal" aria-label="Close">
            Close
         </button>
         <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
       </div>
      </form>
     </div>
   </div>
 </div>  

 
<!-- Modal -->
<div class="modal fade" id="editform" tabindex="-1" aria-labelledby="editform" aria-hidden="true">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="editform">{{ __('Edit Option') }}</h5>
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <form action="{{ route('seller.products.row_update') }}" class="basicform row_update_form" >
         @csrf
       <div class="modal-body">
         <div class="form-group mb-1">
             <label for="name">{{ __('Option Name') }}</label>
             <input type="text" id="edit_name" name="name" required class="form-control">
         </div>
         <div class="form-group mb-1">
             <label for="name">{{ __('Select Type') }}</label>
           
             <select id="edit_select" name="select_type" class="form-control">
                <option value="1">{{ __('Multiple Select') }}</option>
                <option value="0">{{ __('Single Select') }}</option>
             </select>
         </div>
         <input type="hidden" id="edit_id" name="id">
         <label for="edit_required"><input id="edit_required" type="checkbox" name="is_required" value="1" > {{ __('Required') }}</label>
          
        </div>
       <div class="modal-footer">
         <!-- <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">{{ __('Close') }}</button> -->
         <button type="reset" class="btn btn-outline-secondary"
            data-bs-dismiss="modal" aria-label="Close">
            Close
         </button>
         <button type="submit" class="btn btn-primary basicbtn" >{{ __('Save') }}</button>
       </div>
      </form>
     </div>
   </div>
 </div>

<form class="basicform delete_from" action="{{ route('seller.products.option_delete') }}"  method="post">
@csrf
<input type="hidden" name="id" id="option_id">
</form> 
@endsection
@section('page-script')
<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/product_option.js') }}"></script>
@endsection