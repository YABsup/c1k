@extends('admin.layout')

@section('title')
    Панель администратора: пользователи
@endsection

@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
    Пользователи
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">

            <table id="#datatable" class="dataTable table table-striped table-bordered display">
                <thead>
                <tr>

                    <th>Email</th>
                    <th>Имя</th>
                    <th>Дата создания</th>
                    <th>Баланс</th>
                    <th>Переходов</th>
                    <th>Рефералов</th>
                    <th>Обменов</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                <tr{{ auth()->id() === $user->id ? ' class=success' : '' }}>

                    <td>{{ $user->email }}</td>

                    <td>{{ $user->name  }}</td>

                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->balance }}</td>
                    <td>{{ $user->visits }}</td>
                    <td>{{ ($user->referals != null) ? count($user->referals) : 0  }}</td>
                    <td>{{ ($user->orders != null) ? count($user->orders) : 0  }}</td>
                    <td><a href="{{ route('admin.users.show',$user->id) }}" class="btn btn-default">Просмотр</a></td>
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
            $('.dataTable').DataTable({
                'responsive': true,
                'paging'      : true,
                'lengthChange': false,
                'searching'   : true,
                'ordering'    : true,
                'info'        : false,
                'autoWidth'   : false,
                "pageLength": 50
            });
        } );
    </script>
@endsection
