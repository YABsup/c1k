<script data-cfasync="false" src="{{ asset('js/email-decode.min.js') }}"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110604800-1"></script>
<script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    $(window).on('load', function () {
        var $preloader = $('#p_prldr');
        $preloader.fadeOut('slow');
    });
</script>
<script type="text/javascript">
    window.switcher = true;

    $('.working-hours').css({'display': 'none',});
    $('#btn_exchange_id').on('click', function(e){
        if(window.switcher === false) {
            e.preventDefault();
            $('.working-hours').css({
                'display': 'block',
                'position': 'fixed',
                'overflow': 'hidden',
                'z-index': '29',
                'background': 'rgba(255, 255, 255, 0.7)',
                'width': '100vw',
                'height': '100vh'
            })
            $('.working-hours-exit').on('click', function(e){
                e.preventDefault();
                $('.working-hours').css({'display': 'none',});
            })
        }
    });
    $('#cashless-btn_exchange_id').on('click', function(e){
    if(window.switcher === false) {
        e.preventDefault();
        $('.working-hours').css({
            'display': 'block',
            'position': 'fixed',
            'overflow': 'hidden',
            'z-index': '29',
            'background': 'rgba(255, 255, 255, 0.7)',
            'width': '100vw',
            'height': '100vh'
        })
        $('.working-hours-exit').on('click', function(e){
            e.preventDefault();
            $('.working-hours').css({'display': 'none',});
        })
    }
});


</script>
<!--script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/slick.js') }}"></script-->

<script type="text/javascript" src="{{ asset('js/xchange.js') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

<!-- <script src="/js/c1k/validform.js?15Y8HY409H"></script> -->
<!-- <script src="/js/c1k/dropdown-api.js?15Y8HY409H"></script> -->
@yield('footer-add')
