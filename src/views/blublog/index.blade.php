@extends('blublog::blublog.main')
@section('title') Blog of {{blublog_setting('site_name')}} @endsection
@section('meta')
<meta name="description" content="{{blublog_setting('site_descr')}}"/>
<!-- Open Graph / Facebook -->
<meta name="og:description" property="og:description" content="{{blublog_setting('site_descr')}}">
<meta property="og:type" content="website" />
<meta name="og:site_name" property="og:site_name" content="{{blublog_setting('site_name')}}" >
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
