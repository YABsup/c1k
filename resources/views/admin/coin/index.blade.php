@extends('admin.layout')

@section('title')
    Панель администратора: Список валют
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
    Валюты
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <!--div class="form-group">
                <a href=" route('coin.create')" class="btn btn-success btn-sm">Создать валюты</a>
            </div-->

            <table id="#datatable" class="dataTable table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Код</th>
                    <th>Наименование</th>
                    <th>Активность</th>
                    <th>Округление</th>
                    <th>Редактировать</th>
                </tr>
                </thead>
                <tbody>
                @forelse($coins as $coin)
                <tr @if ($coin->active)  @else style="color: darkgrey;" @endif>
                    <td>{{ $coin->id  }}</td>
                    <td>{{ $coin->code  }}</td>
                    <td>{{ $coin->name  }}</td>
                    <td>{{ $coin->active }}</td>
                    <td>{{ $coin->round }}</td>
                    <td class="text-right">
                        @component('admin.components.actions', [
                            'edit' => false,
                            'editUri' => route('admin.coin.edit', $coin),
                            'editLabel' => "Редактировать валюту #$coin->id",
                            'delete' => false,
                            'deleteUri' => route('admin.coin.destroy', $coin),
                            'deleteLabel' => "Удалить валюту #$coin->id",
                            'toggle' => true,
                            'toggleUri' => route('admin.coin.edit', $coin),
                            'toggleAction' => "$coin->active",
                            'toggleLabel' => "$coin->name"
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
