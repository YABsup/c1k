<?php


?>

<script>
function hide_left_sidebar(){
  $('#left_sidebar_main').hide();
  $('#left_sidebar_small').show();
}
function show_left_sidebar(){
  $('#left_sidebar_small').hide();
  $('#left_sidebar_main').show();
}
</script>

<div id="left_sidebar_small" class="left_sidebar_small"  onclick="show_left_sidebar()">
  <!--div>
  <img src="img/mask.svg" class="unMask">
</div-->

<div>
  Наши направления деятельности
</div>

<!--div>
<img src="img/mask.svg" class="unMask">
</div-->

</div>

<div id="left_sidebar_main" class="left_sidebar">
  <div class="left_side">
    ВЫБЕРИТЕ НУЖНОЕ НАПРАВЛЕНИЕ
  </div>

  <div class="left_c1k_logo">
    <img src="/img/c-1-k-finance-logo-copy-2.svg">
    <div class="left_c1k_link">
      <a href="https://c1k.world">BUY/SELL</a>
    </div>
  </div>



  <div class="left_c1k_logo">
    <img src="/img/c-1-k-finance-logo-copy-2.svg">

    <div class="left_c1k_link">
      <a href="https://c1k-fin.world">FINANCE</a>
    </div>
  </div>

  <div class="left_c1k_logo">
    <img src="/img/c-1-k-finance-logo-copy-2.svg">

    <div class="left_c1k_link">
      <a href="https://c1k-consulting.world">CONSULTING</a>
    </div>
  </div>

  <div class="left_side_down" onclick="hide_left_sidebar()">
    СВЕРНУТЬ <img src="img/mask.svg" class="Mask">
  </div>
</div>
