<!DOCTYPE html>
<html lang="en">

<head>
    @include('blublog::front.layout._head')
</head>

<body>
    <div class="bg-dark p-2">
        <div class="container h4">
            <a href="{{ route('blublog.index') }}" class="text-light" style="text-decoration: none;">
                BLUblog</a>
        </div>
    </div>
    @yield('header')

    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                @if (Session::has('success'))
                    <div class="alert alert-dismissible alert-success" role="alert">
                        {{ Session::get('success') }}
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
            <div class="col-lg-3">
                @include('blublog::front.layout._sidebar')
            </div>
        </div>
    </div>
    @include('blublog::front.layout._footer')
</body>

</html>
