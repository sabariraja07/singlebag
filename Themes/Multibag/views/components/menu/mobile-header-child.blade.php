@if ($childrens)
  	<li @if(isset($childrens->children)) class="has-sub-menu" @endif>
  		<a href="{{ url($childrens->href) }}" @if(!empty($childrens->target)) target={{ $childrens->target }} @endif>
            {{ $childrens->text }}
        </a>
  		
		@if (isset($childrens->children)) 
		<ul class="sub-menu-2">
			@foreach($childrens->children as $parent=> $row)
			 @include('multibag::components.menu.mobile-header-child', ['childrens' => $row])
			@endforeach
		</ul>
		@endif
	</li>
@endif