@extends('blublog::blublog.main')
@section('title') @endsection
@section('jumbotron')
<div class="jumbotron">
    <div class="container">
        <p>{{__('panel.search_resuts')}}</p>
        <h2>"{{$search}}"</h2>
    </div>
</div>
@endsection

@section('content')
          <div class="col-lg-9">
            @include('blublog::blublog.parts._listposts')
          </div>
          <div class="col-lg-3">
            @include('blublog::blublog.parts._sidebar')
          </div>
@endsection
