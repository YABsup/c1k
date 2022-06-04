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
        <div class="form-group">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">Создать пользователя</a>
        </div>

        <table id="#datatable" class="dataTable table table-striped table-bordered display">
            <thead>
                <tr>

                    <th>Email</th>
                    <th>id</th>
                    <th>Имя</th>

                    <th>Дата создания</th>
                    <th>Баланс</th>
                    <th>Редактировать</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr{{ auth()->id() === $user->id ? ' class=success' : '' }}>

                <td>{{ $user->email }}</td>
                <td>{{ $user->id  }}</td>
                <td>{{ $user->name  }}</td>

                <td>{{ $user->created_at }}</td>
                <td>{{ $user->balance }}</td>
                <td class="text-right">
                    @component('admin.components.actions', [
                    'edit' => true,
                    'editUri' => route('admin.users.edit', $user),
                    'editLabel' => "Редактировать пользователя #$user->id",
                    'delete' => true,
                    'deleteUri' => route('admin.users.destroy', $user),
                    'deleteLabel' => "Удалить пользователя #$user->id"
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
    $('.dataTable').DataTable({
        'responsive': true,
        'paging'      : true,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
        "pageLength": 50
    });
} );
</script>
@endsection
