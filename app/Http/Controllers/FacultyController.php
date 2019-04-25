<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Faculty\LearningMaterialRequest;
use App\Http\Requests\Faculty\LearningMaterialEditRequest;
use App\Http\Requests\Faculty\AddClassRequest;
use App\Http\Requests\Faculty\AnnouncementRequest;
use App\Http\Requests\Faculty\EditClassRequest;
use App\Http\Requests\Faculty\EditAnnouncementRequest;
use App\Http\Requests\Faculty\UpdateProfileRequest;
use App\Http\Requests\Faculty\UpdateProfilePasswordRequest;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\User;
use App\Subject;
use App\GradeLevel;
use App\SchoolYear;
use App\LearningMaterial;
use App\Announcement;
use App\TeacherClass;
use App\PublicMessage;
use Hash;
use DB;
use Session;
use Redirect;
use Cache;
use URL;
use DateTime;

class FacultyController extends Controller
{
    public function __construct()
    {   
        $this->middleware('auth');
        $this->middleware('faculty');
    }

    public function getFacultyDashboard(){

        $user = Auth::user();
        $total_announcements = Announcement::where('user_id', $user->id)->where('status', 1)->count();
        $total_announcements_today = Announcement::where('user_id', $user->id)->where('status', 1)->whereDate('created_at', Carbon::today())->count();
        $total_learning_materials = LearningMaterial::where('status', 1)->count();
        $total_learning_materials_today = LearningMaterial::where('status', 1)->whereDate('created_at', Carbon::today())->count();
        $total_message = PublicMessage::count();
        $total_message_today = PublicMessage::whereDate('created_at', Carbon::today())->count();
        $announcements = Announcement::with('user')->where('status', 1)->orderBy('created_at', 'desc')->take(5)->get();
        return  view('faculty.index')
            ->with('user',$user)
            ->with('total_announcements',$total_announcements)
            ->with('total_announcements_today', $total_announcements_today)
            ->with('total_learning_materials', $total_learning_materials)
            ->with('total_learning_materials_today', $total_learning_materials_today)
            ->with('total_message', $total_message)
            ->with('total_message_today', $total_message_today)
            ->with('announcements', $announcements);
    }

    public function getFacultyLearningMaterial(){

        $user = Auth::user();

        return  view('faculty.elearning.list')->with('user',$user);
    }

    public function getFacultyLearningMaterialData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();

            if($request->has('sort_by')) {

                $sort = $request->sort_by;
                $order = strToLower($request->order_by);
                
                if ($request->has('order_by')) {
                    
                    if($order == 'RECENT'){
                        $learning_materials = LearningMaterial::with('subject_user','grade_level_user')->orderBy('file_size', $sort);
                    }else{
                        
                        $learning_materials = LearningMaterial::with('subject_user','grade_level_user')->where('file_type',$order)->orderBy('file_size', $sort);
                    }
                } else {

                    if($sort == 'RECENT'){
                        $learning_materials = LearningMaterial::with('subject_user','grade_level_user')->orderBy('created_at', 'desc');
                    } else {
                        $learning_materials = LearningMaterial::with('subject_user','grade_level_user')->orderBy('file_size', $sort);
                    }
                    
                }                   
            } elseif($request->has('order_by')) {

                $order = strToLower($request->order_by);
                $learning_materials = LearningMaterial::with('subject_user','grade_level_user')->where('file_type',$order)->orderBy('created_at', 'desc');
              
            } else {

                $learning_materials = LearningMaterial::with('subject_user','grade_level_user')->orderBy('created_at', 'desc');
            }

            if($learning_materials){

                return Datatables::of($learning_materials)
                ->editColumn('title', function ($learning_materials) {
                    return ucwords($learning_materials->title);
                })
                ->editColumn('subject', function ($learning_materials) {
                    return $learning_materials->subject_user->description;
                })
                ->editColumn('grade_level', function ($learning_materials) {
                    return $learning_materials->grade_level_user->description;
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
                    if($learning_materials->user_id == Auth::user()->id){
                        if($learning_materials->status == 1){
                            return '<a href="/faculty/file/'.$learning_materials->uuid.'/download">Download</a> |
                            <a href="/faculty/elearning/edit/'.$learning_materials->id.'">Edit</a> |
                            <a href="/faculty/file/'.$learning_materials->uuid.'/delete">Delete</a>';
                        }else{
                            return '<a href="/faculty/file/'.$learning_materials->uuid.'/retrieve">Retrieve</a>';
                        }
                    }else{
                        return '<a href="/faculty/file/'.$learning_materials->uuid.'/download">Download</a>';
                    }
                    
                })
                ->addColumn('id', function ($learning_materials) {

                    return $learning_materials->id;
                })
                ->addColumn('date', function ($learning_materials) {
                    return date('F j, Y g:i a', strtotime($learning_materials->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['title','subject','grade_level','downloads','file_size','file_type','status','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getFacultyUploadLearningMaterial(){

        $user = Auth::user();

        $subjects = TeacherClass::with('subject_user')->select('subject')->where('user_id',$user->id)->where('status',1)->distinct()->get();
        $grade_level = TeacherClass::with('grade_level_user')->select('grade_level')->where('user_id',$user->id)->where('status',1)->distinct()->get();
        $teacher_class = TeacherClass::where('user_id', $user->id)->where('status',1)->count();

        return  view('faculty.elearning.create')
            ->with('user',$user)
            ->with('subjects',$subjects)
            ->with('grade_level',$grade_level)
            ->with('teacher_class',$teacher_class);
    }

    public function postFacultyUploadLearningMaterial(LearningMaterialRequest $request){

        try{
            
            $user = Auth::user();

            $learning_material = new LearningMaterial;
            $learning_material->user_id = $user->id;
            $learning_material->uuid = (string) Str::uuid();
            $learning_material->title = $request->title;
            $learning_material->subject = $request->subject;
            $learning_material->grade_level = $request->grade_level;

            if($request->file('doc_file')) {

                $doc_file = $request->file('doc_file');
                $file_name = $doc_file->getClientOriginalName();
                $doc_file->move(public_path('mes_learning_materials'), $file_name);

                $learning_material->filename = $file_name;
            } else {

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }

            $learning_material->description = addslashes($request->description);
            $learning_material->file_size = $request->file('doc_file')->getSize();
            $learning_material->file_type = $request->file('doc_file')->getClientOriginalExtension();
            $learning_material->save();

            return response()->json(array("result"=>true,"message"=> "Successfully Uploaded.") ,200);

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getFacultyEditLearningMaterial($id){

        $user = Auth::user();

        $subjects = TeacherClass::with('subject_user')->select('subject')->where('user_id',$user->id)->where('status',1)->distinct()->get();
        $grade_level = TeacherClass::with('grade_level_user')->select('grade_level')->where('user_id',$user->id)->where('status',1)->distinct()->get();
        $teacher_class = TeacherClass::where('user_id', $user->id)->where('status',1)->count();
        
        $learning_material = LearningMaterial::where('id', $id)->first();

        if($learning_material) {

            return view('faculty.elearning.edit')
                ->with('user',$user)
                ->with('subjects',$subjects)
                ->with('grade_level',$grade_level)
                ->with('teacher_class',$teacher_class)
                ->with('learning_material',$learning_material);

        } else {

            return redirect('/faculty/elearning');
        }
    }

    public function postFacultyEditLearningMaterial($id, LearningMaterialEditRequest $request){

        try{
            
            $user = Auth::user();
            $learning_material = LearningMaterial::where('id', $id)->where('user_id',$user->id)->first();

            if($learning_material){

                $learning_material->title = $request->title;
                $learning_material->subject = $request->subject;
                $learning_material->grade_level = $request->grade_level;

                if($request->file('doc_file')) {

                    $file_extension = $request->file('doc_file')->getClientOriginalExtension();

                    $extensions_list = array('doc','docx','xls','xlsx','ppt','pptx','pdf');

                    if(in_array($file_extension, $extensions_list)){

                        $doc_file = $request->file('doc_file');
                        $file_name = $doc_file->getClientOriginalName();
                        $doc_file->move(public_path('mes_learning_materials'), $file_name);

                        $learning_material->filename = $file_name;
                        $learning_material->file_size = $request->file('doc_file')->getSize();
                        $learning_material->file_type = $request->file('doc_file')->getClientOriginalExtension();

                    } else {

                        return response()->json(array("result"=>false,"message"=>'Invalid or not supported file.'),422);
                    }
                }

                $learning_material->description = addslashes($request->description);
                $learning_material->save();

                return response()->json(array("result"=>true,"message"=> "Successfully Updated.") ,200);

            } else {

                return response()->json(array("result"=>false,"message"=>'Not found or learning material does not belong to you.'),422);
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
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

                return redirect('/faculty/elearning');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function deleteLearningMaterial($uuid){

        try{

            $user = Auth::user();
            $learning_material = LearningMaterial::where('uuid', $uuid)->where('user_id',$user->id)->first();

            if($learning_material){

                $learning_material->status = 0;
                $learning_material->save();

                return redirect('/faculty/elearning');
            }else{

                return redirect('/faculty/elearning');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveLearningMaterial($uuid){

        try{

            $user = Auth::user();
            $learning_material = LearningMaterial::where('uuid', $uuid)->where('user_id',$user->id)->first();

            if($learning_material){

                $learning_material->status = 1;
                $learning_material->save();

                return redirect('/faculty/elearning');
            }else{

                return redirect('/faculty/elearning');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getFacultyAnnouncements(){

        $user = Auth::user();

        return view('faculty.announcements.list')->with('user', $user);
    }

    public function getFacultyAnnouncementsData(Request $request){

        if ($request->wantsJson()) {

            $user = Auth::user();

            $announcement = Announcement::with('user')->orderBy('created_at', 'desc');

            if($announcement){

                return Datatables::of($announcement)
                ->editColumn('name', function ($announcement) {
                    if($announcement->user_id == Auth::user()->id){
                        return 'You';
                    }
                    else{
                        return ucwords($announcement->user->full_name);
                    }
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
                    if($announcement->user_id == Auth::user()->id){
                        if($announcement->status == 1){
                            return '<a href="/faculty/announcements/view/'.$announcement->id.'">View</a> | <a href="/faculty/announcements/edit/'.$announcement->id.'">Edit</a> |
                            <a href="/faculty/announcements/'.$announcement->id.'/delete">Delete</a>';
                        }else{
                            return '<a href="/faculty/announcements/'.$announcement->id.'/retrieve">Retrieve</a>';
                        }
                    }
                    else{
                        return '<a href="/faculty/announcements/view/'.$announcement->id.'">View</a>';
                    }
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

    public function getFacultyCreateAnnouncements() {

        $user = Auth::user();
        
        return view('faculty.announcements.create')->with('user',$user);
    }

    public function postFacultyCreateAnnouncements(AnnouncementRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $announcement = new Announcement();
                $announcement->user_id = $user->id;
                $announcement->title = $request->title;
                $announcement->announcement = addslashes($request->announcement);
                $announcement->save();

                return response()->json(array("result"=>true,"message"=> "Announcement successfully created.") ,200);

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function getFacultyEditAnnouncements($id) {

        $user = Auth::user();
        $announcement = Announcement::find($id);

        if($announcement){
        
            return view('faculty.announcements.edit')->with('user',$user)->with('announcement',$announcement);
        } else {

            return redirect('/faculty/announcements');
        }

    }

    public function postFacultyEditAnnouncements($id, EditAnnouncementRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();
                $announcement = Announcement::where('id', $id)->where('user_id',$user->id)->first();

                if($announcement){
                    $announcement->title = $request->title;
                    $announcement->announcement = addslashes($request->announcement);
                    $announcement->save();

                    return response()->json(array("result"=>true,"message"=> "Announcement successfully updated.") ,200);
                }else{

                    return response()->json(array("result"=>false,"message"=>'Not found or announcement does not belong to you.'),422);
                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function getFacultyViewAnnouncement($id){

        $user = Auth::user();

        $announcement = Announcement::with('user')->where('id', $id)->first();

        if($announcement){

            return view('faculty.announcements.view')
                ->with('user', $user)
                ->with('announcement',$announcement);
        }else{
            
            return redirect('/faculty/announcements');
        }
    }

    public function deleteAnnouncement($id){

        try{

            $user = Auth::user();
            $announcement = Announcement::where('id', $id)->where('user_id',$user->id)->first();

            if($announcement){

                $announcement->status = 0;
                $announcement->save();
                
                return redirect('/faculty/announcements');
            }else{

                return redirect('/faculty/announcements');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveAnnouncement($id){

        try{

            $user = Auth::user();
            $announcement = Announcement::where('id', $id)->where('user_id',$user->id)->first();

            if($announcement){

                $announcement->status = 1;
                $announcement->save();

                return redirect('/faculty/announcements');
            }else{

                return redirect('/faculty/announcements');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getFacultyClass() {

        $user = Auth::user();
        
        return view('faculty.class.list')->with('user',$user);
    }

    public function getFacultyClassData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
            $teacher_class = TeacherClass::with('user','subject_user','grade_level_user')->where('user_id', $user->id)->orderBy('created_at', 'desc');

            if($teacher_class){

                return Datatables::of($teacher_class)
                ->editColumn('subject', function ($teacher_class) {
                    return $teacher_class->subject_user->description;
                })
                ->editColumn('grade_level', function ($teacher_class) {
                    return $teacher_class->grade_level_user->description;
                })
                ->editColumn('credits', function ($teacher_class) {
                    return $teacher_class->credits;
                })
                ->editColumn('weeks', function ($teacher_class) {
                    return $teacher_class->weeks;
                })
                ->editColumn('school_year', function ($teacher_class) {
                    return $teacher_class->school_year;
                })
                ->addColumn('action', function ($teacher_class) {
                    if($teacher_class->status == 1){
                        return '<a href="/faculty/class/edit/'.$teacher_class->id.'">Edit</a> |
                        <a href="/faculty/class/'.$teacher_class->id.'/delete">Delete</a>';
                    }else{
                        return '<a href="/faculty/class/'.$teacher_class->id.'/retrieve">Retrieve</a>';
                    }
                })
                ->addColumn('id', function ($teacher_class) {

                    return $teacher_class->id;
                })
                ->addColumn('date', function ($teacher_class) {
                    return date('F j, Y g:i a', strtotime($teacher_class->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['subject','grade_level','credits','weeks','school_year','action','id','date'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
            }
        } else {
            
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }

    }

    public function getFacultyAddClass() {

        $user = Auth::user();

        $subjects = Subject::where('status',1)->get();
        $grade_level = GradeLevel::where('status',1)->get();
        $school_year = SchoolYear::where('status', 1)->get();

        return view('faculty.class.create')
            ->with('user',$user)
            ->with('subjects',$subjects)
            ->with('grade_level',$grade_level)
            ->with('school_year',$school_year);
    }

    public function postFacultyAddClass(AddClassRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = TeacherClass::select('subject','grade_level','school_year')->where('user_id', $user->id)->first();

                if($check){

                    if($check->subject == (int)$request->subject && $check->grade_level == (int)$request->grade_level && $check->school_year == $request->school_year){

                        return response()->json(array("result"=>false,"message"=> "Subject, Grade Level and School Year already exists."),422);

                    }else{

                        $teacher_class = new TeacherClass();
                        $teacher_class->user_id = $user->id;
                        $teacher_class->subject = $request->subject;
                        $teacher_class->grade_level = $request->grade_level;
                        $teacher_class->credits = $request->credits;
                        $teacher_class->weeks = $request->weeks;
                        $teacher_class->school_year = $request->school_year;
                        $teacher_class->description = addslashes($request->description);
                        $teacher_class->save();

                        return response()->json(array("result"=>true,"message"=> "Class successfully added.") ,200);
                    }

                } else {

                    $teacher_class = new TeacherClass();
                    $teacher_class->user_id = $user->id;
                    $teacher_class->subject = $request->subject;
                    $teacher_class->grade_level = $request->grade_level;
                    $teacher_class->credits = $request->credits;
                    $teacher_class->weeks = $request->weeks;
                    $teacher_class->school_year = $request->school_year;
                    $teacher_class->description = addslashes($request->description);
                    $teacher_class->save();

                    return response()->json(array("result"=>true,"message"=> "Class successfully added.") ,200);
                }
            }catch(\Exception $e){

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
        
    }

    public function getFacultyEditClass($id) {

        $user = Auth::user();
        
        $subjects = Subject::where('status',1)->get();
        $grade_level = GradeLevel::where('status',1)->get();
        $school_year = SchoolYear::where('status', 1)->get();
        $teacher_class = TeacherClass::find($id);
        
        if($teacher_class) {

            return view('faculty.class.edit')
                ->with('user',$user)
                ->with('subjects',$subjects)
                ->with('grade_level',$grade_level)
                ->with('school_year',$school_year)
                ->with('teacher_class',$teacher_class);
        } else {

            return redirect('/faculty/class');
        }
    }

    public function postFacultyEditClass($id, EditClassRequest $request) {

        if ($request->wantsJson()) {

            try{

                $user = Auth::user();

                $check = TeacherClass::select('subject','grade_level','school_year')->where('user_id', $user->id)->first();

                if($check->subject == (int)$request->subject && $check->grade_level == (int)$request->grade_level && $check->school_year == $request->school_year){

                    return response()->json(array("result"=>false,"message"=> "Subject, Grade Level and School Year already exists."),422);

                }else{

                    $teacher_class = TeacherClass::where('id', $id)->first();
                    $teacher_class->subject = $request->subject;
                    $teacher_class->grade_level = $request->grade_level;
                    $teacher_class->credits = $request->credits;
                    $teacher_class->weeks = $request->weeks;
                    $teacher_class->school_year = $request->school_year;
                    $teacher_class->description = addslashes($request->description);
                    $teacher_class->save();

                    return response()->json(array("result"=>true,"message"=> "Class successfully updated.") ,200);

                }

            }catch(\Exception $e){
                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function deleteClass($id){

        try{

            $user = Auth::user();
            $teacher_class = TeacherClass::where('id', $id)->where('user_id',$user->id)->first();

            if($teacher_class){

                $teacher_class->status = 0;
                $teacher_class->save();

                return redirect('/faculty/class');
            }else{

                return redirect('/faculty/class');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function retrieveClass($id){

        try{

            $user = Auth::user();
            $teacher_class = TeacherClass::where('id', $id)->where('user_id',$user->id)->first();

            if($teacher_class){

                $teacher_class->status = 1;
                $teacher_class->save();

                return redirect('/faculty/class');
            }else{

                return redirect('/faculty/class');
            }

        }catch(\Exception $e){
            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
        }
    }

    public function getFacultyPublicMessages(){

        $user = Auth::user();

        return view('faculty.messages.public')->with('user',$user);
    }
    
    public function getFacultyPrivateMessages(){

        $user = Auth::user();

        return view('faculty.messages.private')->with('user',$user);
    }

    public function getFacultyPublicMessagesData(Request $request){

        if($request->wantsJson()){

            $messages = PublicMessage::with('user')->whereDate('created_at',Carbon::today())->orderBy('created_at', 'asc')->get();

            return response()->json(array("result"=>true,"messages"=>$messages),200);

        } else {

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }

    public function postFacultyPublicMessageSend(Request $request){

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

    public function getFacultyProfile(){

        $user = Auth::user();

        return view('faculty.settings.profile.edit')->with('user',$user);
    }

    public function postFacultyProfile(UpdateProfileRequest $request){

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

    public function postFacultyProfilePassword(UpdateProfilePasswordRequest $request){

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

}
