<!doctype html>
<html lang="en" class="fullheight">
    <head>
        <meta charset="utf-8">
        <?php
            $headerStartItems = Event::fire('html.master.header.start', array());
        ?>
        @foreach($headerStartItems as $headerStartItem)
            {!!$headerStartItem!!}
        @endforeach
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('main-head')
            <title>BootlegCMS: {{@$application->name}}</title>
        @show
        
        <script type="text/javascript" src="{{Applicationurl::getBaseUrl()}}vendor/bootleg/cms/js/script.min.js"></script>

        <link rel="stylesheet" href="{{Applicationurl::getBaseUrl()}}vendor/bootleg/cms/css/application.css" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">

        <?php
            $headerEndItems = Event::fire('html.master.header.end', array());
        ?>
        @foreach($headerEndItems as $headerEndItem)
            {!!$headerEndItem!!}
        @endforeach
    </head>

    <body class="">
        <?php
            $startItems = Event::fire('html.body.start', array());
        ?>
        @foreach($startItems as $startItem)
            {{$startItem}}
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <script>
        function endsWith(str, suffix) {
            return str.indexOf(suffix, str.length - suffix.length) !== -1;
        }
        $(function() {
            $(document).on('hidden.bs.modal', function (e) {
                $(e.target).removeData('bs.modal');
            });
            
            $('body').tooltip({
                selector: '[data-toggle="tooltip"]'
            });

            $('body').on('click', "a.js-main-content", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $.get($(this).attr('href'), function(data){
                    $('.main-content').html(data);
                });
            });

            $('body').on('click', ".js-main-content-container a", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $.get($(this).attr('href'), function(data){
                    $('.main-content').html(data);
                });
            });

            $('body').on('click', "input.js-main-content, button.js-main-content", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $form = $(this).closest('form');
                $.post($form.attr('action'), $form.serialize(), function(data){
                    $('.main-content').html(data);
                });
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
