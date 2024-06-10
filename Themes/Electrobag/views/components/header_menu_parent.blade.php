<nav>
    @if(!empty($menus))
        <ul>
            @foreach ($menus as $key=> $row) 
            <li>
                <a href="{{ url($row->href ?? '') }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
                @if (isset($row->children))
                <div class="menu-toggle"></div>
                @include('electrobag::components.header_child_menu', ['childrens' => $row->children])
                @endif
            </li>
            @endforeach
        </ul>
    @else
    <ul>
        <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
    </ul>
    @endif
</nav>
