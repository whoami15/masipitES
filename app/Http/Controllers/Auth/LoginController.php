<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Lang;
use DB;
use Session;
use Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public $decayMinutes = 60; // minutes to lockout
    public $maxAttempts = 5; // number of attempts before lockout

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function getLogin()
    {   
        if (Auth::check()) {
            
            $role = Auth::user()->role;
            $status = Auth::user()->status;

            if($role == 0) {
                
                return redirect('/admin');

            } elseif ($role == 1 && $status == 1) {

                return redirect('/student');   

            } elseif ($role == 2 && $status == 1) {

                return redirect('/faculty');   

            } else {

                Auth::logout();
                Session::flush();
                return redirect('/login'); 
            }

        } else {

            return view('auth.login');
        }
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);

        return response()->json(array("result"=>false,"msg"=> $message),423);

    }

    public function postLogin(LoginRequest $request)
    {

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
          $this->fireLockoutEvent($request);

          return $this->sendLockoutResponse($request);
        }
        
        $userdata = array(
            'username'  => $request->username,
            'password'  => $request->password
        );
        
        //return $userdata;
        // attempt to do the login
        if (Auth::attempt($userdata)) {

                $user = Auth::user();

                //check first if user is not banned

                if($user->status == 0){

                    Auth::logout();
                    Session::flush();
                    Session::flash('danger', "Account not yet accepted by the Administrator.");

                    return redirect('/login');

                } else {

                    $this->clearLoginAttempts($request);

                    
                    if($user->role == 0){
                    
                        $user->last_login_at = Carbon::now();
                        $user->save();

                        return redirect('/admin');

                    } elseif($user->role == 1){
                    
                        $user->last_login_at = Carbon::now();
                        $user->save();

                        return redirect('/student');

                    } elseif($user->role == 2){

                        $user->last_login_at = Carbon::now();
                        $user->save();

                        return redirect('/faculty');

                    }
                }
                
        } else {

            $this->incrementLoginAttempts($request);

            Session::flash('danger', "You have entered an invalid username or password");
        
            return Redirect::back();
            
        }
        
    }

    public function logout() {
        if (Auth::user()){
            Auth::logout();
            Session::flush();
            return redirect('/login');
        } else {
            return redirect('/login');  
        }
    }


}
