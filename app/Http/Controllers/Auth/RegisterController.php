<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\SecurityKeys;
use App\GradeLevel;
use App\Position;
use App\Department;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\RegisterRequest;
use Carbon\Carbon;
use DB;
use Redirect;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    function getRegister()
    {
        if (Auth::check()) {
            
            $role = Auth::user()->role;
            $status = Auth::user()->status;

            if($role == 0) {
                
                return redirect('/admin');

            } elseif($role == 1 && $status == 1) {

                return redirect('/student');

            } elseif($role == 2 && $status == 1) {

                return redirect('/faculty');

            } else {

                Auth::logout();
                Session::flush();
                return redirect('/login');

            }

        } else {

            $grade_level = GradeLevel::where('status',1)->get();
            $position = Position::where('status',1)->get();
            $department = Department::where('status',1)->get();
            
            return view('auth.register')
                ->with('grade_level', $grade_level)
                ->with('position', $position)
                ->with('department', $department);
        }
    }

    public function postRegister(RegisterRequest $request){
        if (Auth::check()) {

            $role = Auth::user()->role;
            $status = Auth::user()->status;

            if($role == 0) {
                
                return redirect('/admin');

            } elseif($role == 1 && $status == 1) {

                return redirect('/student');

            } elseif($role == 2 && $status == 1) {

                return redirect('/faculty');

            } else {

                Auth::logout();
                Session::flush();
                return redirect('/login');

            }
        }
   
        try {

            $user_role = $request->role;

            if($user_role == 1 || $user_role == "1") {

                $user = new User;
                $user->email = $request->email;
                $user->username = $request->username;
                $user->password = bcrypt($request->password);
                $user->first_name = $request->first_name;
                $user->middle_name = $request->middle_name;
                $user->last_name = $request->last_name;
                $user->role = $user_role;
                $user->id_no = $request->id_no;
                $user->grade_level = $request->grade_level;
                $user->save();

                Session::flash('success', "Successfully registered.");
                return redirect('/login');
            } elseif($user_role == 2 || $user_role == "2") {

                $security_key = SecurityKeys::where('key', $request->security_key)->first();

                if($security_key) {

                    if($security_key->status == "UNUSED") {

                        $user = new User;
                        $user->email = $request->email;
                        $user->username = $request->username;
                        $user->password = bcrypt($request->password);
                        $user->first_name = $request->first_name;
                        $user->middle_name = $request->middle_name;
                        $user->last_name = $request->last_name;
                        $user->role = $user_role;
                        $user->position = $request->position;
                        $user->department = $request->department;
                        $user->save();

                        $security_key->used_by_user_id = $user->id;
                        $security_key->status = "USED";
                        $security_key->used_at = Carbon::now();
                        $security_key->save();
                        
                        
                        Session::flash('success', "Successfully registered.");
                        return redirect('/login');
                    } elseif($security_key->status == "USED") {

                        Session::flash('danger', "Security Key was already USED");
                        return Redirect::back();
                    }

                } else {

                    Session::flash('danger', "Security Key was not found.");
                    return Redirect::back();
                }

            } else {

                Session::flash('danger', "Something went wrong. Please try again.");
                return Redirect::back();
            }
              
        }catch(\Exception $e){

            Session::flash('danger', $e->getMessage());
            return Redirect::back();
        }

   }
}
