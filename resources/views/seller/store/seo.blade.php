@extends('layouts.seller')
@section('title', 'SEO')
@section('page-style')
<style>
    hr {
            width: 93% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-top: -5px !important;
        }
          /* Mobile View */
	  @media screen and (max-width: 900px) {
            .seo_set{
				margin-left: 14px !important;
            }
        }
        @media screen and (min-width: 992px) {
            .seo_set{
				margin-left: 21px !important;
            }

        }
        
</style>
@endsection
@section('content')
@php
	$url=domain_info('full_domain');
@endphp
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
                @if (Session::has('error'))
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
               @endif

                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('Seo And Sitemap') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ __('Online store') }}
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('SEO') }}
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
                                    <h4 class="card-title">{{ __('SEO') }}</h4>
                                    
                                </div>
                                <p class="seo_set">{{ __('Search engine optimization (SEO) will help to achieve higher rankings and traffic to your site from search engines. Choose best keywords while making descriptions.') }}</p>
                                <hr>
                                <div class="card-body">
                                    <form id="basicform" action="{{ route('seller.seo.update',$id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Site Title') }}</label>
                                                    <input type="text" name="title"  id="title" class="form-control" value="{{ $info->title ?? '' }}" placeholder="Site Title">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Twitter Title') }}</label>
                                                    <input type="text" name="twitterTitle"  id="twittertitle" class="form-control" value="{{ $info->twitterTitle ?? '' }}" placeholder="{{ __('Twitter Title') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Canonical URL') }}</label>
                                                    <input type="text" name="canonical"  id="canonical" class="form-control" value="{{ $info->canonical ?? ''}}" placeholder="Canonical URL">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Tags') }}</label>
                                                    <input type="text" name="tags"  id="tags" class="form-control" value="{{ $info->tags ?? '' }}" placeholder="Tags">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Description') }}</label>
                                                    <textarea name="description" id="description" class="form-control">{{ $info->description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                           

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary me-1 basicbtn">{{ __('Update') }}</button>
                                                

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
    <script src="{{ asset('assets/js/form.js') }}"></script>

@endsection
