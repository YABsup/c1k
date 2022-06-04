@extends('admin.layout')

@section('title')
Панель администратора: Список рефералов
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
@endsection

@section('page_header')
Рефералы
@endsection

@section('content')
<div class="panel panel-default">
  <div class="panel-body">
    <!--div class="form-group">
    <a href=" route('city.create') " class="btn btn-success btn-sm">Создать города</a>
  </div-->

  <table id="#datatable" class="datatable table table-striped table-bordered">
    <thead>
      <tr>
        <th>Код</th>
        <th>Реферал</th>
        <th>Реферер</th>
        <th>Баланс</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $row)
      <tr>
        <td>{{ $row->id }}</td>
        <td>{{ $row->name }}</td>
        <td>@if($row->referer != null) {{ $row->referer->referer_id }} @endif </td>
        <td>{{ $row->balance }}</td>
      </tr>
      @empty
      <tr>
        <td class="text-center text-muted" colspan="5">Нет записей</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
</div>
@endsection

@section('javascript')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.2/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready( function () {
  $('.datatable').DataTable({
    'responsive': true,
    'paging'      : true,
    'lengthChange': false,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false
  });
} );
</script>
@endsection
