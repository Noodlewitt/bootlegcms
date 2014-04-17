<!doctype html>
<html lang="en" class="fullheight">
    <!--todo:this^^-->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta charset="utf-8">
        <script type="text/javascript" src="/cms_assets/script.min.js"></script>
        <link rel="stylesheet" href="/cms_assets/css/sass.css" />
        
        



        <!--doesn't like being minified and moved out of it's directory so we have to keep it here-->
        
    </head>

    <body class="theme-asphalt main-menu-animated fullheight">


        <div id="main-wrapper" class="fullheight">

            
            @include('cms::layouts.main_menu')

            @yield('main')
        </div>

    </body>

</html>