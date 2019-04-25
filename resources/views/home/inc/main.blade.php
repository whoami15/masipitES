<div class="col-sm-8">                    
    <div id="grid-container">
        @if($news)
        @foreach($news as $news_info)
        <div class="cbp-item">
            <!-- xxx Post Loop xxx -->
            <div class="blog-post">
                <div class="item-thumbs">
                    <img src="{{ URL::asset('uploads/news/'.$news_info->photo) }}" style="width:100%!important;height:100%!important;" alt="image">
                </div>
                <div class="blog-outer">
                    <div class="meta">
                        <span class="date">{{ date('F j, Y g:i a', strtotime($news_info->created_at)) }}</span>
                    </div>
                    <h3 class="blog-title"><a href="#">{{ $news_info->title }}</a></h3>
                    <div class="admin-text">
                        <i>By <a href="#">{{ $news_info->user->full_name }}</a></i>
                    </div>
                </div>
                <div class="blog-bottom">
                    <div class="social-icons pull-left">
                        <ul>
                            <li><a href="https://twitter.com/intent/tweet?url={{ url('/news/'.$news_info->slug) }}&text={{ $news_info->title }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li>                
                            <li><a href="https://www.facebook.com/sharer.php?u={{ url('/news/'.$news_info->slug) }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                    <div class="pull-right"><a href="{{ url('news/'.$news_info->slug) }}" class="more-links">Read More</a></div>
                    <div class="clearfix"></div>
                </div>                        
            </div>
            <!-- xxx Post Loop End xxx -->
        </div>
        @endforeach
        @endif

        @if($events)
        @foreach($events as $event_info)
        <div class="cbp-item">
            <!-- xxx Post Loop xxx -->
            <div class="blog-post">
                <div class="item-thumbs">
                    <img src="{{ URL::asset('uploads/events/'.$event_info->photo) }}" style="width:100%!important;height:100%!important;" alt="image">
                </div>
                <div class="blog-outer">
                    <div class="meta">
                        <span class="date">{{ date('F j, Y g:i a', strtotime($event_info->created_at)) }}</span>
                    </div>
                    <h3 class="blog-title"><a href="#">{{ $event_info->title }}</a></h3>
                    <div class="admin-text">
                        <i>By <a href="#">{{ $event_info->user->full_name }}</a></i>
                    </div>
                </div>
                <div class="blog-bottom">
                    <div class="social-icons pull-left">
                        <ul>
                            <li><a href="https://twitter.com/intent/tweet?url={{ url('/event/'.$event_info->slug) }}&text={{ $event_info->title }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li>                
                            <li><a href="https://www.facebook.com/sharer.php?u={{ url('/event/'.$event_info->slug) }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li>
                        </ul>
                    </div>
                    <div class="pull-right"><a href="{{ url('event/'.$event_info->slug) }}" class="more-links">Read More</a></div>
                    <div class="clearfix"></div>
                </div>                        
            </div>
            <!-- xxx Post Loop End xxx -->
        </div>
        @endforeach
        @endif
    </div>

        
    
    <!--div id="loadMore-container" class="cbp-l-loadMore-button">
        <a href="ajax/loadMore.html" class="cbp-l-loadMore-link">
            <span class="cbp-l-loadMore-defaultText"><i class="fa fa-repeat"></i></span>
            <span class="cbp-l-loadMore-loadingText"><i class="fa fa-spinner fa-spin"></i></span>
            <span class="cbp-l-loadMore-noMoreLoading"><i class="fa fa-smile-o"></i></span>
        </a>
    </div-->
</div>