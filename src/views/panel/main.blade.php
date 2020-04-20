
<!DOCTYPE html>
<html lang="en">
  <head>
    @include('blublog::panel.partials._head')
  </head>
  <body id="page-top">

      <!-- Page Wrapper -->
      <div id="wrapper">

          @include('blublog::panel.partials._nav')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

          <!-- Main Content -->
          <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

              <!-- Sidebar Toggle (Topbar) -->
              <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
              </button>
    @yield('navbar')

    <noscript>
        <div class="alert alert-warning" role="alert">
            Javascript not active.
        </div>
    </noscript>
              <!-- Topbar Navbar -->
              <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                  <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                  </a>
                  <!-- Dropdown - Messages -->
                  <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                      <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                          <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"> {{ __('panel.welcome') }}   {{ Auth::user()->name }}</span>
                  </a>
                  <!-- Dropdown - User Information -->
                  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ url('/panel/settings') }}">
                      <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                      Settings
                    </a>
                    <a class="dropdown-item" href="{{ url('/panel/tracker') }}">
                      <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                      Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal">
                      <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                      {{ __(' Излизане') }}
                    </a>
                  </div>
                </li>

              </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                @if ( isset($customerrors) and !is_null($customerrors))
                @foreach ( $customerrors as $error)
                <div class="alert alert-dismissible alert-danger" role="alert">
                    {{$error}}
                </div>
                @endforeach
                @endif

                @if (file_exists( storage_path().'/framework/down'))
                <div class="alert alert-warning" role="alert">
                    {{__('panel.maintenance')}}
                </div>
                @endif
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

                @if(count($errors) > 0)
                <div class="alert alert-dismissible alert-danger" role="alert">
                 <strong>Грешка:</strong>
                    <ul>
                    @foreach($errors->all() as $error)
                        <li> {{ $error }} </li>
                    @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
                @include('blublog::panel.partials._footer')
            </div>
            <!-- /.container-fluid -->

          </div>
          <!-- End of Main Content -->



        </div>
        <!-- End of Content Wrapper -->

      </div>
      <!-- End of Page Wrapper -->



  <script src="{{ url('/') }}/blublog\js/jquery.min.js"></script>
  <script src="{{ url('/') }}\blublog/js/bootstrap.min.js"></script>
  <script src="{{ url('/') }}\blublog/js/custom.js"></script>
  </body>
</html>
