<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="keywords" content=""/>
    <meta name="description" content="@lang('c1k.main_description')"/>
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('/css/bootstrap/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/normalize.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/layout.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/media.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/css/font.css') }}"/>

@yield('head-add')

    <script src="https://google.com/recaptcha/api.js"></script>
    <script src="{{ asset('js/rates.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('/css/preloader.css') }}">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-126472310-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-126472310-1');
    </script>

</head>
