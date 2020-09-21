@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow">
    <div class="card-header">
        <nav>
            <div class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">{{__('blublog.public')}} ({{$pages->count()}})</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">{{__('blublog.private')}} ({{$hidden_pages->count()}})</a>
                <a href="{{ route('blublog.pages.create') }}" class="nav-item nav-link bg-primary text-white">{{__('blublog.addpage')}}</a>
            </div>
        </nav>
    </div>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            @include('blublog::panel.pages._pagestable')
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            @include('blublog::panel.pages._pagestable', ['pages' => $hidden_pages])
        </div>
    </div>
</div>
@endsection
