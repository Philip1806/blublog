@extends('blublog::panel.main')


@section('content')
<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.search_comment') }}
    </div><br>
    <div class="card-body">
        <input type="text" class="form-control" id="searchfor" placeholder="{{__('blublog.search_comment_enter')}}">
        <br><input type="button" class="btn btn-info " onclick="searchfor('comment')" value="{{__('blublog.search_comment_name')}}">
        <input type="button" class="btn btn-info " onclick="searchfor('comment_ip')" value="{{__('blublog.search_comment_ip')}}">
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
                    @if (blublog_is_admin() or blublog_is_mod())
                    <th scope="col"></th>
                    <th scope="col"></th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                        @foreach ( $comments as $file )
                        <tr>
                        <td>
                        @if ($file->public)
                        <a href="{{ route('blublog.comments.approve', $file->id) }}" class="btn btn-outline-dark btn-block" role="button">{{__('blublog.hide')}}</a>
                        @else
                        <a href="{{ route('blublog.comments.approve', $file->id) }}" class="btn btn-outline-primary btn-block" role="button">{{__('blublog.approve')}}</a>
                        @endif
                        </td>
                        <td>
                            @if ($file->public)
                            <span class="badge badge-success">{{__('blublog.its_approved')}}</span>
                            @else
                            <span class="badge badge-danger">{{__('blublog.its_hiden')}}</span>
                            @endif
                        </td>
                        <td><a href="{{ route('blublog.front.post_show', $file->post_slug) }}">{{$file->body }}</a></td>
                        <td>{{ $file->name }}</td>
                        <td><a href="{{ route('blublog.comments.edit', $file->id) }}" class="btn btn-outline-primary btn-block" role="button">{{__('blublog.edit')}}</a></td>

                        @if (blublog_is_admin() or blublog_is_mod())
                        <td>
                            {!! Form::open(['route' => ['blublog.comments.destroy', $file->id], 'method' => 'DELETE']) !!}
                            {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                            {!! Form::close() !!}
                            </td>
                            <td><a href="{{ route('blublog.comments.ban', $file->id) }}" class="btn btn-outline-danger btn-block" role="button">{{__('blublog.ban')}} {{__('blublog.ip')}}</a></td>
                        @endif
                        </tr>

                        @endforeach

                </tbody>
        </table>
        {!! $comments->links(); !!}
        <hr>
        @else
        <hr>
    <center> <b>{{__('blublog.no_comments')}}</b> </center>
        @endif
</div>

@include('blublog::panel.partials._searchjs')
<script>
function show_files(files){
    let panel = document.getElementById("results");
    remove_all_child(panel);

    for (let i =0; i<files.length ; i++){
        let link = "{{ url('/'). "/". blublog_setting('panel_prefix') }}" + "/comments/" + files[i].id + "/edit";
        let li = document.createElement("li");
        li.innerHTML= '<a href="'  + link + '">' + files[i].name + '</a><br>' + files[i].body;
        li.className="list-group-item";
        panel.appendChild(li);
    }
}
</script>
@endsection
