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
<div class="jumbotron mt-2" style="padding:0px;">
    <div class="container">
        <h3><b>Data Order</b></h3>
        <p class="lead"><a href="/dashboard">Home</a>/<a href="{{route('user.data')}}">DataAkun</a>/<a href="#">DataEntry</a></p>
    </div>
</div>
<div class=
"modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle"> <b>Pilih Produkmu !</b></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <a type="button" class="btn btn-success" href="{{ route('admin.order.createDedicated',$userData->id) }}"
                          style="width: 49%">Dedicated</a>
                      <a type="button" class="btn btn-primary" href="{{ route('admin.order.createColocation',$userData->id) }}"
                          style="width: 49%">Colocation</a>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary w-25" data-dismiss="modal">Close</button>
                      {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                  </div>
              </div>
          </div>
      </div>
<div class="mt-1">
    {{-- <div class="d-flex justify-content-end">
        <a href="{{ route('admin.order.downloadExcel') }}" class="btn btn-success">Export Excel</a>
    </div> --}}
    <br>
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                    Pembelian Baru
                </button> {{-- search page --}}
            </div>
    <table class="table-stripped w-100 table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Client</th>
                <th>user</th>
                <th>Entry Data</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $uniqueCustomers = '';
             @endphp
                {{-- Cek apakah nama pelanggan sudah ditampilkan sebelumnya --}}
                @php
                
                $invoiceDate = Carbon\Carbon::parse($users['created_at'])
                            ->formatLocalized('%y%m%d');
                            $data = [];
                            $entryGet = data_get($users['entryData'],"0",0);
                            
                            // dd($users);   
                            if($entryGet !== 0){

                                for ($i=0; $i < count($users['entryData']); $i++) { 
                                    # code...
                                    $entryData = data_get($users['entryData'][$i],"0",0);
                                    # code...
                                    
                                    array_push($data,$entryData);
                                }
                                
                                $current = count($users['entryData']);
                            }else {
                                $data = 0;
                            }
            // dd(count($users['entryData'][1]));
            $dataGet = data_get($data,'0.0',false);
                @endphp
                {{-- @if($users['name'] ==  $uniqueCustomers) --}}
                    {{-- Jika sudah ditampilkan sebelumnya, lanjutkan ke iterasi berikutnya --}}
                    {{-- @continue --}}
                {{-- @endif --}}
                {{-- Jika belum pernah ditampilkan, tampilkan nama pelanggan --}}
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $users['name'] }} </td>
                    {{-- <td>
                        <ol>
                            @foreach ($userss as $innerOrder)
                                Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini
                                @if ($innerOrder['name'] == $users['name'])
                                    @foreach ($innerOrder['products'] as $product)
                                        <li>{{ $product['name_product'] }} <small>Rp. {{ number_format($product['price'], 0, '.', ',') }}<b>(qty : {{ $product['qty'] }})</b></small> = Rp. {{ number_format($product['price_after_qty'], 0, '.', ',') }}</li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ol>
                    </td> --}}
                    {{-- @dd($dataGet) --}}
                    
                   
                    <td>{{ $users['name'] }} <a href="mailto:user@gmail.com">({{ $users['email'] }})</a></td>
                    @if($data == 0 || $dataGet == false)
                    <td>Tidak ada Pesanan</td>
                    @else
                    <td>
                        {{-- @dd($data) --}}
                        @for ($i = 0; $i < $current; $i++)
                        
                        <li style="list-style-type:square;"><b>Product</b></li >
                            <br>
                            <ol>
                                
                                <li style=""><b> + Product ke {{ $i+1 }}</b></li >
                                    <br>
                            @foreach($data[$i] as $product)
                            
                            <li style="list-style-type:circle;">{{ $product['name_product'] }} <small>Rp.
                                {{ number_format($product['price'], 0, '.', ',') }}<b>(qty :
                                    {{ $product['qty'] }})</b></small> = Rp.
                                    {{ number_format($product['price_after_qty'], 0, '.', ',') }}</li>
                                    {{-- @endif --}}
                                    <hr>
                                    
                                    @endforeach
                                    
                                </ol>
                                <li style="list-style-type:square;"><b>Bulan </b> : {{$users['entryData'][$i][1]['bulan']}} bulan</li>
                                <br>
                                @if($users['entryData'][$i][2]['datacenter'] == "Jakarta")
                                <li style="list-style-type:square;"><b>Data Center </b> :  {{$users['entryData'][$i][2]['datacenter']}} Matrix</li>
                                @else
                                <li style="list-style-type:square;"><b>Data Center </b> :  {{$users['entryData'][$i][2]['datacenter']}} ASNET</li>
                                @endif
                                @php
                                $labelName = data_get($users['entryData'][$i],"3.label",0);
                                // dd($labelName);
                                @endphp
                                @if($labelName !== 0)
                                <br>
                                <li style="list-style-type:square;"><b>Label Name </b> :  {{$labelName}} </li>
                                @else
                                
                                @endif
                                <hr>
                                {{-- <li style="list-style-type:square;"><b>Data Center </b> :  {{$users['entryData'][$i]['OS']}} ASNET</li> --}}
                                @endfor
                                <br>

                    </td>


                    @endif
                </tr>
                {{-- Tambahkan nama pelanggan ke dalam array uniqueCustomers --}}
                {{-- @php $uniqueCustomers[] = $users['name']; @endphp --}}
            {{-- @dd($userss) --}}
        </tbody>
    </table>
    {{-- <div class="d-flex justify-content-end mb-3">
        @if ($users->count())
            {{ $users->links() }}
        @endif
    </div> --}}
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
@endpush
