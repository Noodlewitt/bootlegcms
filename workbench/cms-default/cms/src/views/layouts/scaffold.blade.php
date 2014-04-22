<!doctype html>
<html lang="en" class="fullheight">
    <!--todo:this^^-->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta charset="utf-8">
        <script type="text/javascript" src="/cms_assets/script.min.js"></script>
        <link rel="stylesheet" href="/cms_assets/css/sass.css" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
        



        <!--doesn't like being minified and moved out of it's directory so we have to keep it here-->
        
    </head>

    <body class="theme-asphalt main-menu-animated">

        <div id="main-wrapper" class="">

            @include('cms::layouts.nav')
            @include('cms::layouts.main_menu')
            <div id="main-menu-bg"></div>
            @yield('main')
        </div>

    </body>

</html>