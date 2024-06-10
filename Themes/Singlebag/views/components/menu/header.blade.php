@if(!empty($menus))
@foreach ($menus as $key=> $row) 
<li>
	@if (isset($row->children)) 
	<a href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}<i class="fi-rs-angle-down"></i></a>
	<ul class="sub-menu" style="padding:21px">
		@foreach($row->children as $childrens)
			@include('singlebag::components.menu.header-child', ['childrens' => $childrens])
		@endforeach
	</ul>
	@else
	<a  href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	@endif
</li>
@endforeach
@endif