@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')

@section('main-content')
    <div class="col-md-offset-2 col-sm-10">
        <div class="page-header row">
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12"><i class="glyphicon glyphicon-home"></i>Dashboard</h1>
        </div>
        <div class="row">
            @foreach(Event::fire('dashboard.items', []) as $dashItem)
                @if($dashItem instanceof \Illuminate\Contracts\View\View)
                    {!! $dashItem->render() !!}
                @endif
            @endforeach
        </div>
    </div>
@stop
