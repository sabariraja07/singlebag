@if ($childrens)
	<li>
		<a href="{{ url($childrens->href) }}" @if(!empty($childrens->target)) target={{ $childrens->target }} @endif>
		<span>{{ $childrens->text }}</span>
	</a>
	</li>
  	{{-- <li @if (isset($childrens->children)) class="has-children" @endif>
  		<a href="{{ url($childrens->href) }}" @if(!empty($childrens->target)) target={{ $childrens->target }} @endif>
            {{ $childrens->text }}
            @if(isset($childrens->children))
            <i class="fa fa-angle-down">
            @endif
        </a>
		@if (isset($childrens->children)) 
		<ul class="sub-menu">
			@foreach($childrens->children as $parent=> $row)
			 @include('fashionbag::components.menu.header-child', ['childrens' => $row])
			@endforeach
		</ul>
		@endif
	</li> --}}
@endif