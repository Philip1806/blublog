@extends('blublog::panel.main')


@section('content')
<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.search_comment') }}
    </div><br>
    <div class="card-body">
        <input type="text" class="form-control" id="searchfor" placeholder="{{__('blublog.search_comment_enter')}}">
        <br><input type="button" class="btn btn-info " onclick="searchfor('comment')" value="{{__('blublog.search')}}">
        @if (blublog_is_mod())
            <input type="button" class="btn btn-info " onclick="searchfor('comment_ip')" value="{{__('blublog.search_comment_ip')}}">
        @endif
        <h2><div id="infopanel"></div></h2>
        <ul class="list-group">
            <div id="results"></div>
        </ul>
    </div>
</div>
<div class="card border-primary" style="margin-top:20px;">
    <div class="card-header text-white bg-primary">{{__('blublog.comments')}}</div>
        @if (!empty($comments[0]->id))
        <table class="table">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">{{ __('blublog.status') }}</th>
                    <th scope="col">{{ __('blublog.comment') }}</th>
                    <th scope="col">{{ __('blublog.author') }}</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>

                  </tr>
                </thead>
                <tbody>
                        @foreach ( $comments as $comment )
                        <tr>

                            @can('approve', $comment)
                            <td>
                                @if ($comment->public)
                                <a href="{{ route('blublog.comments.approve', $comment->id) }}" class="btn btn-outline-dark btn-block" role="button">{{__('blublog.hide')}}</a>
                                @else
                                <a href="{{ route('blublog.comments.approve', $comment->id) }}" class="btn btn-outline-primary btn-block" role="button">{{__('blublog.approve')}}</a>
                                @endif
                            </td>
                            @else
                            <td></td>
                            @endcan



                        <td>
                            @if ($comment->public)
                            <span class="badge py-2 btn-block badge-success">{{__('blublog.its_approved')}}</span>
                            @else
                            <span class="badge py-2 btn-block badge-danger">{{__('blublog.its_hiden')}}</span>
                            @endif
                        </td>
                        <td><a href="{{ $comment->post_url }}">{{mb_strimwidth($comment->body, 0, 160, '...') }}</a></td>
                        <td>{{ $comment->name }}</td>

                        @can('update', $comment)
                        <td><a href="{{ route('blublog.comments.edit', $comment->id) }}" class="btn btn-outline-primary btn-block" role="button">{{__('blublog.edit')}}</a></td>
                        @else
                        <td></td>
                        @endcan

                        @can('delete', $comment)
                        <td>
                            {!! Form::open(['route' => ['blublog.comments.destroy', $comment->id], 'method' => 'DELETE']) !!}
                            {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                            {!! Form::close() !!}
                        </td>
                        @else
                        <td></td>
                        @endcan

                        @can('ban', $comment)
                        <td><a href="{{ route('blublog.comments.ban', $comment->id) }}" class="btn btn-outline-danger btn-block" role="button">{{__('blublog.ban')}} {{__('blublog.ip')}}</a></td>
                        @else
                        <td></td>
                        @endcan
                        </tr>

                        @endforeach

                </tbody>
        </table>
        <div class="p-2">
            {!! $comments->links(); !!}
        </div>
        @else
        <div class="card-body text-center">
            <b>{{__('blublog.no_comments')}}</b>
        </div>
        @endif
</div>

@include('blublog::panel.partials._searchjs')
<script>
function show_files(comments){
    let panel = document.getElementById("results");
    remove_all_child(panel);
    for (let i =0; i<comments.length ; i++){
        let link = "{{ url('/'). "/". blublog_setting('panel_prefix') }}" + "/comments/" + comments[i].id + "/edit";
        let li = document.createElement("li");
        li.innerHTML= '<a href="'  + link + '"> <b>' + comments[i].name + '</b> ' + '(<a href="'  + comments[i].post_url + '">{{__("blublog.post")}}</a>)' + '</a><br>' + comments[i].body;
        li.className="list-group-item";
        panel.appendChild(li);
    }
}
</script>
@endsection
