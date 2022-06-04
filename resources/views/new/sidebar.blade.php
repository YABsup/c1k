<?php


?>
<script>

function show_left_sidebar()
{
  $('#left_sidebar_small').hide();
  $('#left_sidebar_main').show();
}

function hide_left_sidebar()
{

  $('#left_sidebar_main').hide();
  $('#left_sidebar_small').show();

}
</script>

<div id="left_sidebar_small" class="left_sidebar_small" onclick="show_left_sidebar()">
  <div>
    {{ __('menu_header.our_ways') }}
  </div>

</div>

<div id="left_sidebar_main" class="left_sidebar">
  <div class="left_side">
    {{ __('menu_header.select_way') }}
  </div>

  <div class="left_c1k_logo">
    <a href="https://c1k.world"><img src="/img/c-1-k-finance-logo-copy-2.svg"></a>
    <div class="left_c1k_link">
      <a href="https://c1k.world">BUY/SELL</a>
    </div>
  </div>

  <div class="left_c1k_logo">
    <a href="https://c1k-fin.world"><img src="/img/c-1-k-finance-logo-copy-2.svg"></a>

    <div class="left_c1k_link">
      <a href="https://c1k-fin.world">FINANCE</a></a>
    </div>
  </div>
  <div class="left_c1k_logo">
     <a href="https://c1k-consulting.world"><img src="/img/c-1-k-finance-logo-copy-2.svg">

    <div class="left_c1k_link">
      <a href="https://c1k-consulting.world">CONSULTING</a>
    </div>
  </div>


  <div class="left_c1k_logo">
    <a href="https://c1k-development.world"><img src="/img/c-1-k-finance-logo-copy-2.svg"></a>
    <div class="left_c1k_link">
      <a href="https://c1k-development.world">DEVELOPMENT</a>
    </div>
  </div>

  <div class="left_side_down" onclick="hide_left_sidebar()">
    {{ __('menu_header.svernut') }} <img src="img/mask.svg" class="Mask">
  </div>
</div>
