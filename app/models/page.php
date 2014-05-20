<?php 

class page extends \Franzose\ClosureTable\Models\Entity implements pageInterface {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * ClosureTable model instance.
     *
     * @var pageClosure
     */
    protected $closure = '\pageClosure';
}