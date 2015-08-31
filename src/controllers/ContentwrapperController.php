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
    public function anyIndex()
    {     

        $this->content = $this->content->with(array('template_setting', 'setting'))->fromApplication()->whereNull('parent_id')->first();
        $content = $this->content;
        $allPermissions = \Permission::getControllerPermission($this->content->id, \Route::currentRouteAction());
        $settings = \Contentsetting::collectSettings($content);
        $content = \Content::setDefaults($content);
        
        return $this->render('layouts.tree', compact('content', 'content_defaults', 'settings', 'allPermissions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function anyCreate($parent_id=null)
    {
        $content = new \Content;
        $content->parent_id = $parent_id;


        if ($content) {
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
    public function anyStore($json = false)
    {
        $input = Input::all();
        $validation = Validator::make($input, $this->content->rules);

        if (!isset($input['parent_id']) || $input['parent_id'] == '#') {
            //we ar not allowed to create a new root node like this.. so set it to the current root.
            //unset($input['parent_id']); //test
            $input['parent_id'] = $this->content->fromApplication()->whereNull('parent_id')->first()->id;
        }
        if ($validation->passes()) {
            Event::fire('content.create', array($this->content));
            Event::fire('content.update', array($this->content));
            $tree = $this->content->superSave($input);
            Event::fire('content.created', array($this->content));
            Event::fire('content.updated', array($this->content));
          //  dd($tree);


            return response()->json($this->renderTree($tree));

           // return Redirect::action('ContentsController@anyIndex');
        }

        return Redirect::action('ContentsController@anyCreate')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    //fixes tree based off parent_id
    public function anyFixtree()
    {
        $this->content->rebuild();
        dd(\Content::isValidNestedSet());
    }

    //fixes slugs based off depth
    public function anyFixslug()
    {
        $Content = Content::where('depth', '=', '5')->get();
        foreach ($Content as $cont) {
            $input = array();
            $input['name'] = $cont->name;
            $parent = Content::find($cont->parent_id);
            $slug = Content::createSlug($input, $parent, true);
            $cont->slug = $slug;
            var_dump($slug);
            $cont->save();
        }
        dd('done');
    }


    /**
     * Constructs the edit form for rendering.
     * @return [type] [description]
     */
    public function getSettings(){
        if (!empty($content->template_setting)) {
            //TODO: There has to be a cleaner way of doing this.
            $all_settings = new \Illuminate\Database\Eloquent\Collection;

            foreach ($content->template_setting as $template_setting) {
                $fl = $content->setting->filter(function ($setting) use ($template_setting) {
                    return($template_setting->name===$setting->name);
                });
                if (($fl->count())) {
                    foreach ($fl as $f) {
                        //if it's fount int content_settings and template_settings, use
                        $all_settings->push($f);
                    }
                } else {
                    $all_settings->push($template_setting);
                }
            }

            foreach ($content->setting as $setting) {
                $fl = $content->template_setting->filter(function ($template_setting) use ($setting) {
                    return($setting->name===$template_setting->name);
                });
                if (($fl->count() == 0)) {
                    $all_settings->push($setting);
                }
            }
        }

        $settings = $all_settings->groupBy('section');
    }


    /**
     * render just the form tabs
     *
     * @param  int  $id
     * @return Response
     */
    public function anyEditTabs($id = false)
    {
        $content = $this->content->with(array('template_setting', 'setting'))->findOrFail($id);
        $allPermissions = \Permission::getControllerPermission($id, \Route::currentRouteAction());
        $settings = \Contentsetting::collectSettings($content);
        $content = \Content::setDefaults($content);

        if (\Request::ajax()) {
            return $this->render($content->edit_view.'-tabs',  compact('content', 'settings', 'allPermissions'));
        } else {
            $tree = $content->getDescendants(config('bootlegcms.cms_tree_descendents'));
            return $this->render('layouts.tree',  compact('content', 'settings', 'allPermissions'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function anyEdit($id = false)
    {
        $content = $this->content->with(array('template_setting', 'setting'))->findOrFail($id);
        $allPermissions = \Permission::getControllerPermission($id, \Route::currentRouteAction());
        $settings = \Contentsetting::collectSettings($content);
        $content = \Content::setDefaults($content);

        if (\Request::ajax()) {
            return $this->render($content->edit_view,  compact('content', 'settings', 'allPermissions'));
        } else {
            $tree = $content->getDescendants(config('bootlegcms.cms_tree_descendents'));
            return $this->render('layouts.tree',  compact('content', 'settings', 'allPermissions'));
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

                //we need to update the language content item..
                if (\App::getLocale() != $this->application->default_locale) {
                    $contentLang = $content->languages(\App::getLocale())->first();
                    if (!$contentLang) {
                        //we need to create this language item..
                        if ($this->content_mode == 'template') {
                            $contentLang = new \TemplateLanguage([
                                'name'=>$input['name'],
                                'slug'=>$input['slug'],
                                'user_id'=>\Auth::user()->id,
                                'template_id'=>$id,
                                'code'=>\App::getLocale()
                            ]);
                        } else {
                            $contentLang = new \ContentLanguage([
                                'name'=>$input['name'],
                                'slug'=>$input['slug'],
                                'user_id'=>\Auth::user()->id,
                                'content_id'=>$id,
                                'code'=>\App::getLocale()
                            ]);
                        }
                        $contentLang->save();
                    } else {
                        $contentLang->update($input);
                    }
                    
                    //remove the fields we should ignore, since we don't want to update the real content 
                    //item with the languaged one..
                    unset($input['name']);
                    unset($input['slug']);
                    unset($input['user_id']);
                }

                //now we can save what we have
                $content->update($input);
                
                
                //position needs looking at too..
                if (isset($input['position']) && $oldPosition != $input['position']) {
                    $siblings = $content->getSiblingsAndSelf();

                    foreach ($siblings as $key=>$sibling) {
                        if ($sibling->id == $content->id) {
                            if ($oldPosition > $content->position) {
                                $siblings[$key]->position = $siblings[$key]->position-0.5;
                            } else {
                                $siblings[$key]->position = $siblings[$key]->position+0.5;
                            }
                        }
                    }

                    $ordered = $siblings->sortBy(function ($sibling) {
                        return ($sibling->position);
                    });

                    $ordered->values();
                    //this will leave us with 2 that are the same position.
                    //we need to loop through and detect which ones to swap.

                    foreach ($ordered as $key=>$sibling) {
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
                                
                                if(is_array($setting)){
                                    //we have an array of stuff in this field - maybe for a tag field or something.
                                    $setting = implode(',', $setting);
                                }

                                if ($this->content_mode == 'template') {
                                    $contentSetting = \Templatesetting::withTrashed()
                                        ->where('name', '=', $name)
                                        ->where('template_id', '=', $content->id)
                                        ->where('id', '=', $key)->first();
                                } else {
                                    if ($type != 'Templatesetting') {
                                        
                                        //dd($name . $content->id . $key);
                                        $contentSetting = \Contentsetting::withTrashed()
                                            ->where('name', '=', $name)
                                            ->where('content_id', '=', $content->id)
                                            ->where('id', '=', $key)->first();

                                    }
                                }


                                //if it's not found (even in trashed) then we need to make a new field.
                                //if it's template, we need to create the contentsetting too it too since 
                                //it doesn't exist!
                                if (($type == 'Templatesetting' || is_null($contentSetting)) && $this->content_mode != 'template') {
                                    //TODO: Do we want protection in there so there has to be a
                                    //template setting in her for this?

                                    //if we can't find the field, we need to create it from the default:

                                    $defaultContentSetting = \Templatesetting::find($key);

                                    if (!$defaultContentSetting) {
                                        $defaultContentSetting = \Templatesetting::where('name', '=', $name)
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
                                    //otherwise this field exists.. we can overwrite it's settings.
                                    $contentSetting->name = $name;
                                    $contentSetting->value = $setting;
                                    $contentSetting->content_id = $content->id;
                                    $contentSetting->field_type = @$contentSetting->field_type?$contentSetting->field_type:'text';
                                }
                                
                                //if we are in a language, we need to save the language version not the item itself..
                                if (\App::getLocale() != $this->application->default_locale) {
                                    $contentSettingLanguage = $contentSetting->languages(\App::getLocale())->first();

                                    if (!$contentSettingLanguage) {
                                        if ($this->content_mode == 'template') {
                                            $contentSettingLanguage = new \TemplatesettingLanguage();

                                            $contentSettingLanguage->template_setting_id = $contentSetting->id;
                                            $contentSettingLanguage->template_id = $content->id;
                                        } else {
                                            $contentSettingLanguage = new \ContentsettingLanguage();
                                            $contentSettingLanguage->content_setting_id = $contentSetting->id;
                                            $contentSettingLanguage->content_id = $content->id;
                                        }
                                    }

                                    $contentSettingLanguage->name = $contentSetting->name;
                                    $contentSettingLanguage->value = $setting;
                                    
                                    
                                    $contentSettingLanguage->field_parameters = $contentSetting->field_parameters;
                                    $contentSettingLanguage->field_type = $contentSetting->field_type;
                                    $contentSettingLanguage->section = $contentSetting->section;
                                    $contentSettingLanguage->code = \App::getLocale();

                                    $contentSettingLanguage->save();
                                } else {
                                    $contentSetting->save();
                                }
                                    
                                //$contentSetting->restore();     //TODO: do we always want to restore the deleted field here?
                            }
                        }
                    }
                }
                
                //TODO: care with Template settings.
                \Event::fire('content.edited', array($content));
                \Event::fire('content.updated', array($content));

                if ($this->content_mode == 'template') {
                    return redirect()->action('\Bootleg\Cms\TemplateController@anyEdit', $id)
                    ->with('success', 'Success, saved correctly');
                } else {
                    return redirect()->action('\Bootleg\Cms\ContentsController@anyEdit', $id)
                    ->with('success', 'Success, saved correctly');
                }
            }
        } else {
            //TODO:
            $validation = 'no id';
        }
        return redirect()->action('ContentsController@anyEdit', $id)
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
            return redirect()->action('ContentsController@anyIndex');
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
        if(config('bootlegcms.cms_tree_descendents')){
            $tree = $this->content->where('id', '=', $id)->first()->getDescendants(config('bootlegcms.cms_tree_descendents'))->toHierarchy();    
        }
        else{
            $tree = $this->content->where('id', '=', $id)->first()->getDescendants()->toHierarchy();
        }
        if (count($tree)) {
            foreach ($tree as $t) {
                $treeOut[] = $this->renderTree($t);
            }
            return response()->json($treeOut);
        } else {
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

        if ($tree->edit_action) {
            $branch->a_attr->href = action($tree->edit_action, array($tree->id));
        } else {
            if ($this->content_mode == 'contents') {
                $branch->a_attr->href = action('\Bootleg\Cms\ContentsController@anyEdit', array($tree->id));
            } else {
                $branch->a_attr->href = action('\Bootleg\Cms\TemplateController@anyEdit', array($tree->id));
            }
        }

        $branch->children = array();
        //$branch->children = ($tree->rgt - $tree->lft > 1);
        if (count($tree->children)) {
            foreach ($tree->children as $child) {
                $c = $this->renderTree($child, $depth);

                $branch->children[] = $c;
            }
        } else {
            if ($depth <= config('bootlegcms.cms_tree_descendents')) {
                //we don't know if there's anymore children.. so assume there is
                $branch->children = true;
            }
        }
        

        return($branch);
    }

    /*delete uploaded file(s)*/
    public function deleteUpload($id = '')
    {
        if ($id) {
            $content_setting = Contentsetting::findOrFail($id);
            //$content_setting->delete(); //we don't actually want to delete here since we wait for the update button to do it's job.
            $delete = new stdClass();
            $fileName = pathinfo($content_setting->value, PATHINFO_FILENAME);

            $delete->{$fileName} = true;
            $return->files[] = $delete;
        } else {
            return(true);
        }


        return response()->json($return);
    }

    public function anyInlineUpload()
    {
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
    public function postUpload($id,  $type = "Custom")
    {
        $setting = $type::find($id);
        if(!$setting && $type == "Contentsetting"){
            //if there's no setting and the field type is 
            //content - we can assume this is coming from 
            //a template instead - we can safely change 
            //this to template and continue
            
            $setting = \Templatesetting::find($id);
        }

        $params = \Contentsetting::parseParams($setting);

        $input = array_except(\Input::all(), '_method');

        $uploadFolder = @$this->application->getSetting('Upload Folder');

        $f = \Input::file();
        $files = (\Input::file(key($f)));

        if (!empty($files)) {
            foreach ($files as $file) {
                $rules = array(
                    //TODO.
                    'file' => 'required|mimes:png,gif,jpeg,txt,pdf,doc,rtf,mpeg|max:20000'
                );
                $validator = \Validator::make(array('file'=> $file), $rules);

                if ($validator->passes()) {
                    $fileId             = uniqid();
                    $extension          = $file->getClientOriginalExtension();
                    $fileName           = trim("$uploadFolder/$fileId.$extension", '/\ ');
                    $destinationPath    = storage_path()."/uploads/";
                    $originalName       = $file->getClientOriginalName();
                    $mime_type          = $file->getMimeType();
                    $size               = $file->getSize();
                    try {
                        $upload_success     = $file->move($destinationPath.$uploadFolder, $fileId.'.'.$extension);
                    } catch (Exception $e) {
                        dd($e->getMessage());
                        //TODO: proper error handling should really take place here..
                        //in the mean time we'll make do with a dd.
                    }

                    $finalUrl = "//".$_SERVER['SERVER_NAME']."/uploads/$fileName";
                    
                    //if s3 is enabled, we can upload to s3!
                    //TODO: should this be shifted to some sort of plugin?
                    
                    if (@$this->application->getSetting('Enable s3')) {


                        //$uploadFolder
                        //file and folder need to be concated and checked.
                        if (@$this->application->getSetting('s3 Folder')) {
                            $pth = trim(@$this->application->getSetting('s3 Folder'), '/\ ').'/'.$fileName;
                        } else {
                            $pth = $fileName;
                        }

                        $s3 = \AWS::get('s3');
                        $s3->putObject(array(
                            'Bucket'     => @$this->application->getSetting('s3 Bucket'),
                            'Key'        => $pth,
                            'SourceFile' => $destinationPath.$fileName,
                            'ACL'=>'public-read' //todo: check this would be standard - would we ever need to have something else in here?
                        ));
                        if (@$this->application->getSetting('s3 Cloudfront Url')) {
                            $cloudUrl = trim($this->application->getSetting('s3 Cloudfront Url'), " /");
                            $finalUrl = "//$cloudUrl/$pth";
                        } else {
                            $finalUrl = "//".@$this->application->getSetting('s3 Bucket')."/$pth";
                        }


                        //todo: remove old file in /uploads?
                    }

                    //and we need to build the json response.
                    $fileObj = new \stdClass();
                    $fileObj->name = $originalName;
                    $fileObj->id = $id;
                    $fileObj->thumbnailUrl = $finalUrl; //todo
                    $fileObj->deleteUrl = "//".$_SERVER['SERVER_NAME']."/uploads/$fileName"; //todo
                    $fileObj->deleteType = "DELETE";

                    $return->files[] = $fileObj;
                } else {
                    //TODO:validation failure messages.
                    echo('val fail');
                    exit();
                }

                \Event::fire('upload.complete', array($finalUrl));
            }
            return response()->json($return);
        }
    }
}
