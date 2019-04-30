@extends('layouts.frontend.master')
@section('title', $event_detail->title)

@section('content')
<!-- xxx Body Content xxx -->
<section id="body-content">    	
    	<div class="container">
        	<div class="row">
            	<!-- xxx Single Post xxx -->           	
                <div class="col-sm-8">
                	<div class="about-wrap">
                    	<div class="item-thumbs">
                            <img src="{{ url('uploads/events/'.$event_detail->photo) }}" style="width:800px!important;height:375px!important;" alt="">
                        </div>
                        <div class="blog-outer">
                            <h3 class="blog-title">{{ $event_detail->title }}</h3>
                            <div class="admin-text">
                            	<i>By <a href="#">{{ $event_detail->user->full_name }}</a></i>
                            </div>
                            <div class="meta">
                                <span class="date">{{ date('F j, Y g:i a', strtotime($event_detail->created_at)) }}</span><br>
                                <span class="date"><b>WHEN: {{ date('F j, Y', strtotime($event_detail->event_date)) }} {{ date('h:i A', strtotime($event_detail->event_time)) }}</b></span>
                                <span class="date"><b>WHERE: {{ $event_detail->event_location }}</b></span>
                            </div>
                        </div>
                        <div class="blog-text">
                            <p>{{ str_replace('\\', '', $event_detail->content) }}</p>
                        </div>
                        <div class="share-post text-center">
                        	<h4>Share This</h4>
                            <div class="social-icons">
                                <ul>
                                    <li><a href="https://twitter.com/intent/tweet?url={{ url('/event/'.$event_detail->slug) }}&text={{ $event_detail->title }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li>                
                                    <li><a href="https://www.facebook.com/sharer.php?u={{ url('/event/'.$event_detail->slug) }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>                        
                    </div>

                    <br>

                    <div class="widgets-box">
                    	<div class="sidebar-head"><span>Comments</span></div>
                    	<div class="sidebar-text">
                        	<div class="comment-wrap">
                                <div class="fb-comments" data-href="{{ url('/event/'.$event_detail->slug) }}" data-width="100%" data-numposts="5"></div>
                            </div>
                        </div>
                    </div>
                    
                    @if($related_events)
                    <div class="widgets-box">
                    	<div class="sidebar-head"><span>Related Events</span></div>
                    	<div class="sidebar-text">
                        	<div class="owl-carousel" id="relatedpost-slider">
                                @foreach($related_events as $related)
            					<div class="item">
                                    <a href="{{ url('/events/'.$related->slug) }}"><img alt="" src="{{ url('/uploads/events/'.$related->photo) }}"></a>
                                    <div class="recent-post-text">
                                        <h4><a href="{{ url('/events/'.$related->slug) }}">{{ $related->title }}</a></h4>
                                        <div class="post-date">
                                             <i class="fa fa-clock-o"></i> {{ date('F j, Y g:i a', strtotime($related->created_at)) }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                         	</div>
                        </div>
                    </div>
                    @else
                    @endif
                              		
                </div>
                <!-- xxx Single Post End xxx -->
                
                <!-- xxx Sidebar xxx -->
                <div class="col-sm-4">
                    
                    <!-- xxx Widet Box xxx -->
                    <div class="widgets-box">
                    	<div class="sidebar-head bg-2"><span>Random News</span></div>
                    	<div class="sidebar-text">
                        	<ul class="sidebar-post">
                                @if($random_news)
                                @foreach($random_news as $random_new)
                            	<li>
                                	<div class="image-thumb">
                                    	<a href="{{ url('/news/'.$random_new->slug) }}"><img src="{{ url('/uploads/news/'.$random_new->photo) }}" alt=""></a>
                                    </div>
                                    <div class="post-text">
                                    	<h4><a href="{{ url('/news/'.$random_new->slug) }}">{{ $random_new->title }}</a></h4>
                                        <p>{{ str_limit($random_new->content, 15) }}</p>
                                        <div class="post-date">
                                        	<i class="fa fa-clock-o"></i> {{ date('F j, Y g:i a', strtotime($random_new->created_at)) }}
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- xxx Widet Box End xxx -->
                    
                </div>
                <!-- xxx Sidebar End xxx -->
            </div>
        </div>    
    </section>
    <!-- xxx Body Content End xxx -->
@endsection