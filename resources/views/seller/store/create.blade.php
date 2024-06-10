@extends('layouts.seller')
@section('title', ' Create Slider')
@section('page-style')
    <style>
        .label_alignment {
            font-size: 15px !important;
            color: #5e5873 !important;
            /* padding-left: 151px !important;
                                padding-top: 79px !important; */
        }

        .form-group {
            margin-bottom: 25px;
        }

    hr {
            width: 93% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-top: -5px !important;
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
                            <h2 class="float-start mb-0">{{ __('Create Slider') }}</h2>

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
                                <h4 class="card-title">Create Slider</h4>
                                
                            </div>
                            <p style="margin-left: 21px;">Add some attractive and elegant  sliders in order to promote your store products. Make sure that all the banners uploaded are of good quality and similar in dimensions.</p>
                            <hr>
                            <div class="card-body">
                                <form method="post" action="{{ route('seller.slider.store') }}" class="basicform_with_reload">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">Thumbnail</label>
                                                <input type="file" accept="Image/*" name="file" id="file" required=""
                                                class="form-control">
                                            <span id="file_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">Title</label>
                                                <input type="text" name="title" class="form-control" required
                                                placeholder="e.g.Best Deal">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" style="height: 40px;" required placeholder="Slider Description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">Button Text</label>
                                                <input type="text" name="btn_text" class="form-control" required
                                                placeholder="e.g.Show Now">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">Position</label>
                                                <select class="form-select" name="position">
                                                    <option value="right" selected="">{{ __('Right') }}</option>
                                                    <option value="left">{{ __('Left') }}</option>
                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-1">
                                                <label class="form-label">Url</label>
                                                <input type="text" name="url" required="" value="#"
                                                class="form-control">
                                            </div>
                                        </div>
                                       

                                        <div class="col-12">
                                            <button type="submit" id="submit" class="btn btn-primary me-1 basicbtn">Save</button>
                                            <button type="reset" class="btn btn-primary me-1" >Reset</button>

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
@endsection
@section('page-script')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
    $('#file').bind('change', function() {
        $("#file_error").html("");
        $(".alertbox").css("border-color", "#F0F0F0");
        var file_size = $('#file')[0].files[0].size;
        if (file_size >= 1048576) {
            $("#file_error").html(" Recommended file size is less than 1 mb");
            $("#file_error").css("color", "red");
            $(".alertbox").css("border-color", "#FF0000");
            $('#submit').hide();
            return false;
        } else {
            $('#submit').show();
        }
        return true;

    });
</script>
@endsection
