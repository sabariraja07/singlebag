<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{ env('APP_NAME') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
  <link rel="icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>

<body>
 <div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
          <div class="login-brand">
            <a href="{{ url('/') }}"><img src="{{ admin_logo_url() }}" alt="{{ env('APP_NAME') }}" width="100"></a>
          </div>

          <div class="card card-primary">
            <div class="card-header"><h4>{{ __('Partner Register') }}</h4></div>
            <form class="basicform"  method="post" action="{{ route('partner.register') }}">
              @csrf

              <div class="card-body">
              
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="first_name">{{ __('First Name') }}</label>
                      <input id="first_name" type="text" class="form-control" name="first_name" autofocus>
                    </div>
                    <div class="form-group col-6">
                      <label for="frist_name">{{ __('Last Name') }}</label>
                      <input id="last_name" type="text" class="form-control" name="last_name" autofocus>
                    </div>
                    <div class="form-group col-12">
                      <label for="last_name">{{ __('Email') }}</label>
                      <input id="email" type="email" class="form-control" name="email">
                    </div>
                  </div>



                  <div class="row">
                    <div class="form-group col-6">
                      <label for="password" class="d-block">{{ __('Password') }}</label>
                      <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password">
                      <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                      </div>
                    </div>
                    <div class="form-group col-6">
                      <label for="password2" class="d-block">{{ __('Password Confirmation') }}</label>
                      <input id="password2" type="password" class="form-control" name="password_confirmation">
                    </div>
                  </div>
                
               

                
              
                @if(env('NOCAPTCHA_SITEKEY') != null)
                 <div class="form-group">
                
                  {!! NoCaptcha::renderJs() !!}
                  {!! NoCaptcha::display() !!}


               
              </div>
                @endif 
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" required="" name="agree" class="custom-control-input" id="agree">
                    <label class="custom-control-label" for="agree">{{ __('I agree with the') }} <a href="https://singlebag.com/terms-and-conditions/" target=_blank>{{ __('terms and conditions') }}</a></label>
                  </div>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-lg btn-block basicbtn">
                   {{ __(' Register') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
          <div class="simple-footer">
            Copyright &copy; {{ env('APP_NAME') }} {{ date('Y') }}
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- General JS Scripts -->
<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>

<script type="text/javascript">
  "use strict";
  
  $(".basicform").on('submit', function(e){
    e.preventDefault();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var btn = $(this).find('.basicbtn');
    var basicbtnhtml=btn.html();
    $.ajax({
      type: 'POST',
      url: this.action,
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function() {

        btn.html("Please Wait....");
        btn.attr('disabled','')

      },
      
      success: function(response){ 
        btn.removeAttr('disabled')
        Sweet('success',response.msg);
        btn.html(basicbtnhtml);
        if (response.redirect == true) {
          window.location.href = response.domain;
        }
        else{
          window.location.href = $('#location_url').val();
        }
        
      },
      error: function(xhr, status, error) 
      {
        if($(".g-recaptcha").length > 0) {
            grecaptcha.reset();
        }
        btn.html(basicbtnhtml);
        btn.removeAttr('disabled')
        $('.errorarea').show();
        $.each(xhr.responseJSON.errors, function (key, item) 
        {
          Sweet('error',item)
          $("#errors").html("<li class='text-danger'>"+item+"</li>")
        });
        errosresponse(xhr, status, error);
      }
    })


  });

  function Sweet(icon,title,time=3000){
    
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: time,
      timerProgressBar: true,
      onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    })

    
    Toast.fire({
      icon: icon,
      title: title,
    })
  }
</script>
<style>
  .form-shop-name{
    font-weight: 600;
    color: #34395e;
    font-size: 12px;
    letter-spacing: .5px;
  }
</style>
</body>
</html>




