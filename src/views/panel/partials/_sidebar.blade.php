
@if (!blublog_is_admin())
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
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/tags') }}">
    <i class="oi oi-tags"></i>
    <span>{{ __('blublog.tags') }}</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ blublog_panel_url('/files') }}">
    <i class="oi oi-file"></i>
    <span>{{ __('blublog.files') }}</span></a>
</li>
<hr class="sidebar-divider">
    @if (blublog_is_mod())
    <div class="sidebar-heading">
        {{ __('blublog.mod_panel') }}
    </div>
    <li class="nav-item">
        <a class="nav-link" href="{{ blublog_panel_url('/posts/rating') }}">
        <i class="oi oi-star"></i>
        <span>{{ __('blublog.posts_rating') }}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ blublog_panel_url('/categories') }}">
        <i class="oi oi-list-rich"></i>
        <span>{{ __('blublog.categories') }}</span></a>
    </li>
    <hr class="sidebar-divider">
    @endif
@endif
@if (blublog_is_admin())
<div class="sidebar-heading">
      {{ __('blublog.general') }}
</div>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="oi oi-list"></i>
          <span>{{ __('blublog.posts') }}</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ blublog_panel_url('/posts') }}">{{ __('blublog.posts') }}</a>
            <a class="collapse-item" href="{{ blublog_panel_url('/categories') }}">{{ __('blublog.categories') }}</a>
            <a class="collapse-item" href="{{ blublog_panel_url('/posts/rating') }}">{{ __('blublog.posts_rating') }}</a>
            @if (!blublog_setting('disable_comments_modul'))
            <a class="collapse-item" href="{{ blublog_panel_url('/comments') }}">{{ __('blublog.comments') }}</a>
            @endif
            <a class="collapse-item" href="{{ blublog_panel_url('/tags') }}">{{ __('blublog.tags') }}</a>
          </div>
        </div>
      </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ blublog_panel_url('/files') }}">
        <i class="oi oi-file"></i>
        <span>{{ __('blublog.files') }}</span></a>
    </li>
    <li class="nav-item">
            <a class="nav-link" href="{{ blublog_panel_url('/pages') }}">
            <i class="oi oi-align-left"></i>
            <span>{{ __('blublog.pages') }}</span></a>
    </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ blublog_panel_url('/menu') }}">
          <i class="oi oi-menu"></i>
          <span>{{ __('blublog.menu') }}</span></a>
      </li>
    <hr class="sidebar-divider">

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
    <hr class="sidebar-divider">
    @endif
