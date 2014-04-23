<?php

class UsersController extends CMSController {

    /**
     * User Repository
     *
     * @var User
     */
    protected $user;
    
    
    public function __construct(User $user){
        parent::__construct();
        $this->user = $user;
        $this->beforeFilter('csrf', array('on'=>'post'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function anyIndex()
    {
        $users = $this->user->all();
        return $this->ajaxRender(View::make($this->application->cms_package.'::users.index', compact('users')));
    }
    
    
    public function anyLogin(){
        //dd(array('email'=>Input::get('email'), 'password'=>Input::get('password')));
        if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))) {
            return Redirect::action('UsersController@anyDashboard')->with('success', 'You are now logged in!');
        }
        
        return($this->ajaxRender(View::make($this->application->cms_package.'::users.login')));
    }
    
    
    public function anyLogout(){
        Auth::logout();
        return Redirect::action('UsersController@anyLogin')->with('message', 'You are now logged out!');
    }
    
    
    public function anyDashboard(){
        return $this->ajaxRender(View::make($this->application->cms_package.'::users.dashboard'));
    }
    
    public function anySettings(){
        return $this->ajaxRender(View::make($this->application->cms_package.'::users.dashboard'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function anyCreate()
    {
        return View::make($this->application->cms_package.'::users.create');
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function anyStore()
    {
        $input = Input::all();
        $validation = Validator::make($input, User::$rules);

        if ($validation->passes())
        {
            $this->user->create($input);
            return Redirect::action('UsersController@anyCreate');
        }

        return Redirect::action('UsersController@anyCreate')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->user->findOrFail($id);

        return View::make($this->application->cms_package.'::users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->user->find($id);

        if (is_null($user))
        {
            return Redirect::route('users.index');
        }

        return View::make($this->application->cms_package.'::users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $input = array_except(Input::all(), '_method');
        $validation = Validator::make($this->application->cms_package.'::'.$input, User::$rules);

        if ($validation->passes())
        {
            $user = $this->user->find($id);
            $user->update($input);

            return Redirect::route('users.show', $id);
        }

        return Redirect::route('users.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->user->find($id)->delete();

        return Redirect::route('users.index');
    }

}