@extends('layouts.seller')
@section('title', 'Themes')
@section('page-style')
    <style>
        .border_not_select {
            border: 1px solid #ddd !important;
        }

        .border_select {
            border: 1px solid #28c76f !important;

        }

        hr {
            width: 93% !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }



        :root {
            --borderWidth: 7px;
            --height: 24px;
            --width: 12px;
            --borderColor: #28c76f;
        }



        .check {
            display: inline-block;
            transform: rotate(45deg);
            height: var(--height);
            width: var(--width);
            border-bottom: var(--borderWidth) solid #fff;
            border-right: var(--borderWidth) solid #fff;
            margin-left: 12px;
            margin-top: 4px;
        }

        .un_check {
            display: inline-block;
            transform: rotate(45deg);
            height: var(--height);
            width: var(--width);
            border-bottom: var(--borderWidth) solid #ffffff00;
            border-right: var(--borderWidth) solid #ffffff00;
            margin-left: 12px;
            margin-top: 4px;

        }

        .circle {
            background: var(--borderColor);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            top: -20px;
            right: -10px;
            position: absolute;
            /* left: 94%;
            margin-top: -17px;
            position: relative; */
        }

        .un_circle {
            position: relative;
            background: #ffffff00;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            left: 94%;
            margin-top: -17px;
        }

        .image_set {
            background-image: url(./../admin/assets/images/screenshot.png);
        }

        /* Desktop View */
        @media screen and (min-width: 992px) {
            .img_set {
                margin-left: 50px;
                display: inline-flex;
            }

        }

        /* Mobile View */
        @media screen and (max-width: 900px) {
            .img_set {
                margin-left: 0;
                display: inline-flex;
            }

            /* .circle {
                margin-left: -6px;
            } */

            .p-20 {
                padding: 20px !important;
            }
        }
    </style>

@endsection
@section('content')
    @php
        $template = $posts;
        $activate_theme = $active_theme->template_id;
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
        </div>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Themes') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Online store') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('Themes') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
            </div>
        </div>

        <div class="content-body">
            <!-- Accordion start -->
            <section id="accordion">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="accordionWrapa1" role="tablist" aria-multiselectable="true">
                            <div class="card" style="display: block;">
                                <div class="card-header">
                                    <h1 class="card-title"
                                        style="font-size: 30px;
                                        margin-bottom: -8px;">
                                        {{ __('Themes') }}</h1>
                                </div>
                                {{-- <div class="card-body"> --}}
                                <p style="margin-left: 25px;">
                                    {{ __('Change how Untitled UI looks and feels in your browser.') }}
                                </p>
                                <hr /><br>
                                <div class="card-title" style="margin-left: 25px;">
                                    <h5
                                        style="margin-bottom: -9px;
                                        font-size: 14px;">
                                        {{ __('Interface theme') }}</h5>
                                </div>
                                <p style="margin-left: 25px;margin-bottom: 30px;">
                                    {{ __('Select your favourite UI theme for storefront.') }}</p>


                                @foreach ($posts as $key => $row)
                                    @if ($key >= 4)
                                    @break
                                @endif

                                <!-- Plan Card -->
                                <div class="col-xl-3 col-lg-5 col-md-6 order-1 order-md-0 img_set p-20">
                                    <form method="post" action="{{ route('seller.theme.update', $row->id) }}">
                                        @method('PUT')
                                        @csrf

                                        <div
                                            class="card {{ $active_theme->template_id == $row->id ? 'border_select' : 'border_not_select' }}">
                                            <div
                                                class="{{ $active_theme->template_id == $row->id ? 'circle' : 'un_circle' }}">
                                                <div
                                                    class="
                                                    {{ $active_theme->template_id == $row->id ? 'check' : 'un_check' }}">
                                                </div>
                                            </div>


                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <span class="badge bg-light-primary">{{ $row->name }}</span><br>

                                                </div>

                                                <div>
                                                    <img src="{{ asset($row->asset_path . '/screenshot.png') }}"
                                                        style="width: 100%;" />
                                                </div>

                                                <div class="d-grid w-100 mt-2">
                                                    @if ($active_theme->template_id == $row->id)
                                                        <button type="submit" class="btn btn-success col-12"
                                                            disabled="" id="submit">
                                                            @if ($active_theme->template_id == $row->id)
                                                                {{ __('Installed') }}
                                                            @else
                                                                {{ __('Activate') }}
                                                            @endif
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn col-12"
                                                            style="box-shadow: 0 2px 6px #gray;
                                                        background-color: gray;
                                                        border-color: gray;
                                                        color: #fff;">
                                                            @if ($active_theme->template_id == $row->id)
                                                                {{ __('Installed') }}
                                                            @else
                                                                {{ __('Activate') }}
                                                            @endif
                                                        </button>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                            <!-- /Plan Card -->


                        </div>
                    </div>
                </div>

            </div>
    </div>
    </section>
    <!-- Accordion end -->

</div>
</div>
</div>

@endsection
@section('page-script')
@endsection
