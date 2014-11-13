<?php

class ContentwrapperController extends CMSController
{
    /**
     * Content Repository
     *
     * @var Content
     */
    public $content;
    public $content_mode = '';  //this is used to check if we are on template or content mode since the models we need are slightly different.

    public function __construct($content)
    {
        parent::__construct();
        View::share('content_mode', $this->content_mode);
        $this->content = $content;
    }
    
    public $policy, $signature;

    
    
    /**
     * main content view.
     *
     * @return Response
     */
    public function anyIndex(){
        
        $this->content = $this->content->with(array('setting.default_setting', 'default_page'))->fromApplication()->whereNull('parent_id')->first();
        
        $permission = Permission::getPermission('content', $this->content->id, 'w');
        $allPermissions = Permission::getControllerPermission($this->content->id, 'content');
       // dd($perm);
        
        //foreach content_default_field on this content item, we want to 
        //add a setting if it exists on the content item (replacing it if necisary)        
        if(@$content->default_page->id){
            $content_defaults = Contentdefaultsetting::where('content_type_id','=',$this->content->default_page->id)->get();
            $all_settings = $content_defaults;

            foreach($content_defaults as $key=>$cd){

                $fl = $content->setting->filter(function($d) use($cd){
                    return($cd->name===$d->name);
                });  
                //$fl would be items that should replace.
                if ($fl) {
                    foreach($fl as $f){
                        //use content->settings value (fl)
                        $all_settings->push($f);
                        $all_settings->forget($key);
                    }
                }
            }
        }
        
        //we now need to add the current settings if they don't exisit in the defaults.
        if (@$all_settings) {
            foreach ($this->content->setting as $setting) {
                dd($setting->id);
                $fl = $all_settings->filter(function ($d) use ($setting) {
                    if ($d->name===$setting->name) {
                        if ($d->id === $setting->id) {
                            return(true);
                        }
                    }
                    return(false);
                });
                if ($fl->isEmpty()) {
                    $all_settings->push($setting);
                }
            }
        } else {
            //if there's no defaults set for this we can just use what's on the content item already.
            $all_settings = $this->content->setting;
        }
        $settings = $all_settings->groupBy('section');
        
        App::register($this->content->edit_service_provider);
        $content = $this->content;
        if (Request::ajax()) {
            $cont = View::make('cms::contents.edit', compact('content', 'content_defaults', 'settings', 'permission', 'allPermissions'));
            return($cont);
        } else {
            $tree = $this->content->getDescendants();
            $cont = View::make('cms::contents.edit', compact('content', 'content_defaults', 'settings', 'permission', 'allPermissions'));
            $cont = View::make('cms::layouts.tree', compact('cont', 'tree'));
            $layout = View::make('cms::layouts.master', compact('cont'));
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

        //perm check here.

        $input = Input::all();
        $validation = Validator::make($input, $this->content->rules);
        if($input['parent_id'] == '#'){
            //we ar not allowed to create a new root node like this.. so set it to the current root.
            //unset($input['parent_id']); //test
            $input['parent_id'] = $this->content->fromApplication()->whereNull('parent_id')->first()->id;
        }
        if ($validation->passes()){
            $application = Application::getApplication();
            
          
            $tree = $this->content->superSave($input);
            
          //  dd($tree);
            
            
            return Response::json($this->renderTree($tree));
            
           // return Redirect::action('ContentsController@anyIndex');
        }
        
        return Redirect::action('ContentsController@anyCreate')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }


    public function anyFixtree(){
        $this->content->rebuild();
        
        dd($this->content->isValid());
        exit();
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyEdit($id = false){
        
        $content = $this->content->with(array('template_setting', 'setting'))->findOrFail($id);

        //dd($content->setting);
        $permission = Permission::getPermission('content', $content->id, 'w');
        $allPermissions = Permission::getControllerPermission($id, 'content');
        
        //foreach template setting we want to add a setting for this row..   
        if(!empty($content->template_setting)){
            //TODO: There has to be a cleaner way of doing this.
            $all_settings = new \Illuminate\Database\Eloquent\Collection;
            
            foreach($content->template_setting as $template_setting){

                $fl = $content->setting->filter(function($setting) use ($template_setting){
                    return($template_setting->name===$setting->name);
                });
                if(($fl->count())){
                    foreach($fl as $f){
                        //if it's fount int content_settings and template_settings, use
                        $all_settings->push($f);
                    }
                }
                else{
                    $all_settings->push($template_setting);    
                }
            }

            foreach($content->setting as $setting){

                $fl = $content->template_setting->filter(function($template_setting) use ($setting){
                    return($setting->name===$template_setting->name);
                });
                if(($fl->count() == 0)){
                    $all_settings->push($setting);    
                }
            }
        }

        $settings = $all_settings->groupBy('section');
        //dd($content->edit_service_provider);
        App::register($content->edit_service_provider); //we need to register any additional sp.. incase we have some weird edit page.
        
        if (Request::ajax()) {
            $view = View::make($content->edit_package . '::' . $content->edit_view,  compact('content', 'content_defaults', 'settings', 'permission', 'allPermissions'));
        } else {
            $tree = $content->getDescendants();
            $cont = View::make($content->edit_package.'::'.$content->edit_view, compact('content', 'content_defaults', 'settings', 'permission', 'allPermissions'));
            $cont = View::make('cms::layouts.tree', compact('cont', 'tree'));
            $view = View::make('cms::layouts.master', compact('cont'));         
        }
        return($view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyUpdate($id = false)
    {
        if (!@$id) {
            $input = array_except(Input::all(), '_method');
            $id = $input['id'];
        }
        if ($id !== false) {

            $input = array_except(Input::all(), '_method');
            
            $validation = Validator::make($input, $this->content->rules);
            if ($validation->passes()) {
                //we need to update the settings too:
                $content = $this->content->find($id);
                
                if (@$input['parent_id'] == '#') {
                    $input['parent_id'] = $this->content->getMainRoot();
                }
                $content->update($input);
                
                //TODO: take another look at a better way of doing this vv ..also VALIDATION!
                //add any settings:
                if (@$input['setting']) {
                    foreach ($input['setting'] as $name => $settingGroup) {
                        foreach ($settingGroup as $type => $setGrp) {
                            foreach ($setGrp as $key => $setting) {
                                //we want to delete this setting.
                                $toDel = Utils::recursive_array_search('deleted', $setGrp);
                                if (is_array($setGrp) && @$toDel) {
                                    $contentSetting = Contentsetting::destroy($toDel);
                                }
                                else {
                                    
                                    if ($type != 'Templatesetting') {
                                        $contentSetting = Contentsetting::withTrashed()
                                            ->where('name', '=', $name)
                                            ->where('content_id', '=', $content->id)
                                            ->where('id', '=', $key)->first();
                                    }
                                    //if it's not found (even in trashed) then we need to make a new field.
                                    //if it's contentdefault, we need to create it too since it doesn't exist!
                                    if ($type == 'Templatesetting' || is_null($contentSetting)) {
                                        //TODO: Do we want protection in there so there has to be a 
                                        //template setting in her for this?

                                        //if we can't find the field, we need to create it from the default:
                                        //dd($name);

                                        $defaultContentSetting = Templatesetting::find($key);
                                        if(!$defaultContentSetting){
                                            $defaultContentSetting = Templatesetting::where('name','=',$name)
                                                                    ->where('template_id', '=', $content->template_id)
                                                                    ->first();
                                        }
                                        //$defaultContentSetting = Templatesetting::where('name','=',)
                                        
                                        $contentSetting = new Contentsetting();
                                        $contentSetting->name = @$defaultContentSetting->name?@$defaultContentSetting->name:$name;
                                        $contentSetting->value = $setting;
                                        $contentSetting->content_id = $content->id;
                                        $contentSetting->field_type = $defaultContentSetting->field_type;
                                    } else {

                                        //otherwise this field exists.. we can overwrite it' settings.
                                        $contentSetting->name = $name;
                                        $contentSetting->value = $setting;
                                        $contentSetting->content_id = $content->id;
                                        $contentSetting->field_type = @$contentSetting->field_type?$contentSetting->field_type:'text';

                                    }
                                    //dd($contentSetting);
                                    $contentSetting->save();

                                    $contentSetting->restore();     //TODO: do we always want to restore the deleted field here?
                                }
                            }
                        }
                    }
                }

                if($this->content_mode == 'template'){
                    return Redirect::action('TemplateController@anyEdit', $id)
                    ->with('success', 'Success, saved correctly');
                }
                else{
                    return Redirect::action('ContentsController@anyEdit', $id)
                    ->with('success', 'Success, saved correctly');    
                }
                
            }
        } else {
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
        if (!$id) {
            $id = Input::all();
            if (@$id['id'] == '#') {
                $id = '';
            } else {
                $id = @$id['id'];
            }
        }
        $this->content->find($id)->delete();

        return Redirect::action('ContentsController@anyIndex');
    }
    
    //requests imediate descendents for given node
    //TODO: recursive.
    public function anyTree()
    {

        $id = Input::all();
        if (@$id['id'] == '#') {
            $id = '';
        } else {
            $id = @$id['id'];
        }
        
        if (!$id) {
            $id = $this->content->fromApplication()->whereNull('parent_id')->first()->id;
        }
        
        $tree = $this->content->where('id','=',$id)->first()->getDescendants()->toHierarchy();
        if(count($tree)){
            foreach($tree as $t){
                $treeOut[] = $this->renderTree($t);
                
            }
            return Response::json($treeOut);    
        }
        else{
            return Response::json();
        }
        
    }
    
    public function renderTree($tree)
    {
        
        $branch = new stdClass();
        $branch->id = $tree->id;
        $branch->text = $tree->name;
        $branch->children = array();
        //$branch->children = ($tree->rgt - $tree->lft > 1);
        
        foreach($tree->children as $child){
            
            $c = $this->renderTree($child);
            
            $branch->children[] = $c;
        }
        
        return($branch);
    }
    
    
    /*delete uploaded file(s)*/
    public function deleteUpload($id){
        $content_setting = Contentsetting::findOrFail($id);
        //$content_setting->delete(); //we don't actually want to delete here since we wait for the update button to do it's job.
        $delete = new stdClass();
        $fileName = pathinfo($content_setting->value, PATHINFO_FILENAME);
        
        $delete->{$fileName} = true;
        $return->files[] = $delete;
        
        return Response::json($return);
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

            
            if($type == 'Contentsetting'){
                $content_setting = Contentsetting::withTrashed()->find($id);
            }
            else{
                //this is on a template field.
                if($this->content_mode == 'contents'){
                    //we need to create an element based off the template if there is anything..
                    $templateSetting = Templatesetting::findOrFail($id);
                    $content_setting = new Contentsetting();
                    $content_setting->name = $templateSetting->name;
                    $content_setting->field_type = $templateSetting->field_type;
                    $content_setting->field_parameters = $templateSetting->field_parameters;
                }
                else{
                    //TODO: 
                    dd("TODO: " . $this->content_mode);
                    //we are in template mode, we want to save it as such.
                }
            }
        }
        $niceName = preg_replace('/\s+/', '', $content_setting->name);
        
        $files = (Input::file($niceName));

        $params = json_decode($content_setting->field_parameters);
        //dd($params->validation->mimes);

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
                    $destinationPath    = storage_path()."/uploads/";
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
            return Response::json($return);
            
        }
    }
}
