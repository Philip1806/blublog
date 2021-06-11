{!! '<' . '?' . 'xml version="1.0" encoding="UTF-8" ?>' !!} <rss version="2.0">
    <channel>
        <title>{{ config('app.name') }}</title>
        <link>{{ url('/') }}</link>
        <description>RSS for {{ config('app.name') }}</description>
        <language>en-EN</language>
        <lastBuildDate>{{ $posts[0]->created_at->format(DateTime::RSS) }}</lastBuildDate>
        @foreach ($posts as $post)
            <item>
                <title>
                    <![CDATA[{!! $post->title !!}]]>
                </title>
                <link>{{ route('blublog.front.single', $post->slug) }}</link>
                <guid isPermaLink="true">{{ route('blublog.front.single', $post->slug) }}</guid>
                <description>
                    <![CDATA[<div><img width="300" height="169" src="{{ $post->thumbnailUrl() }}"/></div>{!! strip_tags($post->seo_descr) !!}]]>
                </description>
                <category>{!! $post->categories[0]->title !!}</category>
                <pubDate>{{ $post->created_at->format(DateTime::RSS) }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>
