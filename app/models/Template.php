<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Template extends Baum\Node{ //Eloquent {
    protected $fillable = array('name', 'identifier', 'position', 'parent_id', 'set_parent_id', 'user_id', 'deleted_at', 'service_provider', 'view', 'layout', 'content_type_id', 'application_id');
    
    protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');
    
    public $table = 'template';
        
    public $policy, $signature;
    
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];
    
    protected $scoped = array('application_id');
    
    protected $_settings = NULL; //holds settings for this content item so we don't have to contantly query it.    
    
    protected $closure = '\contentClosure';
       
    
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

    
    public function default_fields()
    {
        return $this->belongsTo('Contentdefaultfield', 'content_type_id');
    }

    public function permission()
    {
        return $this->morphMany('Permission', 'controller');
    }
    
    public function childs()
    {
        return $this->hasMany('Content', 'parent_id');
    }
	
	public function content()
    {
        return $this->hasMany('Content', 'template_id');
    }

    public function template_setting()
    {
        return $this->hasMany('Templatesetting', 'template_id', 'template_id');
    }
    
    //keeps content within this application.
    public function scopeFromApplication($query)
    {
        $qu = $query->where('application_id', '=', Application::getApplication()->id);
        return($qu);
    }
    
    
        
    public function setting()
    {
        return $this->hasMany('Templatesetting');
    }
    
//    public function contenttype(){
//    	return $this->belongsTo('Contenttype');
//    }
    
    const DRAFT_STATUS = 0;
    const LIVE_STATUS = 1;

	/*
	 *Mutator that should replace the attributes with the correct language
	 **/

    public static function boot(){
        parent::boot();
		
        
//        App::register($this->service_provider);
        
        
        
    }
    
    public function createSlug(){
        if($this->slug){
            return($slug);
        }
        else{
            $parent = Content::find($this->tmp_parent_id);
            
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
            $contentTree = Content::fromApplication()->language()->where('parent_id', '=', $parent_id)->immediateDescendants();
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
    

        /*recursivly create sub pages.*/
    public function superSave($input){

        $input['application_id'] = Application::getApplication()->id;
        $parent = Template::find($input['parent_id']);

        unset($input['parent_id']);
        //SAVE CONTENT ITEM
        $saved = $parent->children()->create($input);  
        return($saved);
    }
    
}