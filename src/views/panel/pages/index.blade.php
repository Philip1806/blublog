@extends('blublog::panel.main')

@section('content')
<a href="{{ route('blublog.pages.create') }}" class="btn btn-primary btn-block">{{__('blublog.addpage')}}</a>
<br>
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.pages')}}</div>
    <div class="card-body text-primary">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">{{__('blublog.public')}} ({{$pages->count()}})</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">{{__('blublog.private')}} ({{$hidden_pages->count()}})</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                @include('blublog::panel.pages._pagestable')
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                @include('blublog::panel.pages._pagestable', ['pages' => $hidden_pages])
            </div>
        </div>
    </div>
</div>
@endsection
