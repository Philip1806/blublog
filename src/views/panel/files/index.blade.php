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
        <br><input type="button" class="btn btn-info " onclick="searchfor('file','filename')" value="{{__('blublog.search_in_filename')}}">
        <input type="button" class="btn btn-info " onclick="searchfor('file','descr')" value="{{__('blublog.search_in_descr')}}">
        <h2><div id="infopanel"></div></h2>
        <ul class="list-group">
            <div id="results"></div>
        </ul>
    </div>
</div>
@include('blublog::panel.files._table')

@include('blublog::panel.partials._searchjs')
<script>
function show_files(files){
    let panel = document.getElementById("results");
    remove_all_child(panel);

    for (let i =0; i<files.length ; i++){
        let link = "{{ url('/'). "/". blublog_setting('panel_prefix') }}" + "/files/" + files[i].id + "/download";
        let li = document.createElement("li");
        li.innerHTML= '<a href="'  + link + '">' + files[i].filename +  ' (' + files[i].size + ')</a><br>' + files[i].descr;
        li.className="list-group-item";
        console.log(files[i]);
        panel.appendChild(li);
    }
}
</script>
@endsection
