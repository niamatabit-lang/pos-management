@props([
    'route',
    'active' => false,
    'icon'   => '',
])

<li>
    <a href="{{ $route }}" class="{{ $active ? 'active' : '' }}">
        <i>{{ $icon }}</i>
        <span>{{ $slot }}</span>
    </a>
</li>
