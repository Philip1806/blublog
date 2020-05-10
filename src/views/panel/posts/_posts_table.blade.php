@if (!empty($posts[0]->id))
<table class="table table-hover">
        <thead class="thead-light">
        <tr>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col">{{__('blublog.title')}}</th>
            <th scope="col">{{__('blublog.author')}}</th>
            <th scope="col">{{__('blublog.views')}}</th>
        </tr>
        </thead>
        <tbody>
                @foreach ( $posts as $post )
                <tr>
                <td><a href="{{ route('blublog.posts.show', $post->id) }}"  role="button" class="btn btn-outline-info btn-block ">{{__('blublog.view')}}</a></td>
                @if (blublog_can_edit_post( $post->id,Auth::user()->id))
                <td><a  class="btn btn-outline-primary btn-block " href="{{ route('blublog.posts.edit', $post->id) }}" >{{__('blublog.edit')}}</a></td>
                @else
                <td></td>
                @endif
                <td><a href="{{ route('blublog.front.post_show', $post->slug) }}" >{{ $post->title }}</a></td>
                <td>{{ $post->user->name }}</td>
                <td>
                    {{ $post->views->count() }}
                </td>
                </tr>
                @endforeach

        </tbody>
</table>
{!! $posts->links(); !!}

@else
<center> <b>{{__('blublog.no_posts')}}</b> </center><br>
@endif
