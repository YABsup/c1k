@extends('admin.layout')

@section('title')
Панель администратора: Заявки на обмен
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
Заявки на обмен - {{ $counts[1] + $counts[2] + $counts[3] + $counts[4]}}
@endsection

@section('content')
<div class="panel panel-default">
  <div class="panel-body">
    <div class="form-group">
      <a href="/admin/orders?filter=1" class="btn {{ $filter==1?"btn-success":"btn-primary" }} btn-sm">Новые - {{$counts[1]}}</a>
      <a href="/admin/orders?filter=2" class="btn {{ $filter==2?"btn-success":"btn-primary" }} btn-sm">В работе - {{$counts[2]}}</a>
      <a href="/admin/orders?filter=3" class="btn {{ $filter==3?"btn-success":"btn-primary" }} btn-sm">Выполнены - {{$counts[3]}}</a>
      <a href="/admin/orders?filter=4" class="btn {{ $filter==4?"btn-success":"btn-primary" }} btn-sm">Завершены - {{$counts[4]}}</a>
    </div>

    <table id="#ordertable" class="dataTable table table-striped table-bordered display">
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
          <th>IP</th>

           <th>Реферер</th>
           <th>Подтвержден</th>
           <th>Статус</th>


        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)

        @if ($order->status_id =='2')
          {{-- <tr style="color: orange;"> --}}
        @elseif ($order->status_id =='4')
          {{-- <tr style="color: darkgrey;"> --}}
        @elseif ($order->status_id =='3')
          {{-- <tr style="color: green;"> --}}
        @else
          {{-- <tr> --}}
        @endif
        <tr>


          <td>{{ $order->id }}</td>

          <td>{{ $order->pair->city->name ?? '@@' }}</td>

          <td>{{ $order->pair->symbol  }}</td>
          <td>{{ $order->side  }}</td>

          @if( $order->side == 'buy' )
            <td>{{ (float)$order->amount_take.' '.( $order->pair->base_currency->code ?? '@@')  }}</td>
            <td>{{ (float)$order->amount_get.' '.( $order->pair->quote_currency->code ?? '@@') }}</td>
          @else
            <td>{{ (float)$order->amount_take.' '.( $order->pair->quote_currency->code ?? '@@') }}</td>
            <td>{{ (float)$order->amount_get.' '.( $order->pair->base_currency->code ?? '@@')  }}</td>
          @endif
          <td>
            @isset($order->first_name) Name:{{ $order->first_name }}<br>@endisset
            @isset($order->address_from) From address: {{ $order->address_from }}<br>@endisset
            @isset($order->address_to) To address: {{ $order->address_to }}<br>@endisset
            @isset($order->viber) viber:{{ $order->viber }}<br>@endisset
            @isset($order->telegram) telegram:{{ $order->telegram }}<br>@endisset
            @isset($order->whatsapp) whatsapp:{{ $order->whatsapp }}<br>@endisset
            @isset($order->email) email:{{ $order->email }}<br>@endisset

          </td>
          <td>{{ $order->updated_at  }}</td>
          <td>{{ $order->user_ip  }}</td>
          @if( $order->user->referer != null)
          <td>{{ $order->user->referer->email }}</td>
          @else
          <td>нет</td>
          @endif
          <td>{{ $order->confirm ? 'Да' : 'Нет' }}</td>


          <!--td>{{ $order->gate }}</td-->
          @if ($order->status_id =='2')
          <td class="text-right">
            {{ $order->status->desc }}<br>
            @component('admin.components.actions_pop', [
            'delete' => true,
            'deleteUri' => route('admin.exchange.destroy', $order),
            'deleteLabel' => "Отмена заявки",
            'edit' => true,
            'editUri' => route('admin.exchange.edit', $order),
            'editLabel' => "В обработку"
            ])@endcomponent
          </td>
          @else
          <td class="text-right">
            {{ $order->status->desc }}<br>
            @component('admin.components.actions', [
            'delete' => true,
            'deleteUri' => route('admin.exchange.destroy', $order),
            'deleteLabel' => "Отмена заявки",
            'edit' => true,
            'editUri' => route('admin.exchange.edit', $order),
            'editLabel' => "В обработку"
            ])@endcomponent
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
