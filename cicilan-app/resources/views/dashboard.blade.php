@extends ('layouts.template')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
<style>
  
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

    <div class="small-box bg-gradient-success mr-2" style="width: 24.5%;">
      <div class="inner">
        <h3>{{$users}}</h3>
        <p>Customers</p>
      </div>
      <div class="icon">
        <i class="fas fa-user-plus"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <div class="small-box bg-info mr-2" style="width: 24.5%;">
      <div class="inner">
        <h3>{{$colocation}}</h3>
        <p>New Colocation</p>
      </div>
      <div class="icon">
        <i class="fas  fa-building"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <div class="small-box bg-warning mr-2" style="width: 24.5%;">
      <div class="inner">
        <h3>{{$dedicated}} </h3>
        <p>Dedicated Server</p>
      </div>
      <div class="icon">
        <i class="fas fa-server"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
    <div class=" small-box bg-danger " style="width: 24.2%;">
      <div class="inner">
        <h3>{{$product}}</h3>
        <p>All Products</p>
      </div>
      <div class="icon">
        <i class="fas fa-briefcase"></i>
      </div>
      <a href="#" class="small-box-footer">
        More info <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
        {{-- <div class="col-sm-6 w-75 ">
          <a href="{{route('user.data')}}">
              <div class="card ">
            <div class="card-body">
              <h5 class="card-title">Customers</h5>
              <div class="card-flex">
                <img src="https://d3n8a8pro7vhmx.cloudfront.net/themes/5db7bca4c29480c061890f10/attachments/original/1553643295/login.png?1553643295" alt="" srcset="">
              <p class="card-text">{{$users}}</p>
            </div>
            </div>
          </div>
        </a>
        </div>
        <div class="col-sm-6 w-25">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Admin</h5>
              <div class="card-flex">
                <img src="https://d3n8a8pro7vhmx.cloudfront.net/themes/5db7bca4c29480c061890f10/attachments/original/1553643295/login.png?1553643295" alt="" srcset="">
              <p class="card-text" style="margin-left: 20px;">{{$admin}}</p>
            </div>
            </div>
          </div>
        </div>
      </div> --}}
      <div class="row mt-2 mb-3">
        <div class="col-sm-6 w-25">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Orders</h5>
              <div class="card-flex">
                <img src="https://vectorified.com/images/order-icon-png-2.png" alt="" srcset="">
              {{-- <p class="card-text">{{$letter_types['name_type']}}</p> --}}
              <p class="card-text" style="margin-left: 20px;">{{$order->count()}}</p>
            </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 w-75">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Products</h5>
              <div class="card-flex">
                <img src="https://corporacionelsol.com/wp-content/uploads/2020/06/icon-newspaper.png" alt="" srcset="">
              <p class="card-text" >{{$product}}</p>
            </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-2 mb-3">
        <div class="col-sm-6 w-50">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Dedicated</h5>
              <div class="card-flex">
                <img
                src="https://dihostingin.com/wp-content/uploads/2021/11/vcpu.png" class="img-responsive" />              {{-- <p class="card-text">{{$letter_types['name_type']}}</p> --}}
              <p class="card-text" style="margin-left: 10px;">{{$dedicated}}</p>
            </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 w-50">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Colocation</h5>
              <div class="card-flex">
                <img src="https://dihostingin.com/wp-content/uploads/2021/11/SSD.png"
                                    alt="">
              <p class="card-text" style="margin-left: 10px;">{{$colocation}}</p>
            </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @if (Auth::user()->role == "user")
      <div class="row mt-5">
        <div class="col-sm-6 w-100 ">
          <div class="card ">
            <div class="card-body">
              <h5 class="card-title">All Order</h5>
              <div class="card-flex">
                <img src="https://vectorified.com/images/order-icon-png-2.png" alt="" srcset="">
                @php
                $totalValues = 0; // inisialisasi variabel untuk menyimpan total nilai dalam array
                @endphp
                
                @foreach($order as $orderer)
                    @if(Auth::user()->name == $orderer['name_customer'])
                        @php 
                        // Menambahkan 1 ke total nilai setiap kali loop melalui nilai array
                        $totalValues += 1; 
                        @endphp
                    @endif
                @endforeach
                
                <p class="card-text">{{ $totalValues }}</p>
                
            </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row mt-2">
        <div class="col-sm-6 w-50 ">
          <div class="card ">
            <div class="card-body">
              <h5 class="card-title">Cicilan Dedicated</h5>
              <div class="card-flex">
                <img src="https://vectorified.com/images/order-icon-png-2.png" alt="" srcset="">
                
                <p class="card-text">{{ $dedicTotal }}</p>
                
            </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 w-50 ">
          <div class="card ">
            <div class="card-body">
              <h5 class="card-title">Layanan Colocation</h5>
              <div class="card-flex">
                <img src="https://vectorified.com/images/order-icon-png-2.png" alt="" srcset="">
                
                <p class="card-text">{{ $colloTotal }}</p>
                
            </div>
            </div>
          </div>
        </div>
      </div>
{{-- <div class="theme-switch-wrapper">
    <label class="theme-switch">
        <input type="checkbox" id="themeSwitch">
        <div class="slider"></div>
    </label>
</div> --}}



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