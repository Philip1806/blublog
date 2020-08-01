@extends('blublog::panel.main')

@section('content')
<div class="row">
    <div class="col-sm-10">
        <a href="{{ route('blublog.users.create') }}" class="btn btn-primary btn-block">{{__('blublog.adduser')}}</a>
    </div>
    <div class="col-sm">
        <a href="{{ route('blublog.roles') }}" class="btn btn-primary btn-block">{{__('blublog.roles')}}</a>
    </div>
</div>
<hr>
<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.users')}}</div>
        <div class="table-responsive">
            <table class="table table-hover">
                    <tbody>
                        @foreach ( $users as $user)

                                  @if ($user->user_role->is_admin)
                                  <tr class="table-danger">
                                  @elseif($user->user_role->is_mod)
                                  <tr class="table-info">
                                  @else
                                  <tr>
                                  @endif
                                        <th>{{ $user->name }}</th>
                                        <td>{{ $user->user_role->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><a href="{{ route('blublog.users.edit', $user->id) }}" class="btn btn-warning btn-block">{{__('blublog.edit')}}</a></td>
                                        @if (Auth::user()->name != $user->name)
                                            <td>
                                                {!! Form::open(['route' => ['blublog.users.destroy', $user->id], 'method' => 'DELETE']) !!}
                                                {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
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
