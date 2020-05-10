@extends('blublog::panel.main')

@section('content')

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('blublog.addinguser')}}</div>

<div class="card-body">
{!! Form::open(['route' => 'blublog.users.add', 'method' => 'POST']) !!}

                {{ Form::label('name', 'Name*') }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}
                {{ Form::label('email', 'E-Mail Address*') }}
                {{ Form::text('email', null, ['class' => 'form-control', 'rows' => '10']) }}
                {{ Form::label('role', __('blublog.role')) }}
                {{ Form::select('role', ['Author' => 'Author', 'Moderator' => 'Moderator', 'Administrator' => 'Administrator'], null ,['class' => 'form-control']) }}
                {{ Form::label('password', 'Password*') }}
                {{ Form::password('password', ['class' => 'form-control']) }}

<hr>
{{ Form::submit(__('blublog.adduser'), ['class' => 'btn btn-primary btn-block']) }}
{!! Form::close() !!}
</div>
</div>
@endsection
