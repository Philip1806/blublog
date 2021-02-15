@extends('blublog::panel.layout.main')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            @livewire('blublog-img-section')
        </div>
        <div class="col-lg-4">
            @livewire('blublog-upload-img')
        </div>
    </div>
@endsection
