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
            <title>BootlegCMS: {{@$application->name}}</title>
        @show
        <script type="text/javascript" src="/vendor/bootleg/cms/js/script.min.js"></script>
        <link rel="stylesheet" href="/vendor/bootleg/cms/css/application.css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
        <?php
            $headerItems = Event::fire('html.master.header.end', array());
        ?>
        @foreach($headerItems as $headerItem)
            {!!$headerItem!!}
        @endforeach
    </head>

    <body class="">
        
        
        <div class="container">
            @section('main-content')

            @show
        </div>
    </body>

</html>
