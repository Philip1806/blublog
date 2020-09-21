@if (!empty($posts[0]->id))
<table class="table table-hover">
        <thead class="thead-light">
        <tr>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col">{{__('blublog.title')}}</th>
            <th scope="col"><a href="{{ blublog_panel_url('/posts?author') }}">{{__('blublog.author')}} </a></th>
            <th scope="col"><a href="{{ blublog_panel_url('/posts?views') }}">{{__('blublog.views')}} </a></th>
        </tr>
        </thead>
        <tbody>
                @foreach ( $posts as $post )
                <tr>
                <td><a href="{{ route('blublog.posts.show', $post->id) }}"  role="button" class="btn btn-outline-info btn-block ">{{__('blublog.view')}}</a></td>
                @can('update', $post)
                <td><a  class="btn btn-outline-primary btn-block " href="{{ route('blublog.posts.edit', $post->id) }}" >{{__('blublog.edit')}}</a></td>
                @else
                <td></td>
                @endcan
                <td><a href="{{ route('blublog.front.post_show', $post->slug) }}" >{{ $post->title }}</a></td>
                <td>{{ $post->user->name }}</td>
                <td>
                    {{ $post->views->count() }}
                </td>
                </tr>
                @endforeach

        </tbody>
</table>
<div class="col align-self-center p-2">
    {!! $posts->links(); !!}
</div>

@else
<div class="p-4 h3 text-center">
    {{__('blublog.no_posts')}}
</div>
@endif
