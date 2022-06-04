@extends('admin.layout')

@section('title')
Панель администратора: Заявки на вывод
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
Заявки на вывод
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

                <th>Номер заявки</th>
                <th>Пользователь</th>
                <th>ФИО</th>
                <th>Контакт телеграм</th>
                <th>Направление вывода</th>
                <th>Номер кошелька/карты</th>
                <th>Сумма</th>
                <th>Текущий баланс</th>
                <th>Создана</th>
                <th>Вывод подтвердил/отменил</th>
                <th>Статус</th>

            </tr>
        </thead>
        <tbody>
            @forelse($data as $order)

            @if($order->status_id =='2')
            <tr style="color: darkgrey;">
                @elseif($order->status_id =='1')
                <tr style="color: green;">
                    @else
                    <tr>
                        @endif
                        <td>{{ $order->id }}</td>
                        <td><a href="/admin/users/{{ $order->user_id }}">{{ $order->user->email }}</a></td>
                        <td>{{ $order->fio }}</td>
                        <td>{{ $order->telegram }}</td>
                        <td>{{ $order->currency  }}</td>

                        <td>{{ $order->address }}</td>
                        <td>{{ $order->balance }}</td>
                        <td>{{ $order->user->balance }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>@if($order->user_approved){{ $order->admin->email }} @else в процессе @endif</td>


                        @if ($order->status_id =='0')

                        <td class="text-right">
                            Новая<br>
                            @component('admin.components.actions', [
                            'delete' => true,
                            'deleteUri' => route('admin.withdraw.destroy', $order),
                            'deleteLabel' => "Отмена заявки",
                            'edit' => true,
                            'editUri' => route('admin.withdraw.edit', $order),
                            'editLabel' => "Выплачено"
                            ])@endcomponent
                        </td>
                        @elseif ($order->status_id =='1')
                        <td class="text-right">

                            Выплачена
                        </td>
                        @elseif ($order->status_id =='2')
                        <td class="text-right">
                            Отклонена
                        </td>
                        @endif
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
            'autoWidth'   : false,
            "pageLength": 50
        });
    });
</script>
@endsection
