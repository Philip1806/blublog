@extends('blublog::panel.layout.main')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            @livewire('blublog-img-section')
        </div>
        <div class="col-lg-4">
            <div class="lead p-2 bg-dark text-white text-center">
                Upload:
            </div>

            <div class="btn-group btn-block" role="group" wire:ignore>
                <button type="button" class="btn btn-secondary rounded-0" data-toggle="modal"
                    data-target="#uploadVideoFileModal">Video</button>
                <button type="button" class="btn btn-primary rounded-0" data-toggle="modal"
                    data-target="#uploadFileModal">Picture</button>
            </div>
            @include('blublog::livewire._modals')
        </div>
    </div>
@endsection
