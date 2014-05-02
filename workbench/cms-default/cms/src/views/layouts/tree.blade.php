<div class="col-sm-3 col-md-offset-2 col-md-2 sidebar treeContainer">
        @include('cms::contents.tree', array('content'=>@$content, 'tree'=>@$tree))
</div>

<div class="col-md-offset-4 col-md-8">
    {{$cont}}
</div>