@extends('blublog::panel.main')


@section('content')

<div class="card border-primary">
    <div class="card-header text-white bg-primary">{{__('panel.comments')}}</div>
        @if (!empty($comments[0]->id))
        <table class="table">
                <thead class="thead-light">
                  <tr>
                    <th scope="col"></th>
                    <th scope="col">{{ __('panel.status') }}</th>
                    <th scope="col">{{ __('panel.comment') }}</th>
                    <th scope="col">{{ __('panel.author') }}</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                        @foreach ( $comments as $file )
                        <tr>
                        <td>
                        @if ($file->public)
                        <a href="{{ route('comments.approve', $file->id) }}" class="btn btn-outline-dark btn-block" role="button">{{__('panel.hide')}}</a>
                        @else
                        <a href="{{ route('comments.approve', $file->id) }}" class="btn btn-outline-primary btn-block" role="button">{{__('panel.approve')}}</a>
                        @endif
                        </td>
                        <td>
                            @if ($file->public)
                            <span class="badge badge-success">{{__('panel.its_approved')}}</span>
                            @else
                            <span class="badge badge-danger">{{__('panel.its_hiden')}}</span>
                            @endif

                        </td>
                        <td><a href="{{ route('blublog.front.post_show', $file->post_slug) }}">{{$file->body }}</a></td>
                        <td>{{ $file->name }}</td>
                        <td><a href="{{ route('blublog.comments.edit', $file->id) }}" class="btn btn-outline-primary btn-block" role="button">{{__('panel.edit')}}</a></td>
                        <td>
                        {!! Form::open(['route' => ['blublog.comments.destroy', $file->id], 'method' => 'DELETE']) !!}
                        {!! form::submit(__('panel.delete'), ['class' => 'btn btn-outline-danger btn-block ' ]) !!}
                        {!! Form::close() !!}
                        </td>
                        </tr>
                        @endforeach

                </tbody>
        </table>
        {!! $comments->links(); !!}
        <hr>
        @else
        <hr>
        <center> <b>Няма добавени.</b> </center>
        @endif
</div>


@endsection