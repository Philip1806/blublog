@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow" style="margin-bottom:20px;">
    <div class="card-header text-white bg-primary">
     {{ __('panel.ban_user') }}
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'blublog.ban.user', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            {{ Form::label('ip', __('panel.ip')) }}
            {{ Form::text('ip', null, ['class' => 'form-control']) }}

            {{ Form::label('descr', __('panel.descr')) }}
            {{ Form::text('descr', null, ['class' => 'form-control']) }}

            {{Form::checkbox('comments', null)}} {{__('panel.banned_from_comments')}}

            {{ Form::submit(__('panel.ban'), ['class' => 'btn btn-primary btn-block','style'=>'margin-top:20px;']) }}
        {!! Form::close() !!}
    </div>
</div>
<div class="card border-primary shadow">
        <div class="card-header text-white bg-primary">
            {{ __('panel.bans') }}
        </div>
            @if (!empty($bans[0]->id))
            <table class="table table-hover">
                <tbody>
                    @foreach ( $bans as $ban )
                    <tr>
                        <td>{{ $ban->ip }}</td>
                        <td>{{ $ban->descr }}</td>
                        <td>
                            <b>
                            @if ($ban->comments)
                            {{__('panel.banned_from_comments')}}
                            @else
                            {{__('panel.banned_from_blog')}}
                            @endif
                            </b>
                        </td>
                        <td>
                            {!! Form::open(['route' => ['blublog.ban.destroy', $ban->id], 'method' => 'DELETE']) !!}
                            {!! form::submit(__('panel.unban'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            {!! $bans->links(); !!}
            @else
            <center> <b>{{__('panel.no_bans')}}</b> </center>
            @endif
</div>
@endsection
