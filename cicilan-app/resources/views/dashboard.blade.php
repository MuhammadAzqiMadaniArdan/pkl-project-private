@extends ('layouts.template')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
<style>
  .inner{
    max-width: 100%
  }
    .jumbotron{
        border-radius: 10px;
        background: whitesmoke;
        box-shadow: 0 5px 7px rgba(0, 0, 0, 5); 
    }
    img{
    width: 50px;
  }
  .card{
    border-radius: 10px;
        background: whitesmoke;
        box-shadow: 0 3px 7px rgba(0, 0, 0, 5);   }

  .card-flex{
    width: 12%;
    display: flex;
    justify-content: space-between;
  }
  .card-text{
    font:bold;
    font: medium;
    font-size: 30px;
  }
  .small-box{
    margin-right: 10px;
    max-width: 100%;
  }
  .row{
    display: flex;
    justify-content: center;
  }
  /* select{
    height: 100px;
  } */
  .inner{
    max-width: 100%;
  }
  @media (max-width:767.98px) {

  .inner p{
    font-size: 20%;
  }

  .display-4{
    font-size: 50px;
    max-width: 100%;
  }
  .container h3{
    font-size: 20px;
  }
  }
    </style>
<body>
  <div class="jumbotron p-4 bg-light mt-5">
    <div class="container">
      @if (Session::get('success'))
    <br>
    @include('sweetalert::alert')
    
    
    @endif
            {{-- @if(Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
            @endif --}}
            <h1 class="display-4">Legalisasi App</h1>
            {{-- <h3>Selamat Datang , </h3> --}}
            <h3>Selamat Datang , {{Auth::user()->name}}</h3>
            <p class="lead">Aplikasi percobaan cicilan dari menu Order dan download SPK ('7')</p>
        </div>
    </div>
    @if (Auth::user()->role == "admin")
  <div class="row mt-2 mb-3 w-100">

    <div class="small-box bg-gradient-success w-25" >
      <div class="inner">
        <h3>{{$users}}</h3>
        <p>Customers</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-plus"></i>
      </div>
      <a href="{{route('user.data')}}" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <div class="small-box bg-info w-25" >
      <div class="inner">
        <h3>{{$colocation}}</h3>
        <p>New Colocation </p>
      </div>
      <div class="icon">
        <i class="fas  fa-building"></i>
      </div>
      <a href="{{route('status.colocation')}}" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <div class="small-box bg-warning w-25" >
      <div class="inner">
        <h3>{{$dedicated}} </h3>
        <p>Dedicated Server</p>
      </div>
      <div class="icon">
        <i class="fas fa-server"></i>
      </div>
      <a href="{{route('status.dedicated')}}" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <div class=" small-box bg-danger w-25" >
      <div class="inner " >
        <h3>{{$product}}</h3>
        <p>All Products</p>
      </div>
      <div class="icon">
        <i class="fas fa-briefcase"></i>
      </div>
      <a href="{{route('product.data')}}" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <div class=" small-box bg-secondary w-25">
      <div class="inner">
        <h3>{{$order->count()}}</h3>
        <p>All Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-dollar"></i>
      </div>
      <a href="{{route('status.dedicated')}}" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
      
      @endif
</body>
@endsection
{{-- @push('script')
<script>
  const themeSwitch = document.getElementById('themeSwitch');

  themeSwitch.addEventListener('change', () => {
      document.body.classList.toggle('dark-theme', themeSwitch.checked);
      document.body.classList.toggle('light-theme', !themeSwitch.checked);
  });
</script>
@endpush --}}