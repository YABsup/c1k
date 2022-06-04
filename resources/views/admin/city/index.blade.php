@extends('admin.layout')

@section('title')
    Панель администратора: Список городов
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
@endsection

@section('page_header')
    Города
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
                    <th>Наименование</th>
                    <th>Страна</th>
                    <th>Активность</th>
                    <th>Порядок</th>

                    <th>Курсы с</th>
                    <th>Покупка</th>
                    <th>Продажа</th>

                    <th>Редактировать</th>
                </tr>
                </thead>
                <tbody>
                @forelse($cityes as $city)
                <tr @if ($city->active)  @else style="color: darkgrey;" @endif>
                    <td>{{ $city->code  }}</td>
                    <td>{{ $city->name  }}</td>
                    <td>{{ $city->country->name  }}</td>
                    <td>{{ $city->active }}</td>
                    <td>{{ $city->order }}</td>

                    <td>{{ $city->ref_city ? $city->ref_city->name : '' }}</td>
                    <td>{{ $city->ref_city ? $city->ref_bid_coef : '' }}</td>
                    <td>{{ $city->ref_city ? $city->ref_ask_coef : '' }}</td>

                    <td class="text-right">
                        @component('admin.components.actions', [
                            'edit' => true,
                            'editUri' => route('admin.city.edit', $city),
                            'editLabel' => "Редактировать город #$city->id",
                            'delete' => false,
                            'deleteUri' => route('admin.city.destroy', $city),
                            'deleteLabel' => "Удалить город #$city->id",
                            'toggle' => true,
                            'toggleUri' => route('admin.city.set_active', $city),
                            'toggleAction' => "$city->active",
                            'toggleLabel' => "$city->name"
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
              "order": [[ 3, "desc" ],[ 4,"asc"]],
              'info'        : true,
              'autoWidth'   : false
            });
        } );
    </script>
@endsection
