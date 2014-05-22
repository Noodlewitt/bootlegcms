<?php

class ContentsController extends CMSController {

    /**
     * Content Repository
     *
     * @var Content
     */
    protected $content;

    public function __construct(Content $content){
        parent::__construct();
        $this->content = $content;
    }
    
    public $policy, $signature;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    /*public function index()
    {
        $contents = $this->content->all();

        return View::make('contents.index', compact('contents'));
    }*/
    
    /**
     * main content view.
     *
     * @return Response
     */
    public function anyIndex(){
        
        $content = Content::with(array('setting.default_setting', 'default_page'))->fromApplication()->whereNull('parent_id')->first();


        $content_defaults = Contentdefaultsetting::where('content_type_id','=',@$content->default_page->id)->get();
        $all_settings = $content_defaults;
        
        //$all_settings = $content_defaults->merge($content->setting);
        
        foreach($content_defaults as $key=>$cd){
            
            $fl = $content->setting->filter(function($d) use($cd){
                return($cd->name===$d->name);
            }); 
            
            //$fl would be items that should replace.
            if($fl){
                foreach($fl as $f){
                    //use content->settings value (fl)
                    $all_settings->push($f);
                    $all_settings->forget($key);
                }
            }
        }
        
        //we now need to add the current settings if they don't exisit in the defaults.
        //if(@$content->setting){
            foreach($content->setting as $setting){
                $fl = $all_settings->filter(function($d) use($setting){
                    if($d->name===$setting->name){
                        if($d->id === $setting->id){
                            return(true);
                        }
                    }
                    return(false);
                }); 
                if($fl->isEmpty()){
                    $all_settings->push($setting);
                }
            }
        //}
        $settings = $all_settings->groupBy('section');
        
        if (Request::ajax()){
            $cont = View::make( 'cms::contents.edit', compact('content', 'settings') );
            return($cont);
        }
        else{
            $tree = $content->getDescendants();
            $cont = View::make( 'cms::contents.edit', compact('content', 'content_defaults', 'settings') );
            $cont = View::make( 'cms::layouts.tree', compact('cont', 'tree'));
            $layout = View::make( 'cms::layouts.master', compact('cont'));
        }
        return($layout);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function anyCreate($parent_id=null){
        $content = new Content;
        $content->parent_id = $parent_id;
        
        
        if($content){
            $tree = $content->getDescendantsAndSelf();
        }
        
        $content_settings = $this->content->setting()->get();
        
        return View::make($this->application->cms_package.'::contents.create', compact('content', 'content_settings', 'tree'));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function anyStore($json = false){
        $input = Input::all();
        $validation = Validator::make($input, Content::$rules);
        if($input['parent_id'] == '#'){
            //we ar not allowed to create a new root node like this.. so set it to the current root.
            //unset($input['parent_id']); //test
            $input['parent_id'] = Content::fromApplication()->whereNull('parent_id')->first()->id;
        }
        if ($validation->passes()){
            $application = Application::getApplication();
            $input['application_id'] = $application->id;
            $parent = Content::find($input['parent_id']);
            //dd($parent->id);
            unset($input['parent_id']);
            
            $saved = $parent->children()->create($input);
            
            if($json){
                return($this->renderTree(array($saved)));
            }
            return Redirect::action('ContentsController@anyIndex');
        }
        
        return Redirect::action('ContentsController@anyCreate')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }


    public function anyFixtree(){
        Content::rebuild();
        
        echo(Content::isValid());
        exit();
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyEdit($id = false){
      
        $content = Content::with(array('setting.default_setting', 'default_page'))->findOrFail($id);
        
        //foreach content_default_field on this content item, we want to 
        //add a setting if it exists on the content item (replacing it if necisary)        
        if(@$content->default_page->id){
            $content_defaults = Contentdefaultsetting::where('content_type_id','=',$content->default_page->id)->get();
            $all_settings = $content_defaults;

            foreach($content_defaults as $key=>$cd){

                $fl = $content->setting->filter(function($d) use($cd){
                    return($cd->name===$d->name);
                });  
                //$fl would be items that should replace.
                if($fl){
                    foreach($fl as $f){
                        //use content->settings value (fl)
                        $all_settings->push($f);
                        $all_settings->forget($key);
                    }
                }
            }
        }
        
        //we now need to add the current settings if they don't exisit in the defaults.
        if(@$all_settings){
            foreach($content->setting as $setting){

                $fl = $all_settings->filter(function($d) use($setting){
                    if($d->name===$setting->name){
                        if($d->id === $setting->id){
                            return(true);
                        }
                    }
                    return(false);
                }); 
                if($fl->isEmpty()){
                    $all_settings->push($setting);
                }
            }
        }
        else{
            //if there's no defaults set for this we can just use what's on the content item already.
            $all_settings = $content->setting;
        }
        $settings = $all_settings->groupBy('section');
        
        App::register($content->edit_service_provider);
        
        if (Request::ajax()){
            $cont = View::make( 'cms::contents.edit', compact('content', 'settings') );
            return($cont);
        }
        else{
            $tree = $content->getDescendants();
            $cont = View::make( 'cms::contents.edit', compact('content', 'content_defaults', 'settings') );
            $cont = View::make( 'cms::layouts.tree', compact('cont', 'tree'));
            $layout = View::make( 'cms::layouts.master', compact('cont'));
        }
        return($layout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyUpdate($id=false){
        if(!@$id){
            $input = array_except(Input::all(), '_method');
            $id = $input['id'];
        }
        if($id !== false){
            $input = array_except(Input::all(), '_method');
            
            $validation = Validator::make($input, Content::$rules);
            if ($validation->passes()){
                //we need to update the settings too:
                $content = $this->content->find($id);
                
                if(@$input['parent_id'] == '#'){
                    $input['parent_id'] = Content::getMainRoot();
                }
                $content->update($input);
                
                //TODO: take another look at a better way of doing this vv ..also VALIDATION!
                //add any settings:
                if(@$input['setting']){
                    foreach($input['setting'] as $name=>$settingGroup){
                        foreach($settingGroup as $type=>$setGrp){
                            foreach($setGrp as $key=>$setting){
                                //we want to delete this setting.
                                if(is_array($setting) && array_key_exists('deleted',$setting)){
                                    $contentSetting = Contentsetting::destroy($key);
                                }
                                else{
                                    if($type != 'Contentdefaultsetting'){
                                        $contentSetting = Contentsetting::withTrashed()
                                            ->where('name','=',$name)
                                            ->where('content_id','=',$content->id)
                                            ->where('id','=',$key)->first();
                                    }
                                    //if it's not found (even in trashed) then we need to make a new field.
                                    //if it's contentdefault, we need to create it too since it doesn't exist!
                                    if($type == 'Contentdefaultsetting' || is_null($contentSetting)){
                                        //if we can't find the field, we need to create it from the default:
                                        $defaultContentSetting = Contentdefaultsetting::findOrFail($key);
                                        $contentSetting = new Contentsetting();
                                        $contentSetting->name = $defaultContentSetting->name;
                                        $contentSetting->value = $setting;
                                        $contentSetting->content_id = $content->id;
                                        $contentSetting->field_type = $defaultContentSetting->field_type;
                                    }
                                    else{
                                        //otherwise this field exists.. we can overwrite it' settings.
                                        $contentSetting->name = $name;
                                        $contentSetting->value = $setting;
                                        $contentSetting->content_id = $content->id;
                                        $contentSetting->field_type = @$contentSetting->field_type?$contentSetting->field_type:'text';
                                    }

                                    $contentSetting->save();
                                    $contentSetting->restore();     //TODO: do we always want to restore the deleted field here?
                                }
                            }
                        }
                    }
                }
                return Redirect::action('ContentsController@anyEdit', $id)
                    ->with('success', 'Success, saved correctly');
            }
        }
        else{
            //TODO:
            $validation = 'no id';
        }
        return Redirect::action('ContentsController@anyEdit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('danger', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyDestroy($id = null)
    {
        if(!$id){
            $id = Input::all();
            if(@$id['id'] == '#'){
                $id = '';
            }
            else{
                $id = @$id['id'];
            }
        }
        $this->content->find($id)->delete();

        return Redirect::action('ContentsController@anyIndex');
    }
    
    //requests imediate descendents for given node
    //TODO: recursive.
    public function anyTree(){
        
        $id = Input::all();
        if(@$id['id'] == '#'){
            $id = '';
        }
        else{
            $id = @$id['id'];
        }
        
        if(!$id){
            $id = Content::fromApplication()->whereNull('parent_id')->first()->id;
        }
        
        $tree = Content::where('parent_id', '=', $id)
                ->orderBy('position')
                ->orderBy('name')->get();
        
        return($this->renderTree($tree));   
    }
    
    public function renderTree($tree){
        $json_out = array();
        
        foreach($tree as $treeItem){
            $branch = new stdClass();
            $branch->id = $treeItem->id;
            $branch->text = $treeItem->name;
            $branch->children = ($treeItem->rgt - $treeItem->lft > 1);
            $json_out[] = $branch;
        }
        if(count($json_out) == 1){
            return Response::json($json_out[0]);
        }
        return Response::json($json_out);
    }
    
    
    /*delete uploaded file(s)*/
    public function deleteUpload($id){
        $content_setting = Contentsetting::findOrFail($id);
        //$content_setting->delete(); //we don't actually want to delete here since we wait for the update button to do it's job.
        $delete = new stdClass();
        $fileName = pathinfo($content_setting->value, PATHINFO_FILENAME);
        
        $delete->{$fileName} = true;
        $return->files[] = $delete;
        
        echo(json_encode($return));
        exit();
    }
    
    
    /*
     * pass in a content_setting id to upload to.
     */
    public function postUpload($id,  $type = "Contentsetting"){
        $input = array_except(Input::all(), '_method');
        
        $application = Application::getApplication();
        $uploadFolder = @$application->getSetting('Upload Folder');
        if($type == 'Applicationsetting'){
            $content_setting = Applicationsetting::withTrashed()->find($id);
        }
        else{           
            $content_setting = Contentsetting::withTrashed()->find($id);
            if(is_null($content_setting) || $input['type'] == 'Contentdefaultsetting'){
                //we need to get the default settings instead:
                $default_content_setting = Contentdefaultsetting::find($id);
                $content_setting = new Contentsetting();
                $content_setting->name = $default_content_setting->name;
                $content_setting->field_type = $default_content_setting->field_type;
                $content_setting->field_parameters = $default_content_setting->field_parameters;
            }
        }
        $niceName = preg_replace('/\s+/', '', $content_setting->name);
        
        $files = (Input::file($niceName));

        if(!empty($files)){
            
            foreach($files as $file) {
                $rules = array(
                    //TODO.
                    'file' => 'required|mimes:png,gif,jpeg,txt,pdf,doc,rtf|max:20000'
                );
                $validator = \Validator::make(array('file'=> $file), $rules);

                if($validator->passes()){

                    $fileId             = uniqid();
                    $extension          = $file->getClientOriginalExtension();
                    $fileName           = trim("$uploadFolder/$fileId.$extension", '/\ ');
                    $destinationPath    = base_path()."/uploads/";
                    $originalName       = $file->getClientOriginalName();
                    $mime_type          = $file->getMimeType();
                    $size               = $file->getSize();
                    $upload_success     = $file->move($destinationPath.$uploadFolder, "$fileId.$extension");
                    
                    $finalUrl = "//".$_SERVER['SERVER_NAME']."/uploads/$fileName";
                    
                    //if s3 is enabled, we can upload to s3!
                    //TODO: should this be shifted to some sort of plugin?
                    if(@$application->getSetting('Enable s3')){

                        //$uploadFolder
                        //file and folder need to be concated and checked.
                        if(@$application->getSetting('s3 Folder')){
                            $pth = trim(@$application->getSetting('s3 Folder'),'/\ ').'/'.$fileName;
                        }
                        else{
                            $pth = $fileName;
                        }
                        
                        $s3 = AWS::get('s3');
                        $s3->putObject(array(
                            'Bucket'     => @$application->getSetting('s3 Bucket'),
                            'Key'        => $pth,
                            'SourceFile' => $destinationPath.$fileName,
                            'ACL'=>'public-read' //todo: check this would be standard - would we ever need to have something else in here?
                        ));
                        if(@$application->getSetting('s3 Cloudfront Url')){
                            $cloudUrl = trim($application->getSetting('s3 Cloudfront Url'), " /");
                            $finalUrl = "//$cloudUrl/$pth";
                        }
                        else{
                            $finalUrl = "//".@$application->getSetting('s3 Bucket')."/$pth";
                        }
                        
                        
                        //todo: remove old file in /uploads?
                    }
                    
                    //and we need to build the json response.
                    $fileObj = new stdClass();
                    $fileObj->name = $originalName;
                    $fileObj->thumbnailUrl = $finalUrl; //todo
                    $fileObj->deleteUrl = "//".$_SERVER['SERVER_NAME']."/uploads/$fileName"; //todo
                    $fileObj->deleteType = "DELETE";
                    
                    $return->files[] = $fileObj;
                }
                else{
                    //TODO:validation failure messages.
                    echo('val fail');
                    exit();
                }
            }
            
            echo(json_encode($return));
            exit();
            
        }
    }
}