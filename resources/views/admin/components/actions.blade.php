
@isset($edit)
@if($edit)
<a href="{{ $editUri }}" class="btn btn-sm btn-primary" title="{{ $editLabel }}">
    <i class="fa fa-pencil"></i>
</a>
@endif
@endisset


@isset($delete)
@if($delete)
<form action="{{ $deleteUri }}" method="POST" class="inline-block">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <button type="submit" class="btn btn-sm btn-danger" title="{{ $deleteLabel }}">
        <i class="fa fa-trash"></i>
    </button>
</form>
@endif
@endisset

@isset($toggle)
@if($toggleAction)
    <a href="{{ $toggleUri }}?toggle=0" class="btn btn-sm btn-primary" title="Выключить {{ $toggleLabel }}">
	<i class="fa fa-toggle-off"></i>
    </a>
@else
    <a href="{{ $toggleUri }}?toggle=1" class="btn btn-sm btn-primary" title="Включить {{ $toggleLabel }}">
	<i class="fa fa-toggle-on"></i>
    </a>
@endif
@endisset