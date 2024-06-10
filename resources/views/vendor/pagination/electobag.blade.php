@if ($paginator->hasPages())
<div class="row electrobag-pagination">
    <div class="col-sm-3 hidden-xs">
        @if ($paginator->onFirstPage())
            <a class="button size-1 style-5 disabled" href="javascript:void(0);" style="background-color:#e4e4e4;">
                <span class="button-wrapper">
                    <span class="icon"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span class="text">prev page</span>
                </span>
            </a>
        @else
            <a class="button size-1 style-5" href="{{ $paginator->previousPageUrl() }}">
                <span class="button-wrapper">
                    <span class="icon"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span class="text">prev page</span>
                </span>
            </a>
        @endif
    </div>
    <div class="col-sm-6 text-center">
        <div class="pagination-wrapper">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination">...</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a class="pagination active" href="javascript:void(0);">{{ $page }}</a>
                        @else
                            <a class="pagination" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
    <div class="col-sm-3 hidden-xs text-right">
        @if ($paginator->hasMorePages())
        <a class="button size-1 style-5" href="{{ $paginator->nextPageUrl() }}">
            <span class="button-wrapper">
                <span class="icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                <span class="text">Next page</span>
            </span>
        </a>
        @else
        <a class="button size-1 style-5 disabled" href="javascript:void(0);" style="background-color:#e4e4e4;">
            <span class="button-wrapper">
                <span class="icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                <span class="text">Next page</span>
            </span>
        </a>
        @endif
    </div>
</div>
@endif
