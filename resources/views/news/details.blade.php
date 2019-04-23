@extends('layouts.frontend.master')
@section('title', $news_detail->title)

@section('content')
<!-- xxx Body Content xxx -->
<section id="body-content">    	
    	<div class="container">
        	<div class="row">
            	<!-- xxx Single Post xxx -->           	
                <div class="col-sm-8">
                	<div class="about-wrap">
                    	<div class="item-thumbs">
                            <img src="{{ url('uploads/news/'.$news_detail->photo) }}" style="width:800px!important;height:375px!important;" alt="">
                        </div>
                        <div class="blog-outer">
                            <h3 class="blog-title">{{ $news_detail->title }}</h3>
                            <div class="admin-text">
                            	<i>By <a href="#">{{ $news_detail->user->full_name }}</a></i>
                            </div>
                            <div class="meta">
                                <span class="date">{{ date('F j, Y g:i a', strtotime($news_detail->created_at)) }}</span>
                            </div>
                        </div>
                        <div class="blog-text">
                            <p>{{ str_replace('\\', '', $news_detail->content) }}</p>
                        </div>
                        <div class="share-post text-center">
                        	<h4>Share This</h4>
                            <div class="social-icons">
                                <ul>
                                    <li><a href="#" data-toggle="tooltip" title="" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li>                
                                    <li><a href="#" data-toggle="tooltip" title="" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>                        
                    </div>

                    <div class="sinlepost-navigation direction-nav row">
                    </div>
                    @if($related_news)
                    <div class="widgets-box">
                    	<div class="sidebar-head"><span>Related News</span></div>
                    	<div class="sidebar-text">
                        	<div class="owl-carousel" id="relatedpost-slider">
                                @foreach($related_news as $related)
            					<div class="item">
                                    <a href="{{ url('/news/'.$related->slug) }}"><img alt="" src="{{ url('/uploads/news/'.$related->photo) }}"></a>
                                    <div class="recent-post-text">
                                        <h4><a href="{{ url('/news/'.$related->slug) }}">{{ $related->title }}</a></h4>
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
                    	<div class="sidebar-head bg-2"><span>Random Events</span></div>
                    	<div class="sidebar-text">
                        	<ul class="sidebar-post">
                                @if($random_events)
                                @foreach($random_events as $random_event)
                            	<li>
                                	<div class="image-thumb">
                                    	<a href="{{ url('/events/'.$random_event->slug) }}"><img src="{{ url('/uploads/events/'.$random_event->photo) }}" alt=""></a>
                                    </div>
                                    <div class="post-text">
                                    	<h4><a href="{{ url('/events/'.$random_event->slug) }}">{{ $random_event->title }}</a></h4>
                                        <p>{{ str_limit($random_event->content, 15) }}</p>
                                        <div class="post-date">
                                        	<i class="fa fa-clock-o"></i> {{ date('F j, Y g:i a', strtotime($random_event->created_at)) }}
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