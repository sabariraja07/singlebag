@if(!empty($menus))
@php
$mainMenus=$menus;
@endphp
@if(isset($mainMenus['name']))
@php
$name=$mainMenus['name'];
$data=$mainMenus['data'];
@endphp
<h2 class="widget-title">{{ $name }}</h2>
<ul class="widget-list">
    @foreach ($data ?? [] as $row)
    {{-- <li><a href="account.html">My Account</a></li> --}}
    <li>
        <a @if(url()->current() == url( $row->href ?? '/' )) class="active" @endif href="{{ url( $row->href ?? '/' ) }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>
            {{ $row->text }}
        </a>
    </li>
    @endforeach
</ul>
@endif
@endif