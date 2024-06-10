@if(!empty($menus))
@php
$mainMenus=$menus;
@endphp
@if(isset($mainMenus['name']))
@php
$name=$mainMenus['name'];
$data=$mainMenus['data'];
@endphp
<h6 class="h6 light">{{ $name }}</h6>
<div class="empty-space col-xs-b20"></div>
<div class="empty-space col-xs-b20"></div>

<div class="col-xs-6" style="right: 12px;top: -6px;width: 90% !important;">

    @foreach ($data ?? [] as $row) 
	<a @if(url()->current() == url($row->href)) class="active" @endif href="{{ url($row->href) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
	@endforeach
</div>


@endif
@endif