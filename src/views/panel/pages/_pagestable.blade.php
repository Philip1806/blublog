@if (!empty($pages[0]->id))
<table class="table table-sm table-hover">
        <tbody>
                @foreach ( $pages as $post )
                <tr>
                        <td>{{ $post->title }}</td>
                        <td><a href="{{ route('blublog.front.pages.show', $post->slug) }}"  role="button" class="btn btn-outline-primary btn-block ">{{__('panel.view')}}</a></td>
                        <td><a href="{{ route('blublog.front.pages.show', $post->slug) }}"  role="button" class="btn btn-outline-primary btn-block ">{{__('panel.edit')}}</a></td>
                        <td>
                        {!! Form::open(['route' => ['blublog.pages.destroy', $post->id], 'method' => 'DELETE']) !!}
                        {!! form::submit(__('panel.delete'), ['class' => 'btn btn-outline-danger btn-block ','onsubmit' => 'return validate(this);' ]) !!}
                        {!! Form::close() !!}
                        </td>
                </tr>
                @endforeach

        </tbody>
</table>
{!! $pages->links(); !!}

@else
<br>
   <center> <b>{{__('panel.no_pages')}}.</b> </center><hr>
@endif
