<div class="navbar navbar-expand-lg fixed-top navbar-dark  bg-primary">
    <div class="container">
      <a href="{{ url(config('blublog.blog_prefix')) }}" class="navbar-brand">{!!blublog_setting('site_name')!!}</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav">
            {!! blublog_main_menu() !!}
        </ul>

      </div>
      @auth
      {!! Form::open(['route' => 'blublog.front.search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
      {{ Form::text('search', null, ['required', 'class' => 'form-control form-control-borderless', 'placeholder'=>__('panel.search_in_blog')]) }}
     {!! Form::close() !!}
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
        <a class="nav-link" href="{{ url(config('blublog.panel_prefix')) }}">Panel</a>
        </li>
      </ul>
      @endauth
    </div>
  </div>
