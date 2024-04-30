@extends('layouts.template')

@section('content')
<style>
    span{
        color:red;
    }
</style>
<div class="jumbotron  mt-2" style="padding:0px;">
    <div class="container">
        @if (Session::get('failed'))
    <div class="alert alert-danger">{{Session::get('failed')}}</div>
    @endif
        <h3><b>Edit Account</b> </h3>
        <p class="lead"><a href="/dashboard">Home</a>/<a href="{{ route('user.data') }}">AccountData</a>/<a
                href="#">EditAccount</a></p>
    </div>
</div> 
    <form action="{{route('user.update', $user ['id'])}}" method="post" class="card bg-light mt-5 p-5">
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
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>            
        @endif
         {{-- <h3><i class="fa-regular fa-map pe-2">  </i>Billing Address</h3> --}}
         <h3>Personal Information</h3>
         <div class="row mt-3 mb-2">
             <div class="col-sm-6 w-50 ">
                <label for="email" class="col-form-label">Email<span>*</span> :</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{$user['email']}}" required>
             </div>
             <div class="col-sm-6 w-50 ">
                <label for="name" class="col-form-label">Username<span>*</span> :</label>
                <input type="text" class="form-control" id="name" name="name" value="{{$user['name']}}" required>
             </div>
             <div class="col-sm-6 w-50 mt-3 mb-3">
                <label for="notelp" class="col-sm-2 col-form-label">Notelp<span>*</span> :</label>
                    <input type="text" class="form-control" id="notelp" name="notelp" value="{{$user['notelp']}}" required>
             </div>
         </div>
         <h3>Billing Address</h3>

          @foreach ($user['address'] as $address2)
          <div class="row mt-3 mb-2">
            <div class="col-sm-6 w-50 ">
                <label for="address" class="col-sm-2 col-form-label">Address<span>*</span> :</label>
                <input type="text" class="form-control" id="address" name="address" value="{{$address2['address']}}" required>
            </div>
            <div class="col-sm-6 w-50 ">
                <label for="city" class="col-sm-2 col-form-label">city<span>*</span> :</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{$address2['city']}}" required>
            </div>
            <div class="col-sm-6 w-50 mt-3">
                <label for="state" class="col-sm-2 col-form-label">state<span>*</span> :</label>
                    <input type="text" class="form-control" id="state" name="state" value="{{$address2['state']}}" required>
            </div>
            <div class="col-sm-6 w-50 mt-3">
                <label for="country" class="col-sm-2 col-form-label">country<span>*</span> :</label>
                <input type="text" class="form-control" id="country" name="country" value="{{$address2['country']}}" required>
            </div>
            
            @endforeach
            <div class="col-sm-6 w-100 mt-3 mb-3">
                <label for="company" class="col-sm-2 col-form-label">Company :</label>
                    <input type="text" class="form-control" id="company" name="company">
            </div>
        </div>
        <h3>Additional Information</h3>
        <div class="row mt-3 mb-3">
            <div class="col-sm-6 w-50 ">
                <label for="role" class="col-sm-2 col-form-label">role :</label>
                <select id="role" class="form-control" name="role">
                    <option disabled hidden selected>Pilih</option>
                    <option value="admin"{{$user['role'] == 'admin' ? 'selected' : ''}}>Admin</option>
                    <option value="user"{{$user['role'] == 'user' ? 'selected' : ''}}>user</option>
                </select>
            </div>
            <div class="col-sm-6 w-50 ">
                <label for="password" class="col-sm-2 col-form-label">Password:</label>
                <input type="text" class="form-control" id="password" name="password" placeholder="Ubah Password">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </form>
@endsection
