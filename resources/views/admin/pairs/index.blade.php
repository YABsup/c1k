
@extends('admin.layout')

@section('title')
Панель администратора: Валютные пары
@endsection

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.2/css/responsive.dataTables.min.css"/>
@endsection

@section('page_header')
Валютные пары
@endsection

@section('content')
<div class="panel panel-default">
  <div class="panel-body">
    <div class="form-group">
      @isset($city_id)
      <a href="{{ route('admin.pairs.create',array('city_id'=>$city_id)) }}" class="btn btn-success btn-sm">Создать валютную пару</a>
      @else
      <a href="{{ route('admin.pairs.create') }}" class="btn btn-success btn-sm">Создать валютную пару</a>
      @endisset
    </div>

    <table id="#pairtable" class="dataTable table table-striped table-bordered display">
      <thead>
        <tr>
          <th>Пунк обмена</th>
          <th>Пара</th>
          <th title="Курс на bestchange для заданой позиции:текущая позиция">BestChange</th>
          <th title="Лимит провайдер">Provider</th>
          <th title="Планка">Планка</th>
          <th title="Коеф.">Коеф.</th>
          <th title="Курс на экспорт с учетом позиции и лимита">Курс c1k</th>
          <th>Редактировать</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pairs as $pair)
        <tr  @if ($pair->active)  @else style="color: darkgrey;" @endif>
          <td>{{ $pair->city->name }}<br /> {{ $pair->city->ref_city ? '( '.$pair->city->ref_city->name.': '.($pair->city->ref_bid_coef*1.0).'/'.($pair->city->ref_ask_coef*1.0).' )' : '' }}</td>
          <td>{{ $pair->base_currency->code }}/{{ $pair->quote_currency->code  }}</td>

          <?php $best_rate = \App\Http\Controllers\Rates\CurrentRate::get_rate_best($pair); ?>

          <td>@if( $best_rate ) <?php echo number_format($best_rate['bid']*1,8,'.', ' ').': '.$best_rate['bid_position']; ?><br><?php echo number_format($best_rate['ask']*1,8,'.',' ').': '.$best_rate['ask_position']; ?>@else no bid<br>no ask @endif </td>

          <?php $limit_rate = \App\Http\Controllers\Rates\CurrentRate::get_rate_limit($pair, $pair->provider->code ); ?>

          <td>{{ $pair->provider->name }}<br>
              @if( $limit_rate )
                  <?php echo number_format($limit_rate['bid']*1,8,'.', ' '); ?><br>
                  <?php echo number_format($limit_rate['ask']*1,8,'.',' '); ?>
              @else
               no bid<br>no ask
              @endif
         </td>


          <td>@if( $limit_rate ) <?php echo number_format($limit_rate['bid']*( $pair->provider_id != 0 ? $pair->bid_coef : 1),8,'.', ' '); ?><br><?php echo number_format($limit_rate['ask']*( $pair->provider_id != 0 ? $pair->ask_coef : 1),8,'.',' '); ?>@else no bid<br>no ask @endif </td>

          <td>
              {{ $pair->bid_coef }}<br>{{ $pair->ask_coef }}
          </td>

          <?php $final_rate = \App\Http\Controllers\Rates\CurrentRate::get_rate_final($pair, $best_rate, $limit_rate ); ?>
          <td>@if( $final_rate ) <?php echo number_format($final_rate['bid']*1,8,'.', ' '); ?><br><?php echo number_format($final_rate['ask']*1,8,'.',' '); ?>@else no bid<br>no ask @endif </td>


          <td class="text-right">
              <a href="/admin/pairs/{{$pair->id}}/edit?favorite={{ $pair->favorite ? '0' : '1'}}" class="btn btn-sm btn-warning" title="{{$pair->favorite ? "Add to favorites" : "Del from favorites"}}">
              <i class="fa fa-toggle-{{$pair->favorite ? 'on' : 'off'}}"></i>
              </a>

              <a href="/admin/pairs/{{$pair->id}}/edit?toggle={{ $pair->active ? '0' : '1'}}" class="btn btn-sm btn-primary" title="{{$pair->active ? "Включить" : "Выключить"}}">
                  <i class="fa fa-toggle-{{$pair->active ? 'on' : 'off'}}"></i>
              </a>
              <br />

            @component('admin.components.actions', [
            //'toggle' => false,
            //'toggleUri' => route('admin.pairs.edit', $pair),
            //'toggleAction' => "$pair->active",
            //'toggleLabel' => "$pair->name",

            'edit' => true,
            'editUri' => route('admin.pairs.edit', $pair),
            'editLabel' => "Редактировать пару #$pair->id",

            'delete' => true,
            'deleteUri' => route('admin.pairs.destroy', $pair),
            'deleteLabel' => "Удалить пару $pair->id",

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
    'paging'      : false,
    'lengthChange': false,
    'searching'   : true,
    'ordering'    : true,
    'info'        : false,
    'autoWidth'   : false,
    "pageLength": 50
  });
});
</script>
@endsection
