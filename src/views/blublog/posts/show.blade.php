@extends('blublog::blublog.main')
@section('title') {{$post->seo_title}} @endsection
@section('meta')
<!-- Open Graph / Facebook -->
<meta name="og:title" property="og:title" content="{{$post->seo_title}}">
<meta name="og:description" property="og:description" content="{{$post->seo_descr}}">
<meta name="og:image" property="og:image" content="{{url('/uploads/posts/')}}/{{$post->img}}" />
<meta name="og:type" property="og:type" content="article" >
<meta name="og:published_time" property="og:published_time" content="{{$post->created_at}}" >
<meta name="og:article:section" property="og:article:section" content="{{$post->categories[0]->title}}" >
@if (isset($post->tags[0]->id))
@foreach ( $post->tags as $tag)
<meta name="og:article:tag" property="og:article:tag" content="{{ $tag->title }}" >
@endforeach
@endif
<meta name="og:url" property="og:url" content="{{ url('/') }}/posts/{{ $post->slug}}" />
<meta name="og:locale" property="og:locale" content="en_EN" />
<meta name="og:site_name" property="og:site_name" content="{!!blublog_setting('site_name')!!}" >
<meta name="robots" content="index, follow">
@endsection
@section('jumbotron')
<style>
.jumbotron {
  background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url("{{url('/uploads/posts/')}}/{{$post->img}}");
  background-size: cover;
  background-repeat: no-repeat;
  border-color: {{$post->categories[0]->colorcode}};
  border-bottom-style: solid;
  border-width: 5px;
}
.display-comment .display-comment {
        margin-left: 40px
}
</style>

<div class="jumbotron" style="max-height: 500px;padding:143px;">
    <div class="container-fluid text-white">
    <h2>{{$post->title}}</h2>
    <span class="badge badge-{{$post->categories[0]->id}}">{{$post->categories[0]->title}}</span>

    </div>
</div>
@endsection
@section('content')
          <div class="col-lg-9">

            {!! $post->content !!}
            <hr>
          <small>Posted by {{ $post->user->name }} on {{ $post->date }}</small>
          @foreach ($post->tags as $tag)
          <a href="{{ route('blublog.front.tag_show', $tag->slug) }}"><span class="badge badge-pill badge-dark">{{$tag->title}}</span></a>
          @endforeach
          <hr>
        @if ($post->comments)
                @include('blublog::comments._comments')
        @else
            <div class="text-center"><small>{{__('panel.comments_disabled')}}</small></div>
        @endif
          </div>
          <div class="col-lg-3">
            @include('blublog::blublog.parts._sidebar')
          </div>
@endsection
