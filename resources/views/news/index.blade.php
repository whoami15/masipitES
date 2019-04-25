@extends('layouts.frontend.master')
@section('title', 'News')

@section('content')
<section id="body-content">
    <div class="container">
        <div class="row">
            <div class="clearfix"></div>
            <div class="col-sm-8">     
                @if($news)               
                <div id="grid-container">  
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
                </div>
                <br>
                <div class="pagination-wrap text-right">
                    {{ $news->links() }}     
                </div>
                @else
                @endif
            </div>
            @include('home.inc.right-sidebar')
        </div>
    </div>
</section>
@endsection