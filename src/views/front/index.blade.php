@extends('blublog::front.layout.main')

@section('title')Our Blog @endsection
@section('meta')
    <meta name="description" content="Our blog" />
    <!-- Open Graph / Facebook -->
    <meta name="og:description" property="og:description" content="Description of the blog">
    <meta property="og:type" content="website" />
    <meta name="og:title" property="og:title" content="Site name">
    <meta name="og:site_name" property="og:site_name" content="Site name">
    <meta name="og:url" property="og:url" content="{{ route('blublog.index') }}" />
    <meta name="robots" content="index, follow">
    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:url" content="{{ route('blublog.index') }}">
    <meta property="twitter:title" content="Site name">
    <meta property="twitter:description" content="Description of the blog">
@endsection


@section('header')
    <div class="jumbotron">
        <div class="container text-center">
            <h1>Welcome to BLUblog 2.0!</h1>
            <p>You can edit your blog theme.</p>
        </div>
    </div>
@endsection

@section('content')
    @include('blublog::front.layout._listPosts')
@endsection
