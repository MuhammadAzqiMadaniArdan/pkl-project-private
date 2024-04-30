@extends('layouts.template')

{{-- isi bagian yield --}}
@section('content')
<style>
  table{
      background: whitesmoke;
      border-radius: 5px;
  }

  a{
      color: black;
      text-decoration:none;
    }
    .card{
      border-radius: 10px;
      background: whitesmoke;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 5); 
  }
</style>
<div class="jumbotron  mt-2" style="padding:0px;">
  <div class="container">
          {{-- @if(Session::get('failed'))
          <div class="alert alert-danger">{{Session::get('failed')}}</div>
          @endif --}}
          <h3><b>Data Akun</b> </h3>
          <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Akun</a></p>
      </div>
  </div>
@if (Session::get('success'))
<br>
@include('sweetalert::alert')

<div class="alert alert-success">
    {{Session::get('success')}}
</div>
@endif
@if (Session::get('deleted')
)
<br>
<div class="alert alert-success">
    {{Session::get('deleted')}}
</div>
@endif
<br>

<div class="mt-1">
  {{-- <form action="{{ route('user.search') }}" method="GET">

  <div class="form-inline">
      <div class="input-group w-25" data-widget="sidebar-search">
          <input class=" form-control" name="searchUser" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
              <button class="btn btn-sidebar" style="background: whitesmoke;">
                  <i class="fas fa-search fa-fw"></i>
              <button>
          </div>

      </div>

      
    </form>
     --}}
    <div class="d-flex justify-content-end
    ">
          <a href="{{ route('user.data') }}" class="btn btn-danger" style="margin-left:5%;margin-right:10px;border-radius:5px;">reset</a>
    <a class=" btn btn-primary " href="{{route('user.create')}}">Add Account</a>
    
    </div>
  </div>

<div class="jumbotron  mt-2" style="padding:0px;">
          {{-- @if(Session::get('failed'))
          <div class="alert alert-danger">{{Session::get('failed')}}</div>
          @endif --}}
          <h3>Admin Account </h3>
  </div>
<table class="table mt-4 table-striped table-bordered table-hovered">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            {{-- <th>password</th> --}}
            <th>Role</th>
            <th>Invoice</th>
            <th>NoTelp</th>
            <th>Address</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no=1; @endphp
        @foreach ($admin as $item)
        <tr>
          @php
                
          $invoiceDate = Carbon\Carbon::parse($item['created_at'])
                      ->formatLocalized('%y%m%d');

          @endphp
            <td>{{$no++}}</td>
            <td>{{$item['name']}}<br><a href="{{route('status.single',$item['id'])}}"><div class="btn btn-success ml-3 mt-2" style="margin-left:10px;">Detail</div></a></td>
            <td>{{$item['email']}}</td>
            {{-- <td>{{$item['password']}}</td> --}}
            <td>{{$item['role']}}</td>
            <td><a href="{{route('admin.order.entryData',$item['id'])}}" style="text-decoration: underline;">{{ $invoiceDate}}</a></td>

            <td>{{$item['notelp']}}</td>
            <td>
              <ol>
                {{-- @php
                $address2 = $item['address'];
                @endphp --}}
            @foreach ($item['address'] as $address2)
            <li>
              o Address : {{ $address2['address'] }} <br> o City : {{ $address2['city'] }}  <br> o State : {{ $address2['state'] }} <br> o Country : {{ $address2['country'] }} Country
            </li>
               
            @endforeach
          </ol>
        </div>
            </td>
            <td class="">
                <a href="{{route('user.edit', $item['id'])}}" class="btn  w-50 ms-4 mt-2" style="background: darkgreen;color:white;">Edit</a>
                {{-- <button type="button" class="btn btn-danger w-50 mt-2 ms-4" data-bs-toggle="modal" data-bs-target="#exampleModal" class="ms-4" style="margin: 2px 10px;margin-left:20px;">
                  Hapus
                </button> --}}
                <a href="{{route('user.delete', $item['id'])}}" class="btn btn-danger w-50 mt-2 ms-4" data-confirm-delete="true" style="background: darkred;color:white;">Delete</a>

              </td>
              <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Form Hapus</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Apakah anda ingin menghapus data ini
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                  <form action="{{route('user.delete', $item['id'])}}" method="post" class="ms-3">
                    {{-- Menimpa atau mengubahj method post menjadi method DELETE sesuai dengan method route(::delete) --}}
                    @csrf
                    @method('DELETE')
                  <button type="submit" class="btn btn-danger" data-confirm-delete="true">Hapus</button>
              </form>
                {{-- </div> --}}
              </div>
            </div>
          </div>

              </form>
        </tr>
        @endforeach

    </tbody>
    
</table>
<hr>
<div class="jumbotron  mt-2" style="padding:0px;">
          <h3>Users Account </h3>
  </div>
<table class="table mt-4 table-striped table-bordered table-hovered">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            {{-- <th>password</th> --}}
            <th>Role</th>
            <th>Invoice</th>
            <th>NoTelp</th>
            <th>Address</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no=1; @endphp
        @foreach ($users as $item)
        <tr>
          @php
                
          $invoiceDate = Carbon\Carbon::parse($item['created_at'])
                      ->formatLocalized('%y%m%d');

          @endphp
            <td>{{$no++}}</td>
            <td>{{$item['name']}}<br><a href="{{route('status.single',$item['id'])}}"><div class="btn btn-success ml-3 mt-2" style="margin-left:10px;">Detail</div></a></td>
            <td>{{$item['email']}}</td>
            {{-- <td>{{$item['password']}}</td> --}}
            <td>{{$item['role']}}</td>
            <td><a href="{{route('admin.order.entryData',$item['id'])}}" style="text-decoration: underline;">{{ $invoiceDate}}</a></td>

            <td>{{$item['notelp']}}</td>
            <td>
              <ol>
                {{-- @php
                $address2 = $item['address'];
                @endphp --}}
            @foreach ($item['address'] as $address2)
            <li>
              o Address : {{ $address2['address'] }} <br> o City : {{ $address2['city'] }}  <br> o State : {{ $address2['state'] }} <br> o Country : {{ $address2['country'] }} Country
            </li>
               
            @endforeach
          </ol>
        </div>
            </td>
            <td class="">
                <a href="{{route('user.edit', $item['id'])}}" class="btn  w-50 ms-4 mt-2" style="background: darkgreen;color:white;">Edit</a>
                {{-- <button type="button" class="btn btn-danger w-50 mt-2 ms-4" data-bs-toggle="modal" data-bs-target="#exampleModal" class="ms-4" style="margin: 2px 10px;margin-left:20px;">
                  Hapus
                </button> --}}
                <a href="{{route('user.delete', $item['id'])}}" class="btn  w-50 mt-2 ms-4" data-confirm-delete="true" style="background: darkred;color:white;">Delete</a>

              </td>
              <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Form Hapus</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Apakah anda ingin menghapus data ini
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                  <form action="{{route('user.delete', $item['id'])}}" method="post" class="ms-3">
                    {{-- Menimpa atau mengubahj method post menjadi method DELETE sesuai dengan method route(::delete) --}}
                    @csrf
                    @method('DELETE')
                  <button type="submit" class="btn btn-danger" data-confirm-delete="true" >Hapus</button>
              </form>
                {{-- </div> --}}
              </div>
            </div>
          </div>

              </form>
        </tr>
        @endforeach

    </tbody>
    
</table>
<div class="d-flex justify-content-end
">
@if ($users->count())
{{$users->links()}}
@endif
</div>

@endsection