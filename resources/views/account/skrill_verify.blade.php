@extends('layouts.c1k-new')

@section('head-add')
<link rel="stylesheet" href="{{ asset('css/forms.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/exchange.css') }}"/>
@endsection

@section('content')
<div class="main">
    <div class="container">
        <div class="row">
            @include('account/sidebar')

            <div class="col">
                <div class="Fill" style="width:100%; text-align:center;">
                    <!-- <h4>@lang('account.skrill_verify.h4')</h4> -->
                    <div class="table-info-person-text mb-5">@lang('skrill_verify.title')</div>


                    @if( \Auth::user()->verified_send == 0)

                    <form method="POST" action="/account/skrill_verify" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row row_magrin_z">
                            <div class="col-lg-12 change-text-title"></div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8 ">

                                <div class="clearfix"></div>
                                <div class="col-md-12 change-text-title-person ">
                                    <h4>@lang('skrill_verify.fill')</h4>
                                </div>

                                <div class="col-md-12 row text-person_data">
                                    <div class="col-md-3 col-sm-4 col-xs-6 text-left">
                                        <label class="col-form-label" for="inputFirstName">@lang('skrill_verify.name')</label>
                                    </div>
                                    <div class="col-md-9 col-sm-8 col-xs-6">
                                        <input type="text" class="form-control" name="first_name" id="inputFirstName"  placeholder="@lang('skrill_verify.name')" required>
                                    </div>
                                </div>
                                <div class="col-md-12 row text-person_data">
                                    <div class="col-md-3 col-sm-4 col-xs-6 text-left">
                                        <label class="col-form-label" for="inputTel">@lang('skrill_verify.phone')</label>
                                    </div>
                                    <div class="col-md-9 col-sm-8 col-xs-6">
                                        <input type="text" class="form-control" name="tel" id="inputTel"  placeholder="Telephone"  required>
                                    </div>
                                </div>

                                <div class="col-md-12 row text-person_data">

                                    <div class="col-md-3 col-sm-4 col-xs-6 text-left">
                                        <label class="col-form-label" for="inputEmail">Email</label>
                                    </div>

                                    <div class="col-md-9 col-sm-8 col-xs-6">
                                        <input type="email" class="form-control" name="email" id="inputEmail"  pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$" value="{{\Auth::user()->email }}" required>
                                    </div>

                                    <div class="postn_msg">
                                        <div id="v" class="exchange_mgs_mis">@lang('exchange.personal.email.error')</div>
                                    </div>
                                </div>
                                <div class="col-md-12 row text-person_data">
                                    <div class="col-md-3 col-sm-4 col-xs-6  text-left">
                                        <label class="col-form-label" for="inputCardNumber">@lang('skrill_verify.card_number')</label>
                                    </div>
                                    <div class="col-md-9 col-sm-8 col-xs-6">
                                        <input type="text" class="form-control" name="card" id="inputCardNumber"  maxlength="16" placeholder="XXXXXXXXXXXXXXXX"  required>
                                    </div>
                                </div>

                                <div class="col-md-12 row text-person_data">
                                    <div class="col-md-3 col-sm-4 col-xs-6 text-left">
                                        <label class="col-form-label" for="inputCardFoto">@lang('skrill_verify.foto')</label>
                                    </div>
                                    <div class="col-md-9 col-sm-8 col-xs-6">
                                        <input type="file" class="form-control" name="foto[]" id="inputCardFoto" multiple required max-data-size="5192000">

                                        <script>
                                        var uploadField = document.getElementById("inputCardFoto");

                                        uploadField.onchange = function() {
                                            max_size = this.getAttribute("max-data-size")

                                            if(this.files.length > 5){
                                                alert("@lang('skrill_verify.maxfiles')");
                                                this.value = "";

                                            }else if (this.files.length > 0) {
                                                for (i = 0; i <= this.files.length - 1; i++) {
                                                    if(this.files[i].size > max_size){
                                                        alert("@lang('skrill_verify.filesize')");
                                                        this.value = "";
                                                        break;
                                                    }
                                                }
                                            }

                                        };
                                        </script>
                                        <!-- start -->


                                        <div class="fotoInPC alert alert-success" role="alert" style="
                                        position: absolute;
                                        top: 51px;
                                        left: -72px;
                                        z-index: 11;
                                        background-color: #d4eddaeb;
                                        width: 350px;
                                        ">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <hr>
                                        <h4 class="alert-heading">@lang('skrill_verify.note')</h4>
                                        <p>@lang('skrill_verify.note1')</p>
                                        <hr>
                                        <a href="" onclick="event.preventDefault();$('.fotoInMobile').show()"role="button" class="btn btn-outline-secondary">@lang('skrill_verify.nopc')</a>
                                    </div>


                                    <div class="fotoInMobile alert alert-warning" role="alert" style="position: absolute; top: -20px; left: -73px; z-index: 11; width: 350px; box-shadow: rgb(0, 0, 0) 0px 0px 20px; display: none;">
                                        <button type="button" class="close" data-dismiss="" aria-label="Close" onclick="($(this).parent().hide())">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <hr>
                                        <h4 class="alert-heading">@lang('skrill_verify.note')</h4>
                                        <p>@lang('skrill_verify.note2')</p>
                                        <hr>
                                    </div>



                                    <!-- end -->


                                </div>



                            </div>

                            <div class="col-md-12 row orderform-rules">

                                <div class="hint-block">
                                    @lang('skrill_verify.oferta')
                                </div>

                                <label class="form-check-label">
                                    <input name="checkbox" type="checkbox" class="form-check-input" id="xhange_checkbox">
                                    @lang('exchange.oferta')
                                </label>
                                <button type="submit" class="btn btn-primary button-change-form" id="btn_change">
                                    @lang('skrill_verify.send')
                                </button>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>


                    </form>
                    @else

                    <div class="col-md-12 change-text-title-person ">
                        <h4>@lang('skrill_verify.pending')</h4>
                    </div>

                    @endif


                </div>
            </div>

        </div>
    </div>
</div>

@endsection
