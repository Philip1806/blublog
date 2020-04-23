@extends('blublog::panel.main')

@section('content')
<div class="card border-primary shadow">
        <div class="card-header text-white bg-primary">
         {{ __('panel.settings') }}
        </div>
        <div class="card-body">

            {{ Form::model( ['route' => ['blublog.settings.store' ], 'method' => "PUT", 'enctype' => 'multipart/form-data']) }}
            <div class="container">
                <div class="row">
                  <div class="col-lg-9">
                    @foreach ($settings as $setting)
                    @if ($setting->type == "text")
                    {{ Form::label($setting->name, $setting->name) }}
                    {{ Form::textarea($setting->name, unserialize($setting->val), ['class' => 'form-control','rows'=>'3']) }}
                    @endif
                    @if ($setting->type == "string")
                    {{ Form::label($setting->name, $setting->name) }}
                    {{ Form::text($setting->name, unserialize($setting->val), ['class' => 'form-control']) }}
                    @endif
                    @endforeach
                  </div>

                  <div class="col-lg-3">
                    @foreach ($settings as $setting)
                    @if ($setting->type == "int")
                    {{ Form::label($setting->name, $setting->name) }}
                    {{ Form::number($setting->name, unserialize($setting->val), ['class' => 'form-control']) }}
                    @endif
                    @if ($setting->type == "bool")
                    {{ Form::label($setting->name, $setting->name) }}
                    {{Form::hidden($setting->name,0)}}
                    {{Form::checkbox($setting->name, null,unserialize($setting->val))}}
                    @endif
                    @endforeach
                  </div>

                </div>
            </div>

            <br>
            {{ Form::submit('ЗАПИС', ['class' => 'btn btn-info btn-block']) }}
            {!! Form::close() !!}
        </div>
</div>



@endsection