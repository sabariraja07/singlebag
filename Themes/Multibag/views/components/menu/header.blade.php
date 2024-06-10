@if(!empty($menus))
@foreach ($menus as $key=> $row) 
<li>
	@if (isset($row->children)) 
	<a href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</i></a>
	<ul class="sub-menu-width" style=" padding: 0px 18px 0px !important;">
		@foreach($row->children as $childrens)
			@include('multibag::components.menu.header-child', ['childrens' => $childrens])
		@endforeach
	</ul>
	@else
	<a  href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	@endif
</li>
@endforeach
@endif
