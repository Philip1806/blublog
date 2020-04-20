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
        <div class="row">
            @yield('content')
        </div>
    </div>
<div class="p-1 mb-2 bg-primary text-white " style="margin-top:50px;-webkit-box-shadow: 0px 0px 77px -1px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 0px 77px -1px rgba(0,0,0,0.75);
box-shadow: 0px 0px 77px -1px rgba(0,0,0,0.75);" >
    <footer id="footer">
    <div class="container" >
        @include('blublog::blublog.parts._footer')
    </div>
    </footer>
</div>


    <script src="{{ url('/blublog/js/jquery.min.js') }}"></script>
    <script src="{{ url('/blublog/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('/blublog/js/custom.js') }}../_assets/js/custom.js"></script>
  </body>
</html>
