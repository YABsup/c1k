@extends('layouts.c1k-new')


@section('content')


<!-- <div class="content" style="place-content: center; margin-top: 38px;"> -->
<div class="row" style="justify-content: space-between; margin-top: 38px;">

  <div class="col-auto">
    @include('account/sidebar')
  </div>

  <div class="Fill col" >

    <div class="content">
      <div class="row">
        <div class="account_card__background">
            <div class="row">

              <div class="total-exchange-text col" style="padding-top: 10px;">Выводов</div><div class="total-exchange-value col"> @if( $withdraws != null) {{  count($withdraws) }} @else 0 @endif </div>
            </div>
            <div class="row">

              <div class="total-exchange-text col" style="padding-top: 10px;">@lang('account.balance')</div>
              <div class="total-exchange-value col">

                @if($user->balance < 100)
                <a href="#" onclick="alert('@lang('account.min')');" title="@lang('account.withdraw')">{{ number_format($user->balance*1,2)  }}$</a>
                @else
                <!--a href="#" onclick="alert('Для вывода обратитесь к в чат');" title="Вывести">{{ number_format($user->balance*1,2)  }}$</a-->
                <a href="{{ route('account.withdraw') }}" title="@lang('account.withdraw')">{{ number_format($user->balance*1,2) }}$</a>
                @endif
              </div>
            </div>

        </div>
        @if( $ref_status == 1)
        <div class="referal-link-block">
          <div class="referal-link-link">
            <a href="https://c1k.world/signup/{{ $user->ref_code }}" onclick="event.preventDefault(); copyToClipboard();"  title="@lang('account.copy')">https://c1k.world/signup/{{ $user->ref_code }}</a>
            <!--<a href="#" onclick="event.preventDefault(); copyLink();">{{ $user->ref_code }}</a> -->
          </div>
          <!-- <div class="referal-link-text">Реферальная ссылка</div> -->
          <div class="referal-link-text"></div>
          <div class="unlink"><img src="/unlink.svg"
            class=""></div>
          </div>
          @endif
        </div>

      </div>
@if( $ref_status == 1)
      <script>
      function copyLink(target) {
        // standard way of copying
        var textArea = document.createElement('textarea');
        textArea.setAttribute
        ('style','width:1px;border:0;opacity:0;');
        document.body.appendChild(textArea);
        textArea.value = "https://c1k.world/signup/{{ $user->ref_code }}";
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert("@lang('account.link_copied')");
      }
      function copyToClipboard() {
        let textarea;
        let result;

        try {
          textarea = document.createElement('textarea');
          textarea.setAttribute('readonly', true);
          textarea.setAttribute('contenteditable', true);
          textarea.style.position = 'fixed'; // prevent scroll from jumping to the bottom when focus is set.
          textarea.value = "https://c1k.world/signup/{{ $user->ref_code }}";

          document.body.appendChild(textarea);

          textarea.focus();
          textarea.select();

          const range = document.createRange();
          range.selectNodeContents(textarea);

          const sel = window.getSelection();
          sel.removeAllRanges();
          sel.addRange(range);

          textarea.setSelectionRange(0, textarea.value.length);
          result = document.execCommand('copy');
        } catch (err) {
          console.error(err);
          result = null;
        } finally {
          document.body.removeChild(textarea);
        }

        // manual copy fallback using prompt
        if (!result) {
          const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
          const copyHotkey = isMac ? '⌘C' : 'CTRL+C';
          result = prompt(`Press ${copyHotkey}`, string); // eslint-disable-line no-alert
          if (!result) {
            return false;
          }
        }else{
          alert("@lang('account.link_copied')");
        }
        return true;
      }
      </script>
      @endif
      <div class="history-block">
        <div class="history-block-title">@lang('account.history')</div>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">send</th>

              <th scope="col">address</th>
              <th scope="col">created</th>
              <th scope="col">status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($withdraws as $withdraw)
            <tr>
              <td>{{ $withdraw->id }}</td>
              <td>
                <img src="/coin-logo/{{ $withdraw->currency }}.png" class="Group-8" style="margin: 4px; max-width: 15px;">
                {{ $withdraw->balance }}
              </td>

              <td>{{ $withdraw->address }}</td>
              <td>{{ $withdraw->created_at }}</td>
              <td><div class="order-status status-{{ $withdraw->status_id }} rounded"></div>{{ !$withdraw->status_id ? 'Pending' : 'Complite' }}</td>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>


      <!-- </div> -->
    </div>


    @endsection
