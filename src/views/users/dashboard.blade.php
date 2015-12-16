@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')

@section('main-content')
<div class="col-md-offset-2 col-sm-10">
    <div class="page-header row">	
            <!-- Page header, center on small screens -->
            <h1 class="col-xs-12"><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;Dashboard</h1>
    </div>
    <div class="row">
        <?php
            $dashItems = Event::fire('dashboard.items', []);
        ?>
        @foreach($dashItems as $dashItem)
          {!! $dashItem->render() !!}
        @endforeach
    </div>
</div>
@stop
