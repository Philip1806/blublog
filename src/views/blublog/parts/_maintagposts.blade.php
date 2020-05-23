@if ( $post->maintag_posts)
<div class="card text-white border-primary" style="margin-top: 20px;">
    <div class="card-header bg-primary text-white">{{__('blublog.on_this_topic')}}</div>
    <div class="card-body">
        @foreach ( $post->maintag_posts as $tag_post)
        <div class="row">
            <div class="col-sm-4">
                <img class="img-fluid border-primary rounded shadow"  src="{{$tag_post->img_url}}" alt="{{$tag_post->title}} image">
            </div>
            <div class="col-sm-8">
                <h5 class="mt-0"><a href="{{$tag_post->slug_url}}">{{$tag_post->title}}</a></h5>
                <p>{{$tag_post->excerpt}}</p>
                <small><span class="badge badge-light"> {!!$tag_post->STARS_HTML!!}</span> <span class="badge badge-light"> <span class="oi oi-eye"> {{$tag_post->total_views}}</span></span><br>
                    @foreach ($tag_post->categories as $category)
                    <a href="{{ route('blublog.front.category_show', $category->slug) }}" style="color:white"><span class="badge badge-{{$category->id}}">{{$category->title}}</span></a>
                    @endforeach
                </small>
            </div>
        </div>
        <br>
        @endforeach
    </div>
</div>
@endif
