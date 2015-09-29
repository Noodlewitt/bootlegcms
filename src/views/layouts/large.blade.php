@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')


@section('main-content')
    <div class="col-md-offset-2 col-md-10 main-content">
        @include('cms::contents.table.index', compact('content', 'content_defaults', 'settings', 'allPermissions'))
    </div>
@stop
