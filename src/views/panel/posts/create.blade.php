@extends('blublog::panel.layout.main')
@section('nav')
    @include('blublog::panel.posts._nav')
@endsection
@section('content')
    {!! Form::open(['route' => 'blublog.panel.posts.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    @livewire('blublog-create-edit-post')
    {!! Form::close() !!}
@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"
        integrity="sha512-hCP3piYGSBPqnXypdKxKPSOzBHF75oU8wQ81a6OiGXHFMeKs9/8ChbgYl7pUvwImXJb03N4bs1o1DzmbokeeFw=="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(".select2-multi").select2();

    </script>
    <script>
        $(document).ready(function() {
            $('#editor').summernote({
                height: 400
            });
        });

    </script>
    <script>
        window.livewire.on('closeModal', () => {
            $('#staticBackdrop').modal('hide');
        })
        window.livewire.on('tagsUpdated', () => {
            $(".select2-multi").select2();
        })

    </script>
@endpush

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endpush
