<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Home\ContactRequest;
use Carbon\Carbon;
use App\User;
use App\News;
use App\Contact;
use App\Events;
use Hash;
use DB;
use Redirect;
use Session;

class HomeController extends Controller
{

    public function getHome()
    {
        $news = News::with('user')->where('status',1)->take(2)->get();
        $events = Events::with('user')->where('status',1)->take(2)->get();

        $random_news = News::with('user')->where('status',1)->take(1)->inRandomOrder()->get();
        $random_events = Events::with('user')->where('status',1)->take(1)->inRandomOrder()->get();
        return view('home.index')
            ->with('events',$events)
            ->with('news',$news)
            ->with('random_news',$random_news)
            ->with('random_events',$random_events);
    }

    public function getNews()
    {
        $news = News::with('user')->where('status',1)->paginate(4);
        $random_news = News::with('user')->where('status',1)->take(1)->inRandomOrder()->get();
        $random_events = Events::with('user')->where('status',1)->take(1)->inRandomOrder()->get();

        return view('news.index')
            ->with('news',$news)
            ->with('random_news',$random_news)
            ->with('random_events',$random_events);
    }

    public function getEvents()
    {
        $events = Events::with('user')->where('status',1)->paginate(4);
        $random_news = News::with('user')->where('status',1)->take(1)->inRandomOrder()->get();
        $random_events = Events::with('user')->where('status',1)->take(1)->inRandomOrder()->get();

        return view('events.index')
            ->with('events',$events)
            ->with('random_news',$random_news)
            ->with('random_events',$random_events);
    }

    public function getViewNews($slug)
    {
        $news_detail = News::where('slug',$slug)->where('status',1)->first();
        $related_news = News::where('id', '!=', $news_detail->id)->where('status',1)->take(5)->get();
        $random_events = Events::with('user')->where('status',1)->take(2)->inRandomOrder()->get();

        if($news_detail){

            return view('news.details')
                ->with('news_detail',$news_detail)
                ->with('related_news',$related_news)
                ->with('random_events',$random_events);
        }else{

            return redirect('/');
        }
    }

    public function getViewEvent($slug)
    {
        $event_detail = Events::where('slug',$slug)->where('status',1)->first();
        $related_events = Events::where('id', '!=', $event_detail->id)->where('status',1)->take(5)->get();
        $random_news = News::with('user')->where('status',1)->take(2)->inRandomOrder()->get();

        if($event_detail){

            return view('events.details')
                ->with('event_detail',$event_detail)
                ->with('related_events',$related_events)
                ->with('random_news',$random_news);
        }else{

            return redirect('/');
        }
    }

    public function getTeachers()
    {
        
        $user = Auth::user();
        $teachers = User::where('role',2)->where('status',1)->get();

        return view('teachers.index')
            ->with('user',$user)
            ->with('teachers',$teachers);
    }

    public function getTerms()
    {

        return view('home.terms');
    }

    public function getPrivacy()
    {

        return view('home.privacy');
    }
    
    public function getContact()
    {

        return view('home.contact');
    }

    public function postContact(ContactRequest $request)
    {

        try {

            $count_messages = Contact::whereDate('created_at',Carbon::today())->count();

            if($count_messages < 10){

                $contact = new Contact();
                $contact->name = $request->name;
                $contact->email = $request->email;
                $contact->subject = $request->subject;
                $contact->content = $request->content;
                $contact->save();

                Session::flash('success', "Successfully sent.");
                return redirect('/contact');
            }else{

                Session::flash('danger', "Sorry, but we have reach the limit of message for today. Please try again tomorrow, Thank you.");
                return Redirect::back();
            }

        }catch(\Exception $e){

            Session::flash('danger', $e->getMessage());
            return Redirect::back();
        }
    }

}
