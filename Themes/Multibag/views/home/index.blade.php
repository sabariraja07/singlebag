@extends('multibag::layouts.app')
@section('content')

<main  class="main">
   @include('multibag::home.sections.top-slider')
</main>
@endsection
@push('js')
<!-- <script src="{{ asset('frontend/multibag/js/home.js') }}"></script> -->
@endpush