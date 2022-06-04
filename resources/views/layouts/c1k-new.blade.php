@include('new/meta')

@include('new/header-block')
@include('new/top-menu-block')

<div class="c1k_main">


  @yield('content')

</div>

<script src="/dist/jquery.js"></script>
<script src="/dist/js/bootstrap.js"></script>
<script src="/js/main.js"></script>
@yield('footer-js')

@yield('new-footer-add')

@include("new/footer")

@include("new/sidebar")

</body>
</html>
