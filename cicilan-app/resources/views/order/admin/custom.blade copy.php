@extends('layouts.template')

@section('content')

    <form action="{{ route('status.update', $orders['id']) }}" method="post" class="card bg-light mt-5 p-5"
        enctype="multipart/form-data">
        {{-- sebagai-token-akses-database --}}
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
            <label for="custom_name" class="col-sm-2 col-form-label">Nama Produk :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="custom_name" name="custom_name" value="{{$orders['products'][1]['name_product']}}" disabled>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="custom_price" class="col-sm-2 col-form-label">Price :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="custom_price" name="custom_price">
            </div>
        </div>
        <div class="mb-3 row" hidden>
            <label for="custom_qty" class="col-sm-2 col-form-label">Qty:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="custom_qty" name="custom_qty" value="1" >
            </div>
        </div>
        @if ($orders['votes'] >= 4)
            <div class="mb-3 row">
                <label for="access" class="col-sm-2 col-form-label">Suspend :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="access" name="access" value="2">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="freeze" class="col-sm-2 col-form-label">Freeze :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="freeze" name="freeze" value="1">
                </div>
            </div>
        @endif

        @if ($orders['votes'] < 4)
            <button type="submit" class="btn btn-primary">Confirm Status user</button>
        @endif

        @if ($orders['bulan'] == 3)
            <button type="button" class="btn btn-success" onclick="setUnfreezeStatus()">UnFreeze User</button>
        @endif

        @if ($orders['votes'] >= 4)
            {{-- <div class="btn-group mt-3" role="group" aria-label="Status User">
                <button type="button" class="btn btn-primary w-50" style="border-radius:5px"
                    onclick="setFreezeStatus()">Freeze User</button>
                <button type="button" class="btn btn-primary w-50" style="margin-left: 2%;border-radius:5px"
                    onclick="setSuspendStatus()">Unsuspend User</button>
            </div> --}}
            <div class="mt-3">
                <button type="submit" class="btn btn-success w-100">Confirm Akses User</button>
            </div>
        @endif

    </form>
@endsection

@push('script')
    <script>
        function setFreezeStatus() {
            document.getElementById('freeze').value = 2;
        }

        function setUnfreezeStatus() {
            document.getElementById('freeze').value = 3;
        }

        function setSuspendStatus() {
            document.getElementById('freeze').value = 1;
        }
    </script>
@endpush
