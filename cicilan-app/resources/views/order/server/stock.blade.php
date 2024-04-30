@extends('layouts.template')

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
            <h3><b>Data Stock</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Stock</a></p>
        </div>
    </div>
{{-- input alert berhasil --}}
<div id="msg-success"><br></div>
<table class="table table-striped table-bordered table-hovered">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Stock</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1;@endphp
        @foreach ($products as $item)
        <tr>
            <td>{{$no++}}</td>
            <td>{{$item['name']}}</td>
            <td style="background: {{$item['stock'] <=0 ? 'red' : 'none'}}">{{$item['stock']}}</td>
            <td>
                <div class="btn btn-primary" onclick="edit({{$item['id']}})">Setting Stock</div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-end
">
@if ($products->count())
{{$products->links()}}
@endif
</div>

<!-- Modal -->
<div class="modal fade" id="tambah-stock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Stock</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post" id="form-stock">
        <div class="modal-body">
            {{-- alert --}}
            <div id="msg"></div>
            {{-- input tidak akan tertampil,biasanya digunakan untuk menyimpan data yg diperlukan di proses BE tapi tidak boleh diketahui/diubah user --}}
            <input type="hidden" name="id" id="id">
          <div>
            <label for="name" class="form-label">Nama Produk: </label>
            <input type="text" name="name" id="name" class="form-control" disabled>
          </div>
          <div>
            <label for="stock" class="form-label">Stock: </label>
            <input type="number" name="stock" id="stock" class="form-control" disabled>
          </div>
          <div class="mt-3">
          <button type="button" class="btn btn-secondary" onclick="setAdaStock()">Stock Ada</button>
          <button type="button" class="btn btn-primary" onclick="setHabisStock()">Stock Habis</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="edit({{$item['id']}})">Simpan</button>
        </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        function setAdaStock() {
            document.getElementById('stock').value = 1;
        }
        function setHabisStock() {
            document.getElementById('stock').value = 0;
        }
</script>
<script>
    // csrf token versi js
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content'),
        }
    });

    function edit (id){
        let url ="{{route('product.show','id')}}";
        url = url.replace('id',id);
        //Pengambilan data dari FE ke BE dijembatani oleh jquery ajax
        $.ajax({
            // rotenya gak pake method :: apa
            type:'GET',
            // link routenya dari let url
            url:url,
            // data yang dihasilkan berupa json
            contentType: 'json',
            // kalau proses ambil data berhasil,ambil data yang dikirim BE lewat parameter res 
            success : function(res){
                // munculkan modal yang id nya tambah-stock
                $("#tambah-stock").modal("show");
                // isi value input dari hasil response
                $('#name').val(res.name);
                $("#stock").val(res.stock);
                $('#id').val(res.id);

            }
        });
    }

    $("#form-stock").submit(function (e) {
        e.preventDefault();
        let id = $('#id').val();
        // ?jaga-jaga
        // route action penanganan update 
        let url ="{{route('product.stock.update', 'id')}}";
        url = url.replace ('id',id);
        // buat variabel data yang akan dikirim ke BE
        let data = {
            stock: $('#stock').val(),
        }

        $.ajax ({
            type : 'PATCH',
            url:url,
            data:data,
            cache:false,
            success: function (res){
                // jika berhasil,modal di hide
                $("#tambah-stock").modal("hide");
                // buat session js bernama 'successUpdateStock'
                sessionStorage.successUpdateStock = true;
                window.location.reload();
            }
            ,
            error: function (err) {
                // kalau terjadi error, pada element id=msg tambah class dengan value alert alert-danger
                $("#msg").attr("class","alert alert-danger");
                // isi teks element id=msg diambil dari response json bagian message
                $("#msg").text(err.responseJSON.message);

            }
        })
    });
    // function tanpa nama akan dijalankan ketika web baru selesai loading
    $(function(){
        if (sessionStorage.successUpdateStock){
            $("#msg-success").attr("class","alert alert-success");
            $("#msg-success").text("berhasil mengubah data stock!");
            // hapus kembali data sesion setelah alert success dimunculkan
            sessionStorage.clear();
        }
    });
</script>
@endpush