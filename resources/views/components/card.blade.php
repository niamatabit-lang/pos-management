@props([
    'width' => null, // 400 | 480 | 600
])

@php
    $classes = ['card'];
    if ($width) $classes[] = 'card-w-' . $width;
@endphp

<div {{ $attributes->class($classes) }}>
    {{ $slot }}
</div>
