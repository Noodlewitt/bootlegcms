<!doctype html>
<html lang="en" class="fullheight">
    <head>
        <meta charset="utf-8">
        <?php
            $headerItems = Event::fire('html.master.header.start', array());
        ?>
        @foreach($headerItems as $headerItem)
            {{$headerItem}}
        @endforeach

        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('main-head')
            <title>{{ @$application->name ? $application->name : config('bootlegcms.cms_title', 'BootlegCMS') }}</title>
        @show
        <script type="text/javascript" src="{{Applicationurl::getBaseUrl()}}vendor/bootleg/cms/js/script.min.js"></script>
        @if($application->getSetting('theme_file'))
            <link rel="stylesheet" href="{{ $application->getSetting('theme_file') }}" />
        @else
            <link rel="stylesheet" href="{{ Applicationurl::getBaseUrl() }}vendor/bootleg/cms/css/application.min.css" />
        @endif
        <?php
            $headerItems = Event::fire('html.master.header.end', array());
        ?>
        @foreach($headerItems as $headerItem)
            {!!$headerItem!!}
        @endforeach
    </head>

    <body class="login-page">
        <div class="container">
            @yield('main-content')
        </div>
    </body>

</html>
