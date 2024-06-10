@extends('fashionbag::layouts.app')
@section('content')

<main  class="main">
   @include('fashionbag::home.sections.top-slider')
</main>
@endsection
@push('js')
<!-- <script src="{{ asset('frontend/fashionbag/js/home.js') }}"></script> -->
@endpush