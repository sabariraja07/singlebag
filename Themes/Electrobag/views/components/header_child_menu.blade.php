<ul>
    @foreach ($childrens as $row)
        <li>
            <a href="{{ url($row->href ?? '/') }}" @if(!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
            @if (isset($row->children))
            <div class="menu-toggle"></div>
            <@include('electrobag::components.header_child_menu' , ['childrens' => $row->children])
            @endif
        </li>
    @endforeach
</ul>
