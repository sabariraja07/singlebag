(function ($) {
    "use strict";
    //basicform submit
    if ($('textarea[name="content"]').length > 0) {

        CKEDITOR.replace('content');
    }

    $("#productform").on('submit', function (e) {
        e.preventDefault();
        var instance = $('#content').val();

        if (instance != null) {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        var btn = $(this).find('.basicbtn');
        var btnhtml = btn.html();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.attr('disabled', '')
                btn.html('Please Wait....')

            },

            success: function (response) {

                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                Sweet('success', response);
                setTimeout(function () {
                    btn.html(btnhtml);
                    location.reload();
                    var redirectUrl = $('#redirectUrl').val();
                    if (redirectUrl != null) {
                        window.location.href = redirectUrl;
                    } else {
                        success(response);
                    }
                }, 3000);
            },
            error: function (xhr, status, error) {
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                btn.html(btnhtml);

                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });


    $("#basicform").on('submit', function (e) {
        e.preventDefault();

        e.preventDefault();
        var instance = $('.content').val();

        if (instance != null) {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var btn = $(this).find('.basicbtn');
        var btnhtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                btn.attr('disabled', '');
            },

            success: function (response) {
                btn.removeAttr('disabled')
                Sweet('success', response);
                setTimeout(function () {
                    btn.removeClass('waves-effect');
                    success(response)
                    location.reload();
                }, 3000);
            },
            error: function (xhr, status, error) {
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                $('.errorarea').show();
                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });

    $(".product_upload").on('submit', function (e) {
        e.preventDefault();
        var instance = $('.content').val();

        if (instance != null) {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var FileLength = $("#file")[0].files.length;
        if (FileLength === 0) {
            Sweet('error', 'No file selected');
            return fasle;
        }
        var btn = $(this).find('.basicbtn');
        var btnHtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.html("Please Wait....");
                btn.attr('disabled', '')

            },

            success: function (response) {
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                Sweet('success', response);
                setTimeout(function () {
                    btn.html(btnHtml);
                    success(response);
                    location.reload();
                }, 3000);
            },
            error: function (xhr, status, error) {
                btn.html(btnHtml);
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                $('.errorarea').show();
                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });

    $(".basicform").on('submit', function (e) {
        e.preventDefault();
        var instance = $('.content').val();

        if (instance != null) {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var btn = $(this).find('.basicbtn');
        var btnHtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.html("Please Wait....");
                btn.attr('disabled', '')

            },

            success: function (response) {

                btn.removeClass('waves-effect');
                Sweet('success', response);
                setTimeout(function () {
                    btn.html(btnHtml);
                    success(response);
                    location.reload();
                }, 3000);
            },
            error: function (xhr, status, error) {
                btn.html(btnHtml);
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                $('.errorarea').show();
                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });

    function errosresponse() {

    }

    $(".basicform_with_reload").on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var btn = $(this).find('.basicbtn');
        var btnHtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.html("Please Wait....");
                btn.attr('disabled', '')

            },

            success: function (response) {
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                Sweet('success', response);
                setTimeout(function () {
                    btn.html(btnHtml);
                    location.reload();
                }, 3000);
            },
            error: function (xhr, status, error) {
                btn.html(btnHtml);
                btn.removeClass('waves-effect');
                btn.removeAttr('disabled')
                $('.errorarea').show();
                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });

    $(".basicform_with_redirect").on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var btn = $(this).find('.basicbtn');
        var btnHtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.html("Pleas Wait....");
                btn.attr('disabled', '');


            },

            success: function (response) {
                // btn.removeAttr('disabled');
                // btn.removeClass('waves-effect');
                Sweet('success', response.message);
                setTimeout(function () {
                    btn.html(btnHtml);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                }, 3000);
            },
            error: function (xhr, status, error) {
                btn.html(btnHtml);
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                $('.errorarea').show();
                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });

    $(".basicform_with_reset").on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var btn = $(this).find('.basicbtn');
        var btnHtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.html("Please Wait....");
                btn.attr('disabled', '')

            },

            success: function (response) {
                btn.removeAttr('disabled')
                btn.removeClass('waves-effect');
                Sweet('success', response);
                setTimeout(function () {
                    btn.html(btnHtml);
                    $('.basicform_with_reset').trigger('reset');
                    if ($(".g-recaptcha").length > 0) {
                        grecaptcha.reset();
                    }
                }, 3000);
            },
            error: function (xhr, status, error) {
                if ($(".g-recaptcha").length > 0) {
                    grecaptcha.reset();
                }
                btn.html(btnHtml);
                btn.removeClass('waves-effect');
                btn.removeAttr('disabled')
                $('.errorarea').show();
                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });
    $(".basicform_with_remove").on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var btn = $(this).find('.basicbtn');
        var btnHtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.html("Please Wait....");
                btn.attr('disabled', '')

            },

            success: function (response) {
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                Sweet('success', response);
                setTimeout(function () {
                    btn.html(btnHtml);
                    $('input[name="ids[]"]:checked').each(function (i) {
                        var ids = $(this).val();
                        $('#row' + ids).remove();
                    });
                }, 3000);
            },
            error: function (xhr, status, error) {
                btn.html(btnHtml);
                btn.removeClass('waves-effect');
                btn.removeAttr('disabled')
                $('.errorarea').show();
                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });



    $(".loginform").on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var btn = $(this).find('.basicbtn');
        var btnHtml = btn.html();
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                btn.html("Loading...");
                btn.attr('disabled', '')

            },

            success: function (response) {
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');
                btn.html(btnHtml);
                location.reload();
            },
            error: function (xhr, status, error) {
                if ($(".g-recaptcha").length > 0) {
                    grecaptcha.reset();
                }
                btn.html(btnHtml);
                btn.removeAttr('disabled');
                btn.removeClass('waves-effect');

                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })


    });

    //id basicform1 when submit 
    $("#basicform1").on('submit', function (e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: this.action,
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                success(response)
                location.reload();
            },
            error: function (xhr, status, error) {
                $('.errorarea').show();

                $.each(xhr.responseJSON.errors, function (key, item) {
                    Sweet('error', item)
                    $("#errors").html("<li class='text-danger'>" + item + "</li>")
                });
                errosresponse(xhr, status, error);
            }
        })
    });

    $(".checkAll").on('click', function () {
        $('table input:checkbox').not(this).prop('checked', this.checked);
    });

    $(".cancel").on('click', function (e) {
        e.preventDefault();
        var link = $(this).attr("href");

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Do It!'
        }).then((result) => {
            if (result.value == true) {
                window.location.href = link;
            }
        })
    });


    function Sweet(icon, title, time = 3000) {

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




})(jQuery);

function copyUrl(id) {
    var copyText = document.getElementById("myUrl" + id);
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    Sweet('success', 'Link copied to clipboard.');
}

function checkPermissionByGroup(className, checkThis) {
    const groupIdName = $("#" + checkThis.id);
    const classCheckBox = $('.' + className + ' input');
    if (groupIdName.is(':checked')) {
        classCheckBox.prop('checked', true);
    } else {
        classCheckBox.prop('checked', false);
    }
}

function success(res) {
    $('input[name="ids[]"]:checked').each(function (i) {
        var ids = $(this).val();
        $('#row' + ids).remove();
    });

    var numberOfChecked = $('input:checkbox:checked').length;
    if (numberOfChecked == 0) {
        location.reload();
    }

}
load_revenue_perfomace(7);
$('#revenue_perfomace_days').on('change', function () {
    var period = $('#revenue_perfomace_days').val();
    load_revenue_perfomace(period);
});

var ctx = document.getElementById("myChart").getContext('2d');

function load_revenue_perfomace_chart(dates, totals) {
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Total Amount',
                data: totals,
                borderWidth: 2,
                backgroundColor: 'rgba(63,82,227,.8)',
                borderWidth: 0,
                borderColor: 'transparent',
                pointBorderWidth: 0,
                pointRadius: 3.5,
                pointBackgroundColor: 'transparent',
                pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    gridLines: {

                        drawBorder: false,
                        color: '#f2f2f2',
                    },
                    ticks: {
                        beginAtZero: true,
                    },

                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                        tickMarkLength: 15,
                    }
                }]
            },
        }
    });
}

function load_revenue_perfomace(period) {
    $('#revenue_performance').show();
    var url = $('#revenue_perfomance').val();
    $.ajax({
        type: 'get',
        url: url + '/' + period,

        dataType: 'json',


        success: function (response) {
            $('#revenue_performance').hide();
            var month_year = [];
            var dates = [];
            var totals = [];



            if (period != 365) {
                $.each(response, function (index, value) {
                    var total = value.total;
                    var dte = value.date;
                    totals.push(total);
                    dates.push(dte);
                });
                load_revenue_perfomace_chart(dates, totals);
            }
            else {
                $.each(response, function (index, value) {
                    var month = value.month;
                    var total = value.total;
                    month_year.push(month);
                    totals.push(total);
                });
                load_revenue_perfomace_chart(month_year, totals);
            }

        }
    })
}
