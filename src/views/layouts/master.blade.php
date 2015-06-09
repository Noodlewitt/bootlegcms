<!doctype html>
<html lang="en" class="fullheight">
    <head>
        <meta charset="utf-8">
        <?php
            $headerItems = Event::fire('html.master.header.start', array());
        ?>
        @foreach($headerItems as $headerItem)
            {!!$headerItem!!}
        @endforeach
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('main-head')
            <title>BootlegCMS: {{@$application->name}}</title>
        @show
        
        <script type="text/javascript" src="{{Applicationurl::getBaseUrl()}}vendor/bootleg/cms/js/script.min.js"></script>

        <link rel="stylesheet" href="{{Applicationurl::getBaseUrl()}}vendor/bootleg/cms/css/application.css" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">

        <?php
            $headerItems = Event::fire('html.master.header.end', array());
        ?>
        @foreach($headerItems as $headerItem)
            {!!$headerItem!!}
        @endforeach
    </head>

    <body class="">
        <?php
            $headerItems = Event::fire('html.body.start', array());
        ?>
        @foreach($headerItems as $headerItem)
            {{$headerItem}}
        @endforeach
        @include('cms::layouts.nav')
        <div class="container-fluid">
            <div class="row">
                @include('cms::layouts.main_menu')  
                <div>
                    @yield('main-content')
                </div>
            </div>
        </div>
        <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popup" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <script>
        $(function() {
            $(document).on('hidden.bs.modal', function (e) {
                $(e.target).removeData('bs.modal');
            });
        });
        </script>
        <?php
            $headerItems = Event::fire('html.body.end', array());
        ?>
        @foreach($headerItems as $headerItem)
            {{$headerItem}}
        @endforeach
    </body>

</html>
