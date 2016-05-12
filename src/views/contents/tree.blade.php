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
                    return  '{{ action("\\Bootleg\\Cms\\".$cm."Controller@anyTreeJson") }}';
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
                            console.log($node);
                            tree.open_node($node, function(){
                                $node = tree.create_node($node);
                                tree.edit($node);
                            });

                        }
                    },
                    'Delete':{
                        'label':'Delete',
                        'action': function(d){
                            if($node.children.length > 0){
                                //this item has sub items - TODO: we need to ask if we can delete:
                            }
                            swal({
                                title: "Are you sure?",
                                type: "error",
                                text: "Are you sure you want to delete?",
                                showCancelButton: true,
                                confirmButtonText: "Yes, delete it!"
                            },
                            function(){   
                                $.post( "{{ action("\\Bootleg\\Cms\\".$cm."Controller@anyDestroy") }}" ,{
                                    id: $node.id,
                                    '_token':'{!!csrf_token()!!}'
                                }).done(function(data){
                                    //successfully deleted.
                                    tree.delete_node($node);
                                }).fail(function(){
                                    $node.refresh();
                                });
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
            $.post( "{{ action("\\Bootleg\\Cms\\".$cm."Controller@anyTreeStore") }}" , {
                name:data.text,
                parent_id:parentnode.id,
                '_token':'{!!csrf_token()!!}'
            }).done(function(d){
                data.node.a_attr.href=d.a_attr.href;
                data.node.a_attr.class += d.a_attr.class;
                data.instance.load_node(parentnode);
                //data.instance.refresh_node(data.node);

            }).fail(function(){
                data.instance.refresh();
            });

        }
        else{
            $.post("{{ action("\\Bootleg\\Cms\\".$cm."Controller@anyUpdate") }}", {
                name:data.node.text,
                id:data.node.id,
                '_token':'{!!csrf_token()!!}'
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
            $('.main-content .overlay').fadeIn();
            $.get(data.node.a_attr.href, function(d){
                $('.main-content').html(d);
            });
        }
    });


    //on move of node.
    $('.tree').on("move_node.jstree", function (e, data) {
        $.post("{{ action("\\Bootleg\\Cms\\".$cm."Controller@anyUpdate") }}", {
            parent_id:data.parent,
            id:data.node.id,
            position: data.position,
            '_token':'{!!csrf_token()!!}'
        }).fail(function(){
            data.instance.refresh();
        });

    });


</script>

