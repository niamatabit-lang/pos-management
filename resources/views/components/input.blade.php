@props([
    'name',
    'label'    => null,
    'type'     => 'text',
    'value'    => null,
    'required' => false,
    'flush'    => false, // no bottom margin (for inline rows)
])

<div class="form-group @if($flush) form-group-flush @endif">
    @if ($label)
        <label class="form-label" for="{{ $name }}">
            {{ $label }} @if($required)<span class="required">*</span>@endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->class(['form-control']) }}
    >

    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
