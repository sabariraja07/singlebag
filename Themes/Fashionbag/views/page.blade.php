@extends('fashionbag::layouts.app')
@section('breadcrumb')
@php
$title=$info->title;
@endphp
<li class="active">{{ $info->title }}</li>
@endsection
@section('content')
<div class="section section-margin">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{{ content($info->content ?? '') }}
			</div>
		</div>
	</div>
</div>
@endsection	