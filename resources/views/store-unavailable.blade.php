@extends('main.page')
@section('content')
<style>
    .error-wrap.error-centered img {
        max-height: 500px !important;
    }
</style>
    <div class="hero is-relative is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <!-- Error Wrapper -->
                    <div class="column error-wrap error-centered has-text-centered">
                        <img src="{{ asset('assets/img/auth/store-green.svg') }}"  alt="" />
                        <div class="error-caption">
                            <h2>Store Not Available.</h2>
                            <p>
                            Sorry, This store is currently unavailable, Please contact store owner.
                            </p>
                            <!-- <div class="button-wrap">
                                <a href="" class="
                      button button-cta
                      btn-outlined
                      is-bold
                      btn-align
                      primary-btn
                      rounded
                      raised
                    ">
                    Renew Now
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection