<div class="form-group @error($name) has-error @enderror" style="@isset($style) {{ $style }} @endisset">
    @if($label)
        <label class="control-label" for="{{ $id }}">{{ $label }}</label>
    @endif
    <input id="{{ $id }}" type="number" @isset($settings) {{ $settings }} @endisset class="form-control" name="{{ $name }}" {{ ($required) ? " required" : "" }} value="{{ old($name, $initialValue) }}">
    @error($name)
    <label for="{{ $id }}" class="text-danger">{{ $message }}</label>
    @enderror
</div>