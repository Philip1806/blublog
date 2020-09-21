<div class="sidebar-heading">
    {{ __('blublog.posts') }}
</div>
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/posts') }}">
    <i class="oi oi-list"></i>
    <span>{{ __('blublog.posts') }}</span></a>
</li>

@if (!blublog_setting('disable_comments_modul'))
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/comments') }}">
    <i class="oi oi-comment-square"></i>
    <span>{{ __('blublog.comments') }}</span></a>
</li>
@endif

@if (blublog_have_permission('create_tags'))
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/tags') }}">
    <i class="oi oi-tags"></i>
    <span>{{ __('blublog.tags') }}</span></a>
</li>
@endif

@if (blublog_have_permission('control_post_rating'))
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/posts/rating') }}">
    <i class="oi oi-star"></i>
    <span>{{ __('blublog.posts_rating') }}</span></a>
</li>
@endif

<div class="sidebar-heading">
    {{ __('blublog.others') }}
</div>

@if (blublog_have_permission('upload_files'))
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/files') }}">
    <i class="oi oi-file"></i>
    <span>{{ __('blublog.files') }}</span></a>
</li>
@endif

@if (blublog_have_permission('view_categories'))
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/categories') }}">
    <i class="oi oi-list-rich"></i>
    <span>{{ __('blublog.categories') }}</span></a>
</li>
@endif

@if (blublog_have_permission('create_pages'))
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/pages') }}">
    <i class="oi oi-align-left"></i>
    <span>{{ __('blublog.pages') }}</span></a>
</li>
@endif

@if (blublog_have_permission('use_menu'))
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/menu') }}">
      <i class="oi oi-menu"></i>
      <span>{{ __('blublog.menu') }}</span></a>
</li>
@endif

@if (blublog_is_admin())
<div class="sidebar-heading">
    {{ __('blublog.admin') }}
</div>
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/users') }}">
      <i class="oi oi-people"></i>
      <span>{{ __('blublog.users') }}</span></a>
</li>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
        <i class="oi oi-cog"></i>
        <span>{{ __('blublog.settings') }}</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{ blublog_panel_url('/settings') }}">{{ __('blublog.settings') }}</a>
        <a class="collapse-item" href="{{ blublog_panel_url('/logs') }}">{{ __('blublog.logs') }}</a>
        <a class="collapse-item" href="{{ blublog_panel_url('/ban') }}">{{ __('blublog.ban') }}</a>
        </div>
    </div>
</li>
@endif
