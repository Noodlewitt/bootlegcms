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
    public function anyTree($id = NULL){
        if(!$id){
            $this->content = $this->content->with(array('template_setting', 'setting'))->fromApplication()->whereNull('parent_id')->first();
        }
        else{
            $this->content = $this->content->with(array('template_setting', 'setting'))->findOrFail($id);    
        }

        $content = $this->content;
        $allPermissions = \Permission::getControllerPermission($this->content->id, \Route::currentRouteAction());
        $settings = \Contentsetting::collectSettings($content);
       // $content = \Content::setDefaults($content);

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
        $parent = \Content::findOrFail($parent_id);
        $content->template_id = \Input::get('template_id');
        $input = $content->loadDefaultValues(array('parent_id'=>$parent_id, 'template_id'=>\Input::get('template_id')));

        foreach($input as $key=>$put){
            $content->$key = $put;
        }
        $allPermissions = array();
        $settings = \Contentsetting::collectSettings($content);
        //$content_settings = $this->content->setting()->get();
        if (\Request::ajax()) {
            return $this->render('contents.edit',  compact('content', 'settings', 'allPermissions'));
        } else {
            return $this->render('layouts.large',  compact('content', 'children', 'childrenSettings', 'settings', 'allPermissions', 'children'));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function anyStore()
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
            $content = $this->content->superSave($input);
            $content = ContentwrapperController::saveSettings($content, $input);
            Event::fire('content.created', array($this->content));
            Event::fire('content.updated', array($this->content));
          //  dd($tree);

            return \Redirect::action(@$content->edit_action?$content->edit_action:'\Bootleg\Cms\ContentsController@anyEdit', $content->id);

        }

        return \Redirect::action('ContentsController@anyCreate')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }


    /**
     * Tree create page..
     *
     * @return Response
     */
    public function anyTreeStore()
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
        $content = Content::where('depth', '=', '5')->get();
        foreach ($content as $cont) {
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
     * render just the form tabs
     *
     * @param  int  $id
     * @return Response
     */
    public function anyEditTabs($id = false)
    {
        $content = $this->content->with(array('template_setting', 'setting'))->findOrFail($id);
        $allPermissions = \Permission::getControllerPermission($id, \Route::currentRouteAction());
        $settings = \Contentsetting::collectSettings($content)->groupBy('section');
      //  $content = \Content::setDefaults($content);

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
        $content = $this->content->findOrFail($id);

        //if they have manually set template_id we want to pretend that is the template id for a while.
        if(\Input::get('template_id')){
            $content->template_id = \Input::get('template_id');
        }
        $content = $content->load('template_setting', 'setting');
    
        
        $allPermissions = \Permission::getControllerPermission($id, \Route::currentRouteAction());
        $settings = \Contentsetting::collectSettings($content);

        $children = $content->children()->with(array('setting', 'template_setting'))->paginate();
        $childrenSettings = new \Illuminate\Database\Eloquent\Collection;

        foreach($children as $child){
            $childrenSettings[$child->id] = \Contentsetting::collectSettings($child);
        }

        if (\Request::ajax()) {
            return $this->render($content->edit_view,  compact('content','childrenSettings',  'settings', 'allPermissions'));
        } else {
            $tree = $content->getDescendants(config('bootlegcms.cms_tree_descendents'));
            return $this->render('layouts.tree',  compact('content', 'childrenSettings', 'settings', 'allPermissions'));
        }
    }

    /**
     * search for content items given parent_id
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getSearch($id){
        //config('bootlegcms.cms_pagination')
        
        $content = $this->content->with(array('template_setting', 'setting'))->findOrFail($id);

        $search = \Input::get('search');
        $settings = \Contentsetting::where(function($q) use ($search){
                return $q->orWhere('value','LIKE',"%$search%")
                         ->orWhere('value', '=' ,'SOUNDEX("$search")');
            })
            ->lists('content_id');

        $children = \Content::with(array('setting', 'template_setting'))
            ->whereIn('id',$settings)
            ->where('parent_id',$id)
            ->paginate();


        $childrenSettings = new \Illuminate\Database\Eloquent\Collection;

        foreach($children as $child){
            //dd($child->id,\Contentsetting::collectSettings($child));
            $childrenSettings[$child->id] = \Contentsetting::collectSettings($child);
        }
        $settings = \Contentsetting::collectSettings($content);
        $search = true;
        if (\Request::ajax()) {
            return $this->render($content->edit_view,  compact('search', 'content', 'settings', 'children', 'childrenSettings'));
        } else {
            return $this->render('layouts.large',  compact('search', 'content', 'settings', 'children', 'childrenSettings'));
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
                $content = $this->content->findOrFail($id);

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

                $content = ContentwrapperController::saveSettings($content, $input);

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
     * saves settings based off input array
     * @return [type] [description]
     */
    public function saveSettings($content, $input){
        //TODO: take another look at a better way of doing this vv ..also VALIDATION!
        //add any settings:
        //dd($input);
        if (isset($input['setting'])) {
            foreach ($input['setting'] as $name => $settingGroup) {
                foreach ($settingGroup as $type => $setGrp) {

                    //if we are on a multi field we need to treat this a little differently..
                    $multimode = (isset($settingGroup['_multi'])?true:false);

                    if($type != '_multi'){

                        foreach ($setGrp as $key => $setting) {
                            if(!$multimode && is_array($setting)){
                                //we have an array of stuff in this field - maybe for a tag field or something.
                                $setting = implode(',', $setting);
                            }
                            if(is_array($setting)){
                                //we have a multifield.. we need to deal with this carefully:

                                foreach($setting as $template_id=>$template_set){

                                    foreach($template_set as $index=>$set){
                                        if(isset($set['deleted'])){
                                            \Contentsetting::where('parent_id',$key)->where('index',$index)->delete();
                                        }
                                        else{
                                            if($type == "Contentsetting"){
                                                //update existing
                                                $contentSetting = \Contentsetting::where('parent_id',$key)->where('index',$index)->first();
                                                if($contentSetting->value != $set){
                                                    $contentSetting->value=$set;
                                                    $contentSetting->save();
                                                }
                                            }
                                            else{
                                                //we need to create this guy.
                                                $template = \Templatesetting::find($template_id);
                                                //we need to find the right parent id based off that ^^
                                                $templateParent = \Templatesetting::find($template->parent_id); //this is the multi field as it exists on the template..
                                                //we can now try and find the parent of this conten item based on that.

                                                $parent = \Contentsetting::where('name',$templateParent->name)->where('content_id',$content->id)->first();

                                                $contentSetting = new \Contentsetting();
                                                $contentSetting->parent_id = $parent->id;//TODO: this should be the key of the newly created multi field. NOT $key;
                                                $contentSetting->index = $index;
                                                $contentSetting->value = $set;
                                                $contentSetting->templatesetting_id = $template->id;
                                                $contentSetting->field_type = $template->field_type;
                                                $contentSetting->field_parameters =  $template->field_parameters;
                                                $contentSetting->section =  $template->section?$template->section:"Content";
                                                $contentSetting->content_id = $content->id;
                                                $contentSetting->name = $template->name;
                                                $contentSetting->save();
                                            }
                                        }
                                    }
                                }
                            }
                            else{
                                //this isn't a multifield.. go and add it as normal
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

                                    //if we are not editing a tepmplate,
                                    // if we can, we're going to create the field based off the relevant template setting.

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
                                    $contentSetting->templatesetting_id = @$defaultContentSetting->id;
                                    $contentSetting->section = @$defaultContentSetting->section;
                                    $contentSetting->templatesetting_id = $defaultContentSetting->id;
                                    //and for multi-fields:
                                    //$contentSetting->parent_id = $input['multi']['parent'][];
                                } else {
                                    //otherwise this field exists.. we can overwrite it's settings.
                                    //
                                    $contentSetting->name = $name;
                                    $contentSetting->value = $setting;
                                    $contentSetting->content_id = $content->id;
                                    $contentSetting->field_type = @$contentSetting->field_type?$contentSetting->field_type:'text';

                                }
                                //dd($contentSetting->name, $contentSetting->value, $contentSetting->content_id);
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

                                    if($contentSetting->value == ''){
                                        $contentSetting->forceDelete();
                                    }
                                    else{

                                        $contentSetting->save();
                                    }

                                }
                            }

                            //$contentSetting->restore();     //TODO: do we always want to restore the deleted field here?
                        }
                    }
                    else{
                        //this is the actual multi field - we need to see if we need create this correctly!

                        foreach($setGrp as $k=>$s){
                            if($k == "Templatesetting"){
                                //we are on a template setting - create the contentsetting!
                                $template = \Templatesetting::find($s);
                                $contentSetting = new \Contentsetting();
                                $contentSetting->templatesetting_id = $template->id;
                                $contentSetting->field_type = $template->field_type;
                                $contentSetting->field_parameters =  $template->field_parameters;
                                $contentSetting->section =  $template->section?$template->section:"Content";
                                $contentSetting->content_id = $content->id;
                                $contentSetting->name = $template->name;
                                $contentSetting->save();
                            }
                            else{
                                //do nothing - this should never really be editable.
                            }
                        }
                        //$contentSetting = \Contentsetting::where('parent_id',$key)->where('index',$index)->first();
                    }
                }
            }
        }
        return $content;
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
            return redirect()->action('ContentsController@anyTree');
        }
    }

    //requests descendents for given node
    public function anyTreeJson()
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
                if($this->renderTree($t)){
                    $treeOut[] = $this->renderTree($t);
                }
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
    public function renderTree($tree, $depth = 0) {
        if(!$tree->hide_in_tree){
            $depth ++;
            $branch = new \stdClass();
            $branch->id = $tree->id;


            $branch->text = $tree->name;

            $branch->a_attr = new \stdClass();
            if($tree->hide_in_tree === false && config('bootlegcms.cms_debug')){
                $branch->a_attr->class = 'text-danger';
            }
            else{
                $branch->a_attr->class = '';
            }


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
           // dd($tree->hide_children);
            if (count($tree->children)) {
                foreach ($tree->children as $child) {
                    if(!$child->hide_in_tree){
                        $c = $this->renderTree($child, $depth);

                        $branch->children[] = $c;
                    }
                    else{
                        $branch->children = false;
                    }

                }
            } else {
                if ($depth <= config('bootlegcms.cms_tree_descendents')) {
                    //we don't know if there's anymore children.. so assume there is
                    $branch->children = true;
                }
            }
            return($branch);
        }
        return false;
    }

    public function anyTableCreate($parent_id=null) {
        $content = new \Content;
        $content->parent_id = $parent_id;
        $parent = \Content::findOrFail($parent_id);
        $input = $content->loadDefaultValues(array('parent_id'=>$parent_id));
        foreach($input as $key=>$put){
            $content->$key = $put;
        }
        $allPermissions = array();
        $settings = \Contentsetting::collectSettings($content);
        //$content_settings = $this->content->setting()->get();
        if (\Request::ajax()) {
            return $this->render('contents.edit',  compact('content', 'settings', 'allPermissions'));
        } else {
            return $this->render('contents.edit',  compact('content', 'children', 'childrenSettings', 'settings', 'allPermissions', 'children'));
        }
    }

    public function getTable($id = NULL){
        \Content::sortBySetting('date')->get();
        if(!$id){
            $content = $this->content->with(array('template_setting', 'setting'))->fromApplication()->whereNull('parent_id')->first();
        }
        else{
            $content = $this->content->with(array('template_setting', 'setting'))->findOrFail($id);    
        }
        
        //try remove from baum.
        $children = \Content::with(array('template_setting', 'setting'))->where('parent_id',$content->id);

        if(\Input::get('sort')){
            if(strtolower(\Input::get('direction')) == 'asc'){
                $children = $children->sortBySetting(\Input::get('sort'), 'ASC');
            }
            else{
                $children = $children->sortBySetting(\Input::get('sort'), 'DESC');    
            }
        }

        //$children = $content->children()->sortBySetting(\Input::get('sort'))->with(array('setting', 'template_setting'));

        $children = $children->paginate();
        $childrenSettings = new \Illuminate\Database\Eloquent\Collection;

        foreach($children as $child){
            $childrenSettings[$child->id] = \Contentsetting::collectSettings($child);
        }

        $allPermissions = \Permission::getControllerPermission($id, \Route::currentRouteAction());
        $settings = \Contentsetting::collectSettings($content);

        if (\Request::ajax()) {
            return $this->render('contents.table.index',  compact('content', 'children', 'childrenSettings', 'settings', 'children', 'allPermissions'));
        } else {
            return $this->render('layouts.large',  compact('content', 'children', 'childrenSettings', 'settings', 'allPermissions', 'children'));
        }
    }

    /**
     * Renders an individual setting field.
     * @param  [type] $id   the id of the setting.
     * @param  [type] $type type of setting - template or content setting
     * @param  [type] $content_id the id of the content item
     * @return [type]       [description]
     */
    public function getRenderSetting($id, $content_id, $type='Contentsetting'){
        $setting = $type::findOrFail($id);
        // if($type == 'Contentsetting'){
        //     $setting = $type::where('name',$contentSetting->name)->where('content_id', $contentSetting->content_id)->get();    
        // }
        // else{
        //     $setting = $type::where('name',$contentSetting->name)->where('template_id', $contentSetting->template_id)->get();       
        // }
        // dd($contentSetting->name, $type, $contentSetting->id);
        //$settingGroup[] = $contentSetting;
        $content = $this->content->findOrFail($content_id);

        if(strpos($setting->field_type, '::')){
            return $this->render($setting->field_type, array('setting'=>$setting, 'content'=>$content));
        }
        else{
            return $this->render('contents.input_types.'.$setting->field_type, array('setting'=>$setting, 'content'=>$content));
        }
        

    }

    //upload plugin won't allow us to use deleteUpload since we can't send a csrf token :(
    public function getDeleteUpload($id = '')
    {
        if ($id) {
            $content_setting = \Contentsetting::findOrFail($id);
            $content_setting->delete(); 
            $delete = new \stdClass();
            $fileName = pathinfo($content_setting->value, PATHINFO_FILENAME);

            $delete->{$fileName} = true;
            $return->files[] = $delete;
            return response()->json($return);
        } else {
            return(true);
        }
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
        $setting = $type::withTrashed()->find($id);
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
                        $finalUrl = S3::upload($fileName, $destinationPath.$uploadFolder.'/'.$fileId.'.'.$extension);
                    }

                    //and we need to build the json response.
                    $fileObj = new \stdClass();
                    $fileObj->name = $originalName;
                    $fileObj->id = $id;
                    $fileObj->thumbnailUrl = $finalUrl; //todo
                    $fileObj->deleteUrl = action('\Bootleg\Cms\ContentsController@getDeleteUpload', array($fileName)); //"//".$_SERVER['SERVER_NAME']."/cms/deleteuploads/$fileName"; //todo
                    $fileObj->deleteType = "GET";

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
