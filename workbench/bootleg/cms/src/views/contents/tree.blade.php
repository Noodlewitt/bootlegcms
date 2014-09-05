<div class="page-header row searchTreeContainer">
    <input class='searchTree form-control' placeholder='search' type='text' />    
</div>
<?php
    $cm = ucfirst($content_mode);
?>
<div class='tree'>
    
</div>    
<script>
    $('.tree').jstree({
        "core" : {
          // so that create works
            "check_callback" : true,
            "data":{
                "url": function(node){
                    return  '{{ action($cm."Controller@anyTree") }}';
                }, 
                "data":function(node){
                    return {"id":node.id}
                }               
            }
        },
        "contextmenu":{         
            "items": function($node){
                var tree = $(".tree").jstree(true);
                return {
                    'Create':{
                        'label':'Create',
                        'action': function(d){
                            $node = tree.create_node($node);
                            tree.edit($node);
                        }
                    },
                    'Delete':{
                        'label':'Delete',
                        'action': function(d){
                            if($node.children.length > 0){
                                //this item has sub items - TODO: we need to ask if we can delete:
                            }
                            $.post( "{{ action($cm."Controller@anyDestroy") }}" ,{
                                id: $node.id
                            }).done(function(data){
                                //successfully deleted.
                                tree.delete_node($node);
                            }).fail(function(){
                                $node.refresh();
                            });
                        }
                    },
                    'Rename':{
                        'label':'Rename',
                        'action': function(d){
                            tree.edit($node);
                        }
                    }
                }
            }
        },
        'plugins':["search", "contextmenu", "dnd", "state", "types"]
    });
    

    var to = false;
    $('.searchTree').keyup(function(){
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
          var v = $('.searchTree').val();
          $('.tree').jstree(true).search(v);
        }, 250);
    });



    $('.tree').on("rename_node.jstree", function (e, data) {
        if(isNaN(data.node.id)){
            //create new content item.
            var parentnode = data.instance.get_node(data.node.parent);         
            $.post( "{{ action($cm."Controller@anyStore", array('json'=>true)) }}" , {
                name:data.text,
                parent_id:parentnode.id
            }).done(function(d){
                data.instance.set_id(data.node, d.id);
                data.instance.refresh_node(data.node);
            }).fail(function(){
                data.instance.refresh();
            });

        }
        else{
            $.post("{{ action($cm."Controller@anyUpdate") }}", {
                name:data.node.text
            }).done(function(d){
                data.instance.refresh_node(data.node);
            }).fail(function(){
                data.instance.refresh();
            });
        }
        
    });
    
    //switch to selected page.
    $('.tree').on('changed.jstree', function (e, data) {
        if(data && data.selected && data.selected.length) {
            $('.col-md-offset-4 .overlay').fadeIn();
            $.get("{{ action($cm."Controller@anyEdit") }}/"+data.node.id, function(d){
                $('.col-md-offset-4').html(d);
                $('.col-md-offset-4 .overlay').fadeOut();
            });
        }
    });


    //on move of node.
    $('.tree').on("move_node.jstree", function (e, data) {
        $.post("{{ action($cm."Controller@anyUpdate") }}", {
            parent_id:data.parent,
            id:data.node.id,
            position: data.position
        }).fail(function(){
            data.instance.refresh();
        });

    });


</script>

