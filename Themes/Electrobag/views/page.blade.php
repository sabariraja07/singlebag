@extends('electrobag::layouts.app') 

@section('content')
<div class="contact-us-area pt-160 pb-160">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{{ content($info->content ?? '') }}
			</div>
		</div>
	</div>
</div>

@endsection	