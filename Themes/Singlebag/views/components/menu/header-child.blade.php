@if ($childrens)
  	<li>
  		<a href="{{ url($childrens->href) }}" @if(!empty($childrens->target)) target={{ $childrens->target }} @endif>
            {{ $childrens->text }}
            @if(isset($childrens->children))
            <i class="fi-rs-angle-right"></i>
            @endif
        </a>
		@if (isset($childrens->children)) 
		<ul class="level-menu" style="top: 42px;left: 19%;width: 210px;
		padding: 14px 0 24px">
			@foreach($childrens->children as $parent=> $row)
			 @include('singlebag::components.menu.header-child', ['childrens' => $row])
			@endforeach
		</ul>
		@endif
	</li>
@endif
