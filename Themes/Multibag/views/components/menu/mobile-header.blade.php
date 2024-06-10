@if(!empty($menus))
@foreach ($menus as $key=> $row) 
<li @if (isset($row->children)) class="has-sub-menu" @endif>
	@if (isset($row->children))
	<a href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	<ul class="sub-menu-2">
		@foreach($row->children as $childrens)
			@include('multibag::components.menu.mobile-header-child', ['childrens' => $childrens])
		@endforeach
	</ul>
	@else
	<a  href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	@endif
</li>
@endforeach
@endif