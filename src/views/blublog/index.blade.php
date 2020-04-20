@extends('blublog::blublog.main')
@section('title') Blog of {{blublog_setting('site_name')}} @endsection
@section('meta')
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
<meta name="og:url" property="og:url" content="{{ url('/') }}" />
<meta name="og:locale" property="og:locale" content="en_EN" />
<meta name="robots" content="index, follow">
@endsection

@section('jumbotron')
<div class="jumbotron">
    <div class="container text-center">
        {!!blublog_setting('head_html')!!}
    </div>
</div>
@endsection

@section('content')
          <div class="col-lg-3">
            @include('blublog::blublog.parts._sidebar')
          </div>
          <div class="col-lg-9">
            @include('blublog::blublog.parts._listposts')
          </div>
@endsection
