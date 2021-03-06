<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Зона Чемпиона</title>

    <meta name="theme-color" content="#fff">
    <meta name="format-detection" content="telephone=no">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('img/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ url('img/favicons/manifest.json') }}">
    <link rel="mask-icon" href="{{ url('img/favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <link rel="shortcut icon" href="{{ url('img/favicons/favicon.ico') }}">
    <meta name="msapplication-config" content="{{ url('img/favicons/browserconfig.xml') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    @yield('styles')

</head>
<body id="app-layout">
    
    <!-- begin content -->
    <div class="content">
    @yield('content')
    </div>
    <!-- end content -->

    <!-- begin Elixir Livereload -->
    <div class="Elixir Livereload">
      @if ( Config::get('app.debug') )
        <script type="text/javascript">
          document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
        </script> 
      @endif
    </div>
    <!-- end Elixir Livereload -->

    <!-- BEGIN scripts -->
    @yield('scripts')
    <script type="text/javascript" src="{{ url('js/app.js') }}"></script>
    @yield('scripts_end')
    <!-- END scripts -->

</body>
</html>
