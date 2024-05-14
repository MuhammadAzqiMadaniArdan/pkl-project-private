@extends ('layouts.template')
@section('content')
<style>
   body {
    margin: 0;
    padding: 0;
    height: 100vh; /* Mengisi tinggi seluruh viewport */
    /* background: linear-gradient(to bottom right,purple,blue); */
    background:
        /* top, transparent black, faked with gradient */ 
        linear-gradient(
          rgba(0, 0, 0, 0.7), 
          rgba(0, 0, 0, 0.7)
        ),            url( https://1.bp.blogspot.com/-cpHPv1dbpiE/Wf2dszf4_gI/AAAAAAAABq4/5DQ-RGG6D2Avqh07MW0twpFwUm_U3qVEwCLcBGAs/s1600/server.jpg);
            background-size: cover;
    background-position: center;
    font-family: 'Arial', sans-serif;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    /* text-align: center; */
}

h2{
    text-align: center;
    margin-bottom: 20px;
}
.container{
    max-width: 600px;
    /* padding: 20px; */
    /* background: rgba(255, 255, 255, 0.9); */
    /* border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); */
}

.card{
    box-shadow: 0 0 10px rgba(0, 0, 2, 3); 
    width: 600px;
    
}

.flex-content{
    display: flex;
    justify-content: center;
}
</style>
<div class="flex-content">
<form action="{{route ('auth-login')}}" class="card p-4 mt-5" method="POST">
    @csrf
    @if ($errors->any())
            <ul class="alert alert-danger p-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        {{-- menampilkan session dari controller auth login yang berada pada with namanya failed --}}
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
        @endif
        <h2>Login</h2>
        <div class="mb-3 mx-1">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" id="email" class="form-control" placeholder="masukkan email anda....">
        </div>
        <div class="mb-3 mx-1">
            <label for="password" class="form-label">password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="masukkan password anda....">
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
        <div class="footer mt-3">
            Not Registered ? <a href= {{ route('register.index') }}> Created Account</a>
        </div>
    </form>
</div>
    @endsection
