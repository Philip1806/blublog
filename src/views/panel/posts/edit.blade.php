@extends('blublog::panel.layout.main')
@section('nav')
    <ul class="nav nav-pills nav-fill bg-light m-2">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('blublog.panel.posts.create') }}"><span class="oi oi-pencil"></span> Add
                Post</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('blublog.panel.categories.index') }}"><span class="oi oi-spreadsheet"></span>
                Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('blublog.panel.tags') }}"><span class="oi oi-tags"></span> Tags</a>
        </li>
    </ul>
@endsection
@section('content')
    {{ Form::model($post, ['route' => ['blublog.panel.posts.update', $post->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
    <livewire:blublog-create-edit-post :post="$post">
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
