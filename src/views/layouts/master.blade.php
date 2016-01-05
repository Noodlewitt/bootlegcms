<?php
    $html_head_start = Event::fire('html.master.header.start', []);
    $html_head_end = Event::fire('html.master.header.end', []);
    $html_body_start = Event::fire('html.body.start', []);
    $html_body_end = Event::fire('html.body.end', []);

    $cms_title = $application->name ? $application->name : ($application->getSetting('logo_title') ? $application->getSetting('logo_title') : 'BootlegCMS');
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
        <title>{{ $cms_title }}</title>
    @show

    <script type="text/javascript" src="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/js/script.min.js"></script>

    @if($application->getSetting('theme_file'))
        <link rel="stylesheet" href="{{ $application->getSetting('theme_file') }}" />
    @else
        <link rel="stylesheet" href="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/css/application.css" />
    @endif

    @foreach($html_head_end as $html_head_end_item)
        {!! $html_head_end_item !!}
    @endforeach
</head>

<body>
    @foreach($html_body_start as $html_body_start_item)
        {{ $html_body_start_item }}
    @endforeach
    @include('cms::partials.nav')
    <div class="container-fluid">
        <div class="row">
            @include('cms::partials.sidebar')
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
        {!! $html_body_end_item !!}
    @endforeach
</body>

</html>
