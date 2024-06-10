<ul class="nav nav-pills flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('seller.products.edit', $info->id) ? 'active' : '' }}"
            href="{{ route('seller.products.edit', $info->id) }}"><i class="fas fa-cogs"></i> {{ __('Item') }}</a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link {{ request()->is('seller/product/' . $info->id . '/price') ? 'active' : '' }}"
            href="{{ url('seller/product/' . $info->id . '/price') }}"><i class="fas fa-money-bill-alt"></i>
            {{ __('Price') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('seller/product/' . $info->id . '/option') ? 'active' : '' }}"
            href="{{ url('seller/product/' . $info->id . '/option') }}"><i class="fas fa-tags"></i>
            {{ __('Options') }}</a>
    </li> --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->is('seller/product/' . $info->id . '/variant') ? 'active' : '' }}"
            href="{{ url('seller/product/' . $info->id . '/variant') }}"><i class="fas fa-expand-arrows-alt"></i>
            {{ __('Variants') }}</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('seller/product/' . $info->id . '/image') ? 'active' : '' }}"
            href="{{ url('seller/product/' . $info->id . '/image') }}"><i class="far fa-images"></i>
            {{ __('Images') }}</a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link {{ request()->is('seller/product/' . $info->id . '/inventory') ? 'active' : '' }}"
            href="{{ url('seller/product/' . $info->id . '/inventory') }}"><i class="fa fa-cubes"></i>
            {{ __('Inventory') }}</a>
    </li> --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->is('seller/product/' . $info->id . '/files') ? 'active' : '' }}"
            href="{{ url('seller/product/' . $info->id . '/files') }}"><i class="fas fa-file"></i>
            {{ __('Files') }}</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('seller/product/' . $info->id . '/seo') ? 'active' : '' }}"
            href="{{ url('seller/product/' . $info->id . '/seo') }}"><i class="fas fa-chart-line"></i>
            {{ __('SEO') }}</a>
    </li>
</ul>
