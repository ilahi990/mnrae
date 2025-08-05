@extends('global.index')
@section('content')



    <!-- Blog Grid Area Start  -->
    <div class="blog-grid-area sec-margin">
        <div class="container">
            <div class="row">
                @if (count($blogs) > 0)
                    <div class="col-lg-8">
                        <div class="row">
                            @foreach ($blogs as $blog)
                                @php $date = date('M d, Y', strtotime($blog->created_at));
                                $length = strlen($blog->description); @endphp
                                <div class="col-lg-6 col-md-6 mb-40">
                                    <!-- Blog Start -->
                                    <div class="post-item antry-blog-post h-100">
                                        <div class="post-image">
                                            <a href="{{ route('blogDetails', ['slug' => slugify($blog->title), 'id' => $blog->id]) }}"><img
                                                    src="{{ asset('public/uploads/blog/' . $blog->thumbnail) }}" alt="blog-image"></a>
                                        </div>
                                        <div class="post-content blog_lest  mt-0">
                                            <div class="post-meta ">
                                                <ul class="blog_metas">
                                                    <li>
                                                        
                                                        {{ $date }}
                                                    </li>
                                                    @if ($blog->is_popular)
                                                        <li><span class="color-2">{{ get_phrase('Featured') }}</span></li>
                                                    @else
                                                        <li><span class="color-2"> </span></li>
                                                    @endif
                                                    <li>
                                                        
                                                        @php
                                                            $name = App\Models\User::where('id', $blog->user_id)->first();
                                                        @endphp
                                                        {{ get_phrase('by') }}<span>&nbsp;
                                                            @if ($blog->user_id == 1)
                                                                {{ get_phrase('admin') }}
                                                            @else
                                                                {{ substr($name->name, 0, 6) }}
                                                            @endif
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <h3><a href="{{ route('blogDetails', ['slug' => slugify($blog->title), 'id' => $blog->id]) }}">{{ $blog->title }}</a></h3>
                                            @if ($length < 85)
                                                <p> {{ Str::limit(strip_tags($blog->description), 80) }}</p>
                                            @else
                                                <p>{{ Str::limit(strip_tags($blog->description), 85) }}</p>
                                            @endif
                                            <a href="{{ route('blogDetails', ['slug' => slugify($blog->title), 'id' => $blog->id]) }}"
                                                class="read-more">{{ get_phrase('Read More') }}<i class="fa-solid fa-arrow-right-long"></i></a>
                                        </div>
                                    </div>
                                    <!-- Blog End -->
                                </div>
                            @endforeach
                        </div>
                        <!-- Pagination -->
                        <div class="pagination-items">
                            {!! $blogs->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                @else
                    <div class="col-lg-8">
                        <div class="row">
                            @include('no_data_found')
                        </div>
                    </div>
                @endif
                <div class="col-lg-4">
                    <div class="l_sidebar-items">
                        
                        <div class="wiget-items categories">
                            <h6 class="widget-titles">{{ get_phrase('Categories') }}</h6>
                            <ul>
                               
                            </ul>
                        </div>
                        <div class="wiget-items recent-posts-entry">
                            <h6 class="widget-titles">{{ get_phrase('Recent Post') }}</h6>
                            @foreach ($latest_blogs as $latest)
                                @php
                                    $date = date('M d, Y', strtotime($latest->created_at));
                                @endphp
                                <a href="{{ route('blogDetails', ['slug' => slugify($latest->title), 'id' => $latest->id]) }}">
                                    <div class="widget-post-bx">
                                        <div class="widget-posts clearfix d-flex align-items-center">
                                            <div class="ttr-post-media"> <img src="{{ asset('public/uploads/blog/' . $latest->thumbnail) }}" alt=""> </div>
                                            <div class="ttr-post-info">
                                                <div class="ttr-post-header">
                                                    <h6 class="post-titles">{{ Str::limit($latest->title, 30) }}</h6>
                                                </div>
                                                <ul class="media-posts">
                                                    <li><i class="fa-regular fa-calendar-days"></i>{{ $date }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="wiget-items  tags-area">
                            <h6 class="widget-titles">{{ get_phrase('Tag') }}</h6>
                            <ul>
                                @foreach ($tags as $key => $tag)
                                    <li><a href="{{ route('blogGrid', ['tag' => $tag]) }}">{{ $tag }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog Area End  -->
@endsection
