@extends('blublog::panel.main')

@section('content')
<a href="{{ route('blublog.files.create') }}" class="btn btn-info btn-block">{{__('blublog.add')}}</a>
<br>
<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.search_file') }}
    </div><br>
    <div class="card-body">
        <input type="text" class="form-control" id="searchfor">
        <br><input type="button" class="btn btn-info " onclick="searchfor('file')" value="{{__('blublog.search')}}">
        <h2><div id="infopanel"></div></h2>
        <ul class="list-group">
            <div id="results"></div>
        </ul>
    </div>
</div>
<div class="card border-primary shadow" style="margin-top:20px;">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.files') }}
    </div>
    @if (!empty($files[0]->id))
    <table class="table">
        <thead class="thead-light">
            <tr>
            <th scope="col">{{__('blublog.title')}}</th>
            <th scope="col">{{__('blublog.size')}}</th>
            <th scope="col">{{__('blublog.status')}}</th>
            <th scope="col">{{__('blublog.address')}}</th>
            <th scope="col"></th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $files as $file )
            <tr>
            <td>
                @if ($file->public)
                <a href="{{ $file->url }}" > {{ $file->descr }} </a>
                @else
                {{ $file->descr }}
                @endif
            </td>
            <td>{{ $file->size }}</td>
            <td>
                @if ($file->public)
                <span class="badge badge-success">{{__('blublog.public')}}</span>
                @else
                <span class="badge badge-danger">{{__('blublog.hide')}}</span>
                @endif

            </td>
            <td>{{ $file->filename }}</td>
            <td><a href="{{ route('blublog.files.download', $file->id) }}" class="btn btn-primary btn-block" role="button">{{__('blublog.download')}}</a></td>
            <td>
                {!! Form::open(['route' => ['blublog.files.destroy', $file->id], 'method' => 'DELETE']) !!}
                {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
                {!! Form::close() !!}
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $files->links(); !!}
    <hr>
    @else
    <div class="mb-5 mt-5 text-center"><h1>{{ __('blublog.no_results')}}</h1> </div>
    @endif
</div>
@include('blublog::panel.partials._searchjs')
<script>
function show_files(files){
    let panel = document.getElementById("results");
    remove_all_child(panel);

    for (let i =0; i<files.length ; i++){
        let link = "{{ url('/'). "/". blublog_setting('panel_prefix') }}" + "/files/" + files[i].id + "/download";
        let li = document.createElement("li");
        li.innerHTML= '<a href="'  + link + '">' + files[i].filename + '</a>';
        li.className="list-group-item";
        panel.appendChild(li);
    }
}
</script>
@endsection
