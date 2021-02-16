<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ blublog_panel_url('') }}">Blog Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ blublog_panel_url('/posts') }}"><span
                            class="oi oi-justify-left"></span> Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blublog.panel.images') }}"><span class="oi oi-image"></span>
                        Images</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blublog.panel.users.index') }}"><span
                            class="oi oi-people"></span> Users</a>
                </li>
                @if (blublog_is_admin())
                    <li class="nav-item">
                        <a class="nav-link" href="#"><span class="oi oi-cog"></span> Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><span class="oi oi-lock-locked"></span> Security</a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>
<div class="p-1 bg-primary">
    <div class="container">
        @yield('nav')
    </div>
</div>
