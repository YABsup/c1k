<div class="form-group @error($name) has-error @enderror">
    @if($label)
        <label class="control-label" for="{{ $id }}">{{ $label }}</label>
    @endif
    <input id="{{ $id }}" type="password" class="form-control" name="{{ $name }}" placeholder="{{ $placeholder }}"{{ ($required) ? " required" : "" }} value="{{ old($name, $initialValue) }}" autocomplete="new-password">
    @error($name)
        <label for="{{ $id }}" class="text-danger">{{ $message }}</label>
    @enderror
</div>