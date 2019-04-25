<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\AddSubjectRequest;
use App\Http\Requests\Admin\EditSubjectRequest;
use App\Http\Requests\Admin\AddGradeLevelRequest;
use App\Http\Requests\Admin\EditGradeLevelRequest;
use App\Http\Requests\Admin\AddDepartmentRequest;
use App\Http\Requests\Admin\EditDepartmentRequest;
use App\Http\Requests\Admin\AddPositionRequest;
use App\Http\Requests\Admin\EditPositionRequest;
use App\Http\Requests\Admin\AddNewsRequest;
use App\Http\Requests\Admin\AddEventsRequest;
use App\Http\Requests\Admin\EditNewsRequest;
use App\Http\Requests\Admin\EditEventsRequest;
use App\Http\Requests\Admin\UpdateProfilePasswordRequest;
use App\Http\Requests\Admin\EditStudentRequest;
use App\Http\Requests\Admin\EditFacultyRequest;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\User;
use App\Announcement;
use App\SecurityKeys;
use App\LearningMaterial;
use App\Subject;
use App\GradeLevel;
use App\Department;
use App\Position;
use App\News;
use App\Events;
use App\PublicMessage;
use App\PrivateMessage;
use App\Contact;
use Hash;
use DB;
use Session;
use Redirect;
use Cache;
use URL;
use DateTime;

class AdminController extends Controller
{
    public function __construct()
    {   
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function getAdminDashboard(){

        $user = Auth::user();
        $pending_users = User::with('grade_level_user')->where('status', 0)->orderBy('created_at','desc')->take(5)->get();
        $total_pending_users = User::where('status', 0)->count();
        $total_teacher = User::where('role', 2)->count();
        $total_teacher_today = User::where('role', 2)->whereDate('created_at', Carbon::today())->count();
        $total_student = User::where('role', 1)->count();
        $total_student_today = User::where('role', 1)->whereDate('created_at', Carbon::today())->count();
        $total_news = News::count();
        $total_news_today = News::whereDate('created_at', Carbon::today())->count();
        $total_events = Events::count();
        $total_events_today = Events::whereDate('created_at', Carbon::today())->count();
        return view('admin.index')
            ->with('user',$user)
            ->with('pending_users',$pending_users)
            ->with('total_pending_users',$total_pending_users)
            ->with('total_teacher', $total_teacher)
            ->with('total_teacher_today', $total_teacher_today)
            ->with('total_student', $total_student)
            ->with('total_student_today', $total_student_today)
            ->with('total_news', $total_news)
            ->with('total_news_today', $total_news_today)
            ->with('total_events', $total_events)
            ->with('total_events_today', $total_events_today);       
    }

    public function postAdminAcceptUser($id, Request $request) {

        if ($request->wantsJson()) {

            try{
                //find user
                $user = User::find($id);

                if($user){

                    $user->status = 1;
                    $user->updated_at = Carbon::now();
                    $user->save();

                    return response()->json(array("result"=>true,"message"=>'User successfully approved.'),200);
                }else{

                    return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }
        }else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function postAdminDeclineUser($id, Request $request) {

        if ($request->wantsJson()) {

            try{
                //find user
                $user = User::find($id);

                if($user){

                    $user->status = 2;
                    $user->updated_at = Carbon::now();
                    $user->save();

                    return response()->json(array("result"=>true,"message"=>'User successfully declined.'),200);
                }else{

                    return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }
        }else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getAdminPendingUsers() {

        $user = Auth::user();

        return view('admin.users.pending')->with('user',$user);
    }

    public function getAdminPendingUsersData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
            $pending_users = User::with('grade_level_user')->where('status', 0)->orderBy('created_at','desc');

            if($pending_users){

                return Datatables::of($pending_users)
                ->editColumn('name', function ($pending_users) {
                    
                    return ucwords($pending_users->full_name);
                    
                })
                ->editColumn('username', function ($pending_users) {
                    return $pending_users->username; 
                })
                ->editColumn('role', function ($pending_users) {
                    if($pending_users->role == 1){
                        return '<mark>Student</mark>';
                    } elseif($pending_users->role == 2) {
                        return '<mark>Faculty</mark>';
                    } else {
                        return '<strong class="text-info">unavailable</strong>';
                    }
                })
                ->editColumn('details', function ($pending_users) {
                    if($pending_users->role == 1){
                        return '<h6 class="mb-2">ID Number: <mark>'. $pending_users->id_no .'</mark></h6>
                        <h6 class="mb-1">Grade Level: <mark>'. $pending_users->grade_level_user->description .'</mark></h6>';
                    } elseif($pending_users->role == 2) {
                        return '<h6 class="mb-2">Position: <mark>'. $pending_users->position .'</mark></h6>
                        <h6 class="mb-1">Department: <mark>'. $pending_users->department .'</mark></h6>';
                    } else {
                        return '<strong class="text-info">unavailable</strong>';
                    }
                })
                ->addColumn('action', function ($pending_users) {
                    if($pending_users->status == 0){
                        return '<small id="processing'.$pending_users->id.'" style="display:none">Processing... <i class="fa fa-spinner fa-spin"></i></small>
                        <button onclick="angular.element(this).scope().frm.accept('.$pending_users->id.')" id="accept_btn'.$pending_users->id.'" class="btn shadow-1 btn-success btn-sm">approve</button>
                        <button onclick="angular.element(this).scope().frm.decline('.$pending_users->id.')" id="decline_btn'.$pending_users->id.'" class="btn shadow-1 btn-danger btn-sm">decline</button>';
                    } else {

                        return '';
                    } 
                })
                ->addColumn('id', function ($pending_users) {

                    return $pending_users->id;
                })
                ->addColumn('date', function ($pending_users) {
                    return date('F j, Y g:i a', strtotime($pending_users->created_at)) . ' | ' . $pending_users->created_at->diffForHumans();
                })
                ->addIndexColumn()
                ->rawColumns(['name','username','role','details','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }

        }else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getAdminListUsers() {

        $user = Auth::user();

        return view('admin.users.list')->with('user',$user);
    }

    public function getAdminListUsersData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();

            if($request->has('sort_by')) {

                $sort = $request->sort_by;
                $order = $request->order_by;
                
                if ($request->has('order_by')) {
                    
                    if($order == 'RECENT'){
                        $users = User::with('grade_level_user')->where('status',$sort)->where('role','!=',0)->orderBy('created_at','desc'); 
                    }else{
                        
                        $users = User::with('grade_level_user')->where('role',$order)->where('status',$sort)->where('role','!=',0)->orderBy('created_at','desc');
                    }
                } else {

                    if($sort == 'RECENT'){
                        $users = User::with('grade_level_user')->where('role','!=',0)->orderBy('created_at','desc');
                    } else {
                        $users = User::with('grade_level_user')->where('status',$sort)->where('role','!=',0)->orderBy('created_at','desc');
                    }
                    
                }                   
            } elseif($request->has('order_by')) {

                $order = $request->order_by;
                $users = User::with('grade_level_user')->where('role',$order)->where('role','!=',0)->orderBy('created_at','desc');
              
            } else {

                $users = User::with('grade_level_user')->where('role','!=',0)->orderBy('created_at','desc');
            }


            if($users){

                return Datatables::of($users)
                ->editColumn('name', function ($users) {
                    
                    return ucwords($users->full_name);
                    
                })
                ->editColumn('username', function ($users) {
                    return $users->username; 
                })
                ->editColumn('role', function ($users) {
                    if($users->role == 1){
                        return '<mark>Student</mark>';
                    } elseif($users->role == 2) {
                        return '<mark>Faculty</mark>';
                    } else {
                        return '<strong class="text-info">unavailable</strong>';
                    }
                })
                ->editColumn('details', function ($users) {
                    $user_status = '';
                    if($users->status == 0){
                        $user_status = '<h6 class="text-warning">Status: Pending</h6>';
                    }elseif($users->status == 1){
                        $user_status = '<h6 class="text-success">Status: Approved</h6>';
                    }elseif($users->status == 2){
                        $user_status = '<h6 class="text-danger">Status: DECLINED</h6>';
                    }
                    if($users->role == 1){
                        return '<h6 class="mb-2">ID Number: <mark>'. $users->id_no .'</mark></h6>
                        <h6 class="mb-1">Grade Level: <mark>'. $users->grade_level_user->description .'</mark></h6>
                        '.$user_status;
                    } elseif($users->role == 2) {
                        return '<h6 class="mb-2">Position: <mark>'. $users->position .'</mark></h6>
                        <h6 class="mb-1">Department: <mark>'. $users->department .'</mark></h6>
                        '.$user_status;
                    } else {
                        return '<strong class="text-info">unavailable</strong>';
                    }
                })
                ->addColumn('action', function ($users) {
                    if($users->role == 1){
                        return '<a href="/admin/user/student/edit/'.$users->id.'">Edit</a>';
                    }else{
                        return '<a href="/admin/user/faculty/edit/'.$users->id.'">Edit</a>';
                    }
                })
                ->addColumn('id', function ($users) {

                    return $users->id;
                })
                ->addColumn('date', function ($users) {
                    return date('F j, Y g:i a', strtotime($users->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['name','username','role','details','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }

        }else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getAdminStudentEdit($id){

        $user = Auth::user();

        $student = User::with('grade_level_user')->where('id', $id)->first();
        $grade_level = GradeLevel::where('status', 1)->get();

        if($student){

            return view('admin.users.edit-student')
                ->with('user', $user)
                ->with('student',$student)
                ->with('grade_level',$grade_level);
        }else{
            
            return redirect('/admin/list');
        }
    }

    public function getAdminFacultyEdit($id){

        $user = Auth::user();

        $faculty = User::with('grade_level_user')->where('id', $id)->first();
        $positions = Position::where('status', 1)->get();
        $departments = Department::where('status', 1)->get();

        if($faculty){

            return view('admin.users.edit-faculty')
                ->with('user', $user)
                ->with('faculty',$faculty)
                ->with('positions',$positions)
                ->with('departments',$departments);
        }else{
            
            return redirect('/admin/list');
        }
    }

    public function postAdminStudentEdit($id, EditStudentRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $user = User::where('id', $id)->first();

                if($user){

                    $user->first_name = $request->first_name;
                    $user->middle_name = $request->middle_name;
                    $user->last_name = $request->last_name;
                    $user->gender = $request->gender;
                    $user->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
                    $user->grade_level = $request->grade_level;
                    $user->save();

                    return response()->json(array("result"=>true,"message"=> "Subject successfully updated.") ,200);
                }else{

                    return redirect('/admin/list');
                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function postAdminFacultyEdit($id, EditFacultyRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $user = User::where('id', $id)->first();

                if($user){

                    $user->first_name = $request->first_name;
                    $user->middle_name = $request->middle_name;
                    $user->last_name = $request->last_name;
                    $user->gender = $request->gender;
                    $user->birth_date = Carbon::parse($request->birth_date)->format('Y-m-d');
                    $user->position = $request->position;
                    $user->department = $request->department;
                    $user->save();

                    return response()->json(array("result"=>true,"message"=> "Subject successfully updated.") ,200);
                }else{

                    return redirect('/admin/list');
                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function getAdminUsersGradeLevel() {

        $user = Auth::user();

        return view('admin.users.grade-level')->with('user',$user);
    }

    public function getAdminUsersGradeLevelData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();

            if($request->has('sort_by')) {

                $sort = $request->sort_by;

                if($sort == 'RECENT'){
                    $grade_level_users = User::with('grade_level_user')->where('role', 1)->where('status', 1)->orderBy('created_at','desc');
                } else {
                    $grade_level_users = User::with('grade_level_user')->where('role', 1)->where('grade_level', $sort)->where('status', 1)->orderBy('created_at','desc');
                }
            } else {

                $grade_level_users = User::with('grade_level_user')->where('role', 1)->where('status', 1)->orderBy('created_at','desc');
            }

            if($grade_level_users){

                return Datatables::of($grade_level_users)
                ->editColumn('name', function ($grade_level_users) {
                    return ucwords($grade_level_users->full_name);
                })
                ->editColumn('username', function ($grade_level_users) {
                    return $grade_level_users->username;
                })
                ->editColumn('id_no', function ($grade_level_users) {
                    if($grade_level_users->role == 1){
                        return '<h6>'.$grade_level_users->id_no.'</h6>';
                    }
                })
                ->editColumn('grade_level', function ($grade_level_users) {
                    return '<h6>'. $grade_level_users->grade_level_user->description.'</h6>';
                })
                ->addColumn('action', function ($grade_level_users) {
                    if($grade_level_users->status == 1){
                        return '<small id="processing'.$grade_level_users->id.'" style="display:none">Processing... <i class="fa fa-spinner fa-spin"></i></small>
                        <select class="form-control form-control" name="grade_level'.$grade_level_users->id.'" id="grade_level'.$grade_level_users->id.'"  onchange="angular.element(this).scope().frm.changeGradeLevel('.$grade_level_users->id.')" >
                            <option selected="selected">Select</option>
                            <option value="1">Grade I</option> 
                            <option value="2">Grade II</option> 
                            <option value="3">Grade III</option>
                            <option value="4">Grade IV</option>
                            <option value="5">Grade V</option>
                            <option value="6">Grade VI</option>
                        </select>';
                    } else {
                        return '';
                    } 
                })
                ->addColumn('id', function ($grade_level_users) {

                    return $grade_level_users->id;
                })
                ->addColumn('date', function ($grade_level_users) {
                    return date('F j, Y g:i a', strtotime($grade_level_users->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['name','username','id_no','grade_level','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }

        }else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function postAdminChangeGradeLevel($id, Request $request) {

        if ($request->wantsJson()) {

                $user = User::find($id);

                if($user){

                    $user->grade_level = $request->grade_level;
                    $user->save();

                    return response()->json(array("result"=>true,"message"=>'Grade Level sucessfully changed.'),200);
                                        
                } else {

                    return response()->json(array("result"=>false,"message"=>'User not found!'),422);
                }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }
    
    public function getAdminAnnouncements(){

        $user = Auth::user();

        return view('admin.announcements.list')->with('user', $user);
    }

    public function getAdminAnnouncementsData(Request $request){
        
        if ($request->wantsJson()) {

            $user = Auth::user();

            $announcement = Announcement::with('user')->orderBy('created_at', 'desc');

            if($announcement){

                return Datatables::of($announcement)
                ->editColumn('name', function ($announcement) {
                    return ucwords($announcement->user->full_name);
                })
                ->editColumn('title', function ($announcement) {
                    return $announcement->title;
                })
                ->editColumn('content', function ($announcement) {
                    return str_limit($announcement->announcement, 15);
                })
                ->editColumn('status', function ($announcement) {
                    if($announcement->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($announcement) {
            
                    return '<a href="/admin/announcements/view/'.$announcement->id.'">View</a>';
                })
                ->addColumn('id', function ($announcement) {

                    return $announcement->id;
                })
                ->addColumn('date', function ($announcement) {
                    return date('F j, Y g:i a', strtotime($announcement->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['name','title','content','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
        
    }

    public function getAdminViewAnnouncement($id){

        $user = Auth::user();

        $announcement = Announcement::with('user')->where('id', $id)->first();

        if($announcement){

            return view('admin.announcements.view')
                ->with('user', $user)
                ->with('announcement',$announcement);
        }else{
            
            return redirect('/admin/announcements');
        }
    }

    public function getAdminCreateAnnouncements() {

        $user = Auth::user();
        
        return view('admin.announcements.create')->with('user',$user);
    }

    public function postAdminCreateAnnouncements(Request $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $announcement = new Announcement();
                $announcement->user_id = $user->id;
                $announcement->title = '';
                $announcement->description = addslashes('');
                $announcement->save();

                return response()->json(array("result"=>true,"message"=> "Announcement successfully created.") ,200);

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function getAdminLearningMaterial(){

        $user = Auth::user();

        return  view('admin.elearning.list')->with('user',$user);
    }

    public function getAdminLearningMaterialData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();

            if($request->has('sort_by')) {

                $sort = $request->sort_by;
                $order = strToLower($request->order_by);
                
                if ($request->has('order_by')) {
                    
                    if($order == 'RECENT'){
                        $learning_materials = LearningMaterial::with('subject_user')->orderBy('file_size', $sort);
                    }else{
                        
                        $learning_materials = LearningMaterial::with('subject_user')->where('file_type',$order)->orderBy('file_size', $sort);
                    }
                } else {

                    if($sort == 'RECENT'){
                        $learning_materials = LearningMaterial::with('subject_user')->orderBy('created_at', 'desc');
                    } else {
                        $learning_materials = LearningMaterial::with('user','subject_user')->orderBy('file_size', $sort);
                    }
                    
                }                   
            } elseif($request->has('order_by')) {

                $order = strToLower($request->order_by);
                $learning_materials = LearningMaterial::with('user','subject_user')->where('file_type',$order)->orderBy('created_at', 'desc');
              
            } else {

                $learning_materials = LearningMaterial::with('user','subject_user')->orderBy('created_at', 'desc');
            }
            
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
                ->editColumn('downloads', function ($learning_materials) {
                    return $learning_materials->downloads;
                })
                ->editColumn('file_size', function ($learning_materials) {
                    return convert_filesize($learning_materials->file_size);
                })
                ->editColumn('file_type', function ($learning_materials) {
                    return strToUpper($learning_materials->file_type);
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
                        return '<a href="/admin/file/'.$learning_materials->uuid.'/download">Download</a>';
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
                ->rawColumns(['name','title','subject','downloads','file_size','file_type','status','action','id','date'])
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

                $pathToFile = public_path('mes_learning_materials/' . $learning_material->filename);
                return response()->download($pathToFile);
            }else{

                return redirect('/admin/files');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getAdminKeys(){

        $user = Auth::user();

        return view('admin.security-keys.list')->with('user', $user);
    }

    public function getAdminKeysData(Request $request){

        if($request->wantsJson()){

            $user = Auth::user();

            $security_keys = SecurityKeys::with('user')->orderBy('created_at', 'asc');

            if($security_keys){

                return Datatables::of($security_keys)
                ->editColumn('key', function ($security_keys) {
                    return $security_keys->key;
                })
                ->editColumn('name', function ($security_keys) {
                    if($security_keys->used_by_user_id == ''){
                        return '';
                    }else{
                        return ucwords($security_keys->user->full_name);
                    }
                })
                ->editColumn('status', function ($security_keys) {
                    return $security_keys->status; 
                })
                ->editColumn('used_at', function ($security_keys) {
                    if($security_keys->used_at == ''){
                        return '';
                    }else{
                        return date('F j, Y g:i a', strtotime($security_keys->used_at));
                    }
                })
                ->addColumn('id', function ($security_keys) {
                    return $security_keys->id;
                })
                ->addColumn('date', function ($security_keys) {
                    return date('F j, Y g:i a', strtotime($security_keys->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['key','name','status','used_at','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function getAdminGenerateKeys() {

        $user = Auth::user();
        
        return view('admin.security-keys.generate')->with('user',$user);
    }

    public function postAdminGenerateKeys(Request $request) {

        if ($request->wantsJson()) {

            for($x = 1; $x <= $request->quantity; $x++){

                $alphabet = 'abc#defgh$ijklrNOPQRSstuvwxyzABCDmnopqEFGH12IJKLM9TUVW%XYZ3456&7890';
                $pass = array();
                $alphaLength = strlen($alphabet) - 1;
                for ($i = 0; $i < 10; $i++) {
                    $n = rand(0, $alphaLength);
                    $pass[] = $alphabet[$n];
                }

                $security_keys = SecurityKeys::where('key', implode($pass))->first();
                if(!$security_keys){
                    $security_keys = new SecurityKeys;
                    $security_keys->key = implode($pass);
                    $security_keys->status = "UNUSED";
                    $security_keys->save();
                    $security_keys = substr(md5(uniqid(mt_rand(), true)) , 0, 10);
                    $security_keys = strToUpper($security_keys);
                }
                
            }

            return response()->json(array("result"=>true,"message"=> "Successfully generated ".$request->quantity." Security Keys.") ,200);

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    // Admin Settings

    public function getAdminSubject() {

        $user = Auth::user();
        
        return view('admin.settings.subject.list')->with('user',$user);
    }

    public function getAdminSubjectData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
            $subjects = Subject::orderBy('created_at', 'desc');

            if($subjects){

                return Datatables::of($subjects)
                ->editColumn('subject', function ($subjects) {
                    return $subjects->subject;
                })
                ->editColumn('description', function ($subjects) {
                    return str_limit($subjects->description, 15);
                })
                ->editColumn('status', function ($subjects) {
                    if($subjects->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($subjects) {
                    if($subjects->status == 1){
                        return '<a href="/admin/settings/subject/edit/'.$subjects->id.'">Edit</a> |
                        <a href="/admin/settings/subject/'.$subjects->id.'/delete">Delete</a>';
                    }else{
                        return '<a href="/admin/settings/subject/'.$subjects->id.'/retrieve">Retrieve</a>';
                    }
                })
                ->addColumn('id', function ($subjects) {

                    return $subjects->id;
                })
                ->addColumn('date', function ($subjects) {
                    return date('F j, Y g:i a', strtotime($subjects->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['subject','description','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getAdminAddSubject() {

        $user = Auth::user();

        $subjects = Subject::get();

        return view('admin.settings.subject.create')
            ->with('user',$user)
            ->with('subjects',$subjects);
    }

    public function postAdminAddSubject(AddSubjectRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = Subject::select('subject')->first();

                if($check){

                    if(strToLower($check->subject) == strToLower($request->subject)){

                        return response()->json(array("result"=>false,"message"=> "Subject already exists."),422);

                    }else{

                        $subject = new Subject();
                        $subject->subject = $request->subject;
                        $subject->description = addslashes($request->description);
                        $subject->save();

                        return response()->json(array("result"=>true,"message"=> "Subject successfully added.") ,200);
                    }

                } else {

                    $subject = new Subject();
                    $subject->subject = $request->subject;
                    $subject->description = addslashes($request->description);
                    $subject->save();

                    return response()->json(array("result"=>true,"message"=> "Subject successfully added.") ,200);
                }
            }catch(\Exception $e){

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
        
    }

    public function getAdminEditSubject($id) {

        $user = Auth::user();
        
        $subjects = Subject::get();
        $getsubject = Subject::find($id);

        if($getsubject){

            return view('admin.settings.subject.edit')
            ->with('user',$user)
            ->with('subjects',$subjects)
            ->with('getsubject',$getsubject);
        } else {

            return redirect('/admin/settings/subjects');
        }
    }

    public function postAdminEditSubject($id, EditSubjectRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = Subject::select('subject')->first();

                if(strToLower($check->subject) == strToLower($request->subject)){

                    return response()->json(array("result"=>false,"message"=> "Subject already exists."),422);

                }else{

                    $subject = Subject::where('id', $id)->first();
                    $subject->subject = $request->subject;
                    $subject->description = addslashes($request->description);
                    $subject->save();

                    return response()->json(array("result"=>true,"message"=> "Subject successfully updated.") ,200);

                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function deleteSubject($id){

        try{

            $subject = Subject::where('id', $id)->first();
            if($subject){

                $subject->status = 0;
                $subject->save();

                return redirect('/admin/settings/subject');
            }else{

                return redirect('/admin/settings/subject');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveSubject($id){

        try{

            $subject = Subject::where('id', $id)->first();
            if($subject){

                $subject->status = 1;
                $subject->save();

                return redirect('/admin/settings/subject');
            }else{

                return redirect('/admin/settings/subject');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getAdminGradeLevel() {

        $user = Auth::user();
        
        return view('admin.settings.grade.list')->with('user',$user);
    }

    public function getAdminGradeLevelData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
            $grade_level = GradeLevel::orderBy('created_at', 'desc');

            if($grade_level){

                return Datatables::of($grade_level)
                ->editColumn('level', function ($grade_level) {
                    return $grade_level->level;
                })
                ->editColumn('description', function ($grade_level) {
                    return str_limit($grade_level->description, 15);
                })
                ->editColumn('status', function ($grade_level) {
                    if($grade_level->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($grade_level) {
                    if($grade_level->status == 1){
                        return '<a href="/admin/settings/grade/edit/'.$grade_level->id.'">Edit</a> |
                        <a href="/admin/settings/grade/'.$grade_level->id.'/delete">Delete</a>';
                    }else{
                        return '<a href="/admin/settings/grade/'.$grade_level->id.'/retrieve">Retrieve</a>';
                    }
                })
                ->addColumn('id', function ($grade_level) {

                    return $grade_level->id;
                })
                ->addColumn('date', function ($grade_level) {
                    return date('F j, Y g:i a', strtotime($grade_level->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['level','description','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getAdminAddGradeLevel() {

        $user = Auth::user();

        $grade_level = GradeLevel::get();

        return view('admin.settings.grade.create')
            ->with('user',$user)
            ->with('grade_level',$grade_level);
    }

    public function postAdminAddGradeLevel(AddGradeLevelRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = GradeLevel::select('level')->first();

                if($check){

                    if($check->level == (int)$request->level){

                        return response()->json(array("result"=>false,"message"=> "Grade Level already exists."),422);

                    }else{

                        $grade_level = new GradeLevel();
                        $grade_level->level = $request->level;
                        $grade_level->description = addslashes($request->description);
                        $grade_level->save();

                        return response()->json(array("result"=>true,"message"=> "Grade Level successfully added.") ,200);
                    }

                } else {

                    $grade_level = new GradeLevel();
                    $grade_level->level = $request->level;
                    $grade_level->description = addslashes($request->description);
                    $grade_level->save();

                    return response()->json(array("result"=>true,"message"=> "Grade Level successfully added.") ,200);
                }
            }catch(\Exception $e){

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
        
    }

    public function getAdminEditGradeLevel($id) {

        $user = Auth::user();
        
        $grade_level = GradeLevel::get();
        $getgrade_level = GradeLevel::find($id);

        if($getgrade_level) {
        
            return view('admin.settings.grade.edit')
                ->with('user',$user)
                ->with('grade_level',$grade_level)
                ->with('getgrade_level',$getgrade_level);

        } else {

            return redirect('/admin/settings/grade-level');
        }
    }

    public function postAdminEditGradeLevel($id, EditGradeLevelRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = GradeLevel::select('level')->first();

                if($check->level == (int)$request->level){

                    return response()->json(array("result"=>false,"message"=> "Grade Level already exists."),422);

                }else{

                    $grade_level = GradeLevel::where('id', $id)->first();
                    $grade_level->level = $request->level;
                    $grade_level->description = addslashes($request->description);
                    $grade_level->save();

                    return response()->json(array("result"=>true,"message"=> "Grade Level successfully updated.") ,200);

                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function deleteGradeLevel($id){

        try{

            $grade_level = GradeLevel::where('id', $id)->first();
            if($grade_level){

                $grade_level->status = 0;
                $grade_level->save();

                return redirect('/admin/settings/grade-level');
            }else{

                return redirect('/admin/settings/grade-level');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveGradeLevel($id){

        try{

            $grade_level = GradeLevel::where('id', $id)->first();
            if($grade_level){

                $grade_level->status = 1;
                $grade_level->save();

                return redirect('/admin/settings/grade-level');
            }else{

                return redirect('/admin/settings/grade-level');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getAdminDepartment() {

        $user = Auth::user();
        
        return view('admin.settings.department.list')->with('user',$user);
    }

    public function getAdminDepartmentData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
            $department = Department::orderBy('created_at', 'desc');

            if($department){

                return Datatables::of($department)
                ->editColumn('department', function ($department) {
                    return $department->department;
                })
                ->editColumn('description', function ($department) {
                    return str_limit($department->description, 15);
                })
                ->editColumn('status', function ($department) {
                    if($department->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($department) {
                    if($department->status == 1){
                        return '<a href="/admin/settings/department/edit/'.$department->id.'">Edit</a> |
                        <a href="/admin/settings/department/'.$department->id.'/delete">Delete</a>';
                    }else{
                        return '<a href="/admin/settings/department/'.$department->id.'/retrieve">Retrieve</a>';
                    }
                })
                ->addColumn('id', function ($department) {

                    return $department->id;
                })
                ->addColumn('date', function ($department) {
                    return date('F j, Y g:i a', strtotime($department->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['department','description','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getAdminAddDepartment() {

        $user = Auth::user();

        $department = Subject::get();

        return view('admin.settings.department.create')
            ->with('user',$user)
            ->with('department',$department);
    }

    public function postAdminAddDepartment(AddDepartmentRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = Department::select('department')->first();

                if($check){

                    if(strToLower($check->department) == strToLower($request->department)){

                        return response()->json(array("result"=>false,"message"=> "Department already exists."),422);

                    }else{

                        $department = new Department();
                        $department->department = $request->department;
                        $department->description = addslashes($request->description);
                        $department->save();

                        return response()->json(array("result"=>true,"message"=> "Department successfully added.") ,200);
                    }

                } else {

                    $department = new Department();
                    $department->department = $request->department;
                    $department->description = addslashes($request->description);
                    $department->save();

                    return response()->json(array("result"=>true,"message"=> "Department successfully added.") ,200);
                }
            }catch(\Exception $e){

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
        
    }

    public function getAdminEditDepartment($id) {

        $user = Auth::user();
        
        $department = Department::get();
        $getdepartment = Department::find($id);

        if($getdepartment) {

            return view('admin.settings.department.edit')
            ->with('user',$user)
            ->with('department',$department)
            ->with('getdepartment',$getdepartment);
        } else {

            return redirect('/admin/settings/department');
        }
        
    }

    public function postAdminEditDepartment($id, EditDepartmentRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = Department::select('department')->first();

                if(strToLower($check->department) == strToLower($request->department)){

                    return response()->json(array("result"=>false,"message"=> "Department already exists."),422);

                }else{

                    $department = Department::where('id', $id)->first();
                    $department->department = $request->department;
                    $department->description = addslashes($request->description);
                    $department->save();

                    return response()->json(array("result"=>true,"message"=> "Department successfully updated.") ,200);

                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function deleteDepartment($id){

        try{

            $department = Department::where('id', $id)->first();
            if($department){

                $department->status = 0;
                $department->save();

                return redirect('/admin/settings/department');
            }else{

                return redirect('/admin/settings/department');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveDepartment($id){

        try{

            $department = Department::where('id', $id)->first();
            if($department){

                $department->status = 1;
                $department->save();

                return redirect('/admin/settings/department');
            }else{

                return redirect('/admin/settings/department');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getAdminPosition() {

        $user = Auth::user();
        
        return view('admin.settings.position.list')->with('user',$user);
    }

    public function getAdminPositionData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
            $position = Position::orderBy('created_at', 'desc');

            if($position){

                return Datatables::of($position)
                ->editColumn('position', function ($position) {
                    return $position->position;
                })
                ->editColumn('description', function ($position) {
                    return str_limit($position->description, 15);
                })
                ->editColumn('status', function ($position) {
                    if($position->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($position) {
                    if($position->status == 1){
                        return '<a href="/admin/settings/position/edit/'.$position->id.'">Edit</a> |
                        <a href="/admin/settings/position/'.$position->id.'/delete">Delete</a>';
                    }else{
                        return '<a href="/admin/settings/position/'.$position->id.'/retrieve">Retrieve</a>';
                    }
                })
                ->addColumn('id', function ($position) {

                    return $position->id;
                })
                ->addColumn('date', function ($position) {
                    return date('F j, Y g:i a', strtotime($position->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['position','description','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getAdminAddPosition() {

        $user = Auth::user();

        $position = Position::get();

        return view('admin.settings.position.create')
            ->with('user',$user)
            ->with('position',$position);
    }

    public function postAdminAddPosition(AddPositionRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = Position::select('position')->first();

                if($check){

                    if(strToLower($check->position) == strToLower($request->position)){

                        return response()->json(array("result"=>false,"message"=> "Position already exists."),422);

                    }else{

                        $position = new Position();
                        $position->position = $request->position;
                        $position->description = addslashes($request->description);
                        $position->save();

                        return response()->json(array("result"=>true,"message"=> "Position successfully added.") ,200);
                    }

                } else {

                    $position = new Position();
                    $position->position = $request->position;
                    $position->description = addslashes($request->description);
                    $position->save();

                    return response()->json(array("result"=>true,"message"=> "Position successfully added.") ,200);
                }
            }catch(\Exception $e){

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
        
    }

    public function getAdminEditPosition($id) {

        $user = Auth::user();
        
        $position = Position::get();
        $getposition = Position::find($id);

        if($getposition){
        
            return view('admin.settings.position.edit')
                ->with('user',$user)
                ->with('position',$position)
                ->with('getposition',$getposition);
        } else {

            return redirect('/admin/settings/position');
        }
    }

    public function postAdminEditPosition($id, EditPositionRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = Position::select('position')->first();

                if(strToLower($check->position) == strToLower($request->position)){

                    return response()->json(array("result"=>false,"message"=> "Position already exists."),422);

                }else{

                    $position = Position::where('id', $id)->first();
                    $position->position = $request->position;
                    $position->description = addslashes($request->description);
                    $position->save();

                    return response()->json(array("result"=>true,"message"=> "Position successfully updated.") ,200);

                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function deletePosition($id){

        try{

            $position = Position::where('id', $id)->first();
            if($position){

                $position->status = 0;
                $position->save();

                return redirect('/admin/settings/position');
            }else{

                return redirect('/admin/settings/position');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrievePosition($id){

        try{

            $position = Position::where('id', $id)->first();
            if($position){

                $position->status = 1;
                $position->save();

                return redirect('/admin/settings/position');
            }else{

                return redirect('/admin/settings/position');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    // News

    public function getAdminNews(){

        $user = Auth::user();

        return view('admin.news.list')->with('user', $user);
    }

    public function getAdminNewsData(Request $request){

        if ($request->wantsJson()) {

            $user = Auth::user();

            $news = News::with('user')->orderBy('created_at', 'desc');

            if($news){

                return Datatables::of($news)
                ->editColumn('name', function ($news) {
                    return ucwords($news->user->full_name);
                })
                ->editColumn('title', function ($news) {
                    return $news->title;
                })
                ->editColumn('content', function ($news) {
                    return str_limit($news->content, 15);
                })
                ->editColumn('status', function ($news) {
                    if($news->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($news) {
                    if($news->user_id == Auth::user()->id){ 
                        if($news->status == 1){
                            return '<a href="/admin/news/'.$news->slug.'">View</a> | <a href="/admin/news/edit/'.$news->id.'">Edit</a> |
                            <a href="/admin/news/'.$news->id.'/delete">Delete</a>';
                        }else{
                            return '<a href="/admin/news/'.$news->id.'/retrieve">Retrieve</a>';
                        }
                    }else{
                        return '<a href="/admin/news/'.$news->slug.'">View</a>';
                    }
                })
                ->addColumn('id', function ($news) {

                    return $news->id;
                })
                ->addColumn('date', function ($news) {
                    return date('F j, Y g:i a', strtotime($news->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['name','title','content','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

        
    }

    public function getAdminCreateNews() {

        $user = Auth::user();
        
        return view('admin.news.create')->with('user',$user);
    }

    public function postAdminCreateNews(AddNewsRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $news = new News();
                $news->user_id = $user->id;
                $news->title = $request->title;
                $news->content = addslashes($request->content);

                if($request->file('photo')) {

                    $photo = $request->file('photo');
                    $photo_name = strtotime("now").'-'.str_replace(' ', '', $photo->getClientOriginalName());
                    $photo->move(public_path('uploads/news'), $photo_name);
    
                    $news->photo = $photo_name;
                }

                $news->save();

                return response()->json(array("result"=>true,"message"=> "News successfully created.") ,200);

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function getAdminEditNews($id) {

        $user = Auth::user();
        $news = News::find($id);

        if($news){
        
            return view('admin.news.edit')->with('user',$user)->with('news',$news);
        } else {

            return redirect('/admin/news');
        }
    }

    public function postAdminEditNews($id, EditNewsRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $news = News::where('user_id',$user->id)->find($id);

                if($news){

                    $news->title = $request->title;
                    $news->content = addslashes($request->content);

                    if($request->file('photo')) {

                        $photo = $request->file('photo');
                        $photo_name = strtotime("now").'-'.str_replace(' ', '', $photo->getClientOriginalName());
                        $photo->move(public_path('uploads/news'), $photo_name);

                        $news->photo = $photo_name;

                    }

                    $news->save();

                    return response()->json(array("result"=>true,"message"=> "News successfully updated.") ,200);
                }else{

                    return response()->json(array("result"=>false,"message"=>'News not found!.'),422);
                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    // Events

    public function getAdminEvents(){

        $user = Auth::user();

        return view('admin.events.list')->with('user', $user);
    }

    public function getAdminEventsData(Request $request){

        if ($request->wantsJson()) {

            $user = Auth::user();

            $events = Events::with('user')->orderBy('created_at', 'desc');

            if($events){

                return Datatables::of($events)
                ->editColumn('name', function ($events) {
                    return ucwords($events->user->full_name);
                })
                ->editColumn('title', function ($events) {
                    return $events->title;
                })
                ->editColumn('content', function ($events) {
                    return str_limit($events->content, 15);
                })
                ->editColumn('status', function ($events) {
                    if($events->status == 1){
                        return '<mark>Active</mark>';
                    }else{
                        return '<mark>Inactive</mark>';
                    }
                })
                ->addColumn('action', function ($events) {
                    if($events->user_id == Auth::user()->id){
                        if($events->status == 1){
                            return '<a href="/admin/events/'.$events->slug.'">View</a> | <a href="/admin/events/edit/'.$events->id.'">Edit</a> |
                            <a href="/admin/events/'.$events->id.'/delete">Delete</a>';
                        }else{
                            return '<a href="/admin/events/'.$events->id.'/retrieve">Retrieve</a>';
                        }
                    }else{
                        return '<a href="/admin/events/'.$events->slug.'">View</a>';
                    }
                })
                ->addColumn('id', function ($events) {

                    return $events->id;
                })
                ->addColumn('date', function ($events) {
                    return date('F j, Y g:i a', strtotime($events->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['name','title','content','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

        
    }

    public function getAdminCreateEvents() {

        $user = Auth::user();
        
        return view('admin.events.create')->with('user',$user);
    }

    public function postAdminCreateEvents(AddEventsRequest $request) {

        if ($request->wantsJson()) {

            try{
                
                $user = Auth::user();

                $events = new Events();
                $events->user_id = $user->id;
                $events->title = $request->title;
                $events->content = addslashes($request->content);
                $events->event_date = Carbon::parse($request->event_date)->format('Y-m-d');
                $events->event_time = Carbon::parse($request->event_time)->format('H:i A');
                $events->event_location = $request->event_location;

                if($request->file('photo')) {

                    $photo = $request->file('photo');
                    $photo_name = strtotime("now").'-'.str_replace(' ', '', $photo->getClientOriginalName());
                    $photo->move(public_path('uploads/events'), $photo_name);
    
                    $events->photo = $photo_name;
                }

                $events->save();

                return response()->json(array("result"=>true,"message"=> "Event successfully created.") ,200);

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>$e->getMessage()),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function getAdminEditEvents($id) {

        $user = Auth::user();
        $events = Events::find($id);

        if($events){
        
            return view('admin.events.edit')->with('user',$user)->with('events',$events);
        } else {

            return redirect('/admin/events');
        }
    }

    public function postAdminEditEvents($id, EditEventsRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $event = Events::where('user_id',$user->id)->find($id);

                if($event){

                    $event->title = $request->title;
                    $event->content = addslashes($request->content);
                    $event->event_date = Carbon::parse($request->event_date)->format('Y-m-d');
                    $event->event_time = $request->event_time;
                    $event->event_location = $request->event_location;

                    if($request->file('photo')) {

                        $photo = $request->file('photo');
                        $photo_name = strtotime("now").'-'.str_replace(' ', '', $photo->getClientOriginalName());
                        $photo->move(public_path('uploads/events'), $photo_name);

                        $event->photo = $photo_name;

                    }

                    $event->save();

                    return response()->json(array("result"=>true,"message"=> "Event successfully updated.") ,200);
                }else{

                    return response()->json(array("result"=>false,"message"=>'Event not found!.'),422);
                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function deleteNews($id){

        try{

            $user = Auth::user();
            $news = News::where('id', $id)->where('user_id',$user->id)->first();

            if($news){

                $news->status = 0;
                $news->save();
                
                return redirect('/admin/news');
            }else{

                return redirect('/admin/news');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function deleteEvent($id){

        try{

            $user = Auth::user();
            $event = Events::where('id', $id)->where('user_id',$user->id)->first();

            if($event){

                $event->status = 0;
                $event->save();
                
                return redirect('/admin/events');
            }else{

                return redirect('/admin/events');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveNews($id){

        try{

            $user = Auth::user();
            $news = News::where('id', $id)->where('user_id',$user->id)->first();

            if($news){

                $news->status = 1;
                $news->save();
                
                return redirect('/admin/news');
            }else{

                return redirect('/admin/news');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveEvent($id){

        try{

            $user = Auth::user();
            $event = Events::where('id', $id)->where('user_id',$user->id)->first();

            if($event){

                $event->status = 1;
                $event->save();
                
                return redirect('/admin/events');
            }else{

                return redirect('/admin/events');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getPublicMessages(){

        $user = Auth::user();

        return view('admin.messages.public')->with('user',$user);
    }
    
    public function getPrivateMessages(){

        $user = Auth::user();

        return view('admin.messages.private')->with('user',$user);
    }

    public function getPublicMessagesData(Request $request){

        if($request->wantsJson()){

            $messages = PublicMessage::with('user')->whereDate('created_at',Carbon::today())->orderBy('created_at', 'asc')->get();

            return response()->json(array("result"=>true,"messages"=>$messages),200);

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function postPublicMessageSend(Request $request){

        try {

            $user = Auth::user();

            $message = new PublicMessage();
            $message->user_id = $user->id;
            $message->content = $request->message;
            $message->save();

            return response()->json(array("result"=>true,"message"=>'Sent!'),200);
        }catch(\Exception $e){

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getAdminProfile(){

        $user = Auth::user();

        return view('admin.settings.profile.edit')->with('user',$user);
    }

    public function postAdminProfilePassword(UpdateProfilePasswordRequest $request){

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
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
                
        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }           
    }

    public function getAdminContact(){

        $user = Auth::user();

        return view('admin.contact.list')->with('user', $user);
    }

    public function getAdminContactData(Request $request){
        
        if ($request->wantsJson()) {

            $user = Auth::user();

            $contact = Contact::orderBy('created_at', 'desc');

            if($contact){

                return Datatables::of($contact)
                ->editColumn('name', function ($contact) {
                    return ucwords($contact->name);
                })
                ->editColumn('subject', function ($contact) {
                    return $contact->subject;
                })
                ->editColumn('content', function ($contact) {
                    return str_limit($contact->content, 15);
                })
                ->addColumn('action', function ($contact) {
            
                    return '<a href="/admin/contact/view/'.$contact->id.'">View</a>';
                })
                ->addColumn('id', function ($contact) {

                    return $contact->id;
                })
                ->addColumn('date', function ($contact) {
                    return date('F j, Y g:i a', strtotime($contact->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['name','subject','content','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
        
    }

    public function getAdminViewContact($id){

        $user = Auth::user();

        $contact = Contact::where('id', $id)->first();

        if($contact){

            return view('admin.contact.view')
                ->with('user', $user)
                ->with('contact',$contact);
        }else{
            
            return redirect('/admin/contact');
        }
    }

}
