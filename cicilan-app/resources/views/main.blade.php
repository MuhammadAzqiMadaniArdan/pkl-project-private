<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{-- <title>Admin Dashboard</title> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- kirim csrf token buat ajax --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>Legalisasi App</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        :root {
            --blue: #1e90ff;
            --white: #ffffff;
        }

        body {

            margin: 0;
            padding: 0;
            /* height: 100vh; Mengisi tinggi seluruh viewport */
            /* background: linear-gradient(to bottom right,purple,blue); */
            background: linear-gradient(to bottom right, darkred, black);
            background-size: cover;
            /* background-position: center; */
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            /* background: linear-gradient(to bottom right, black, purple); */
            color: white;
            opacity: 0, 1;
            padding: 20px;
            padding-top: 20px;
            /* background-color: var(--blue); */
            overflow: hidden;
  position: fixed; /* Set the navbar to fixed position */
  top: 0; /* Position the navbar at the top of the page */
  width: 100%; /* Full width */
  /* box-shadow: 0 0 10px rgba(0, 0, 2, 3); */
        }

        .nav-item a,
        .navbar-brand {
            color: white;
            font-weight: 600;
        }

        .nav-item a:hover,
        .navbar-brand:hover {
            color: aqua;
            font-weight: 600;
        }

        .navbar-brand img {
            width: 300px
        }

        .card {
            box-shadow: 0 0 10px rgba(0, 0, 2, 3);

        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        .container {
            margin: 0;
            padding: 0;
        }

        .C1 {
            width: 100%;
            background: red;
        }

        .box {
            --mask:
                radial-gradient(201.56px at 50% 275.00px, #000 99%, #0000 101%) calc(50% - 200px) 0/400px 100%,
                radial-gradient(201.56px at 50% -175px, #0000 99%, #000 101%) 50% 100px/400px 100% repeat-x;
            -webkit-mask: var(--mask);
            mask: var(--mask);
        }

        .L1 {
            height: 100%;
            background:
                /* top, transparent black, faked with gradient */
                linear-gradient(rgba(0, 0, 0, 0.7),
                    rgba(0, 0, 0, 0.7)), url(https://i.pinimg.com/originals/de/1a/0d/de1a0d60ba98d22aa76f07e781205e72.gif);
            background-size: cover;
        }

        .text1 {
            width: 50%;
            padding: 200px 50px;
            color: white;
            padding-bottom: 50px;
            margin-bottom: 20px;
        }

        .C2 {
            width: 100%;
            background: linear-gradient(to bottom left, white, gray);
            padding: 20px;
            padding-bottom: 50px;
        }

        .C2 .text2 {
            text-align: center;
            color: black;

        }

        .C2 h1 {
            color: darkblue;
            font-weight: bold;
        }

        .C2 .img-card img {
            width: 30%;
            margin-bottom: 20px;
        }

        .card1 {
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .card1 a {
            font-weight: bold;
        }

        .container-sm {
            background: #000;
        }

        .dds {
            color: aqua;

        }

        .btn-info {
            width: 130px;
            text-align: center;

        }

        .C3 .card{
            box-shadow: none;
            border: none;
            background-color: rgba(0, 0, 0, 0);
        }
        .card2{
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .card2 a {
            font-weight: bold;
        }
        .card2 h5{
            font-weight: 600;
        }
        .card2 p{
            font-size: 18px;
        }

        .card2 .row {
            display: flex;
            justify-content: space-around;
        }

        .C3 .img-card img {
            width: 280px;
        }
    </style>


</head>

<body>

    {{-- @csrf --}}

    {{-- <div class="container-fluid1"> --}}
        
    <div class="C1">

        <div class="L1">
            <header>
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">
                            {{-- <img
                                src="https://dihostingin.com/wp-content/uploads/2021/11/White-Dihostingin-with-IKADA.png"
                                alt=""> --}}
                                LEGALISASI APP</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="d-flex space-around" id="navbarText">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#layanan">Features</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register.index') }}">Register</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                            </ul>
                            {{-- <span class="navbar-text">
                          Navbar text with an inline element
                        </span> --}}
                        </div>
                    </div>
                </nav>
            </header>
            <div class="text1">
                <h2><b>Cicil Dedicated Server</b></h2>
                <h1><strong>Nyicil <span class="dds">Server</span> lebih Efektif dan <span class="dds">Murah !
                        </span></strong></h1>
                <h6><b><q>Nyicil Harga Anak Kosan</q></b></h6>
                <br>
                <div class="btn btn-info"><b>Beli Disini</b></div>
            </div>
        </div>
    </div>
    <div class="C2" id="layanan">
        <div class="text2 mb-5">
            <h1>Layanan Kami</h1>
            <h4><b>Harga Kosan Kualitas Sultan</b> </h4>
        </div>
        <div class="card1">
            <div class="row" style="margin-right: 1px;">
                <div class="col-sm-6 mb-3 mb-sm-0 w-25">
                    <div class="card p-2">
                        <div class="card-body">
                            <div class="img-card"><img
                                    src="https://dihostingin.com/wp-content/uploads/2021/11/Server-Ujian-Online.png"
                                    alt=""></div>
                            <h5 class="card-title">Paket VPS 10 Gb</h5>
                            <a href="#" class="btn btn-secondary">Tidak Tersedia</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 w-25">
                    <div class="card p-2">
                        <div class="card-body">
                            <div class="img-card"><img src="https://dihostingin.com/wp-content/uploads/2021/11/vcpu.png"
                                    alt=""></div>
                            <h5 class="card-title">Colocation Server</h5>
                            <a href="{{route('login')}}" class="btn btn-primary">More Info</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0 w-25">
                    <div class="card p-2">
                        <div class="card-body">
                            <div class="img-card"><img src="https://dihostingin.com/wp-content/uploads/2021/11/SSD.png"
                                    alt=""></div>
                            <h5 class="card-title">Dedicated Server</h5>
                            <a href="{{route('login')}}" class="btn btn-primary">More Info</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 w-25">
                    <div class="card p-2">
                        <div class="card-body">
                            <div class="img-card"><img
                                    src="https://dihostingin.com/wp-content/uploads/2021/11/Server-1.png"
                                    alt=""></div>
                            <h5 class="card-title">Unlimited Cloud Hosting</h5>
                            <a href="#" class="btn btn-secondary">Tidak Tersedia</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="C3">
            <div class="text3 mb-5 mt-5" style="text-align: center">
                <h1>Kenapa Pilih Dihostingin ? </h1>
            </div>
            <div class="card2">
                <div class="row" style="margin-right: 1px;">
                    <div class="col-sm-7 mb-3 mb-sm-0 w-25">
                        <div class="card p-2">
                            <div class="card-body">
                                <div class="img-card"><img
                                        src="https://dihostingin.com/wp-content/uploads/2021/11/choose-us-1.png"
                                        alt=""></div>
                                <h5 class="card-title">Safe and Secured</h5>
                                <p>Tim kami menjamin website anda selalu aman dan terjamin

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-3 mb-sm-0 w-25">
                        <div class="card p-2">
                            <div class="card-body">
                                <div class="img-card"><img
                                        src="https://dihostingin.com/wp-content/uploads/2021/11/choose-us-1.png"
                                        alt=""></div>
                                <h5 class="card-title">99.5% Uptime Guarantee
                                </h5>
                                <p>Uptime sampai dengan 99,5% tanpa gangguan. Test kecepatan

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 w-25">
                        <div class="card p-2">
                            <div class="card-body">
                                <div class="img-card"><img
                                        src="https://dihostingin.com/wp-content/uploads/2021/11/choose-us-1.png"
                                        alt=""></div>
                                <h5 class="card-title">Our Dedicated Support
                                </h5>
                                <p>Uptime sampai dengan 99,98% tanpa gangguan

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(function(){
 var visibleTop = 50;
  $(window).scroll(function() {
    var scroll = getCurrentScroll();
      if ( scroll >= visibleTop ) {
           $('header.Header').addClass('tuan');
        }
        else {
            $('header.Header').removeClass('tuan');
        }
  });
function getCurrentScroll() {
    return window.pageYOffset || document.documentElement.scrollTop;
    }
});
</script>
<style>
  header.Header.Header--top.tuan {
    background-color: green;
} --}}
<script>
    var scroll = document.getElementById('Scroll');
    if(Scroll < scrollGetObject()){
        scroll.createElement.class = "scrollDown";
        if(scroll.getElementbyClass("scrollDown")){
            scrollDown.style.background = "green";

        }else{
            console.log('error1');

        }
    }else{
        console.log('error2');
    }

</script>
