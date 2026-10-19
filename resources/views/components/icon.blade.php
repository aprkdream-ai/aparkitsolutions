@props(['name'])
<svg {{ $attributes->merge(['class' => 'icon']) }} aria-hidden="true" focusable="false"><use href="{{ asset_v('assets/img/icons.svg') }}#{{ $name }}"></use></svg>
