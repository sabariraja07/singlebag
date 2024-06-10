@extends('main.page')

@section('content-header')
        
<div id="main-hero" class="hero-body">
    <div class="container has-text-centered">
        <div class="columns is-vcentered">
            <div class="column is-6 is-offset-3 has-text-centered is-subheader-caption">
                <h1 class="title is-2">{{ $info->title }}</h1>
                <!-- <h2 class="subtitle">Lorem ipsum dolor sit amet consectetur elit.</h2> -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="section is-medium">
    <div class="container">
        {{ content($info->content->value ?? '') }}
    </div>
</div>
@endsection
