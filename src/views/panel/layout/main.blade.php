<!DOCTYPE html>
<html lang="en">

<head>
    @include('blublog::panel.layout._head')
</head>

<body>

    @include('blublog::panel.layout._nav')



    <div class="container p-4">
        @if (Session::has('success'))
            <div class="alert alert-dismissible alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        @if (Session::has('warning'))
            <div class="alert alert-dismissible alert-warning" role="alert">
                {{ Session::get('warning') }}
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-dismissible alert-danger" role="alert">
                {{ Session::get('error') }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-dismissible alert-danger" role="alert">
                <strong>Error:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li> {{ $error }} </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>


    @include('blublog::panel.layout._footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @livewireScripts
    <script>
        window.livewire.on('alert', param => {
            toastr[param['type']](param['message'], param['title']);
        });
        window.livewire.on('closeModal', id => {
            $(id).modal('hide');
        })
        window.livewire.on('showModal', id => {
            $(id).modal('show');
        })
    </script>

    @stack('scripts')
</body>

</html>
