{{-- memanggil file template --}}
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
        text-decoration: none;
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
            <h3><b>Data Produk</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Produk</a></p>
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
<div class="mt-2">
    <form action="{{ route('product.search') }}" method="GET">
  
    <div class="form-inline">
        <div class="input-group w-25" data-widget="sidebar-search">
            <input class=" form-control" name="search" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar" style="background: whitesmoke;">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
            <a href="{{ route('product.data') }}" class="btn btn-danger w-25 " style="margin-left:5%;border-radius:5px;">reset</a>
        </div>
        
    </form>
</div>
  
<div class="container-fluid">
    <div class="table-responsive">
        <table class="table mt-5 table-striped table-bordered table-hovered">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tipe</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no=1; @endphp
        @foreach ($products as $item)
        <tr>
            <td>{{$no++}}</td>
            <td>{{$item['name']}}</td>
            <td>{{$item['type']}}</td>
            <td>{{$item['price']}}</td>
            <td>{{$item['stock']}}</td>
            <td class="d-flex">
                <a href="{{route('product.edit', $item['id'])}}" class="btn btn-success">Edit</a>
                {{-- method delete tidak bisa digunakan di href harus pakai form --}}
                
                <a href="{{ route('product.delete', $item['id']) }}" class="btn btn-danger ms-2" data-confirm-delete="true">Delete</a>

                {{-- <form action="{{route('product.delete', $item['id'])}}" method="post" class="ms-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" >Hapus</button>

                </form> --}}
            </td>
        </tr>
        @endforeach

    </tbody>
    
</table>
    </div>
</div>
<div class="d-flex justify-content-end
">
@if ($products->count())
{{$products->links()}}
@endif
</div>
@endsection