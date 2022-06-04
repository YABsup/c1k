@extends('layouts.c1k-new')


@section('content')
<?php

$jpegs = array(
  '88x31.jpg',
  '120x60.jpg',
  '234x60.jpg',
  '468x60.jpg',
  '120x90.jpg',
  '728x90.jpg',
  '125x125.jpg',
  '180x150.jpg',
  '300x250px.jpg',
  '336x280.jpg',
  '250x250px.jpg',
  'C1K-BANNER.jpg',
  '120x240.jpg',
  '240x400.jpg',
  '120x600.jpg',
  '160x600.jpg',
  '300x600.jpg',
   
);

 ?>

<!-- <div class="content" style="place-content: center; margin-top: 38px;"> -->
<div class="row" style="place-content: center; margin-top: 38px;">

  <div class="col-auto">
    @include('account/sidebar')
  </div>

  <div class="col">

    <div class="content">
      <div class="row">
        <div class="row change-text-title-person ">
          <h4>Медиа</h4>
        </div>
      </div>

      <div class="row" style="justify-content: center">
        <div class="p-1 m-auto">
          <div class="thumbnail">
            <a href="/media/gifs/doc_2019-08-22_22-58-18.gif"  target="_blank" class="d-flex flex-wrap flex-column">
              <img src="/media/gifs/doc_2019-08-22_22-58-18.gif" alt="Lights" >
              336x280
            </a>
          </div>
        </div>


        <div class="p-1 m-auto">
          <div class="thumbnail">
            <a href="/media/gifs/doc_2019-08-22_22-58-28.gif" target="_blank" class="d-flex flex-wrap flex-column">
              <img src="/media/gifs/doc_2019-08-22_22-58-28.gif" alt="Lights" >
              336x280
            </a>

          </div>
        </div><div class="p-1 m-auto">
          <div class="thumbnail">
            <a href="/media/gifs/doc_2019-08-22_22-58-34.gif" target="_blank" class="d-flex flex-wrap flex-column">
              <img src="/media/gifs/doc_2019-08-22_22-58-34.gif" alt="Lights" >
              336x280
            </a>
          </div>
        </div>
      </div>


    </div>

    <div class="row">
      @foreach($jpegs as $jpeg)
      <div class="p-3 m-auto">
        <div class="thumbnail">
          <a href="/media/jpeg/{{ $jpeg }}"  target="_blank">
            <img src="/media/jpeg/{{ $jpeg }}" alt="Lights" style="    max-width: 250px;">
          </a>
        </div>
        <div class="caption">
{{ str_replace('.jpg','', $jpeg) }}
        </div>
      </div>
      @endforeach
    </div>

  </div>

</div>
<!-- </div> -->
@endsection
