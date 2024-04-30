@extends('layouts.template')

@section('content')
    <form action="{{route('userAkun.updateAkun', $user ['id'])}}" method="post" class="card bg-light mt-5 p-5">
        {{--sebagai-token-akses-database --}}
        @csrf
        {{-- jika terjadi error di validasi, akan ditampilkan bagian error nya : --}}
        @method('PATCH')
        {{-- menimpa method post agar berubah menjadi patch --}}
        <h2 style="text-align: center;">Edit Akunmu !</h2>
        <hr>
        @if ($errors->any())
            <ul class="alert alert-danger p-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        {{-- jika berhasil munculkan notifnya : --}}
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>            
        @endif
        <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Nama User :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{$user['name']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email" value="{{$user['email']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="notelp" class="col-sm-2 col-form-label">Notelp :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="notelp" name="notelp" value="{{$user['notelp']}}">
            </div>
        </div>
          @foreach ($user['address'] as $address2)
       
        <div class="mb-3 row">
            <label for="address" class="col-sm-2 col-form-label">Address :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="address" name="address" value="{{$address2['address']}}">
            </div>
        </div>
       
        <div class="mb-3 row">
            <label for="city" class="col-sm-2 col-form-label">city :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="city" name="city" value="{{$address2['city']}}">
            </div>
        </div>
       
        <div class="mb-3 row">
            <label for="state" class="col-sm-2 col-form-label">state :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="state" name="state" value="{{$address2['state']}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="country" class="col-sm-2 col-form-label">country :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="country" name="country" value="{{$address2['country']}}">
            </div>
        </div>
        @endforeach
        <div class="mb-3 row">
            <label for="company" class="col-sm-2 col-form-label">Company :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="company" name="company">
            </div>
        </div>
        <div class="mb-3 row" hidden>
            <label for="role" class="col-sm-2 col-form-label">role :</label>
            <div class="col-sm-10">
                <select id="role" class="form-control" name="role">
                    <option disabled hidden selected>Pilih</option>
                    <option value="admin"{{$user['role'] == 'admin' ? 'selected' : ''}}>Admin</option>
                    <option value="user"{{$user['role'] == 'user' ? 'selected' : ''}}>user</option>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="password" class="col-sm-2 col-form-label">Ubah Password:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="password" name="password" >
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </form>
@endsection
