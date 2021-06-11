<div class="p-1 bg-primary"></div>
<footer class="bg-dark text-white p-4">
    <div class="container">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                    <span class="oi oi-account-logout"></span>{{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            <li class="nav-item">
                @if (Auth::user()
        ->blublogRoles->first()
        ->havePermission('edit-profile'))
                    <a class="nav-link" data-toggle="modal" data-target="#exampleModal"><span
                            class="oi oi-person"></span>
                        {{ Auth::user()->name }} ({{ Auth::user()->blublogRoles()->first()->name }})</a>
                @else
                    <a class="nav-link"><span class="oi oi-person"></span>
                        {{ Auth::user()->name }}</a>
                @endif
                <div class="modal fade text-dark" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit your profile</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @include('blublog::panel.users._editUser',['user'=>Auth::user()])
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <a href="https://github.com/Philip1806/blublog/">BLUblog</a> - Blog package for Laravel
    </div>
</footer>
