@props([
    'title'    => null,
    'subtitle' => null,
    'flat'     => false,
])

<div {{ $attributes->class(['page-header', 'page-header-flat' => $flat]) }}>
    <div>
        @isset($title)
            <h1 class="page-title">{{ $title }}</h1>
        @endisset

        @isset($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endisset

        {{ $heading ?? '' }}
    </div>

    @isset($actions)
        <div class="table-actions">
            {{ $actions }}
        </div>
    @endisset
</div>
