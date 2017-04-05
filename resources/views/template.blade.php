<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="/">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('meta')
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.0.0/jquery.lazyloadxt.fadein.css">
        <link rel="stylesheet" href="css/materialize-tags.min.css">
        <link rel="stylesheet" href="css/dietlah.css">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        @yield('stylesheets')
        <title>@yield('title')</title>
    </head>
    <body class="site">
        <main class="site-content">
            @include('navbar')

            @yield('page-content')
        </main>

        <div class="bottom-wrapper">
            @yield('bottom-anchor')

            @include('footer')
        </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.1/js/materialize.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/salvattore/1.0.9/salvattore.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyloadxt/1.0.0/jquery.lazyloadxt.min.js"></script>
        <script type="text/javascript" src="js/navigation.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-96837033-1', 'auto');
            ga('send', 'pageview');
        </script>
        @yield('scripts')
        @include('jsvars')
    </body>
</html>
