@extends('singlebag::layouts.app') 

@section('content')
<main class="main pages">
	<div class="page-header breadcrumb-wrap">
		<div class="container">
		    <h1 class="mb-15">{{ $info->title }}</h1>
			<div class="breadcrumb">
				<a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>{{ __('Home') }}</a>
				<span></span> {{ $info->slug }}
			</div>
		</div>
	</div>
	<div class="page-content pt-50" style="padding-left: 10px">
	{{ content($info->content ?? '') }}
	</div>
</main> 	

@endsection	