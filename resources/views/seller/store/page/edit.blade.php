@extends('layouts.seller')
@section('title', 'Edit Page')
@section('page-style')
    <style>
        
        .form-group {
            margin-bottom: 25px;
        }
    </style>
@endsection

@section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        <ul style="margin-top: 1rem;">
                            <li>{{ Session::get('success') }}</li>
                        </ul>
                    </div>
                @endif

                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('Edit Page') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/setting/page') }}">{{ __('Pages') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('Edit Page') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    {{-- <h4 class="card-title">Create Plan</h4> --}}

                                </div>
                                <div class="card-body">
                                    <form  action="{{ route('seller.page.update',$info->id) }}"
                                        method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    {{ input((array('title'=>'Page Title','name'=>'title','is_required'=>true,'value'=>$info->title))) }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
													{{ input((array('title'=>'Page Slug','name'=>'slug','is_required'=>true,'value'=>$info->slug))) }}
                                                </div>
                                            </div>


                                            <div class="col-sm-12">

												{{ textarea(array('title'=>'Page Description','name'=>'short_description','is_required'=>true,'value'=>$info->meta['meta_description'])) }}
                                            </div>
                                            <div class="col-sm-12">
                                                {{-- {{ editor(['title' => 'Page Content', 'name' => 'content', 'id' => 'content', 'value' => $page_info->content ?? '']) }}<br> --}}
												{{ editor(['title'=>'Page Content','name'=>'content','class'=>'content','value'=>$info->content ?? '']) }}
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary me-1 basicbtn"> {{ __('Save Changes') }}</button>
                                                

                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Floating Label Form section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('page-script')
<script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js?v=1.0') }}"></script>

@endsection
