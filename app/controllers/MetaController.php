<?php

class TreeController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($parent_id = null, $recurse = false){
        if($parent_id){
            $contentTree = Content::fromApplication()->where('parent_id', '=', $parent_id)->immediateDescendants();
        }
        else{
            $contentTree = Content::fromApplication()->immediateDescendants();
        }
        dd($contentTree);
    }

}