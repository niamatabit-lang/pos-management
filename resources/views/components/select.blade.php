@props([
    'name',
    'label'       => null,
    'options'     => [],  // ['value' => 'Label']
    'selected'    => null,
    'placeholder' => null,
    'required'    => false,
    'flush'       => false,
])

<div class="form-group @if($flush) form-group-flush @endif">
    @if ($label)
        <label class="form-label" for="{{ $name }}">
            {{ $label }} @if($required)<span class="required">*</span>@endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->class(['form-select']) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $label_)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>
                {{ $label_ }}
            </option>
        @endforeach

        {{ $slot ?? '' }}
    </select>

    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
