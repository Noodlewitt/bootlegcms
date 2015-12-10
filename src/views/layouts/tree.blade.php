@extends(view()->exists($cms_package.'::layouts.master') ? $cms_package.'::layouts.master' : 'cms::layouts.master')
@section('main-content')
    <div class="col-sm-3 col-md-offset-2 col-md-2 text-center treeOptions">
        <a href="{{ $content_mode === 'template' ? action('\Bootleg\Cms\TemplateController@anyCreate') : action('\Bootleg\Cms\ContentsController@anyCreate') }}" class="btn create btn-cms-primary">
            <i class="glyphicon glyphicon-plus"></i> Create New Item
        </a>
    </div>

    <script>
        $(function() {
            $('.treeOptions .create').click(function(e){
                e.preventDefault();
                var tree = $(".tree").jstree(true);



                var parent = $('.tree').jstree('get_selected');
                if(parent == ''){
                    parent = '#';
                }
                var newNode = { state: "open", data: "New node!" };
                $('.tree').jstree("create_node", parent, newNode, 'last', function(e){
                    tree.edit(e);
                });  
            });
            $('.treeContainer').click(function(e){
                e.stopPropagation();
                var selected = $('.tree').jstree('get_selected');
                $('.tree').jstree('deselect_node', selected);
            });
            $('.tree').click(function(e){
                e.stopPropagation();
            });
        });
    </script>

    <div class="col-sm-3 col-md-offset-2 col-md-2 sidebar treeContainer">
        @include('cms::contents.tree', array('content'=>@$content, 'tree'=>@$tree))
    </div>

    <div class="col-sm-offset-3 col-md-offset-4 col-md-8 main-content">
        @include('cms::contents.edit', compact('content', 'content_defaults', 'settings', 'allPermissions'))
    </div>
@stop
