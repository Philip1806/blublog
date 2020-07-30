<!DOCTYPE html>
<html lang="en">
  <head>
    @include('blublog::blublog.parts._head')
  </head>
  <body>
    @include('blublog::blublog.parts._nav')

    @yield('jumbotron')

    <div class="container">

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
        <div class="mb-4 row">
            @yield('content')
        </div>
        @yield('similar_posts')
    </div>
    <div class="p-1 mb-2 bg-primary text-white">
        <footer id="footer">
        <div class="container" >
            @include('blublog::blublog.parts._footer')
        </div>
        </footer>
    </div>


    <script src="{{ url('/blublog/js/jquery.min.js') }}"></script>
    <script src="{{ url('/blublog/js/bootstrap.min.js') }}"></script>
  </body>
</html>
