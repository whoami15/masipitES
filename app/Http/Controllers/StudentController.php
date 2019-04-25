<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Student\UpdateProfileRequest;
use App\Http\Requests\Student\UpdateProfilePasswordRequest;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\User;
use App\LearningMaterial;
use App\News;
use App\Events;
use Hash;
use DB;

class StudentController extends Controller
{
    public function __construct()
    {   
        $this->middleware('auth');
        $this->middleware('student');
    }

    public function getStudentDashboard(){

        $user = Auth::user();

        $learning_materials = LearningMaterial::with('user','subject_user')->where('grade_level', $user->grade_level)->orderBy('created_at', 'desc')->get();
        $total_learning_materials = LearningMaterial::where('grade_level', $user->grade_level)->where('status', 1)->count();
        $total_learning_materials_today = LearningMaterial::where('grade_level', $user->grade_level)->where('status', 1)->whereDate('created_at', Carbon::today())->count();
        $total_news = News::count();
        $total_news_today = News::whereDate('created_at', Carbon::today())->count();
        $total_events = Events::count();
        $total_events_today = Events::whereDate('created_at', Carbon::today())->count();
        
        return view('student.index')
            ->with('user',$user)
            ->with('learning_materials', $learning_materials)
            ->with('total_learning_materials', $total_learning_materials)
            ->with('total_learning_materials_today', $total_learning_materials_today)
            ->with('total_news', $total_news)
            ->with('total_news_today', $total_news_today)
            ->with('total_events', $total_events)
            ->with('total_events_today', $total_events_today);       
    }

    public function getStudentLearningMaterial(){

        $user = Auth::user();

        return  view('student.elearning.list')->with('user',$user);
    }

    public function getStudentLearningMaterialData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
            $learning_materials = LearningMaterial::with('user','subject_user')->where('grade_level', $user->grade_level)->orderBy('created_at', 'desc');

            if($learning_materials){

                return Datatables::of($learning_materials)
                ->editColumn('name', function ($learning_materials) {
                    return ucwords($learning_materials->user->full_name);
                })
                ->editColumn('title', function ($learning_materials) {
                    return ucwords($learning_materials->title);
                })
                ->editColumn('subject', function ($learning_materials) {
                    return '<h6>'.$learning_materials->subject_user->description.'</h6>';
                })
                ->editColumn('status', function ($learning_materials) {
                    if($learning_materials->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($learning_materials) {
                    if($learning_materials->status == 1){
                        return '<a href="/student/file/'.$learning_materials->uuid.'/download">Download</a>';
                    }else{
                        return '';
                    }
                })
                ->addColumn('id', function ($learning_materials) {

                    return $learning_materials->id;
                })
                ->addColumn('date', function ($learning_materials) {
                    return date('F j, Y g:i a', strtotime($learning_materials->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['name','title','subject','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function downloadLearningMaterial($uuid){

        try{

            $learning_material = LearningMaterial::where('uuid', $uuid)->first();
            if($learning_material){

                $learning_material->increment('downloads');
                $learning_material->save();

                $pathToFile = public_path('mes_learning_materials/' . $learning_material->filename);
                return response()->download($pathToFile);
            }else{

                return redirect('/student/elearning');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"msg"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getStudentProfile(){

        $auth_user = Auth::user();
        $user = User::with('grade_level_user')->find($auth_user->id);

        return view('student.settings.profile.edit')->with('user',$user);
    }

    public function postStudentProfile(UpdateProfileRequest $request){
        
        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $user->gender = $request->gender;
                $user->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
                $user->save();

                return response()->json(array("result"=>true,"message"=> "Profile successfully updated.") ,200);
            }catch(\Exception $e){

                return response()->json(array("result"=>false,"message"=>$e->getMessage()),422);
            }
        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function postStudentProfilePassword(UpdateProfilePasswordRequest $request){

        if($request->wantsJson()) {

            try {

                if (Hash::check($request->current_password ,Auth::user()->password))
                {

                    $user = Auth::user();
                    $user->password = bcrypt($request->password);
                    $user->save();

                    return response()->json(array("result"=>true,"message"=>'Password has been updated.'),200); 
    

                } else {
                    return response()->json(array("result"=>false,"message"=>'Wrong current password.'),422);
                }
                    
            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>$e->getMessage()),422);
            }
                
        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }           
    }

}
