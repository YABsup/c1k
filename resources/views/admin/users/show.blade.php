@extends('admin.layout')

@section('title')
Панель администратора: просмотр пользователя #{{ $user->id }}
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
Просмотр пользователя #{{ $user->id }}
@endsection

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
        <form action="" method="POST">

            @component('admin.components.input-text', [
            'initialValue' => $user->name,
            'id'   => 'name',
            'name' => 'name',
            'placeholder' => 'Введите имя',
            'label' => 'Имя',
            'required' => true
            ])@endcomponent

            @component('admin.components.input-email', [
            'initialValue' => $user->email,
            'id'   => 'email',
            'name' => 'email',
            'placeholder' => 'Введите email',
            'label' => 'Email',
            'required' => true
            ])@endcomponent

            <div class="form-group ">
                <label class="control-label" for="balance">Balance</label>
                <input id="balance" type="number" class="form-control" name="balance" step="0.01" placeholder="" value="{{ $user->balance}}" disabled>
            </div>
            <hr/>
            <section class="content-header">
                <h3>
                    Рефералы
                </h3>
            </section>
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
                    @forelse($referals as $referal)
                    <tr{{ auth()->id() === $referal->id ? ' class=success' : '' }}>

                    <td>{{ $referal->email }}</td>
                    <td>{{ $referal->id  }}</td>
                    <td>{{ $referal->name  }}</td>

                    <td>{{ $referal->created_at }}</td>
                    <td>{{ $referal->balance }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.users.show',$referal->id) }}" class="btn btn-default">Просмотр</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center text-muted" colspan="5">Записей на </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <section class="content-header">
            <h3>
                Обмены пользователя
            </h3>
        </section>
        <table id="#ordertable" class="orderTable table table-striped table-bordered display">
            <thead>
                <tr>

                    <th>Номер заявки</th>

                    <th>Пунк обмена</th>
                    <th>Пара</th>
                    <th>Направление</th>
                    <th>Получаем</th>
                    <th>Отдаем</th>
                    <th>Контакты</th>
                    <th>Создана</th>
                    <!--th>Шлюз</th-->

                    <th>Реферер</th>
                    <th>Статус</th>
                    <th>Профит</th>

                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)

                @if ($order->status_id =='2')
                <tr style="color: orange;">
                    @elseif ($order->status_id =='4')
                    <tr style="color: darkgrey;">
                        @elseif ($order->status_id =='3')
                        <tr style="color: green;">
                            @else
                            <tr>
                                @endif


                                <td>{{ $order->id }}</td>

                                <td>{{ $order->pair->city->name }}</td>
                                <td>{{ $order->pair->symbol  }}</td>
                                <td>{{ $order->side  }}</td>

                                @if( $order->side == 'buy' )
                                <td>{{ (float)$order->amount_take.' '.$order->pair->base_currency->code  }}</td>
                                <td>{{ (float)$order->amount_get.' '.$order->pair->quote_currency->code }}</td>
                                @else
                                <td>{{ (float)$order->amount_take.' '.$order->pair->quote_currency->code }}</td>
                                <td>{{ (float)$order->amount_get.' '.$order->pair->base_currency->code  }}</td>
                                @endif
                                <td>
                                    @isset($order->first_name) Name:{{ $order->first_name }}<br>@endisset
                                    @isset($order->address_to) Number:{{ $order->address_to }}<br>@endisset
                                    @isset($order->viber) viber:{{ $order->viber }}<br>@endisset
                                    @isset($order->telegram) telegram:{{ $order->telegram }}<br>@endisset
                                    @isset($order->whatsapp) whatsapp:{{ $order->whatsapp }}<br>@endisset
                                    @isset($order->email) email:{{ $order->email }}<br>@endisset

                                </td>
                                <td>{{ $order->updated_at  }}</td>
                                @if( $order->user->referer != null)
                                <td>{{ $order->user->referer->email }}</td>
                                @else
                                <td>нет</td>
                                @endif

                                <td class="text-right">
                                    {{ $order->status->desc }}<br>
                                </td>
                                <td>
                                    {{ $order->profit }}
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td class="text-center text-muted" colspan="5">Записей на </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>


                    <section class="content-header">
                        <h3>
                            Обмены рефералов
                        </h3>
                    </section>
                    <table id="#ordertable" class="referalOrderTable table table-striped table-bordered display">
                        <thead>
                            <tr>

                                <th>Номер заявки</th>

                                <th>Пунк обмена</th>
                                <th>Пара</th>
                                <th>Направление</th>
                                <th>Получаем</th>
                                <th>Отдаем</th>
                                <th>Контакты</th>
                                <th>Создана</th>
                                <!--th>Шлюз</th-->

                                <th>Реферер</th>
                                <th>Статус</th>
                                <th>Профит</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($referalOrders as $order)

                            @if ($order->status_id =='2')
                            <tr style="color: orange;">
                                @elseif ($order->status_id =='4')
                                <tr style="color: darkgrey;">
                                    @elseif ($order->status_id =='3')
                                    <tr style="color: green;">
                                        @else
                                        <tr>
                                            @endif


                                            <td>{{ $order->id }}</td>

                                            <td>{{ $order->pair->city->name }}</td>
                                            <td>{{ $order->pair->symbol  }}</td>
                                            <td>{{ $order->side  }}</td>

                                            @if( $order->side == 'buy' )
                                            <td>{{ (float)$order->amount_take.' '.$order->pair->base_currency->code  }}</td>
                                            <td>{{ (float)$order->amount_get.' '.$order->pair->quote_currency->code }}</td>
                                            @else
                                            <td>{{ (float)$order->amount_take.' '.$order->pair->quote_currency->code }}</td>
                                            <td>{{ (float)$order->amount_get.' '.$order->pair->base_currency->code  }}</td>
                                            @endif
                                            <td>
                                                @isset($order->first_name) Name:{{ $order->first_name }}<br>@endisset
                                                @isset($order->address_to) Number:{{ $order->address_to }}<br>@endisset
                                                @isset($order->viber) viber:{{ $order->viber }}<br>@endisset
                                                @isset($order->telegram) telegram:{{ $order->telegram }}<br>@endisset
                                                @isset($order->whatsapp) whatsapp:{{ $order->whatsapp }}<br>@endisset
                                                @isset($order->email) email:{{ $order->email }}<br>@endisset

                                            </td>
                                            <td>{{ $order->updated_at  }}</td>
                                            @if( $order->user->referer != null)
                                            <td>{{ $order->user->referer->email }}</td>
                                            @else
                                            <td>нет</td>
                                            @endif

                                            <td class="text-right">
                                                {{ $order->status->desc }}<br>
                                            </td>
                                            <td>
                                                {{ $order->profit }}
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td class="text-center text-muted" colspan="5">Записей на </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>




                                <div class="form-group">
                                    <a href="{{ url()->previous() }}" class="btn btn-default">Назад к списку</a>
                                </div>
                            </form>
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
                            "pageLength": 20
                        });
                        $('.orderTable').DataTable({
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
