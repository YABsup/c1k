<div class="form-group @error($name) has-error @enderror" style="@isset($style) {{ $style }} @endisset">
    @if($label)
        <label class="control-label" for="{{ $id }}">{{ $label }}</label>
    @endif
    <select id="{{ $id }}" class="form-control" name="{{ $name }}" {{ ($required) ? " required" : "" }} @isset($settings) {{ $settings }} @endisset>
    @foreach($options as $option)

    <option @if($initialValue == $option->id)selected @endif value="{{ $option->id }}">{{ $option->name }}</option>

    @endforeach
    </select>
    @error($name)
    <label for="{{ $id }}" class="text-danger">{{ $message }}</label>
    @enderror
</div>
