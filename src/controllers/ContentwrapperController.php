<?php namespace Bootleg\Cms;

use \Validator;
use \Input;
use \Event;

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
        view()->share('content_mode', $this->content_mode);
        $this->content = $content;
    }

    public $policy, $signature;



    /**
     * main content view.
     *
     * @return Response
     */
    public function anyIndex(){
        //dd('here');
        $this->content = $this->content->with(array('template_setting', 'setting'))->fromApplication()->whereNull('parent_id')->first();

        $content = $this->content;

        $allPermissions = \Permission::getControllerPermission($this->content->id, \Route::currentRouteAction());

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

        $content = \Content::setDefaults($content);

        return $this->render('layouts.tree', compact('content', 'content_defaults', 'settings', 'allPermissions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function anyCreate($parent_id=null){
        $content = new \Content;
        $content->parent_id = $parent_id;


        if($content){
            $tree = $content->getDescendantsAndSelf(config('bootlegcms.cms_tree_descendents'));
        }

        $content_settings = $this->content->setting()->get();

        return $this->render('contents.create', compact('content', 'content_settings', 'tree'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function anyStore($json = false){



        $input = \Input::all();
        $validation = \Validator::make($input, $this->content->rules);

        if(!isset($input['parent_id']) || $input['parent_id'] == '#'){
            //we ar not allowed to create a new root node like this.. so set it to the current root.
            //unset($input['parent_id']); //test
            $input['parent_id'] = $this->content->fromApplication()->whereNull('parent_id')->first()->id;
        }
        if ($validation->passes()){

            \Event::fire('content.create', array($this->content));
            \Event::fire('content.update', array($this->content));
            $tree = $this->content->superSave($input);
            \Event::fire('content.created', array($this->content));
            \Event::fire('content.updated', array($this->content));
          //  dd($tree);


            return response()->json($this->renderTree($tree));

           // return Redirect::action('ContentsController@anyIndex');
        }

        return \Redirect::action('\Bootleg\Cms\ContentsController@anyCreate')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    //fixes tree based off parent_id
    public function anyFixtree(){
        $this->content->rebuild();
        dd(\Content::isValidNestedSet());
    }

    //fixes slugs based off depth
    public function anyFixslug(){
        $Content = \Content::where('depth','=','5')->get();
        foreach($Content as $cont){
            $input = array();
            $input['name'] = $cont->name;
            $parent = \Content::find($cont->parent_id);
            $slug = \Content::createSlug($input,$parent,true);
            $cont->slug = $slug;
            var_dump($slug);
            $cont->save();
        }
        dd('done');
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
        //$permission = Permission::getPermission('content', $content->id, 'w');
        //$allPermissions = Permission::getContentPermissions($id);
        $allPermissions = \Permission::getControllerPermission($id, \Route::currentRouteAction());

        //foreach template setting we want to add a setting for this row..
        //dd($content->template_setting);
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
        //dd($content->edit_package);
        //dd($content->edit_service_provider);
        //App::register($content->edit_service_provider); //we need to register any additional sp.. incase we have some weird edit page.
        $content = \Content::setDefaults($content);

        //dd($content->edit_package.'::'.$content->edit_view);
        if (\Request::ajax()) {
            return $this->render($content->edit_view,  compact('content', 'content_defaults', 'settings', 'allPermissions'));
        } else {
            $tree = $content->getDescendants(config('bootlegcms.cms_tree_descendents'));
            return $this->render('layouts.tree',  compact('content', 'content_defaults', 'settings', 'allPermissions'));
        }
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
            $input = array_except(\Input::all(), '_method');
            $id = $input['id'];
        }
        if ($id !== false) {

            $input = array_except(\Input::all(), '_method');

            $validation = \Validator::make($input, $this->content->rules);
            if ($validation->passes()) {
                //we need to update the settings too:
                $content = $this->content->find($id);
                //TODO: care with Template settings.
                \Event::fire('content.edit', array($content));
                \Event::fire('content.update', array($content));

                if (@$input['parent_id'] == '#') {
                    $input['parent_id'] = $this->content->getMainRoot();
                }

                $oldPosition = $content->position;
                $content->update($input);

                //position needs looking at too..
                if(isset($input['position']) && $oldPosition != $input['position']){

                    $siblings = $content->getSiblingsAndSelf();

                    foreach($siblings as $key=>$sibling){

                        if($sibling->id == $content->id){
                            if($oldPosition > $content->position){
                                $siblings[$key]->position = $siblings[$key]->position-0.5;
                            }
                            else{
                                $siblings[$key]->position = $siblings[$key]->position+0.5;
                            }
                        }
                    }

                    $ordered = $siblings->sortBy(function($sibling){
                        return ($sibling->position);
                    });

                    $ordered->values();
                    //this will leave us with 2 that are the same position.
                    //we need to loop through and detect which ones to swap.

                    foreach($ordered as $key=>$sibling){
                        $sibling->position = $key;
                        $sibling->save();
                    }

                }

                //TODO: take another look at a better way of doing this vv ..also VALIDATION!
                //add any settings:
                if (isset($input['setting'])) {
                    foreach ($input['setting'] as $name => $settingGroup) {
                        foreach ($settingGroup as $type => $setGrp) {
                            foreach ($setGrp as $key => $setting) {
                                //we want to delete this setting.

                                $toDel = \Bootleg\Cms\Utils::recursive_array_search('deleted', $setGrp);
                                if (is_array($setGrp) && @$toDel) {
                                    $contentSetting = \Contentsetting::destroy($toDel);
                                }
                                else if(is_array($setting) && @$setting['deleted']){
                                    //THIS IS AN UPLOAD CONTENT ITEM.
                                    //we need to count if there are others.. if so we need to remove this item.
                                    //otherwise we need to set it to blank.
                                    $thisSetting = \ContentSetting::find($key);

                                    $otherSettings = \Contentsetting::where('name', $thisSetting->name)->where('content_id',$content->id)->get();
                                    if(count($otherSettings) > 1){
                                        $contentSetting = \Contentsetting::destroy($key);
                                    }
                                    else{
                                        //we jsut want to set it to blank.
                                        $thisSetting->value = '';
                                        $thisSetting->save();
                                    }
                                }
                                else {

                                    if ($type != 'Templatesetting') {
                                        //dd($type);
                                        //dd($name . $content->id . $key);
                                        $contentSetting = \Contentsetting::withTrashed()
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

                                        $defaultContentSetting = \Templatesetting::find($key);

                                        if(!$defaultContentSetting){
                                            $defaultContentSetting = \Templatesetting::where('name','=',$name)
                                                                    ->where('template_id', '=', $content->template_id)
                                                                    ->first();
                                        }

                                        $contentSetting = new \Contentsetting();
                                        $contentSetting->name = @$defaultContentSetting->name?@$defaultContentSetting->name:$name;
                                        $contentSetting->value = $setting;
                                        $contentSetting->content_id = $content->id;
                                        $contentSetting->field_parameters = @$defaultContentSetting->field_parameters;
                                        $contentSetting->field_type = @$defaultContentSetting->field_type;
                                        $contentSetting->section = @$defaultContentSetting->section;

                                    } else {

                                        //otherwise this field exists.. we can overwrite it' settings.
                                        $contentSetting->name = $name;
                                        $contentSetting->value = $setting;
                                        $contentSetting->content_id = $content->id;
                                        $contentSetting->field_type = @$contentSetting->field_type?$contentSetting->field_type:'text';

                                    }
                                    //dd($contentSetting);

                                        //dd($contentSetting);
                                        $contentSetting->save();



                                    $contentSetting->restore();     //TODO: do we always want to restore the deleted field here?
                                }
                            }
                        }
                    }
                }

                //TODO: care with Template settings.
                \Event::fire('content.edited', array($content));
                \Event::fire('content.updated', array($content));

                if($this->content_mode == 'template'){
                    return redirect()->action('\Bootleg\Cms\TemplateController@anyEdit', $id)
                    ->with('success', 'Success, saved correctly');
                }
                else{
                    return redirect()->action('\Bootleg\Cms\ContentsController@anyEdit', $id)
                    ->with('success', 'Success, saved correctly');
                }

            }
        } else {
            //TODO:
            $validation = 'no id';
        }
        return redirect()->action('\Bootleg\Cms\ContentsController@anyEdit', $id)
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
            $id = \Input::all();
            if (@$id['id'] == '#') {
                $id = '';
            } else {
                $id = @$id['id'];
            }
        }
        $this->content->find($id)->delete();

        if (!\Request::ajax()) {
            return redirect()->action('\Bootleg\Cms\ContentsController@anyIndex');
        }
    }

        //requests imediate descendents for given node
    //TODO: recursive.
    public function anyTree()
    {

        $id = \Input::all();
        if (@$id['id'] == '#') {
            $id = '';
        } else {
            $id = @$id['id'];
        }

        if (!$id) {
            $id = $this->content->fromApplication()->whereNull('parent_id')->first()->id;
        }

        $config =\Config::get('bootlegcms.cms_tree_descendents');
        $tree = $this->content->where('id','=',$id)->first();
        $tree = $config != null ? $tree->getDescendants($config) : $tree->getDescendants();
        $tree = $tree->toHierarchy();

        if(count($tree)){
            foreach($tree as $t){
                $treeOut[] = $this->renderTree($t);

            }
            return response()->json($treeOut);
        }
        else{
            return response()->json();
        }

    }

    /**
     * Renders a tree from a given node.. for use in jstree.
     * @param  [type]  $tree  [description]
     * @param  integer $depth current depth - used to see where to put children nodes.
     * @return [type]         [description]
     */
    public function renderTree($tree, $depth = 0)
    {
        $depth ++;
        $branch = new \stdClass();
        $branch->id = $tree->id;
        $branch->text = $tree->name;
        $branch->a_attr = new \stdClass();
        if($tree->edit_action){
            $branch->a_attr->href = action($tree->edit_action, array($tree->id));
        }
        else{
            if($this->content_mode == 'contents'){
                $branch->a_attr->href = action('\Bootleg\Cms\ContentsController@anyEdit', array($tree->id));
            }
            else{
                $branch->a_attr->href = action('\Bootleg\Cms\TemplateController@anyEdit', array($tree->id));
            }

        }

        $branch->children = array();
        //$branch->children = ($tree->rgt - $tree->lft > 1);
        if(count($tree->children)){
            foreach($tree->children as $child){

                $c = $this->renderTree($child, $depth);

                $branch->children[] = $c;
            }
        }
        else{
            if($depth <= config('bootlegcms.cms_tree_descendents')){
                //we don't know if there's anymore children.. so assume there is
                $branch->children = true;
            }
        }


        return($branch);
    }

    /*delete uploaded file(s)*/
    public function deleteUpload($id = ''){
        if($id){
            $content_setting = \Contentsetting::findOrFail($id);
            //$content_setting->delete(); //we don't actually want to delete here since we wait for the update button to do it's job.
            $delete = new \stdClass();
            $fileName = pathinfo($content_setting->value, PATHINFO_FILENAME);

            $delete->{$fileName} = true;
            $return->files[] = $delete;
        }
        else{
            return(true);
        }


        return response()->json($return);
    }

    public function anyInlineUpload(){
        //$setting = array();
        $setting = new \Illuminate\Database\Eloquent\Collection;
        $setting->add(new \Contentsetting());
        $setting[0]->field_parameters = \Contentsetting::DEFAULT_UPLOAD_JSON;
        $setting[0]->name = '_inline';
        $setting[0]->field_type = '_inline';
        $setting[0]->id = 0;

        return $this->render('contents.inline-upload', compact('setting'));

    }

    /*
     * pass in a content_setting id to upload to.
     */
    public function postUpload($id){

        $u = [
            'local' => [
                'folder' => trim(@$this->application->getSetting('Upload Folder'),'/\ '),
                'delete_uploads' => @$this->application->getSetting('deleteUploads'),
            ],
            's3' => [
                'enabled' => @$this->application->getSetting('Enable s3'),
                'folder' => trim(@$this->application->getSetting('s3 Folder'),'/\ '),
                'bucket' => @$this->application->getSetting('s3 Bucket'),
                'cloudfront_url' => trim(@$this->application->getSetting('s3 Cloudfront Url'), " /"),
            ]
        ];
        $a = [
            'includes' => ['_aws'],
            'services' => [
                'default_settings' => [
                    'params' => [
                        'key'    => @$this->application->getSetting('s3 access key'),
                        'secret' => @$this->application->getSetting('s3 secret'),
                        'region' => @$this->application->getSetting('s3 region')
                    ]
                ]
            ]
        ];

        $input = array_except(\Input::all(), '_method');

        $inline = false;

        $type = $input['type'];
        //dd($type);
        if($type == 'Contentsetting' || $type == 'Templatesetting'){
            $setting = @$type::withTrashed()->find($id);

            if(!$setting){
                //there's no setting in here already - so we can make one.
                $setting = new $type;

                //we can try and find it in the template?
                if(@$this->content_mode == 'contents' && $id){
                    $templateSetting = \Templatesetting::findOrFail($id);
                    $setting->name = $templateSetting->name;
                    $setting->field_type = $templateSetting->field_type;
                    $setting->field_parameters = $templateSetting->field_parameters;
                }

                //otherwise maybe it's custom.
                $setting = new $type;
                $setting->name = '_custom'; //just dummy stuff for now..
                $setting->field_type = 'upload';
                $setting->field_parameters = \Contentsetting::DEFAULT_UPLOAD_JSON;
            }

            if($setting->name == '_custom'){
                $niceName = preg_replace('/\s+/', '', $setting->name);
                $f = \Input::file();
                $files = (\Input::file(key($f)));
            }
            else{
                $niceName = preg_replace('/\s+/', '_', $setting->name);
                $files = \Input::file($niceName);
            }

            $params = json_decode($setting->field_parameters);
        } else {
            $f = \Input::file();
            $files = (\Input::file(key($f)));
        }

        if(!empty($files)){

            foreach($files as $file) {

                $rules = array(
                    // @todo.
                    'file' => 'required|mimes:'.$input['mimes'].'|max:'.$input['maxsize']
                );
                $validator = \Validator::make(array('file'=> $file), $rules);

                if($validator->passes()){

                    $f = [
                        //@todo validate
                        'mime'          =>  $file->getMimeType(),
                        'size'          =>  $file->getSize(),
                        'name'          =>  trim(uniqid() . '.' . $file->getClientOriginalExtension(), '/\ '),
                        'original_name' =>  $file->getClientOriginalName(),
                        'upload_path'   =>  trim('uploads/'.$u['local']['folder'],'/\ ')
                    ];
                    $f['upload_full'] = $f['upload_path'].'/'.$f['name'];
                    try {
                        $upload_success     = $file->move(public_path($f['upload_path']), $f['name']);
                    } catch(\Exception $e) {
                        dd($e->getMessage());
                        //TODO: proper error handling should really take place here..
                        //in the mean time we'll make do with a dd.
                    }

                    $f['url'] = url($f['upload_full']);

                    //if s3 is enabled, we can upload to s3!
                    //TODO: should this be shifted to some sort of plugin?
                    if($u['s3']['enabled']){

                        //file and folder need to be concated and checked.
                        $upload_path = $u['local']['folder'].'/'.$f['name'];

                        //prepend S3 folder if set
                        if($u['s3']['folder']){
                            $upload_path = $u['s3']['folder'].'/'.$upload_path;
                        }

                        //strip excess slashes
                        $upload_path = trim($upload_path,'/\ ');

                        $aws = \Aws\Common\Aws::factory($a);
                        $s3 = $aws->get('s3');
                        $s3->putObject([
                            'Bucket'     =>     $u['s3']['bucket'],
                            'Key'        =>     $upload_path,
                            'SourceFile' =>     public_path($f['upload_full']),
                            'ACL'        =>     'public-read' //todo: check this would be standard - would we ever need to have something else in here?
                        ]);
                        if($u['s3']['cloudfront_url']) {
                            $f['url'] = '//'.$u['s3']['cloudfront_url'].'/'.$upload_path;
                        } elseif($u['s3']['bucket']){
                            $f['url'] = '//'.$u['s3']['bucket'].'/'.$upload_path;
                        }

                        //todo: remove old file in /uploads?
                        if ($u['local']['delete_uploads'] && \File::exists($f['upload_full'])) {
                            \File::delete($f['upload_full']);
                        }

                    }

                    //and we need to build the json response.
                    $fileObj = new \stdClass();
                    $fileObj->name = $f['name'];
                    $fileObj->original_name = $f['original_name'];
                    $fileObj->id = $id;
                    $fileObj->thumbnailUrl = $f['url']; //@todo
                    $fileObj->url = $f['url'];

                    //is this stuff still needed?
                    $fileObj->deleteUrl = url($f['upload_full']); //todo
                    $fileObj->deleteType = "DELETE";

                    $return->files[] = $fileObj;
                }
                else{
                    //TODO:validation failure messages.
                    echo('val fail');
                    exit();
                }

                \Event::fire('upload.complete', array($f['url']));
            }
            return response()->json($return);

        }
    }
}
