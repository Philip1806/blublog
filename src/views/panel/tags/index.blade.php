@extends('blublog::panel.main')

@section('content')

<div class="row">
    <div class="col-xl-6">
        <div class="card border-primary shadow">
            <div class="card-header text-white bg-primary">
             {{ __('blublog.search_tag') }}
            </div><br>
            <div class="card-body">
                <input type="text" class="form-control" id="searchfor">
                <br><input type="button" class="btn btn-info " onclick="searchfor('tag')" value="Search">
                <h2><div id="infopanel"></div></h2>
                <ul class="list-group">
                    <div id="results"></div>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card border-primary shadow">
            <div class="card-header text-white bg-primary">
            {{ __('blublog.add_tag') }}
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'blublog.tags.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                @include('blublog::panel.tags._form', ['button_title' => __('blublog.create')])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


<div class="card border-primary shadow" style="margin-top:20px;">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.tags') }}
    </div>
    <div class="card-body">
        @if (!empty($tags[0]->id))
        <table class="table table-hover">
                <tbody>
                        @foreach ( $tags as $tag )
                        <tr>
                                <td><a href="{{ route('blublog.tags.edit', $tag->id) }}" >{{ $tag->title }}</a></td>
                                <td><a href="{{ route('blublog.front.tag_show', $tag->slug) }}"  role="button" class="btn btn-outline-primary btn-block ">{{__('blublog.view')}}</a></td>
                                <td><a href="{{ route('blublog.tags.edit', $tag->id) }}" class="btn btn-outline-warning btn-block">{{__('blublog.edit')}}</a></td>
                                <td>
                                {!! Form::open(['route' => ['blublog.tags.destroy', $tag->id], 'method' => 'DELETE']) !!}
                                {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                                {!! Form::close() !!}
                                </td>
                        </tr>
                        @endforeach

                </tbody>
        </table>
        {!! $tags->links(); !!}
        <hr>
        @else
        <hr>
        <center> <b>Няма добавени категории</b> </center>
        @endif
    </div>
</div>

@include('blublog::panel.partials._searchjs')
<script>
function show_files(files){
    let panel = document.getElementById("results");
    remove_all_child(panel);

    for (let i =0; i<files.length ; i++){
        let link = "{{ url('/'). "/". blublog_setting('panel_prefix') }}" + "/tags/" + files[i].id + "/edit";
        let li = document.createElement("li");
        li.innerHTML= '<a href="'  + link + '">' + files[i].title + '</a>';
        li.className="list-group-item";
        panel.appendChild(li);
    }
}
</script>
@endsection
