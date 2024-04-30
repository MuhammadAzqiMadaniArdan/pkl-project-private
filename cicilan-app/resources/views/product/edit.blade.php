@extends('layouts.template')

@section('content')
<style>
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
            <h3><b>Pengeditan Produk</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Edit Produk</a></p>
        </div>
    </div>
    <form action="{{route('product.update', $product ['id'])}}" method="post" class="card bg-light mt-5 p-5">
        {{--sebagai-token-akses-database --}}
        @csrf
        {{-- jika terjadi error di validasi, akan ditampilkan bagian error nya : --}}
        @method('PATCH')
        {{-- menimpa method post agar berubah menjadi patch --}}
        @if ($errors->any())
            <ul class="alert alert-danger p-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        {{-- jika berhasil munculkan notifnya : --}}
        @if (Session::get('succes'))
            <div class="alert alert-success">{{ Session::get('succes') }}</div>            
        @endif
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Nama Produk :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{$product['name']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="type" class="col-sm-2 col-form-label">Type Produk :</label>
            <div class="col-sm-10">
                <select id="type" class="form-control" name="type">
                    <option disabled hidden selected>Pilih</option>
                    <option value="cloud"{{$product['type'] == 'cloud' ? 'selected' : ''}}>Cloud</option>
                    <option value="dedicated"{{$product['type'] == 'dedicated' ? 'selected' : ''}}>Dedicated</option>
                    <option value="colocation"{{$product['type'] == 'colocation' ? 'selected' : ''}}>Colocation</option>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="price" class="col-sm-2 col-form-label">Harga Produk :</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="price" name="price" value="{{$product['price']}}">
            </div>
        </div>
        {{-- <div class="mb-3 row">
            <label for="stock" class="col-sm-2 col-form-label">Stock Awal:</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="stock" name="stock">
            </div>
        </div> --}}
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </form>
@endsection
