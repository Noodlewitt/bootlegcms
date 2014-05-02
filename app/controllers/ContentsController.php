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
        
        $root_node = Content::fromApplication()->whereNull('parent_id')->first();

        $this->content = $this->content->findOrFail($root_node->id);
        
        
        $content_settings = $this->content->setting()->get();
        $content  = $this->content;
        
        
        if (Request::ajax()){
            $cont = View::make( 'cms::contents.edit', compact('content', 'content_settings') );
            return($cont);
        }
        else{
            $tree = $root_node->getDescendants();
            $cont = View::make( 'cms::contents.edit', compact('content', 'content_settings') );
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
        
        if ($validation->passes()){
            $saved = array($this->content->create($input));
            if($json){
                return($this->renderTree($saved));
            }
            return Redirect::action('ContentsController@anyIndex');
        }
        
        return Redirect::action('ContentsController@anyCreate')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyEdit($id = false){
       
        $root_node = Content::getMainRoot();
        
        //$root_node->getTree(false);
        
        $this->content = $this->content->findOrFail($id);
        
        $tree = $root_node->getDescendants();
        
        $content_settings = $this->content->setting()->get();
        $content  = $this->content;
        
        //var_dump($content->contenttype()->get());
        return View::make($this->application->cms_package.'::contents.edit', compact('content', 'content_settings', 'tree'));
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
                
                //TODO: take another look at a better way of doing this:
                //add any settings:
                if(@$input['setting']){
                    foreach($input['setting'] as $key=>$setting){
                        $contentSetting = Contentsetting::firstOrNew(array(
                            'name'=>$key, 
                            'content_id'=> $content->id,
                        ));
                        $contentSetting->name = $key;
                        $contentSetting->value = $setting;
                        $contentSetting->content_id = $content->id;
                        $contentSetting->field_type = @$contentSetting->field_type?$contentSetting->field_type:'text';
                        $contentSetting->save();
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
    public function anyDestroy($id)
    {
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
    
    
    /*retrieve uploaded file(s)*/
    public function deleteUpload($id){
        $content_setting = Contentsetting::findOrFail($id);
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
        $application = Application::getApplication();
        $uploadFolder = @$application->getSetting('Upload Folder');
        if($type == 'Applicationsetting'){
            $content_setting = Applicationsetting::findOrFail($id);
        }
        else{
            $content_setting = Contentsetting::findOrFail($id);
        }
        $niceName = preg_replace('/\s+/', '', $content_setting->name);
        
        $files = (Input::file($niceName));
        
        if(!empty($files)){

            foreach($files as $file) {
                $rules = array(
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