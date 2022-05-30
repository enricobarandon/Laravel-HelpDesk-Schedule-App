<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Styles -->
    <link href="{{ asset('css/min/backend.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    @yield('style')

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" id="app">
        <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <!-- <a href="/home" class="nav-link">Home</a> -->
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
            @endif

            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            @endif
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                <div class="dropdown">
            </li>
        @endguest
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <h3 class="brand-text font-weight-light">Sample</h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/default-150x150.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         
          <li class="nav-item">
            <a href="/home" class="nav-link {{ (request()->is('home')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/schedules" class="nav-link {{ (request()->is('schedules*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-calendar"></i>
              <p>
                Schedule
              </p>
            </a>
          </li>
          @if(Auth::user()->user_type_id == 1)
          <li class="nav-item">
            <a href="/users" class="nav-link {{ (request()->is('users')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="/accounts" class="nav-link {{ (request()->is('accounts*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-list"></i>
              <p>
                Accounts
              </p>
            </a>
          </li>
          @if(Auth::user()->email == 'enricobarandon@gmail.com')
          <li class="nav-item">
            <a href="/data" class="nav-link {{ (request()->is('data*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-list"></i>
              <p>
                Sync Data
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="/requests" class="nav-link {{ (request()->is('requests*')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-list"></i>
              <p>
                Requests
              </p>
            </a>
          </li>
          <li class="nav-item {{ (request()->is('groups/*')) ? 'menu-is-opening menu-open' : 'menu-close' }}">
            <a href="#" class="nav-link  {{ (request()->is('groups/*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Groups<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/groups/view/active" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Group</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/groups/view/deactivated" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Deactivated</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pullout</p>
                </a>
              </li>
            </ul>
          </li>
          @if(Auth::user()->user_type_id == 1)
          <li class="nav-item">
            <a href="/logs" class="nav-link {{ (request()->is('logs')) ? 'active' : '' }}">
              <i class="nav-icon fa fa-list"></i>
              <p>
                Activity Logs
              </p>
            </a>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
            <main class="py-4">
                @yield('content')
            </main>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->
    </div>
</body>



<!-- Scripts -->
<script src="{{ asset('js/min/backend.min.js') }}"></script>
@yield('script')

</html>
