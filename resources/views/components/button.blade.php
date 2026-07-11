@props([
    'variant' => 'primary', // primary | secondary | success | warning | danger | info
    'size'    => null,      // sm | lg
    'block'   => false,
    'tag'     => 'button',  // 'button' or 'a'
    'href'    => null,
    'type'    => 'submit',
])

@php
    $classes = ['btn', 'btn-' . $variant];
    if ($size) $classes[] = 'btn-' . $size;
    if ($block) $classes[] = 'btn-block';
@endphp

@if ($tag === 'a')
    <a
        href="{{ $href }}"
        {{ $attributes->class($classes) }}
    >{{ $slot }}</a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->class($classes) }}
    >{{ $slot }}</button>
@endif
