<?php namespace Bootleg\Cms; 

use Content;

class ContentsController extends ContentwrapperController
{
    /**
     * Content Repository
     *
     * @var Content
     */
    public $content;
    public $content_mode = 'contents';

    public function __construct(Content $content)
    {
        parent::__construct($content);
        $this->content = $content;
    }
    
}
