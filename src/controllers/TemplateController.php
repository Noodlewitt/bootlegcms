<?php namespace Bootleg\Cms; 

class TemplateController extends ContentwrapperController
{
    /**
     * Content Repository
     *
     * @var Content
     */
    public $content;
    public $content_mode = 'template';

    public function __construct(Template $content)
    {
        parent::__construct($content);
        $this->content = $content;
    }
}