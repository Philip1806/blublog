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
<div class="card border-primary shadow">
    <div class="card-header">
            <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-public-tab" data-toggle="tab" href="#nav-public" role="tab" aria-controls="nav-public" aria-selected="true">Public ({{$posts->total()}})</a>
                <a class="nav-item nav-link" id="nav-private-tab" data-toggle="tab" href="#nav-private" role="tab" aria-controls="nav-private" aria-selected="false">Private ({{$private_posts->total()}})</a>
                <a class="nav-item nav-link" id="nav-draft-tab" data-toggle="tab" href="#nav-draft" role="tab" aria-controls="nav-draft" aria-selected="false">Draft ({{$draft_posts->total()}})</a>
                <a class="nav-item nav-link" id="nav-search-tab" data-toggle="tab" href="#nav-search" role="tab" aria-controls="nav-search" aria-selected="false">{{ __('blublog.search_post') }}</a>
                <a href="{{ route('blublog.posts.create') }}" class="nav-item bg-primary text-white nav-link">{{__('blublog.add_post')}}</a>
            </div>
    </div>

        <div class="tab-content mt-0" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-public" role="tabpanel" aria-labelledby="nav-public-tab">
                @include('blublog::panel.posts._posts_table', ['posts' => $posts])
            </div>
            <div class="tab-pane fade" id="nav-private" role="tabpanel" aria-labelledby="nav-private-tab">
                <div class="alert alert-info rounded-0 m-0" role="alert">
                    <strong>{{ __('blublog.private') }}</strong> ({{ __('blublog.private_info') }})
                </div>
                @include('blublog::panel.posts._posts_table', ['posts' => $private_posts])
            </div>
            <div class="tab-pane fade" id="nav-draft" role="tabpanel" aria-labelledby="nav-draft-tab">
                <div class="alert alert-info rounded-0 m-0" role="alert">
                    <strong>{{ __('blublog.draft') }}</strong> ({{ __('blublog.draft_info') }})
                </div>
                @include('blublog::panel.posts._posts_table', ['posts' => $draft_posts])
            </div>
            <div class="tab-pane fade" id="nav-search" role="tabpanel" aria-labelledby="nav-search-tab">
                <div class="card-body">
                    <input type="text"  autocomplete="off" class="form-control" id="searchfor" placeholder="Search for post here">
                <br><input type="button" class="btn btn-info " onclick="searchfor('post', 'title')" value="{{__('blublog.search_in_title')}}">
                    <input type="button" class="btn btn-info " onclick="searchfor('post', 'content')" value="{{__('blublog.search_in_content')}}">
                    <h2><div id="infopanel"></div></h2>
                    <ul class="list-group">
                        <div id="results"></div>
                    </ul>
                </div>
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
