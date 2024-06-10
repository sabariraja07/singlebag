@extends('singlebag::layouts.app')
@section('content')

<main  class="main">
   @include('singlebag::home.sections.top-slider')
</main>
@endsection
@push('js')
<!-- <script src="{{ asset('frontend/singlebag/js/home.js') }}"></script> -->
@endpush