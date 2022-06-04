@extends('admin.layout')

@section('title')
Панель администратора: Валютные пары
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
Валютные пары
@endsection

@section('content')
<div class="panel panel-default">
  <div class="panel-body">
    <div class="form-group">
      <a href="{{ route('admin.reserv.create') }}" class="btn btn-success btn-sm">Создать резерв валюты</a>
    </div>

    <table id="#reservtable" class="dataTable table table-striped table-bordered display">
      <thead>
        <tr>
          <th>Валюта</th>
          <th>Резерв</th>
          <th>Редактировать</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reservs as $reserv)
        <tr>
          <td>{{ $reserv->coin->name  }}</td>
          <td>{{ $reserv->amount  }}</td>
          <td class="text-right">
            @component('admin.components.actions', [
            'edit' => true,
            'editUri' => route('admin.reserv.edit', $reserv),
            'editLabel' => "Редактировать",
            ])@endcomponent
          </td>
        </tr>
        @empty
        <tr>
          <td class="text-center text-muted" colspan="5">Записей на </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready( function () {
  $('.dataTable').DataTable({
    'responsive': true,
    'paging'      : true,
    'lengthChange': false,
    'searching'   : true,
    'ordering'    : false,
    'info'        : false,
    'autoWidth'   : false
  });
});
</script>
@endsection
