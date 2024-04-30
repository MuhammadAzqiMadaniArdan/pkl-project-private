@extends('layouts.template')

@section('content')
    <form action="{{route('order.choose-collocation', $order ['id'])}}" method="post" class="card bg-light mt-5 p-5">
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
        <div class="mb-3 row" hidden>
            <label for="name_customer" class="col-sm-2 col-form-label">Nama Produk :</label>
            <div class="col-sm-10">
                <input hidden type="text" class="form-control" id="name_customer" name="name_customer" value="{{$order['name_customer']}}">
            </div>
        </div>
        <div class="mb-3" style="width: 100%;margin-left:30px;">
            <label style="width:50%;"for="name_product" class="col-sm-2 col-form-label">Klasifikasi Surat:</label>
            <div class="col-sm-10">
                <select  id="name_product" class="form-control" name="name_product">
                    <option disabled hidden selected>Pilih</option>
                    @foreach ($orders['products'] as $product)
                <option value="{{$product['type'] == "colocation" }}">{{$product['name_product']}}</option>
                @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="mb-3 row">
            <label for="price" class="col-sm-2 col-form-label">Harga Produk :</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="price" name="price" value="{{$product['price']}}">
            </div>
        </div> --}}
        {{-- <div class="mb-3 row">
            <label for="stock" class="col-sm-2 col-form-label">Stock Awal:</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="stock" name="stock">
            </div>
        </div> --}}
        <button type="submit" class="btn btn-primary">Bayar Cicilan</button>
    </form>
@endsection
