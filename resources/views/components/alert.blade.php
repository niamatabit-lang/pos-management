@props([
    'variant' => 'success', // success | danger | warning | info
    'size'    => null,      // sm | lg
])

@php
    $classes = ['alert', 'alert-' . $variant];
    if ($size) $classes[] = 'alert-' . $size;
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
