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
