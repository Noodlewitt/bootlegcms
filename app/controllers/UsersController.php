<?php

class UsersController extends CMSController
{

    /**
     * User Repository
     *
     * @var User
     */
    protected $user;
    
    
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
        $this->beforeFilter('csrf', array('on'=>'post'));

        //add in some standard dash items..
        \Event::listen('dashboard.items', function(){
            $application = Application::getApplication();
            $user = Auth::user();
            return \View::make($application->cms_package.'::users.dash_item', array('user'=>$user))->render();
        });

        \Event::listen('dashboard.items', function(){
            $application = Application::getApplication();
            return \View::make($application->cms_package.'::application.dash_item', array('application'=>$application))->render();
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function anyIndex()
    {
        $users = $this->user->all();
        
        if (Request::ajax()) {
            $cont = View::make($this->application->cms_package.'::users.index', compact('cont', 'users')) ;
            return($cont);
        } else {
            $cont = View::make($this->application->cms_package.'::users.index', compact('cont', 'users'));
            $layout = View::make('cms::layouts.master', compact('cont'));
        }
        return($layout);
    }
    
    public function anyLocale()
    {
        dd(App::getLocale());
    }

    public function anyLogin()
    {
        //dd(array('email'=>Input::get('email'), 'password'=>Input::get('password')));
        //var_dump(Hash::make('admin'));
        if (Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))) {
            //and we need to update last logged in datetime
            $user = User::find(Auth::user()->id);

            $user->last_loggedin_at = $user->loggedin_at;
            $user->loggedin_at = date("Y-m-d H:i:s");
            $user->save();

            Session::flash('success', 'You are now logged in!');
            return Redirect::intended(action('UsersController@anyDashboard'));
        }
        else if(Input::get('email') && Input::get('password')){
            Session::flash('danger', 'Authentication Failed!');
        }


        if (Request::ajax()) {
            $cont = View::make($this->application->cms_package.'::users.login');
            return($cont);
        } else {
            $cont = View::make($this->application->cms_package.'::users.login');
            $layout = View::make('cms::layouts.bare', compact('cont'));
        }
        return($layout);
    }
    
    
    public function anyLogout()
    {
        Auth::logout();
        return Redirect::action('UsersController@anyLogin')->with('warning', 'You are now logged out!');
    }
    
    
    public function anyDashboard()
    {
        
        if (Request::ajax()) {
            $cont = View::make($this->application->cms_package.'::users.dashboard', compact('cont'));
            return($cont);
        } else {
            $cont = View::make($this->application->cms_package.'::users.dashboard', compact('cont'));
            $layout = View::make('cms::layouts.master', compact('cont'));
        }
        return($layout);
    }
    
    public function anySettings()
    {
        if (Request::ajax()) {
            $cont = View::make($this->application->cms_package.'::users.dashboard', compact('cont'));
            return($cont);
        } else {
            $cont = View::make($this->application->cms_package.'::users.dashboard', compact('cont'));
            $layout = View::make('cms::layouts.master', compact('cont'));
        }
        return($layout);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function anyCreate()
    {
        $roles = Role::lists('name', 'id');
        if (Request::ajax()) {
            $cont = View::make($this->application->cms_package.'::users.create', compact('cont', 'roles'));
            return($cont);
        } else {
            $cont = View::make($this->application->cms_package.'::users.create', compact('cont', 'roles'));
            $layout = View::make('cms::layouts.master', compact('cont'));
        }
        return($layout);
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
        $application = Application::getApplication();

        if ($validation->passes()) {
            if($input['password']){
                $input['password'] = Hash::make($input['password']);
            }
            $user = $this->user->create($input);
            if($input['send_email']){
                //we need to send an email to the user with details for login and reset pw etc.
                
                Mail::send('emails.auth.new_user', $data, function($message) use($application, $user){
                    $message->from($application->getSetting('Admin Email'), $application->name);
                    $message->to($user->email);
                });

            }
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

        if (is_null($user)) {
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

        if ($validation->passes()) {
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
