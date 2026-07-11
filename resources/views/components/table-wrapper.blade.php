@props([
    'padded' => false,
])

<div {{ $attributes->class(['table-wrapper', 'table-wrapper-padded' => $padded]) }}>
    {{ $slot }}

    @isset($footer)
        <div class="table-footer">
            {{ $footer }}
        </div>
    @endisset
</div>
