@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/panel') }}">{{ __('blublog.home') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('blublog.posts') }}</li>
    </ol>
</nav>
@endsection

@section('content')
<a href="{{ route('blublog.posts.create') }}" class="btn btn-primary btn-block">{{__('blublog.add_post')}}</a>
<br>
<div class="card border-primary shadow" style="margin-bottom:20px;">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.search_post') }}
    </div><br>
    <div class="card-body">
        <input type="text" class="form-control" id="searchfor" placeholder="Search for post here">
    <br><input type="button" class="btn btn-info " onclick="searchfor('post', 'title')" value="{{__('blublog.search_in_title')}}">
        <input type="button" class="btn btn-info " onclick="searchfor('post', 'content')" value="{{__('blublog.search_in_content')}}">
        <h2><div id="infopanel"></div></h2>
        <ul class="list-group">
            <div id="results"></div>
        </ul>
    </div>
</div>

<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.posts') }}
    </div><br>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-public-tab" data-toggle="tab" href="#nav-public" role="tab" aria-controls="nav-public" aria-selected="true">Public ({{$posts->total()}})</a>
                <a class="nav-item nav-link" id="nav-private-tab" data-toggle="tab" href="#nav-private" role="tab" aria-controls="nav-private" aria-selected="false">Private ({{$private_posts->total()}})</a>
                <a class="nav-item nav-link" id="nav-draft-tab" data-toggle="tab" href="#nav-draft" role="tab" aria-controls="nav-draft" aria-selected="false">Draft ({{$draft_posts->total()}})</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-public" role="tabpanel" aria-labelledby="nav-public-tab">
                @include('blublog::panel.posts._posts_table', ['posts' => $posts])
            </div>
            <div class="tab-pane fade" id="nav-private" role="tabpanel" aria-labelledby="nav-private-tab">
                <div class="alert alert-info" role="alert">
                    <strong>{{ __('blublog.private') }}</strong> ({{ __('blublog.private_info') }})
                </div>
                @include('blublog::panel.posts._posts_table', ['posts' => $private_posts])
            </div>
            <div class="tab-pane fade" id="nav-draft" role="tabpanel" aria-labelledby="nav-draft-tab">
                <div class="alert alert-info" role="alert">
                    <strong>{{ __('blublog.draft') }}</strong> ({{ __('blublog.draft_info') }})
                </div>
                @include('blublog::panel.posts._posts_table', ['posts' => $draft_posts])
            </div>
        </div>

</div>

@include('blublog::panel.partials._searchjs')
<script>
function show_files(files){
    let panel = document.getElementById("results");
    remove_all_child(panel);

    for (let i =0; i<files.length ; i++){
        let link = "{{ url('/'). "/". blublog_setting('panel_prefix') }}" + "/posts/" + files[i].id;
        let li = document.createElement("li");
        li.innerHTML= '<a href="'  + link + '">' + files[i].title + '</a>';
        li.className="list-group-item";
        panel.appendChild(li);
    }
}
</script>
@endsection
