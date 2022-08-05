<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">
    <title>Dashboard | {{ $title }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- Template CSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select.bootstrap4.min.css') }}">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
</head>

<body>
<div id="app">
    <div class="main-wrapper">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <div class="d-sm-none d-lg-inline-block">
                            Hi, {{ request()->session()->get('user')->name }}
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="/user/profile" class="dropdown-item has-icon">
                            <i class="far fa-user"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('/user/logout') }}" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <div class="main-sidebar">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="{{ url('dashboard') }}">MART v2</a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="{{ url('dashboard') }}">MT</a>
                </div>

                <ul class="sidebar-menu">
                    <li class="menu-header">Halaman Dashboard</li>
                    <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('dashboard') }}">
                            <i class="fa fa-home"></i>
                            <span>Halaman Home</span>
                        </a>
                    </li>

                    <li class="{{ Request::is('dashboard/user') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('dashboard/user') }}">
                            <i class="fa fa-user"></i> <span>Halaman User</span>
                        </a>
                    </li>

                    <li class="{{ Request::is('dashboard/data') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('dashboard/data') }}">
                            <i class="fa fa-users"></i> <span>Halaman Pendataan</span>
                        </a>
                    </li>

                    <li class="{{ Request::is('dashboard/transaction') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('dashboard/transaction') }}">
                            <i class="fa fa-book"></i> <span>Halaman Transaksi</span>
                        </a>
                    </li>

                    <li class="{{ Request::is('dashboard/tarif') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('dashboard/tarif')}}">
                            <i class="fa fa-file-invoice"></i> <span>Halaman Tarif K3</span>
                        </a>
                    </li>
                </ul>
            </aside>
        </div>