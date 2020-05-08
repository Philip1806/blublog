{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>' !!}
<rss version="2.0">
  <channel>
    <title>{{blublog_setting('site_name')}}</title>
    <link>{{ url('/') }}</link>
    <description>Blog of {{blublog_setting('site_name')}}</description>
    <language>en-en</language>
    <lastBuildDate>{{ $posts[0]->created_at->format(DateTime::RSS) }}</lastBuildDate>
    <generator>https://blublog.info</generator>
    @foreach($posts as $post)
    <item>
      <title><![CDATA[{!! $post->title !!}]]></title>
      <link>{{ route('blublog.front.post_show', $post->slug) }}</link>
      <guid isPermaLink="true">{{ route('blublog.front.post_show', $post->slug) }}</guid>
      <description><![CDATA[<div><img width="300" height="169" src="{{ url('/uploads/posts/') }}/thumbnail_{{ $post->img }}"/></div>{!! strip_tags($post->descr) !!}]]></description>
      <category>{!! $post->categories[0]->title !!}</category>
      <pubDate>{{ $post->created_at->format(DateTime::RSS) }}</pubDate>
    </item>
    @endforeach
  </channel>
</rss>
