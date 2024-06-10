@if ($childrens)
  	<li @if(isset($childrens->children)) class="menu-item-has-children" @endif>

        @if(isset($childrens->children))
        <span class="menu-expand"><i class="fi-rs-angle-small-down"></i></span>
        @endif
  		<a href="{{ url($childrens->href) }}" @if(!empty($childrens->target)) target={{ $childrens->target }} @endif>
            {{ $childrens->text }}
        </a>
  		
		@if (isset($childrens->children)) 
		<ul class="dropdown">
			@foreach($childrens->children as $parent=> $row)
			 @include('fashionbag::components.menu.mobile-header-child', ['childrens' => $row])
			@endforeach
		</ul>
		@endif
	</li>
@endif