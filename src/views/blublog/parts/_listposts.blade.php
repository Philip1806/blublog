@if (isset($posts[0]->id))
@foreach ($posts as $post)
<div class="row">
    <div class="col-sm-4">
        <img class="img-thumbnail border-primary rounded"  src="{{$post->img_url}}" alt="{{$post->title}} image">
    </div>
    <div class="col-sm-8">
        <h5 class="mt-0"><a href="{{$post->slug_url}}">{{$post->title}}</a></h5>
        <p>{{$post->excerpt}}</p>
        <small>
            @foreach ($post->categories as $category)
            <a href="{{ route('blublog.front.category_show', $category->slug) }}" style="color:white"><span class="badge badge-{{$category->id}}">{{$category->title}}</span></a>
            @endforeach

            @foreach ($post->tags as $tag)
            <a href="{{ route('blublog.front.tag_show', $tag->slug) }}"><span class="badge badge-pill badge-dark">{{$tag->title}}</span></a>
            @endforeach


        </small>
    </div>
</div>
<hr>
@endforeach
{!!$posts->links()!!}
@else
<center><b>{{__('panel.no_posts')}}</b></center>
@endif

