<meta charset="utf-8">
<title>@yield('title')</title>
@yield('meta')
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="stylesheet" href="{{ url('/blublog/css/bootstrap.min.css') }}" media="screen">
<link rel="stylesheet" href="{{ url('/blublog/css/custom.min.css') }}">
<link href="{{ url('/blublog/css/open-iconic-bootstrap.min.css') }}" rel="stylesheet">
<!--
    ^_-
    App name: BLUblog
    App github: https://github.com/Philip1806/blublog
    Theme name: BLUblog
-->
<style>
@import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');
.foo {
    float: left;
    width: 15px;
    height: 15px;
    margin: 5px;
    border: 1px solid rgba(0, 0, 0, .2);
}
</style>
@foreach ($categories as $category)
<style>
.badge-{{$category->id}} {
background-color: {{$category->colorcode}};
}
</style>
@endforeach
