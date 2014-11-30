<?php
class Node
{
    var $name;
    var $category;
    var $depth;
    var $lft;
    var $rgt;
    var $selected;
    var $nodes = array();

    public function __construct( $name, $category, $depth, $lft, $rgt, $selected = false )
    {
        $this->name = $name;
        $this->category = $category;
        $this->depth = $depth;
        $this->lft = $lft;
        $this->rgt = $rgt;
        $this->selected = $selected;
    }

    public function addNode( Node $node )
    {
        array_push( $this->nodes, $node );
    }

    public function render()
    {
        $renderedNodes = '';
        if ( $this->isSelected() ) {
            $renderedNodes = $this->renderNodes();
        }
        return sprintf( '<li id="c%s"><a href="">%s</a>%s</li>', $this->category, $this->name, $renderedNodes );
    }

    protected function renderNodes()
    {
        $renderedNodes = '';
        foreach ( $this->nodes as $node )
        {
            $renderedNodes .= $node->render();
        }
        return sprintf( '<ul>%s</ul>', $renderedNodes );
    }

    /** Return TRUE if this node or any subnode is selected */
    protected function isSelected()
    {
        return ( $this->selected || $this->hasSelectedNode() );
    }

    /** Return TRUE if a subnode is selected */
    protected function hasSelectedNode()
    {
        foreach ( $this->nodes as $node )
        {
            if ( $node->isSelected() )
            {
                return TRUE;
            }
        }
        return FALSE;
    }
}

class RootNode extends Node
{
    public function __construct() {}

    public function render()
    {
        return $this->renderNodes();
    }
}

function MyRenderTree( $tree, $current )
{
    /** Convert the $tree array to a real tree structure based on the Node class */
    $nodeStack = array();
    $rootNode = new RootNode();
    $nodeStack[-1] = $rootNode;

    foreach ( $tree as $category => $rawNode )
    {
        $node = new Node( $rawNode['name'], $category, $rawNode['depth'], $rawNode['lft'], $rawNode['rgt'], $rawNode['lft'] == $current['lft'] );
        $nodeStack[($node->depth -1)]->addNode( $node );
        $nodeStack[$node->depth] = $node;
        end( $nodeStack );
    }

    /** Render the tree and return the output */
    return $rootNode->render();
}