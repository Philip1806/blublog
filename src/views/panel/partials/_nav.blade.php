<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ blublog_panel_url('') }}">
    <div class="sidebar-brand-text mx-3">
        @if (blublog_is_admin())
            {{ __('blublog.admin_panel') }}
        @elseif(blublog_is_mod())
            {{ __('blublog.mod_panel') }}
        @else
            {{ __('blublog.user_panel') }}
        @endif
    </div>
</a>

<hr class="sidebar-divider my-0">

<li class="nav-item active">
        <a class="nav-link" href="{{ url(config('blublog.blog_prefix')) }}">
            <i class="oi oi-arrow-thick-left"></i>
            <span>{{ __('blublog.backtosite') }}</span></a>
</li>

<li class="nav-item active">
  <a class="nav-link" href="{{ blublog_panel_url('') }}">
    <i class="oi oi-home"></i>
    <span>{{ __('blublog.home') }}</span></a>
</li>

<hr class="sidebar-divider">
@include('blublog::panel.partials._sidebar')


</ul>
<!-- End of Sidebar -->
