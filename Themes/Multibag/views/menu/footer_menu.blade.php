@if(!empty($menus))
@php
$mainMenus=$menus;
@endphp
@if(isset($mainMenus['name']))
@php
$name=$mainMenus['name'];
$data=$mainMenus['data'];
@endphp
<h3 class="footer-title">{{ $name }}</h3>
<div class="footer-info-list">
    <ul>
        @foreach ($data ?? [] as $row) 
        <li><a @if(url()->current() == url( $row->href ?? '/' )) class="active" @endif href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a></li>
        @endforeach
    </ul>
</div>
@endif
@endif