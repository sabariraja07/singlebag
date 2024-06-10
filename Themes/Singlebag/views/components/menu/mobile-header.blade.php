@if(!empty($menus))
@foreach ($menus as $key=> $row) 
<li @if (isset($row->children)) class="menu-item-has-children" @endif>
	@if (isset($row->children))
	<span class="menu-expand"><i class="fi-rs-angle-small-down"></i></span>
	<a href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	<ul class="dropdown" style="display: none;">
		@foreach($row->children as $childrens)
			@include('singlebag::components.menu.mobile-header-child', ['childrens' => $childrens])
		@endforeach
	</ul>
	@else
	<a  href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	@endif
</li>
@endforeach
@endif
