@extends('layouts.template')

@section('content')
    <style>
        a {
            color: black;
            text-decoration: none;
        }

        .card {
            border-radius: 10px;
            background: whitesmoke;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 5);
        }
    </style>
    <div class="jumbotron  mt-2" style="padding:0px;">
        <div class="container">
            {{-- @if (Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
            @endif --}}
            <h3><b>Penambahan Server</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="{{ route('detail_server.data') }}">DataServer</a>/<a
                    href="#">TambahServer</a></p>
        </div>
    </div>
    <form action="{{ route('detail_server.store', $order['id']) }}" method="post" class="card bg-light mt-5 p-5">
        {{-- sebagai-token-akses-database --}}
        @csrf
        {{-- jika terjadi error di validasi, akan ditampilkan bagian error nya : --}}
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
        {{-- <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Tipe Server :</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name">
            </div>
        </div> --}}
        <div class="mb-3 row">
            <label for="type" class="col-sm-2 col-form-label">Tipe Server :</label>
            <div class="col-sm-10">
                <select id="type" class="form-control" name="type">

                    <option value="dell">
                        Dell
                    </option>
                    <option value="HP">
                        HP
                    </option>
                    <option value="supermicro">
                        Supermicro
                    </option>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="series" class="col-sm-2 col-form-label">Seri Server:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="series" name="series">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="dimension" class="col-sm-2 col-form-label">Dimensi :</label>
            <div class="col-sm-10">
                <select id="dimension" class="form-control" name="dimension">
                    <option value="1U">
                        1U
                    </option>
                    <option value="2U">
                        2U
                    </option>
                    <option value="tower">
                        Tower
                    </option>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="serialNumber" class="col-sm-2 col-form-label">serial Number:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="serialNumber" name="serialNumber">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="" class="col-sm-2 col-form-label">Server Inventory:</label>
            {{-- <div class="col-sm-10">
                <input type="text" class="form-control" id="inventory[]" name="inventory[]">
            </div> --}}
      
        <table class="table table-bordered" id="dynamicAddRemove">
            <tr>
                <th>Ram</th>
                <th>Disk</th>
                <th>Processor</th>
                <th>Action</th>
            </tr>
            <tr>
                <td><input type="number" name="ram[]" placeholder="Enter ram" class="form-control" /></td>
                <td><input type="text" name="disk[]" placeholder="Enter disk" class="form-control" /></td>
                <td><input type="text" name="processor[]" placeholder="Enter processor" class="form-control" /></td>
                {{-- <td><input type="text" name="inventory[0][ram]" placeholder="Enter ram" class="form-control" /></td>
                <td><input type="text" name="inventory[0][disk]" placeholder="Enter disk" class="form-control" /></td>
                <td><input type="text" name="inventory[0][processor]" placeholder="Enter processor" class="form-control" /></td> --}}
                <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button></td>
            </tr>
        </table>
    <div class="mb-3 row">
        <label for="entryDate" class="col-sm-2 col-form-label">Tanggal masuk:</label>
        <div class="col-sm-10">
            <input type="date" name="entryDate" id="entryDate" class="form-control" style="width:100%;">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="serverLabel" class="col-sm-2 col-form-label">Server Label:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="serverLabel" name="serverLabel">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Data</button>
    </form>
@endsection
@push('script')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>

<script type="text/javascript">
    var i = 0;

    $("#add-btn").click(function() {

        ++i;

        $("#dynamicAddRemove").append('<tr><td><input type="number" name="ram[]" placeholder="Enter ram" class="form-control" /></td><td><input type="text" name="disk[]" placeholder="Enter disk" class="form-control" /></td><td><input type="text" name="processor[]" placeholder="Enter processor" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
            );
    });

    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });
</script>
@endpush