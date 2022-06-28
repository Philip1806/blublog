<!DOCTYPE html>
<html lang="en">

<head>
    @include('blublog::panel.layout._head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>

    @include('blublog::panel.layout._nav')



    <div class="container p-4">
        @if (Session::has('success'))
            <script>
                let message = {{ Illuminate\Support\Js::from(Session::get('success')) }};
                toastr["success"](message, "Success");
            </script>
        @endif

        @if (Session::has('warning'))
            <script>
                let message = {{ Illuminate\Support\Js::from(Session::get('warning')) }};
                toastr["warning"](message, "Warning");
            </script>
        @endif

        @if (Session::has('error'))
            <script>
                let message = {{ Illuminate\Support\Js::from(Session::get('error')) }};
                toastr["error"](message, "Error");
            </script>
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
