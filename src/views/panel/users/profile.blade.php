@extends('blublog::panel.main')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
      Your profile
      @if (Gate::allows('blublog_edit_users', $user->id))
      <a href="{{ route('blublog.users.edit', $user->user_id) }}" class="btn btn-warning btn-sm">{{__('blublog.edit')}}</a>
      @endif
    </div>
    <div class="card-body">
        <div class="media">
            @if ($user->img_url)
            <img class="mr-3" src="{{$user->img_url}}" width="64" >
            @else
            <img class="mr-3" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2264%22%20height%3D%2264%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_173a4e907a5%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_173a4e907a5%22%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2213.546875%22%20y%3D%2236.5%22%3E64x64%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E">
            @endif
            <div class="media-body">
            <h5 class="mt-0">{{$user->name}}</h5>
            <p>{{$user->descr}}</p>
            </div>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><b>Your role:</b> {{$user->user_role->name}}</li>
        <li class="list-group-item"><b>Your posts:</b> {{$user->posts->count()}}</li>
        <li class="list-group-item"><b>Your comments:</b> {{$user->comments->count()}}</li>
        <li class="list-group-item"><b>Your files:</b> {{$user->files->count()}}</li>
        <li class="list-group-item"><b>Your email:</b> {{$user->email}}</li>
      </ul>
  </div>


@endsection
