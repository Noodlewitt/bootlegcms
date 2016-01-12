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
        {{ $html_head_start_item }}
    @endforeach

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @section('main-head')
        <title>{{ $cms_title }}</title>
    @show
    <script type="text/javascript" src="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/js/scripts.min.js"></script>

    @if($application->getSetting('theme_file'))
        <link rel="stylesheet" href="{{ $application->getSetting('theme_file') }}" />
    @else
        <link rel="stylesheet" href="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/css/application.min.css" />
    @endif

    @foreach($html_head_end as $html_head_end_item)
        {!! $html_head_end_item !!}
    @endforeach
</head>

<body class="login-page">
    @foreach($html_body_start as $html_body_start_item)
        {{ $html_body_start_item }}
    @endforeach
    <div class="container">
        @yield('main-content')
    </div>
    @foreach($html_body_end as $html_body_end_item)
        {!! $html_body_end_item !!}
    @endforeach
</body>
</html>
