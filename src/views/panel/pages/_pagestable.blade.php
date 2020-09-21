@if (!empty($pages[0]->id))
<table class="table table-sm table-hover">
        <tbody>
                @foreach ( $pages as $post )
                <tr>
                        <td>{{ $post->title }}</td>
                        <td><a href="{{ route('blublog.front.pages.show', $post->slug) }}"  role="button" class="btn btn-outline-primary btn-block ">{{__('blublog.view')}}</a></td>
                        <td><a href="{{ route('blublog.pages.edit', $post->id) }}"  role="button" class="btn btn-outline-primary btn-block ">{{__('blublog.edit')}}</a></td>
                        <td>
                        {!! Form::open(['route' => ['blublog.pages.destroy', $post->id], 'method' => 'DELETE']) !!}
                        {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-outline-danger btn-block ','onsubmit' => 'return validate(this);' ]) !!}
                        {!! Form::close() !!}
                        </td>
                </tr>
                @endforeach

        </tbody>
</table>
{!! $pages->links(); !!}

@else
<br>
<div class="p-4 h3 text-center">
    {{__('blublog.no_pages')}}
</div>
@endif
