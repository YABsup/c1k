@extends('admin.layout')

@section('title')
    Панель администратора: Верификации аккаунта
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
@endsection

@section('page_header')
    Верификации аккаунта
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
                    <th>Номер</th>
                    <th>email</th>
                    <th>Пользователь</th>
                    <th>Утвержден</th>

                    <th>Создана</th>
                    <th>Редактировать</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data as $row)
                @if( $row->approved )
                <tr style="background-color: lightgreen;">
                @else
                <tr>
                @endif
                    <th>{{ $row->id }} </th>
                    <th><a href="/admin/users/{{$row->user_id}}/edit">{{ $row->user->email }}</a> </th>
                    <th><a href="/admin/users/{{$row->user_id}}/edit">{{ $row->user->name }}</a> </th>
                    <th>{{ $row->approved?'да':'нет' }}{{ $row->admin->email??'' }} </th>

                    <td>{{ $row->created_at  }}</td>
                    <td class="text-right">
                        @component('admin.components.actions', [
                            'delete' => true,
                            'deleteUri' => route('admin.account_verifications.destroy', $row),
                            'deleteLabel' => "Удалить верификацию #$row->id",
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
              'ordering'    : false,
              'info'        : true,
              'autoWidth'   : false
            });
        } );
    </script>
@endsection
