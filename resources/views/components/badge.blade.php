@props([
    'variant' => 'success', // success | danger | warning | info
])

<span {{ $attributes->class(['badge', 'badge-' . $variant]) }}>{{ $slot }}</span>
