<?php

namespace Bootleg\Cms;

use Auth;
use Input;
use Session;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\Request;

class UsersController extends CMSController
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The password broker implementation.
     *
     * @var PasswordBroker
     */
    protected $passwords;

//use \Illuminate\Routing\Controller;


    /**
     * User Repository
     *
     * @var User
     */
    protected $user;
    
    
    public function __construct(User $user, Guard $auth, PasswordBroker $passwords)
    {
        parent::__construct();

        $this->user = $user;
        $this->auth = $auth;
        $this->passwords = $passwords;
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

    public function anyLogin()
    {
        //dd(array('email'=>Input::get('email'), 'password'=>Input::get('password')));
        //var_dump(Hash::make('admin'));
        if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')], Input::get('remember'))) {
            //and we need to update last logged in datetime
            //$user = User::find(Auth::user()->id);

            Session::flash('success', 'You are now logged in!');
            return redirect()->action('\Bootleg\Cms\UsersController@anyDashboard');
        }
        else if(Input::get('email') && Input::get('password')){
            Session::flash('danger', 'Incorrect email or password');
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



    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
    public function getForgotPassword()
    {
        return $this->render('users.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  Request  $request
     * @return Response
     */
     //23ave@chempro.com.au
    public function postForgotPassword(Request $request)
    {
        try {
            $this->validate($request, ['email' => 'required|email']);
        } catch(\Exception $e) {
            return redirect()->back()->with('danger', 'Invalid email address');
        }

        $this->passwords->setEmailView('pharmacy-cms::emails.password-reset');

        $response = $this->passwords->sendResetLink($request->only('email'), function($m)
        {
            $m->subject('Password reset request');
        });

        switch ($response)
        {
            case PasswordBroker::RESET_LINK_SENT:
                return redirect()->back()->with('message', trans($response));

            case PasswordBroker::INVALID_USER:
                return redirect()->back()->with('danger', 'User not found');
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getResetPassword($token = null)
    {
        if (is_null($token)) throw new NotFoundHttpException;

        return $this->render('users.reset-password')->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = $this->passwords->reset($credentials, function($user, $password)
        {
            $user->password = bcrypt($password);

            $user->save();

            $this->auth->login($user);
        });

        switch ($response)
        {
            case PasswordBroker::PASSWORD_RESET:
                return redirect()->action('\Bootleg\Cms\UsersController@anyDashboard')->with(['success' => 'Password reset successfully.']);

            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }
}
