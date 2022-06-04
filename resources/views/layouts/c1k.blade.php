<!DOCTYPE html>
<html>

@include('blocks/meta')

<body>

@include("blocks/working-hours")

@include("blocks/p_prldr")

@include("blocks/popular_link_telegram")

@include("blocks/chart")

<div class="fixed-window-position">

@include("blocks/body-header")

@yield('content')

@include('blocks/footer')

@include("blocks/footer-js")
</div>

</body>
</html>
