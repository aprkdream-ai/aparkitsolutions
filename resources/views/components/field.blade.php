@props(['name', 'label', 'type' => 'text', 'required' => false, 'hint' => null, 'autocomplete' => null])
<div class="field">
    <label for="f-{{ $name }}">{{ $label }} @if ($required)<span class="req" aria-hidden="true">*</span>@else<span class="opt">(optional)</span>@endif</label>
    @php($describedBy = trim(($hint ? "f-{$name}-hint " : '').($errors->has($name) ? "f-{$name}-error" : '')))
    @if ($type === 'textarea')
        <textarea class="input" id="f-{{ $name }}" name="{{ $name }}" rows="6" @if ($required) required @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif @error($name) aria-invalid="true" @enderror {{ $attributes }}>{{ old($name) }}</textarea>
    @elseif ($type === 'select')
        <select class="input" id="f-{{ $name }}" name="{{ $name }}" @if ($required) required @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif @error($name) aria-invalid="true" @enderror {{ $attributes }}>{{ $slot }}</select>
    @else
        <input class="input" id="f-{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name) }}" @if ($required) required @endif
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif @error($name) aria-invalid="true" @enderror {{ $attributes }}>
    @endif
    @if ($hint)<span class="field-hint" id="f-{{ $name }}-hint">{{ $hint }}</span>@endif
    @error($name)<span class="field-error" id="f-{{ $name }}-error">{{ $message }}</span>@enderror
</div>
