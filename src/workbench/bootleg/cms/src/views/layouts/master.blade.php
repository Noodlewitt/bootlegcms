<!doctype html>
<html lang="en" class="fullheight">
    <!--todo:this^^-->
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta charset="utf-8">
        <script type="text/javascript" src="{{Applicationurl::getBaseUrl()}}/packages/bootleg/cms/js/script.min.js"></script>
        <link rel="stylesheet" href="{{Applicationurl::getBaseUrl()}}/packages/bootleg/cms/css/application.css" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
        
    </head>
    
    <body class="">
        @include('cms::layouts.nav')
        <div class="container-fluid">
            <div class="row">
                @include('cms::layouts.main_menu')
                <div>
                    {{$cont}}
                </div>
            </div>
        </div>
    </body>

</html>