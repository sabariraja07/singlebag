<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="./vendors/css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="./vendors/css/forms/wizard/bs-stepper.min.css">
<link rel="stylesheet" type="text/css" href="./vendors/css/extensions/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="./vendors/css/forms/select/select2.min.css">
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="./admin/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="./admin/css/bootstrap-extended.css">
<link rel="stylesheet" type="text/css" href="./admin/css/colors.css">
<link rel="stylesheet" type="text/css" href="./admin/css/components.css">
<link rel="stylesheet" type="text/css" href="./admin/css/themes/dark-layout.css">
<link rel="stylesheet" type="text/css" href="./admin/css/themes/bordered-layout.css">
<link rel="stylesheet" type="text/css" href="./admin/css/themes/semi-dark-layout.css">
<!-- END: Theme CSS-->

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="./admin/css/core/menu/menu-types/vertical-menu.css">
<link rel="stylesheet" type="text/css" href="./admin/css/themes/semi-dark-layout.css">
<link rel="stylesheet" type="text/css" href="./admin/css/core/menu/menu-types/vertical-menu.css">
<link rel="stylesheet" type="text/css" href="./admin/css/plugins/forms/form-validation.css">
<link rel="stylesheet" type="text/css" href="./admin/css/plugins/forms/form-wizard.css">
<link rel="stylesheet" type="text/css" href="./admin/css/pages/authentication.css">
<link rel="stylesheet" type="text/css" href="../admin/css/plugins/extensions/ext-component-sweet-alerts.css">
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="./admin/css/style.css">
<!-- END: Custom CSS-->

<style>
    hr {
        width: 93% !important;
        margin-left: auto !important;
        margin-right: auto !important;
        margin-top: -5px !important;
    }

    .mobile_view .card .img {
        width: 100px;
        height: auto;
        object-fit: cover;
    }

    .mobile_view .card {
        margin-bottom: 1rem !important;
    }

    @media screen and (min-width: 992px) {
        .mobile_view {
            display: none;
        }

        .desktop_view {
            display: block;
        }
    }

    @media screen and (max-width: 900px) {

        .card-header-form {
            width: 100% !important;
        }

        .badge {
            font-size: 65% !important;
        }

        .mobile_view {
            display: block;
        }

        .desktop_view {
            display: none;
        }
    }

    .card-image {
        width:100px;
        height:100px;
    }
    .card-image img {
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .card-dropdown{
        position: absolute;
        top: 15px;
        right: 15px;
    }
    .card-header .card-title{
        width: calc(100% - 15px) !important;
    }

    .mobile_view .card:not(.border_select) {
        overflow: hidden;
    }
</style>

<!-- BEGIN: Vendor CSS-->
@yield('vendor-style')
<!-- END: Vendor CSS-->

{{-- Page Styles --}}
@yield('page-style')
<!-- END: Page Styles -->