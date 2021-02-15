@extends('blublog::panel.layout.main')

@section('nav')

    <ul class="nav nav-pills nav-fill bg-light m-2">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('blublog.panel.users.roles') }}"><span class="oi oi-chevron-right"></span>
                Roles</a>
        </li>
    </ul>


@endsection

@section('content')
    @livewire('blublog-users-table')
@endsection
