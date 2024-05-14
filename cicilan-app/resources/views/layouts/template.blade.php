    <!DOCTYPE html>
    <html lang="en" data-bs-theme="green">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

        <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/@mahozad/theme-switch"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- kirim csrf token buat ajax --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Legalisasi App</title>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <style>
            /* import */
            .toggle-custom {
                position: absolute !important;
                top: 0;
                right: 0;
            }

            .toggle-custom[aria-expanded='true'] .glyphicon-plus:before {
                content: "\2212";
            }
            
            .hidden{
                display: none;
            }
            /* onono */
            tr th {
                text-align: center;
            }

            .dark-theme {
                /* background-color: yellow; */
                color: black;
            }

            .light-theme {
                background-color: #ecf0f1;
                color: #ecf0f1;
            }

            .theme-switch-wrapper {
                /* position: fixed; */
                top: 10px;
                right: 10px;
            }

            theme-switch {
                width: 50px;
                padding: 5x;
                /* background: #888; */

                /*
    * There is a special property called --theme-switch-icon-color
    * which you can set, to change the color of the icon (shapes) in the switch.
    * You can even make the color change with animation (for example, on mouse hover);
    * see https://gist.github.com/mahozad/a8114b6145cac721f7652aa7b0732cf6
    */
                --theme-switch-icon-color: var(--my-text-color);
                ;
                background: transparent;
                /* background: var(--my-page-background-color); */

            }

            .theme-switch input {
                display: none;
            }

            .slider {
                background-color: #ccc;
                bottom: 0;
                cursor: pointer;
                left: 0;
                position: absolute;
                right: 0;
                top: 0;
                transition: .4s;
            }

            .slider:before {
                background-color: white;
                bottom: 4px;
                content: "";
                height: 26px;
                left: 4px;
                position: absolute;
                transition: .4s;
                width: 26px;
            }

            input:checked+.slider {
                background-color: #3498db;
            }

            input:focus+.slider {
                box-shadow: 0 0 1px #3498db;
            }

            input:checked+.slider:before {
                transform: translateX(26px);
            }

            /* turuturuturuturuturtu */
            @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }

            body {
                font-family: 'Poppins', sans-serif;
                font-size: 0.875rem;
                opacity: 1;
                overflow-y: scroll;
                margin: 0;
                /* pppp */
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                /* height: 100vh; */
                margin: 0;
                transition: background-color 0.5s ease, color 0.5s ease;
            }


            a {
                color: var(--my-text-color);

                cursor: pointer;
                text-decoration: none;
                font-family: 'Poppins', sans-serif;
            }

            .main table {
                background: var(--table-bg);
            }

            main,
            .main {
                max-width: 100%;
            }


            li {
                list-style: none;
            }

            h4 {
                font-family: 'Poppins', sans-serif;
                font-size: 1.275rem;
                color: var(--bs-emphasis-color);
            }

            /* Layout for admin dashboard skeleton */

            .wrapper {
                align-items: stretch;
                display: flex;
                justify-content: end;
                width: 100%;
            }
            .searchPage{
                width: 50%;
            }

                    @media (max-width:767.98px) {
                    .searchPage{
                        width: 120%;
                    }
                    }

            #sidebar {

                max-width: 200px;
                min-width: 200px;
                /* background: linear-gradient(to bottom,red,blue,yellow); */
                background: var(--bs-dark);
                /* background: linear-gradient(to bottom, black,black,rgb(177, 177, 177)); */

                transition: all 0.35s ease-in-out;
                color: grey;
            }

            .main {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                min-width: 0;
                overflow: hidden;
                transition: all 0.35s ease-in-out;
                width: 100%;
                /* background: #d7d8da; */
                /* background: linear-gradient(to bottom, #d7d8da, gainsboro, rgb(177, 177, 177)); */
                /* background: linear-gradient(to bottom,#060047, #B3005E); */
                /* background: linear-gradient(to bottom,#060047, rgb(144, 144, 255)); */
                /* background: var(--bs-dark-bg-subtle); */
                background: var(--my-page-background-color);
                color: var(--my-text-color);
            }

            .main .jumbotron a {
                color: var(--my-text-under-color);

            }


            /* Sidebar Elements Style */

            .sidebar-logo {
                padding: 1.15rem;
            }

            .sidebar-logo a {
                color: white;
                font-size: 1.15rem;
                font-weight: 600;
            }

            .sidebar-nav {
                flex-grow: 1;
                list-style: none;
                margin-bottom: 0;
                padding-left: 0;
                margin-left: 0;
            }

            .sidebar-header {
                color: #e9ecef;
                font-size: .75rem;
                padding: 1.5rem 1.5rem .375rem;
            }

            a.sidebar-link {
                padding: .625rem 1.625rem;
                color: #e9ecef;
                position: relative;
                display: block;
                font-size: 0.875rem;
            }

            .sidebar-link[data-bs-toggle="collapse"]::after {
                border: solid;
                border-width: 0 .075rem .075rem 0;
                content: "";
                display: inline-block;
                padding: 2px;
                position: absolute;
                right: 1.5rem;
                top: 1.4rem;
                transform: rotate(-135deg);
                transition: all .2s ease-out;
            }

            .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
                transform: rotate(45deg);
                transition: all .2s ease-out;
            }

            .roleName {
                color: var(--my-text-color);


            }

            .avatar {
                height: 40px;
                width: 40px;
                background: var(--my-text-under-avatar);
            }

            .navbar-expand .navbar-nav {
                margin-left: auto;
            }

            .content {
                flex: 1;
                max-width: 100vw;
                width: 100vw;
            }

            @media (min-width:768px) {
                .content {
                    max-width: auto;
                    width: auto;
                }
            }

            .card {
                box-shadow: 0 0 .875rem 0 rgba(34, 46, 60, .05);
                margin-bottom: 24px;
            }

            .illustration {
                background-color: var(--bs-primary-bg-subtle);
                color: var(--bs-emphasis-color);
            }

            .illustration-img {
                max-width: 150px;
                width: 100%;
            }

            /* Sidebar Toggle */

            #sidebar.collapsed {
                margin-left: -264px;
            }

            /* Footer and Nav */

            @media (max-width:767.98px) {

                .js-sidebar {
                    max-width: 100px;
                    min-width: 100px;
                    /* height: 10px; */
                    /* margin-left: -267px; */
                }
                
                #sidebar{
                    max-width: 120px;
                    min-width: 100px;
                }

                #sidebar.collapsed {
                    margin-left: 0;
                }

                .navbar,
                footer {
                    width: 100vw;
                }
            }

            /* Theme Toggler */

            .theme-toggle {
                position: fixed;
                top: 50%;
                transform: translateY(-65%);
                text-align: center;
                z-index: 10;
                right: 0;
                left: auto;
                border: none;
                background-color: var(--bs-body-color);
            }

            /*
            html[data-bs-theme="dark"] .theme-toggle .fa-sun,
            html[data-bs-theme="light"] .theme-toggle .fa-moon {
                cursor: pointer;
                padding: 10px;
                display: block;
                font-size: 1.25rem;
                color: #FFF;
            }
            

            html[data-bs-theme="dark"] .theme-toggle .fa-moon {
                display: none;
            }

            html[data-bs-theme="light"] .theme-toggle .fa-sun {
                display: none;
            } */
            html[data-theme="dark"] {
                /* --my-page-background-color: #112233; */
                --my-page-background-color: linear-gradient(to bottom, #060047, #B3005E);
                --my-text-color: #FF5F9E;
                --my-text-under-color: #ebb6ca;
                --table-bg: rgb(201, 201, 201);


            }

            html[data-theme="light"] {
                --my-text-color: black;
                --my-page-background-color: linear-gradient(to bottom, #d7d8da, gainsboro, rgb(177, 177, 177));
                --my-text-under-color: black;
                --my-text-under-avatar: transparent;
                --table-bg: whitesmoke;


            }

            html[data-theme="auto"] {
                /* --my-page-background-color: #112233; */
                --my-page-background-color: linear-gradient(to bottom, #43b143, #EEF0E5);
                --my-text-color: black;
                --my-text-under-color: black;
                --my-text-under-avatar: transparent;
                --table-bg: white;

            }

            li .sidebar-link:hover {
                background: rgb(24, 24, 24);
                /* background: var(--bs-dark); */
                color: white;
            }
        </style>
    </head>
    @include('sweetalert::alert')

    <body>
        <div class="wrapper">
            @if (Auth::check())

                <aside id="sidebar" class="js-sidebar">
                    <!-- Content For Sidebar -->
                    <div class="h-100" id="sb">
                        <div class="sidebar-logo">
                            <a href="/dashboard" >Legalisasi App</a>
                        </div>

                        <ul class="sidebar-nav">
                            {{-- <li class="sidebar-header">
                                Admin Elements
                            </li> --}}
                            {{-- Auth::check -> cek apakah sudah ada data login atau belum, kalau ada munculin menu navnya --}}
                            <li class="sidebar-item">
                                <a href="/dashboard" aria-current="page" class="sidebar-link">
                                    <i class="fa-solid fa-list pe-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            {{-- cek value dr column role table users data yg login, kalo value rolenya admin, li dimunculkan --}}

                            @if (Auth::user()->role == 'admin')
                                <li class="sidebar-item sidebar-title" style="background:black;align-items:baseline;padding-top:10px;">
                                    <a href="#" class="sidebar-link collapsed">
                                        <p style="color: rgb(170, 170, 170);">NAVIGATION</p>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('user.data') }}" class="sidebar-link">
                                        <i class="fa-solid fa-list fa-user pe-2"></i>
                                        Kelola Akun
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->role == 'admin')
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link collapsed" data-bs-target="#pages"
                                        data-bs-toggle="collapse" aria-expanded="false"><i
                                            class="fa-solid fa-file-lines pe-2"></i>
                                        Data Product
                                    </a>
                                    <ul id="pages" class="sidebar-dropdown list-unstyled collapse"
                                        data-bs-parent="#sidebar">
                                        <li class="sidebar-item">
                                            <a href="{{ route('product.data') }}" class="sidebar-link">Data Produk</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('product.create') }}" class="sidebar-link">Tambah Produk</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('product.data.stock') }}" class="sidebar-link">Data Stock</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('detail_server.data') }}" class="sidebar-link">Data Server</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link collapsed" data-bs-target="#internal"
                                        data-bs-toggle="collapse" aria-expanded="false"><i
                                            class="fa-solid fa-server pe-2"></i>
                                        Data Internal
                                    </a>
                                    <ul id="internal" class="sidebar-dropdown list-unstyled collapse"
                                        data-bs-parent="#sidebar">
                                        <li class="sidebar-item">
                                            <a href="{{ route('internal.Bogor') }}" class="sidebar-link">DC Asnet</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('internal.Jakarta') }}" class="sidebar-link">DC Cyber</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->role == 'admin')
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link collapsed" data-bs-target="#posts"
                                        data-bs-toggle="collapse" aria-expanded="false"><i
                                            class="fa-solid fa-sliders pe-2"></i>
                                        Data Client
                                    </a>
                                    <ul id="posts" class="sidebar-dropdown list-unstyled collapse"
                                        data-bs-parent="#sidebar">

                                        <li class="sidebar-item">
                                            <a href="{{ route('status.dedicated') }}" class="sidebar-link">Data
                                                Dedicated</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('status.colocation') }}" class="sidebar-link">Data
                                                Colocation</a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ route('status.sewaIndex') }}" class="sidebar-link">Data Sewa</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->role == 'user')
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link collapsed" data-bs-target="#order"
                                        data-bs-toggle="collapse" aria-expanded="false"><i
                                            class="fa-solid fa-sliders pe-2"></i>
                                        Data Order
                                    </a>
                                    <ul id="order" class="sidebar-dropdown list-unstyled collapse"
                                        data-bs-parent="#sidebar">
                                        <li class="sidebar-item">
                                            <a href="{{ route('order.index') }}" class="sidebar-link">Pembelian</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                        </ul>
                    </div>
                </aside>

                <div class="main" id="main" role="navigation" aria-label="my-main-navigation">

                    <nav class="navbar navbar-expand px-3 border-bottom" style="box-shadow: 0 1px 2px rgba(0, 0, 0, 5);">
                        <div class="btn-group mr-2" style="margin-right: 20px">

                            <button onclick="openNav()" type="button">Open</button>
                            <button onclick="closeNav()" type="button">Close</button>
                        </div>

                        <theme-switch></theme-switch>

                            <form action="{{ route('user.search') }}" method="GET" class="w-50"
                                style="margin-left:20px;">

                                <div class="">
                                    <div class="input-group w-100" data-widget="sidebar-search" style="max-width: 100%;">

                                        <select class="liveSearch form-control form-select w-75" id="adminSearch"
                                            name="searchUser" style="color:black;"></select>

                                        <div class="input-group-append w-25">
                                            <button class="btn btn-sidebar" style="background: whitesmoke;">
                                                <i class="fas fa-search fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        <div class="navbar-collapse navbar">

                            <ul class="navbar-nav">

                                <li class="nav-item dropdown mr-2">

                                    <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                        <img src="http://pluspng.com/img-png/user-png-icon-big-image-png-2240.png"
                                            class="avatar img-fluid rounded" alt=""
                                            style="margin-right: 30px;clear:left;">
                                        {{-- <i class="fa-regular fa-user pe-2"></i> --}}</img>

                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end"
                                        style="--bs-dropdown-item-padding-x: 4rem;">

                                        <a href="#" class="dropdown-item">Profile</a>
                                        <a href="#" class="dropdown-item text-align-center">Setting</a>
                                        <a href="{{ route('auth-logout') }}" class="dropdown-item">Logout</a>
                                    </div>
                                </li>

                            </ul>
                            {{-- modal --}}
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel" style="text-align: center;">
                                                Your Profile !</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3"
                                                style="display:flex;justify-content:center;align-items:center;">
                                                <img src="http://pluspng.com/img-png/user-png-icon-big-image-png-2240.png"
                                                    class="avatar img-fluid rounded" alt=""
                                                    style="width: 100px;height:100px;margin-top:10px;">
                                            </div>
                                            <form action="{{ route('user.update', Auth::user()->id) }}" method="post">
                                                @csrf
                                                {{-- jika terjadi error di validasi, akan ditampilkan bagian error nya : --}}
                                                @method('PATCH')
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">Name :</label>
                                                    <div class="col-sm-10">
                                                        <input disabled type="text" class="form-control"
                                                            value="{{ Auth::user()->name }}" name="name">
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">Email:</label>
                                                    <div class="col-sm-10">
                                                        <input disabled type="text" class="form-control"
                                                            value="{{ Auth::user()->email }}" name="email">
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-sm-2 col-form-label">NoTelp :</label>
                                                    <div class="col-sm-10">
                                                        <input disabled type="text" class="form-control"
                                                            value="{{ Auth::user()->notelp }}" name="notelp">
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">

                                                    <div class="col-sm-10">
                                                        <label class="col-sm-2 col-form-label">Role:</label>
                                                        <input disabled type="text" class="form-control"
                                                            value="{{ Auth::user()->role }}" name="role">
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">

                                                    <div class="col-sm-10">
                                                        <label class="col-sm-2 col-form-label">Password:</label>
                                                        <input disabled type="text" class="form-control"
                                                            value="{{ Auth::user()->password }}" name="password">
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">

                                                    <div class="col-sm-10">
                                                        <label class="col-sm-2 col-form-label">Company:</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ Auth::user()->company }}" name="company">
                                                    </div>
                                                </div>
                                                <button type="submit">Edit</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- modal dua --}}
                            {{-- mda dua --}}

                    </nav>
                    <main class="content px-3 py-2 " style="max-width:100%;" id="main">

                        <div class="table-responsive">

                            <!-- Table Element -->
                            {{-- <div class="card border-0"> 
                                <div class="card-header">
                                    <h5 class="card-title">
                                        Basic Table
                                    </h5>
                                    <h6 class="card-subtitle text-muted">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum ducimus,
                                        necessitatibus reprehenderit itaque!
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First</th>
                                                <th scope="col">Last</th>
                                                <th scope="col">Handle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td colspan="2">Larry the Bird</td>
                                                <td>@twitter</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> --}}
                            {{-- </div> --}}
                            {{-- </main --}}

                            {{-- </div> --}}
                            {{-- </div> --}}

            @endif
            <div class="container-fluid">
                <div class="container content px-3 py-2" style="max-width:100%">
                    @yield('content')
                    {{-- untuk menyimpan html yg sifatnya dinamis/berubah tiap page nya --}}
                    {{-- wajib diiisi ketika template dipanggil --}}
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

        <script src="js/script.js"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
            // Data Script Bootsratp 5
            <script src="https://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript" ></script>
            @stack('script') --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"> --}}
        {{-- // Data Script Bootsratp 5
            <script src="https://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript" ></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript" ></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <script>
            function openNav() {
                document.getElementById("sidebar").style.marginLeft = "-9px";
                // let sidekick = document.getElementById("sidebar");

                // console.log(sidekick);
            }

            function closeNav() {
                document.getElementById("sidebar").style.marginLeft = "-267px";
            }
        </script>

        <script type="text/javascript">
            let urlAdmin = "{{ route('status.sewaSearch') }}";
            let placeholderAdmin = 'Search Users';


            $('#adminSearch').select2({
                placeholder: placeholderAdmin,
                ajax: {
                    url: urlAdmin,
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.name,
                                }
                            })
                        };
                    },

                    cache: true
                }
            });
        </script>
        <script>
            document.addEventListener("themeToggle", event => {
                console.log(`Old theme: ${ event.detail.oldState }`);
                console.log(`New theme: ${ event.detail.newState }`);
                // More operations...
            });
        </script>

        {{-- </script> --}}


        {{-- mengisi js/css yg dinamis (optional) --}}
        {{-- diisi dengan push --}}
        @stack('script')
    </body>

    </html>
