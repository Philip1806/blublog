
<div class="card border-primary shadow" style="margin-top:20px;">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.files') }}
    </div>
    @if (!empty($files[0]->id))
    <table class="table">
        <thead class="thead-light">
            <tr>
            <th scope="col">{{__('blublog.title')}}</th>
            <th scope="col">{{__('blublog.size')}}</th>
            <th scope="col">{{__('blublog.status')}}</th>
            <th scope="col">{{__('blublog.address')}}</th>
            <th scope="col"></th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $files as $file )
            <tr>
            <td>
                @if (isset($no_title))
                <img src="{{ $file->url }}" class="img-thumbnail" width="200">
                @else
                    @if ($file->public)
                    <a href="{{ $file->url }}" > {{ $file->descr }} </a>
                    @else
                    {{ $file->descr }}
                    @endif
                @endif
            </td>
            <td>{{ $file->size }}</td>
            <td>
                @if ($file->public)
                <span class="badge badge-success">{{__('blublog.public')}}</span>
                @else
                <span class="badge badge-danger">{{__('blublog.hide')}}</span>
                @endif

            </td>
            <td>{{ $file->filename }}</td>
            <td><a href="{{ route('blublog.files.download', $file->id) }}" class="btn btn-primary btn-block" role="button">{{__('blublog.download')}}</a></td>
            @can('delete', $file)
            <td>
                {!! Form::open(['route' => ['blublog.files.destroy', $file->id], 'method' => 'DELETE']) !!}
                {!! form::submit(__('blublog.delete'), ['class' => 'btn btn-danger btn-block ' ]) !!}
                {!! Form::close() !!}
            </td>
            @endcan
            </tr>
            @endforeach
        </tbody>
    </table>
    {!! $files->links(); !!}
    <hr>
    @else
    <div class="mb-5 mt-5 text-center"><h1>{{ __('blublog.no_results')}}</h1> </div>
    @endif
</div>

