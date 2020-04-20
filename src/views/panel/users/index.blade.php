@extends('blublog::panel.main')

@section('content')
<a href="{{ route('blublog.users.create') }}" class="btn btn-primary btn-block">{{__('panel.adduser')}}</a>
<hr>
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.users')}}</div>
        <div class="table-responsive">
            <table class="table table-hover">
                    <tbody>
                        @foreach ( $users as $user)

                                  @if ($user->role == "Administrator")
                                  <tr class="table-danger">
                                  @elseif($user->role == "Moderator")
                                  <tr class="table-info">
                                  @else
                                  <tr>
                                  @endif
                                        <th>{{ $user->name }}</th>
                                        <td>{{ $user->email }}</td>
                                        <td><a href="{{ route('blublog.users.edit', $user->user_id) }}" class="btn btn-warning btn-block">{{__('panel.edit')}}</a></td>
                                        @if (Auth::user()->name != $user->name)
                                            <td>
                                                {!! Form::open(['route' => ['blublog.users.destroy', $user->id], 'method' => 'DELETE']) !!}
                                                {!! form::submit(__('panel.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        @else
                                        <td>
                                        </td>
                                        @endif
                                  </tr>
                        @endforeach

                    </tbody>
                  </table>

    </div>
</div>

@endsection
