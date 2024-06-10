@if(!empty($menus))
@php
$mainMenus=$menus;
@endphp
@if(isset($mainMenus['name']))
@php
$name=$mainMenus['name'];
$data=$mainMenus['data'];
@endphp
<h4 class="widget-title">{{ $name }}</h4>
<ul class="footer-list mb-sm-5 mb-md-0">
    @foreach ($data ?? [] as $row) 
	<li><a @if(url()->current() == url($row->href)) class="active" @endif href="{{ url($row->href) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a></li>
	@endforeach
</ul>

@endif
@endif