@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow" style="margin-bottom:20px;">
    <div class="card-header text-white bg-primary">
     {{ __('blublog.update_info') }}
    </div>
    <div class="card-body">
        @if ($data['have_update'])
            <h3>{{$data['msg']}}</h3>
            <p>{{$data['fix']}}</p>
            <hr>
            @if ($can_update & !$data['major'])
            <div class="alert alert-info" role="alert">
                {{__('blublog.update_now')}}
            </div>
            @else
                <div class="alert alert-warning" role="alert">
                    {{__('blublog.cant_update')}}
                </div>
            @endif
        @else
            {{__('blublog.no_updates')}}
        @endif
    </div>
</div>
@endsection
