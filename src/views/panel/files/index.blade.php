@extends('blublog::panel.main')

@section('content')
<a href="{{ route('blublog.files.create') }}" class="btn btn-info btn-block">{{__('panel.upload')}}</a>
<br>
@if (!empty($files[0]->id))
<table class="table">
        <thead class="thead-light">
          <tr>
            <th scope="col">{{__('panel.title')}}</th>
            <th scope="col">Рзмер</th>
            <th scope="col">Публично</th>
            <th scope="col">Адрес</th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
                @foreach ( $files as $file )
                <tr>
                <td>
                  @if ($file->public)
                  <a href="/uploads/{{ $file->filename }}" > {{ $file->descr }} </a>
                  @else
                  {{ $file->descr }}
                  @endif
                </td>
                <td>{{ $file->size }}</td>
                <td>
                  @if ($file->public)
                  <span class="badge badge-success">{{__('general.yes')}}</span>
                  @else
                  <span class="badge badge-danger">{{__('general.no')}}</span>
                  @endif

                </td>
                <td>{{ $file->filename }}</td>
                <td><a href="{{ route('blublog.files.download', $file->id) }}" class="btn btn-primary btn-block" role="button">ИЗТЕГЛЯНЕ</a></td>
                <td>
                {!! Form::open(['route' => ['blublog.files.destroy', $file->id], 'method' => 'DELETE']) !!}
                {!! form::submit(__('panel.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
                {!! Form::close() !!}
                </td>
                </tr>
                @endforeach

        </tbody>
</table>
{!! $files->links(); !!}
<hr>
@else
<hr>
<center> <b>{{ __('front.no_results')}}</b> </center>
@endif

@endsection
