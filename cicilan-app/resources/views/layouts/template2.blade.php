<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- kirim csrf token buat ajax --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Legalisasi App</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{-- Vertical Submenu --}}
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.viewer.min.css" integrity="sha512-j+crE6vH+36HXVIVv0DU8rlXJoUc9J0IK3+C5AjnpO+ak67P6Y+J7oOatZoTQr1VAgJ2g0XODHqdnzjUSU6JRg==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.viewer.min.js" integrity="sha512-c1W7jRI9obk9S2IVIDDFRiIcy02IkqFG+smf2xjqbFdFjPLI9gK6rV1o2D4WuYQmJmrO9CQhQI7nrm0JdS1I4Q==" crossorigin="anonymous" defer></script>
    {{-- --------------------------------botstrap 5 new ------------ --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cdbootstrap/css/cdb.min.css"/> --}}
  <style>
    .toggle-custom {
  position: absolute !important;
  top: 0;
  right: 0;
}
.toggle-custom[aria-expanded='true'] .glyphicon-plus:before {
  content: "\2212";
}
  </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Legalisasi App</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
              {{-- Auth::check -> cek apakah sudah ada data login atau belum, kalau ada munculin menu navnya --}}
              @if (Auth::check())
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/dashboard">DASHBOARD</a>
              </li>
              {{-- cek value dr column role table users data yg login, kalo value rolenya admin, li dimunculkan --}}
              @if (Auth::user()->role == "admin")
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Product
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('product.data')}}">Data Product</a></li>
                  <li><a class="dropdown-item" href="{{route('product.create')}}">Tambah Data</a></li>
                  <li><a class="dropdown-item" href="{{ route('product.data.stock')}}">Stock</a></li>
                </ul>
              </li>
              @endif
              @if (Auth::user()->role == 'admin')
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.order.data') }}">Data Pembelian</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('status.status') }}">Data Status</a>
              </li>
              @endif
              @if (Auth::user()->role == 'user')
              <li class="nav-item">
                <a class="nav-link" href="{{ route('order.index') }}">Pembelian</a>
              </li>
              @endif

              @if (Auth::user()->role == "admin")
              <li class="nav-item">
                <a class="nav-link" href="{{ route('user.data')}}">Kelola akun</a>
              </li>
              @endif
              <li class="nav-item">
                <a class="nav-link" href="{{ route('auth-logout') }}">Logout</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('user.data')}}">Kelola Akun</a>
              </li>
              @endif
            </ul>
          </div>
        </div>
    </nav>
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="text-align: center;">
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
              @php
                  $address = Auth::user();
              @endphp
              @foreach ($address['address'] as $address2)
                  <div class="mb-3 row">

                      <div class="col-sm-10">
                          <label class="col-sm-2 col-form-label">address:</label>
                          <input type="text" class="form-control"
                              value="{{ $address2['address'] }}" name="address">
                      </div>
                  </div>
                  <div class="mb-3 row">

                      <div class="col-sm-10">
                          <label class="col-sm-2 col-form-label">city:</label>
                          <input type="text" class="form-control"
                              value="{{ $address2['city'] }}" name="city">
                      </div>
                  </div>
                  <div class="mb-3 row">

                      <div class="col-sm-10">
                          <label class="col-sm-2 col-form-label">state:</label>
                          <input type="text" class="form-control"
                              value="{{ $address2['state'] }}" name="state">
                      </div>
                  </div>
                  <div class="mb-3 row">

                      <div class="col-sm-10">
                          <label class="col-sm-2 col-form-label">country:</label>
                          <input type="text" class="form-control"
                              value="{{ $address2['country'] }}" name="country">
                      </div>
                  </div>
              @endforeach

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
</div> --}}
    <div class="container">
        @yield('content') 
        {{-- untuk menyimpan html yg sifatnya dinamis/berubah tiap page nya --}}
        {{-- wajib diiisi ketika template dipanggil --}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    // Data Script Bootsratp 5
    <script src="https://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript" ></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript" ></script>

  
    </script>

    {{-- mengisi js/css yg dinamis (optional) --}}
    {{-- diisi dengan push --}}
    @stack('script')
</body>
</html>