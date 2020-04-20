@extends('blublog::panel.main')

@section('navbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">{{ __('panel.Home') }}</li>
    </ol>
</nav>
@endsection
@section('content')
{!! Form::open(['route' => 'blublog.settings.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}



@foreach ($settings as $setting)
    @if ($setting->type == "string")
    {{ Form::text($setting->name, null, ['class' => 'form-control', 'placeholder'=> $setting->name]) }}

    @else
    {{ Form::text($setting->name, null, ['class' => 'form-control', 'placeholder'=>  $setting->name ]) }}

    @endif
@endforeach
asdas
{{ Form::submit(__('panel.add_post'), ['class' => 'btn btn-primary btn-block', 'style' => 'margin-top:20px;']) }}


{!! Form::close() !!}
@endsection
