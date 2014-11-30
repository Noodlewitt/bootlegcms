<?php

class Content extends Baum\Node{ //Eloquent {
    protected $fillable = array('name', 'identifier', 'position', 'parent_id', 'set_parent_id', 'user_id', 'deleted_at', 'service_provider', 'view', 'layout', 'content_type_id', 'application_id');
    
    protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');
    
    protected $table = 'content';
        
    public $policy, $signature;
    
    protected $softDelete = true;
    
    protected $scoped = array('application_id');
    
    protected $_settings = NULL; //holds settings for this content item so we don't have to contantly query it.    
    
    protected $closure = '\contentClosure';
    
    //tmp for baum.
    protected $parentColumn = 'parent_id';
    
    
    public static $rules = array(
		//'content' => 'required',
		//'parent_id' => 'required'
    );

    public function author(){
    	return $this->belongsTo('User');
    }
    
    public function application(){
        return($this->belongsTo('Application'));
    }

    public function content_parent(){
    	return $this->belongsTo('Content');
    }

    public function default_page(){
        return $this->belongsTo('Contentdefaultpage', 'content_type_id');
    }
    
    public function default_fields(){
        return $this->belongsTo('Contentdefaultfield', 'content_type_id');
    }
        
    public function permission(){
        return $this->morphMany('Permission', 'controller');
    }
    
    public function childs(){
    	return $this->hasMany('Content', 'parent_id');
    }
    
    //keeps content within this application.
    public function scopeFromApplication($query){
        $qu = $query->where('application_id', '=', Application::getApplication()->id);
        return($qu);
    }
    
    //keeps content within this application.
    public function scopeLive($query){
        $qu = $query->where('status', '=', Content::LIVE_STATUS);
        return($qu);
    }
        
    public function setting()
    {
        return $this->hasMany('Contentsetting');
    }
    
//    public function contenttype(){
//    	return $this->belongsTo('Contenttype');
//    }
    
    const DRAFT_STATUS = 0;
    const LIVE_STATUS = 1;


    
    public static function boot(){
        parent::boot();
        
//        App::register($this->service_provider);
        
        
        //we need to fill in all the defaults for this item..
        Content::creating(function($content){    
            $content->loadDefaultValues();
        });
        
    }
    
    //Creates default pages recursivly.
    public function createDefaultPages(){
            
        
        $contentDefaults = Contentdefaultpage::where('parent_id', '=', $this->content_type_id)->get();
        
        foreach($contentDefaults as $contentDefault){
            if(is_null($contentDefault->quantity)){
                $contentDefault->quantity = 1;
            }
            for($i=0; $i<$contentDefault->quantity; ++$i) { //TODO: fix this.
               //nothing here yet.. 
            }
        }
    }
    
    //Loads default values into the model based off the tree stuff..
    public function loadDefaultValues(){
        
        if(!$this->content_type_id){
            //we need to grab the parent_id's link:
            if($this->parent_id){
                //we try and grab the content_type from the parent's tree..
                $parent = Content::find($this->parent_id);
                $parent_default_page_type = @Contentdefaultpage::where('parent_id','=',$parent->content_type_id)->first()->id;
                if(!$parent_default_page_type){
                    $parent_default_page_type = 0;
                }
                $this->content_type_id = $parent_default_page_type;
            }
            else{
                //otherwise we need to guess and set it to the default blank page:
                $this->content_type_id = 0;
            }
        }
        
        //TODO: replace with something like this: dd($this->default_fields()->first()->id);
        $contentDefaultFields = Contentdefaultfield::where('content_type_id', '=', $this->content_type_id)->get();
        
        //plug in the fields we wanted..
        foreach($contentDefaultFields as $contentDefaultField){
            
            if(!$this->name)$this->name = $contentDefaultField->name;
            if(!$this->view)$this->view = $contentDefaultField->view;
            if(!$this->layout)$this->layout = $contentDefaultField->layout;
            if(!$this->identifier)$this->identifier = $contentDefaultField->identifier;
            
            if(!$this->package)$this->package = $contentDefaultField->package;
            if(!$this->service_provider)$this->service_provider = $contentDefaultField->service_provider;
        }
        //work out the slug if not manually set
        if(!$this->slug){
            $this->slug = $this->createSlug();
        }
        
        //and the user_id (author)
        $this->user_id = Auth::user()->id;
        
        //and the application:
        if(!$this->application_id){
            $application = Application::getApplication();
            $this->application_id = $application->id;
        }
        
        //and the service_provider if not set
        if(!$this->service_provider){
            //set it as parent one..
            $this->service_provider = @$parent->service_provider;
            
            //still nothing - set from application

            $application = Application::getApplication();
            if($application->service_provider){
                $this->service_provider = $application->service_provider;
            }
            
            //still nothing - we have to set it to default.
            if(!$this->service_provider){
                //last ditch attempt to put something sensible in here
                $this->service_provider = 'Bootleg\Cms\CmsServiceProvider';
            }
        }
        
        //and the package if not set
        if(!$this->package){
            //set it as parent one..
            $this->package = @$parent->package;
            
            //still nothing - set from application
            $application = Application::getApplication();
            if($application->package){
                $this->package = $application->package;
            }
            
            //still nothing - we have to set it to default.
            if(!$this->package){
                //last ditch attempt to put something sensible in here
                $this->package = 'cms';
            }
        }
        
        //and the view/layout if they're not set can safely be set to default.
        if(!$this->layout){
            $this->view = 'default.layout';
        }
        if(!$this->view){
            $this->view = 'default.view';
        }        
    }
    
    
    //saves default settings for page based off content_type_id
    /*public function setDefaultSettings(){
        $contentDefaultSettings = Contentdefaultsetting::where('content_type_id', '=', $this->content_type_id)->get();
        
        $data = array();
        foreach($contentDefaultSettings as $contentDefaultSetting){
            
            $data[] = array(
                'name'=>$contentDefaultSetting->name, 
                'value'=>$contentDefaultSetting->value,
                'field_type'=>$contentDefaultSetting->field_type,
                'field_parameters'=>$contentDefaultSetting->field_parameters,
                'content_id'=>$this->id
            );
        }
        if(!empty($data)){
            Contentsetting::insert($data);
        }
    }*/
    
    
    public function createSlug(){
        if($this->slug){
            return($slug);
        }
        else{
            $parent = Content::find($this->parent_id);
            
            if($this->title){
                $pageSlug = $this->title;    
            }
            else if($this->name){
                $pageSlug = $this->name;    
            }
            else{
                $pageSlug = uniqid();    
            }
            
            $pageSlug = str_replace(" ", "-", $pageSlug);    //spaces
            $pageSlug = urlencode($pageSlug);  //last ditch attempt to sanitise
            
            $wholeSlug = rtrim(@$parent->slug,"/")."/$pageSlug";
            //does it already exist?
            if(Content::where("slug","=",$wholeSlug)->first()){
                //it already exists.. find the highest numbered example and increment 1.
                $highest = Content::where('slug', 'like', "$wholeSlug-%")->orderBy('slug', 'desc')->first();
                $num = 1;
                if($highest){
                    $num = str_replace("$wholeSlug-", "", $highest->slug);
                    $num++;
                }
                return("$wholeSlug-$num");
            }
            else{
                return($wholeSlug);
            }
        }
    }
    
    
    
    /*TODO: figure out this better.*/
    public function getTree($parent_id = null, $recurse = false){
        //TODO: look at this.
        if($parent_id){
            $contentTree = Content::fromApplication()->where('parent_id', '=', $parent_id)->immediateDescendants();
        }
        else{
            $contentTree = $this->immediateDescendants();
        }
        
        $obj = new stdClass;
        $data = array();
        foreach($contentTree as $content){
            $data[] = '{
                "text": "'.$content->name.'",
                "state": {
                  "opened": false,
                  "selected": false
                },
                "li_attr":{},
                "a_attr":{}
            },';
        }
        $data[count($data)] = rtrim($data[count($data)], ',');
    }
    
    /*
     * returns a single setting given the name;
     */
    public function getSetting($getSetting){
        return($this->setting->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
            
        })->first()->value);
    }
    
    
    public static function getMainRoot(){
        return(Content::fromApplication()->whereNull('parent_id')->first());
    }
    
    /*duplicating $this app into $newApp*/
    public static function doop($recursive, $themeContent, $parent_id, $newAppId){  
        
        //we neeed to dupe all this crap..
        $newContent = new Content();
        $newContent->name = $themeContent->name;
        $newContent->slug = $themeContent->slug;
        $newContent->identifier = $themeContent->identifier;
        $newContent->service_provider = $themeContent->service_provider;
        $newContent->package = $themeContent->package;
        $newContent->view = $themeContent->view;
        $newContent->layout = $themeContent->layout;
        $newContent->content_type_id = $themeContent->content_type_id;
        $newContent->position = $themeContent->position;
        $newContent->edit_view = $themeContent->edit_view;
        $newContent->edit_service_provider = $themeContent->edit_service_provider;
        $newContent->edit_package = $themeContent->edit_package;
        $newContent->status = $themeContent->status;
        
        
        //$newContent->children = @$themeContent->children;
        if($themeContent->parent_id){
            $newContent->parent_id = $parent_id;
        }
        
        $newContent->application_id = $newAppId;
        echo('duplicated ' . $newContent->name."|".$newContent->application_id. "<br />");
        
        if($saved = $newContent->save()){
            if(@$themeContent->children){
                foreach($themeContent->children as $oldContent){
                    //dd($newContent->id);
                    Content::doop(true, $oldContent, $newContent->id, $newAppId);               
                    //exit();
                }
            }
        }
    }
}