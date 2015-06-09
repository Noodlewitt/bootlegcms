<?php namespace Bootleg\Cms; 
use Auth;
use Illuminate\Routing\Controller;
class UsersController extends CMSController
{



//use \Illuminate\Routing\Controller;


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
            $user = \Auth::user();
            return $this->render('users.dash_item', array('user'=>$user));
        });

        //TODO: move this into applications
        \Event::listen('dashboard.items', function(){
            return $this->render('application.dash_item', array('application'=>$this->application));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function anyIndex(){
        $users = $this->user->paginate();
        return $this->render('users.index', compact('users')) ;
        
    }
    
    public function anyLocale()
    {
        dd(App::getLocale());
    }

    public function anyLogin()
    {
        //dd(array('email'=>Input::get('email'), 'password'=>Input::get('password')));
        //var_dump(Hash::make('admin'));
        if (\Auth::attempt(array('email'=>\Input::get('email'), 'password'=>\Input::get('password')))) {
            //and we need to update last logged in datetime
            $user = User::find(Auth::user()->id);

            $user->last_loggedin_at = $user->loggedin_at;
            $user->loggedin_at = date("Y-m-d H:i:s");
            $user->save();

            \Session::flash('success', 'You are now logged in!');
            return redirect()->intended(action('\Bootleg\Cms\UsersController@anyDashboard'));
        }
        else if(\Input::get('email') && \Input::get('password')){
            \Session::flash('danger', 'Authentication Failed!');
        }  

        return $this->render('users.login');

        //return View::make($this->application->cms_package.'::users.login');
           
    }
    
    
    public function anyLogout()
    {
        Auth::logout();
        return redirect()->action('\Bootleg\Cms\UsersController@anyLogin')->with('warning', 'You are now logged out!');
    }
    
    
    public function anyDashboard(){
        return $this->render('users.dashboard');
    }
    
    public function anySettings()
    {
        return $this->render('users.dashboard');
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function anyCreate()
    {
        $roles = Role::lists('name', 'id');
        return $this->render('users.create', compact('cont', 'roles'));
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

        if ($validation->passes()) {
            if($input['password']){
                $input['password'] = Hash::make($input['password']);
            }
            $user = $this->user->create($input);
            if($input['send_email']){
                //we need to send an email to the user with details for login and reset pw etc.
                $application = $this->application;
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

        return $this->render('users.show', compact('user'));
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

        return $this->render('users.edit', compact('user'));
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
