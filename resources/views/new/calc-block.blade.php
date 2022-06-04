<?php  ?>

<div class="calc-bg-mix">
  <div class="calc-bg" style="
  mix-blend-mode: hard-light;
  position: absolute;
  /* background-image: url(/img/group-3.png); */
  /* background-size: contain; */
  ">
  <img src="/img/group-3.png" srcset="img/group-3@1x.png 1x, /img/group-3@2x.png 2x,
               /img/group-3@3x.png 3x" class="img-fluid">

</div>
<div class="calc-bg" style="
mix-blend-mode: hard-light;
position: absolute;
right: 0px;
    transform: rotate(180deg);
/* background-image: url(/img/group-3.png); */
/* background-size: contain; */
">
<img src="/img/group-3.png" srcset="img/group-3@1x.png 1x, /img/group-3@2x.png 2x,
             /img/group-3@3x.png 3x" class="img-fluid">

</div>

<div class="row calc">
  <div class="c1k_main Fill">
    @include('new/calc-give')
  </div>

  <div class="c1k_main Fill">
    @include('new/calc-get')
  </div>

  <div class="c1k_main Fill">
    @include('new/calc-data')
  </div>
</div>
</div>
