<div class="col-sm-4 col-md-4 col-lg-4">
    <!-- xxx Widet Box xxx -->
    <div class="widgets-box">
    	<div class="sidebar-head bg-2"><span>Random News</span></div>
    	<div class="sidebar-text">
        	<ul class="sidebar-post">
                @if($random_news)
                @foreach($random_news as $random_news_info)
            	<li>
                	<div class="image-thumb">
                    	<a href="#"><img src="{{ URL::asset('uploads/news/'.$random_news_info->photo) }}" alt=""></a>
                    </div>
                    <div class="post-text">
                    	<h4><a href="{{ url('/news/'.$random_news_info->slug) }}">{{ $random_news_info->title }}</a></h4>
                        <p>{{ str_limit($random_news_info->content, 15) }}</p>
                        <div class="post-date">
                        	 <i class="fa fa-clock-o"></i> {{ date('F j, Y g:i a', strtotime($random_news_info->created_at)) }}
                        </div>
                    </div>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
    <!-- xxx Widet Box End xxx -->

    <!-- xxx Widet Box xxx -->
    <div class="widgets-box">
    	<div class="sidebar-head"><span>Random Events</span></div>
    	<div class="sidebar-text">
        	<div>
                @if($random_events)
                @foreach($random_events as $random_events_info)
				<div class="item">
                    <a href="#"><img alt="" src="{{ URL::asset('uploads/events/'.$random_events_info->photo) }}"></a>
                    <div class="recent-post-text">
                        <h4><a href="{{ url('/event/'.$random_events_info->slug) }}">{{ $random_events_info->title }}</a></h4>
                        <div class="post-date">
                             <i class="fa fa-clock-o"></i> {{ date('F j, Y g:i a', strtotime($random_events_info->created_at)) }}
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
         	</div>
        </div>
    </div>
    <!-- xxx Widet Box End xxx -->
    
    <!-- xxx Widet Box xxx -->
    <!--div class="widgets-box">
    	<div class="sidebar-head"><span>Categories</span></div>
    	<div class="sidebar-text">
        	<ul class="category">
            	<li>
                    <div class="cat-desc">
                        <div class="category-details">
                            <span><a href="#">Entertainment</a></span>
                        </div>
                        <div class="dots"></div>
                        <div class="category-links">
                            <span>(10)</span>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="cat-desc">
                        <div class="category-details">
                            <span><a href="#">Sports</a></span>
                        </div>
                        <div class="dots"></div>
                        <div class="category-links">
                            <span>(05)</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div-->
    <!-- xxx Widet Box End xxx -->
</div>