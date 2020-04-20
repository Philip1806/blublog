@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('/panel') }}">{{ __('panel.home') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('panel.posts') }}</li>
                </ol>
</nav>
@endsection

@section('content')
<a href="{{ route('blublog.posts.create') }}" class="btn btn-primary btn-block">{{__('panel.add_post')}}</a>
<br>
<br>

<div class="card border-primary shadow">
    <div class="card-header text-white bg-primary">
     {{ __('panel.posts') }}
    </div><br>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-public-tab" data-toggle="tab" href="#nav-public" role="tab" aria-controls="nav-public" aria-selected="true">Public ({{$posts->count()}})</a>
              <a class="nav-item nav-link" id="nav-private-tab" data-toggle="tab" href="#nav-private" role="tab" aria-controls="nav-private" aria-selected="false">Private ({{$private_posts->count()}})</a>
              <a class="nav-item nav-link" id="nav-draft-tab" data-toggle="tab" href="#nav-draft" role="tab" aria-controls="nav-draft" aria-selected="false">Draft ({{$draft_posts->count()}})</a>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-public" role="tabpanel" aria-labelledby="nav-public-tab">
                @include('blublog::panel.posts._posts_table', ['posts' => $posts])
            </div>
            <div class="tab-pane fade" id="nav-private" role="tabpanel" aria-labelledby="nav-private-tab">
                <div class="alert alert-info" role="alert">
                    <strong>{{ __('panel.private') }}</strong> ({{ __('panel.private_info') }})
                </div>
                @include('blublog::panel.posts._posts_table', ['posts' => $private_posts])
            </div>
            <div class="tab-pane fade" id="nav-draft" role="tabpanel" aria-labelledby="nav-draft-tab">
                <div class="alert alert-info" role="alert">
                    <strong>{{ __('panel.draft') }}</strong> ({{ __('panel.draft_info') }})
                </div>
                @include('blublog::panel.posts._posts_table', ['posts' => $draft_posts])
            </div>
        </div>

</div>


@endsection
