
@if (!blublog_is_admin())
<div class="sidebar-heading">
    {{ __('blublog.posts') }}
</div>
<li class="nav-item">
    <a class="nav-link" href="{{ url('/panel/posts') }}">
    <i class="fas fa-fw fa-newspaper"></i>
    <span>{{ __('blublog.posts') }}</span></a>
</li>

@if (!blublog_setting('disable_comments_modul'))
<li class="nav-item">
    <a class="nav-link" href="{{ url('/panel/comments') }}">
    <i class="fas fa-comments"></i>
    <span>{{ __('blublog.comments') }}</span></a>
</li>
@endif
<li class="nav-item">
    <a class="nav-link" href="{{ url('/panel/tags') }}">
    <i class="fas fa-tags"></i>
    <span>{{ __('blublog.tags') }}</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item">
    <a class="nav-link" href="{{ url('/panel/files') }}">
    <i class="fas fa-fw fa-file"></i>
    <span>{{ __('blublog.files') }}</span></a>
</li>
<hr class="sidebar-divider">
    @if (blublog_is_mod())
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/panel/posts/rating') }}">
        <i class="fas fa-star-half-alt"></i>
        <span>{{ __('blublog.posts_rating') }}</span></a>
    </li>
    <div class="sidebar-heading">
        {{ __('blublog.mod_panel') }}
    </div>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/panel/categories') }}">
        <i class="fas fa-columns"></i>
        <span>{{ __('blublog.categories') }}</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/panel/pages') }}">
        <i class="fas fa-fw fa-pager"></i>
        <span>{{ __('blublog.pages') }}</span></a>
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
          <i class="fas fa-fw fa-newspaper"></i>
          <span>{{ __('blublog.posts') }}</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ url('/panel/posts') }}">{{ __('blublog.posts') }}</a>
            <a class="collapse-item" href="{{ url('/panel/categories') }}">{{ __('blublog.categories') }}</a>
            <a class="collapse-item" href="{{ url('/panel/posts/rating') }}">{{ __('blublog.posts_rating') }}</a>
            @if (!blublog_setting('disable_comments_modul'))
            <a class="collapse-item" href="{{ url('/panel/comments') }}">{{ __('blublog.comments') }}</a>
            @endif
            <a class="collapse-item" href="{{ url('/panel/tags') }}">{{ __('blublog.tags') }}</a>
          </div>
        </div>
      </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/panel/files') }}">
        <i class="fas fa-fw fa-file"></i>
        <span>{{ __('blublog.files') }}</span></a>
    </li>
    <li class="nav-item">
            <a class="nav-link" href="{{ url('/panel/pages') }}">
            <i class="fas fa-fw fa-pager"></i>
            <span>{{ __('blublog.pages') }}</span></a>
    </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ url('/panel/menu') }}">
          <i class="fas fa-fw fa-ellipsis-h"></i>
          <span>{{ __('blublog.menu') }}</span></a>
      </li>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">
    {{ __('blublog.admin') }}
    </div>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/panel/users') }}">
          <i class="fas fa-fw fa-users"></i>
          <span>{{ __('blublog.users') }}</span></a>
      </li>

    <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>{{ __('blublog.settings') }}</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/panel/settings') }}">{{ __('blublog.settings') }}</a>
                <a class="collapse-item" href="{{ url('/panel/logs') }}">{{ __('blublog.logs') }}</a>
                <a class="collapse-item" href="{{ url('/panel/ban') }}">{{ __('blublog.ban') }}</a>
                </div>
            </div>
    </li>
    <hr class="sidebar-divider">
    @endif
