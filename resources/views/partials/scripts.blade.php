{{-- <script src="{{ asset('assets/js/jquery-3.5.1.min.js')}}"></script> --}}
<script src="./vendors/js/vendors.min.js"></script>
<script src="./admin/js/core/app-menu.js"></script>
<script src="./admin/js/core/app.js"></script>
<script src="./vendors/js/forms/select/select2.full.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>

@yield('vendor-script')

<!-- BEGIN: Page JS-->
<script src="./vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
@yield('page-script')
<!-- END: Page JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    });
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    $(window).on('load', function() {

        $("#shop_mode").change(function() {
            var shop_id = $("#shop_id_for_mode").val();
            if ($(this).is(':checked')) {
                var shop_mode = 'online';
                $("#shop_mode_status").text('Online');
            } else {
                $('#shop_mode_duration_modal').modal('show');
                var shop_mode = 'offline';
                $("#shop_mode_status").text('Offline');
            }
            if (shop_mode == 'online') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: 'seller/shop-mode',
                    data: {
                        shop_id: shop_id,
                        shop_mode: shop_mode
                    },
                    success: function(data) {
                        if (data.success) {
                            Sweet('success', data.success)
                        }
                    }
                });
            }
        });

        $("#shop_mode_duration_frm").on('submit', function(e) {

            var shop_mode_duration;

            $(".shop_mode_duration_checkbox:checked").each(function() {
                shop_mode_duration = $(this).val();
            });

            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: this.action,
                data: {
                    shop_mode_duration: shop_mode_duration
                },
                success: function(data) {
                    if (data.success) {
                        Sweet('success', data.success)
                        $('#shop_mode_duration_modal').modal('hide');
                    }
                }
            });
        });

        $(".modal_close_button").on('click', function() {
            $("#shop_mode").click();
        });
        
        toggleFloatingHeader();
    });
    $( window ).resize(function() {
        toggleFloatingHeader();
    });

    function toggleFloatingHeader() {
        if ($(window).width() > 900) {
            $('.vertical-overlay-menu').addClass('navbar-floating');
            $('.vertical-overlay-menu .header-navbar').removeClass('fixed-top');
            $('.vertical-overlay-menu .header-navbar').addClass('floating-nav');
            if($('#order_create_cards').length) {
                $( "#order_create_cards" ).remove();
            }
        } else {
            $('.vertical-overlay-menu').removeClass('navbar-floating');
            $('.vertical-overlay-menu .header-navbar').addClass('fixed-top');
            $('.vertical-overlay-menu .header-navbar').removeClass('floating-nav');
            if($('#order_create_table').length) {
                $( "#order_create_table" ).remove();
            }
        }
    }
</script>
