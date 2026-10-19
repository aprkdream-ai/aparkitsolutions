<header class="site-header">
    <div class="container header-inner">
        <x-brand />

        <button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false" aria-label="Open menu">
            <x-icon name="menu" class="icon-menu" />
            <x-icon name="close" class="icon-close" />
        </button>

        <nav class="nav" id="site-nav" aria-label="Main">
            <ul class="nav-list">
                @foreach ($navItems as $item)
                    @php($base = \Illuminate\Support\Str::before($item['route'], '.'))
                    <li>
                        <a class="nav-link" href="{{ route($item['route']) }}"
                           @if (request()->routeIs($item['route'], $base.'.*')) aria-current="page" @endif>{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="nav-cta">
                <a class="btn btn--primary btn--sm" href="{{ route('contact') }}">Start a Project <x-icon name="arrow-right" class="icon-arrow" /></a>
            </div>
            @if (config('site.contact.email') || config('site.contact.phone'))
                <div class="nav-mobile-extra">
                    @if ($email = config('site.contact.email'))<p class="mb-0">Email: <a href="mailto:{{ $email }}">{{ $email }}</a></p>@endif
                    @if ($phone = config('site.contact.phone'))<p class="mb-0">Phone: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a></p>@endif
                </div>
            @endif
        </nav>
    </div>
</header>
