<?php
    $html_head_start = Event::fire('html.master.header.start', []);
    $html_head_end = Event::fire('html.master.header.end', []);
    $html_body_start = Event::fire('html.body.start', []);
    $html_body_end = Event::fire('html.body.end', []);
?>

<!doctype html>
<html lang="en" class="fullheight">
    <head>
        <meta charset="utf-8">
        @foreach($html_head_start as $html_head_start_item)
            {!! $html_head_start_item !!}
        @endforeach
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('main-head')
            <title>{{ config('bootlegcms.cms_title', 'BootlegCMS') }}: {{@$application->name}}</title>
        @show

        <script type="text/javascript" src="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/js/scripts.min.js"></script>
        <script type="text/javascript" src="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/js/cms.min.js"></script>

        @if($application->getSetting('theme_file'))
            <link rel="stylesheet" href="{{ $application->getSetting('theme_file') }}" />
        @else
            <link rel="stylesheet" href="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/css/application.min.css" />
        @endif
        <link rel="stylesheet" href="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/components/bootleg-imagetagger/bootleg-imagetagger.css" />
        <script src="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/components/bootleg-imagetagger/bootleg-imagetagger.js" ></script>
        @foreach($html_head_end as $html_head_end_item)
            {!! $html_head_end_item !!}
        @endforeach
    </head>

    <body class="">
        @foreach($html_body_start as $html_body_start_item)
            {{ $html_body_start_item }}
        @endforeach
        @include('cms::layouts.nav')
        <div class="container-fluid">
            <div class="row">
                @include('cms::layouts.sidebar')
                <div class="content-area">
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
        @foreach($html_body_end as $html_body_end_item)
            {{ $html_body_end_item }}
        @endforeach
        <div id="colour_swatch">
            <div class="grey-lightest"></div>
            <div class="grey-lighter"></div>
            <div class="grey-light"></div>
            <div class="grey"></div>
            <div class="grey-dark"></div>
            <div class="grey-darker"></div>
            <div class="grey-darkest"></div>
            <div class="gray-base"></div>
            <div class="primary-color"></div>
            <div class="brand-primary"></div>
            <div class="brand-success"></div>
            <div class="brand-info"></div>
            <div class="brand-warning"></div>
            <div class="brand-danger"></div>
            <script>
                $('.navbar-logo-image').on('click', function(){
                    $('#colour_swatch').stop(true).stop(true).fadeToggle('slow');
                });
            </script>
        </div>
    </body>

</html>
