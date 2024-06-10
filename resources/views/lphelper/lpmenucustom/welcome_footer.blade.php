@if(!empty($menus))
@php
$mainMenus=$menus;
@endphp
@if(isset($mainMenus['name']))
@php
$name=$mainMenus['name'];
$data=$mainMenus['data'];
@endphp
 <ul class="footer-column">
    <li class="column-header">{{ $name }}</li>
    @foreach ($data ?? [] as $row) 
    <li class="column-item @if(url()->current() == url($row->href)) active @endif"><a href="{{ url($row->href) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a></li>
    @endforeach
</ul>
@endif
@endif