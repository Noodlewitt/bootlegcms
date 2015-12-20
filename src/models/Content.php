<?php 
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends \Baum\Node{ //Eloquent {status
    protected $fillable = array('name', 'identifier', 'position', 'package', 'parent_id', 'set_parent_id', 'user_id', 'deleted_at', 'template_id', 'view', 'application_id', 'status', 'slug','edit_action');
    
    protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');
    
    public $table = 'content';
        
    public $policy, $signature;
    
    //use SoftDeletingTrait;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $orderColumn = 'position'; //Baum sorting and ordering modifier

    protected $scoped = array('application_id');
    
    protected $_settings = NULL; //holds settings for this content item so we don't have to contantly query it.    
    
    protected $closure = '\contentClosure';
    

    //some refaults for thw whole app should normal database stuff fail.
    
    const PACKAGE = 'cms';
    const VIEW = 'default.view';
    const EDIT_VIEW = 'contents.edit';
    const EDIT_ACTION = 'ContentsController@anyEdit';
    const DRAFT_STATUS = 0;
    const LIVE_STATUS = 1;

    public $rules = array(
		//'content' => 'required',
		//'parent_id' => 'required'
    );

    public function author(){
        return $this->belongsTo('User');
    }
    
    public function application()
    {
        return($this->belongsTo('Application'));
    }

    public function content_parent()
    {
        return $this->belongsTo('Content');
    }
	
	public function template()
	{
        if(!$this->template_id){
            $this->template_id = 1;
        }
		return $this->belongsTo('Template', 'template_id');
	}

    public function template_setting()
    {
        return $this->hasMany('Templatesetting', 'template_id', 'template_id');
    }

    
    public function default_fields()
    {
        return $this->belongsTo('Templatesetting', 'template_id');
    }

    public function scopeLang($q){
        $res =  $q->get();
        if(@$res->setting){
            foreach($res->setting as $settingKey=>$setting){
                foreach($setting->language as $settingLang=>$lang){
                    dd($lang->value);
                }
            }       
        }
        
        return $res;
    }

    public function permission()
    {
        return $this->morphMany('Permission', 'controller');
    }

    public function languages($code = NULL){
        $langs = $this->hasMany('ContentLanguage', 'content_id');
        if($code){
            $langs->where('code',$code);
        }
        return($langs);
    }
    
    public function childs()
    {
        return $this->hasMany('Content', 'parent_id');
    }
    
    //keeps content within this application.
    public function scopeFromApplication($query)
    {
        $qu = $query->where('application_id', '=', Application::getApplication()->id);
        return($qu);
    }
    
    //keeps content within this application.
    public function scopeLive($query)
    {
        $qu = $query->where('status', '=', Content::LIVE_STATUS);
        return($qu);
    }
    
    public function setOrderColumn($order){
        $this->orderColumn = $order;
    }

    public function scopeSortBySetting($query, $setting_name, $direction = 'DESC'){
        $settings_table = $this->setting()->getModel()->getTable();
        $subquery = "(select $settings_table.content_id, $settings_table.value from $settings_table where name='$setting_name' ORDER BY value $direction) AS j";
        $this->orderColumn = $settings_table.'.value';
        return $query->join(\DB::raw($subquery), $this->getTable().'.id', '=', 'j.content_id');
    }
    
    /**
     * Searches by settings
     * @param  [type] $query     $query object (handled by laravel)
     * @param  [type] $parent_id id of parent to search in
     * @param  [type] $search    search string
     * @return [type]            $query object.
     */
    public function scopeSearchBySetting($query, $parent_id, $search){
       /* $settings_table $subquery = "(select content_id from $settings_table where value LIKE '$search' ) AS s";= $this->setting()->getModel()->getTable();
        

        $query->whereIn('id', $subquery)
        

        $this->orderColumn = $settings_table.'.value';
        return $query->join(\DB::raw($subquery), $this->getTable().'.id', '=', 'j.content_id');
        */
    }
        
    public function setting()
    {
        return $this->hasMany('Contentsetting');
    }

    public function settinglanguage()
    {
        return $this->hasMany('ContentsettingLanguage');
    }
    
//    public function contenttype(){
//    	return $this->belongsTo('Contenttype');
//    }
    


	/*
	 *Mutator that should replace the attributes with the correct language
	 **/

    public static function boot(){
        parent::boot();
		
        
//        App::register($this->service_provider);
        
        
        //we need to fill in all the defaults for this item..

		
		Content::created(function($content){
			//we need check for sub pages and create them!
		});
        
    }
    
    /*recursivly create sub pages.*/
    public function superSave($input){

        $input = Content::loadDefaultValues($input);
        $parent = Content::with('children')->find($input['parent_id']);
        $template = Template::find($input['template_id']);
        
        $input['position'] = count($parent->children); //we always want to create this one at the end.

        unset($input['parent_id']);
        //SAVE CONTENT ITEM
        $saved = $parent->children()->create($input); 

        if($template){
            $templateChildren = $template->getImmediateDescendants();
            foreach($templateChildren as $templateChild){
                if($templateChild->auto_create){
                    //dd($templateChild->id);
                    //we need to run a create on this..
                    $inp['template_id'] = $templateChild->id;
                    $inp['parent_id'] = $saved->id;
                    $this->superSave($inp);    
                }
            }
        }
          
        return($saved);
    }
    
    //Loads default values into the model based off the tree stuff..
    public static function loadDefaultValues($input = ''){
		
        $parent = Content::find($input['parent_id']);
        
        if(!isset($input['template_id']) || !$input['template_id']){
            $parentTemplate = Template::find($parent->template_id);

            
            if(@$parentTemplate->id){
                //since we occasionally want to process a looped back tree (which makes the whole tree 
                //invalid, we can't use baum's built in functions to get the first child.
                if($parentTemplate->loopback){
                    $parentTemplateChild = Template::find($parentTemplate->loopback);
                }
                else{
                    $parentTemplateChild = @$parentTemplate->getImmediateDescendants()->first();  
                }                

                $input['template_id'] = @$parentTemplateChild->id;    
            }
            if(!@$input['template_id']){
                //if it's still nothing we can safely set this to 0;
                $input['template_id'] = null;
            }
        }
        $template = Template::find($input['template_id']);
		
		//dd($template->name);
        //$contentDefaultFields = Contentdefaultfield::where('content_type_id', '=', $this->content_type_id)->get();
        
        //plug in the fields we wanted..
        if(!@$input['template_id'])$input['template_id'] = @$template->id;
        if(!@$input['name'])$input['name'] = @$template->name;
        if(!@$input['view'])$input['view'] = @$template->view;
        if(!@$input['identifier'])$input['identifier'] = @$template->identifier;

        if(!@$input['package'])$input['package'] = @$template->package;

        if(!@$input['edit_view'])$input['edit_view'] = @$template->edit_view;
        if(!@$input['edit_action'])$input['edit_action'] = @$template->edit_action;

        if(!@$input['hide_slug'])$input['hide_slug'] = @$template->hide_slug;
        if(!@$input['hide_name'])$input['hide_name'] = @$template->hide_name;
        if(!@$input['hide_published'])$input['hide_published'] = @$template->hide_published;
        if(!@$input['hide_id'])$input['hide_id'] = @$template->hide_id;
        if(!@$input['protect'])$input['protect'] = @$template->protect;

        //work out the slug if not manually set
        if(!@$input['slug']){
            $input['slug'] = Content::createSlug($input, $parent);
        }
		
		
        //and the user_id (author)
        $input['user_id'] = Auth::user()->id;
        
        //and the application:
        if(!@$input['application_id']){
            $application = Application::getApplication();
            $input['application_id'] = $application->id;
        }
		
        
		
        
        //and the package if not set
        if(!@$input['package']){
            //set it as parent one..
            $input['package'] = @$parent->package;
            
            //still nothing - set from application
            $application = Application::getApplication();
            if($application->package){
                $input['package'] = $application->package;
            }
            
            //still nothing - we have to set it to default.
            if(!$input['package']){
                //last ditch attempt to put something sensible in here
                $input['package'] = Content::PACKAGE;
            }
        }


        if(!@$input['edit_view']){
            //set it as parent one..
            $input['edit_view'] = @$parent->edit_view;
            
            //still nothing - we have to set it to default.
            if(!$input['edit_view']){
                //last ditch attempt to put something sensible in here
                $input['edit_view'] = Content::EDIT_VIEW;
            }
        }

        if(!@$input['view']){
            $input['view'] = Content::VIEW;
        }
		return($input);
    }
    
    
    public static function createSlug( $input, $parent, $ignoreDuplicates = false ){

        if(@$input['name']){
            $pageSlug = $input['name'];
        }
        else{
            $pageSlug = uniqid();    
        }

        $pageSlug = str_replace(" ", "-", $pageSlug);    //spaces
        $pageSlug = urlencode($pageSlug);  //last ditch attempt to sanitise

        $wholeSlug = rtrim(@$parent->slug,"/")."/$pageSlug";
        if($ignoreDuplicates){
            return($wholeSlug);
        }
        //does it already exist?
        if(Content::where("slug","=",$wholeSlug)->first()){
            //it already exists.. find the highest numbered example and increment 1.
            $highest = Content::where('slug', 'like', "$wholeSlug-%")->orderBy('slug', 'desc')->first();
            $num = 1;
            if($highest){
                $num = str_replace("$wholeSlug-", "", $highest->slug);
                $num++;
            }
            return(strtolower("$wholeSlug-$num"));
        }
        else{
            return(strtolower($wholeSlug));
        }
        
    }

    
    /*
     * returns a single setting given the name;
     */
    public function getSetting($getSetting){
        $settings = $this->setting->filter(function($model) use(&$getSetting){
            return $model->name === $getSetting;
        });

        if($settings->count() == 0){
            return null;
        }
        if($settings->count() > 1){

            $return = array();
            foreach($settings as $setting){
                if($setting->languages->count()){
                    $return[] = $setting->languages->first()->value;
                }
                else{
                    $return[] = $setting->value;   
                }
            }
        }
        else{
            if(isset($settings->first()->languages->first()->value)){

                $return = $settings->first()->languages->first()->value;
            }
            else{
                $return = $settings->first()->value;
            }
        }
        return($return);
    }
    

    //sets default attributes into blank fields
    //TODO: these should be in constants up above somewhere.
    public static function setDefaults($content){
        if(!$content->edit_view){
            $content->edit_view = 'contents.edit';
        }
        return($content);
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
        $newContent->package = $themeContent->package;
        $newContent->view = $themeContent->view;
        $newContent->position = $themeContent->position;
        $newContent->edit_view = $themeContent->edit_view;
        $newContent->edit_action = $themeContent->edit_action;
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


    public function getHideInTreeAttribute($value){
        if($value){
            if(config('bootlegcms.cms_debug') == true){
                return(false);
               //if we are in debug mode - alwys return false - since we want to see what' happening.
            }
            else{
                return($value);    
            }    
        }
        
        return($value);
    }

    public function getNameAttribute($name){
        //dd(Application::getApplication()->languages);
        //$this->language()->first();
        //dd($this->languages(\App::getLocale())->first()->id);

        if(!isset($this->orig_name)){

            if(config('bootlegcms.cms_languages')){

                $this->language = $this->languages(\App::getLocale())->first();
            }
        }
        $this->orig_name = $name;
        return @$this->language->name?$this->language->name:$name;
    }

    public function getSlugAttribute($slug){
        //dd(Application::getApplication()->languages);

        if(!isset($this->orig_slug)){
            if(config('bootlegcms.cms_languages')){
                $this->language = $this->languages(\App::getLocale())->first();
            }
        }
        $this->orig_slug = $slug;
        return @$this->language->slug?$this->language->slug:$slug;
    }
}
