<?php

//TODO: we should probably do this as JSON. 
function renderTree( $tree = array()){

    $current_depth = 0;
    $counter = 0;

    $result = '<ul>';
    if(!empty($tree)){
        foreach($tree as $node){
            $node_depth = $node->depth;
            $node_name = $node->name;
            $node_id = $node->id;

            if($node_depth == $current_depth){
                if($counter > 0) $result .= '</li>';
            }
            elseif($node_depth > $current_depth){
                if($result != '<ul>'){
                    $result .= '<ul>';
                }
                $current_depth = $current_depth + ($node_depth - $current_depth);
            }
            elseif($node_depth < $current_depth){
                $result .= str_repeat('</li></ul>',$current_depth - $node_depth).'</li>';
                $current_depth = $current_depth - ($current_depth - $node_depth);
            }
            $result .= '<li id="c'.$node_id.'">';
            $update_url = action('ContentsController@anyUpdate', array('id'=>$node_id));
            $destroy_url = action('ContentsController@anyDestroy', array('id'=>$node_id));
            $create_url = action('ContentsController@anyStore');

            $result .= link_to_action('ContentsController@anyEdit',$node_name, array('id'=>$node_id), array('data-update_url'=>$update_url, 'data-destroy_url'=>$destroy_url, 'data-create_url'=>$create_url));
            ++$counter;
        }

        $result .= str_repeat('</li></ul>',@$node_depth).'</li>';

        $result .= '</ul>';

        return $result;
    }
    else return false;
}

// "$current" may contain category_id, lft, rgt for active list item
?>
<div class="page-header row searchTreeContainer">
    <input class='searchTree form-control' placeholder='search' type='text' />    
</div>

<div class='tree'>
    {{renderTree($tree)}}
</div>
<script>
    $('.tree').jstree({
        "core" : {
          // so that create works
          "check_callback" : true
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

    //on change of selected item we want to change the currently editing page.
    $('.tree').on("activate_node.jstree", function (e, data) {
            //we only have 1 item selected - we want to switch to this page.
            //TODO: AJAX THIS.
            window.location = data.node.a_attr.href;
            //
    });

    //on delete item..
    $('.tree').on("delete_node.jstree", function (e, data) {
        if(data.node.children.length > 0){
            //this item has sub items - we need to ask if we can delete:
        }
        console.log(data);
    });

    //on rename of item.. (also fires on rename of new node)
    $('.tree').on("rename_node.jstree", function (e, data) {
        //we need to parse the id properly:
        if(data.node.data == null){
            //alert('new node.'+data.node.a_attr['data-update_url']);
            //we need to get the parent node and get a new url from it.
            
            var parentnode = data.instance.get_node(data.node.parent);
            console.log(parentnode);
            console.log(parentnode.a_attr['data-create_url']);
            
            var parent_id = data.node.parent.substring(1);
            $.post(parentnode.a_attr['data-create_url'], {
                name:data.text,
                parent_id:parent_id
            }, function(data){
                alert('created.');
                //TODO: error trap.
                //for now we'll assume success!
            });
            
        }
        else{
            $.post(data.node.a_attr['data-update_url'], {
                name:data.text
            }, function(data){
                //TODO: error trap.
                //for now we'll assume success!
            });
        }
    });


    //on move of node.
    $('.tree').on("move_node.jstree", function (e, data) {
        //TODO: ajax post to /edit with the moved position details.
        var parent_id = data.parent.substring(1);

        $.post(data.node.a_attr['data-update_url'], {
            parent_id:parent_id
        }, function(data){
            //TODO: error trap.
            //for now we'll assume success!

        });

    });


</script>

