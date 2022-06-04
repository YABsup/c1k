@extends('admin.layout')

@section('title')
Панель администратора: Список анкет на партнерство
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
Анкеты
@endsection

@section('content')
<div class="panel panel-default">
  <div class="panel-body">
    <!--div class="form-group">
      <a href="" class="btn btn-success btn-sm">Обновить резервы</a>
    </div-->

    <table id="#ordertable" class="dataTable table table-striped table-bordered display">
      <thead>
        <tr>
          <th>username</th>
          <th>email</th>
          <th>telegram</th>
          <th>status</th>
          <th>type</th>
          <th>created_at</th>
          <th>updated_at</th>
          <th>edit</th>
        </tr>
      </thead>
      <tbody>
        @forelse($anketas as $anketa)

        @if( $anketa->status == 1 )
            <tr style="color: orange;">
        @elseif( $anketa->status == 2 )
            <tr style="color: green;">
        @elseif( $anketa->status == 3 )
            <tr style="color: darkgrey;">
        @else
            <tr>
        @endif

          <td>{{ $anketa->username }}</td>
          <td>{{ $anketa->email }}</td>
          <td>{{ $anketa->telegram }}</td>
          <td>@if($anketa->status == 1 ) Почта подтвеждена @elseif( $anketa->status == 2 ) Утверждена @elseif( $anketa->status == 3 ) Отклонена @else Новая @endif</td>
          <td>{{ $anketa->type }}</td>
          <td>{{ $anketa->created_at }}</td>
          <td>{{ $anketa->updated_at }}</td>

          <td class="text-right">
            @component('admin.components.actions', [
            'delete' => true,
            'deleteUri' => route('admin.anketas.destroy', $anketa),
            'deleteLabel' => "Отмена заявки",
            'edit' => true,
            'editUri' => route('admin.anketas.edit', $anketa),
            'editLabel' => "В обработку"
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
