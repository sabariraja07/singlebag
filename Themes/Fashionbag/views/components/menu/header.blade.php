@if(!empty($menus))
@foreach ($menus as $key=> $row) 
<li @if (isset($row->children)) class="has-children" @endif>
	@if (isset($row->children)) 
	<a href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>
		<span>{{ $row->text }}</span>
		<i class="fa fa-angle-down"></i>
	</a>
	<ul class="sub-menu">
		@foreach($row->children as $childrens)
			@include('fashionbag::components.menu.header-child', ['childrens' => $childrens])
		@endforeach
	</ul>
	@else
	<a  href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	@endif
</li>
@endforeach
@endif