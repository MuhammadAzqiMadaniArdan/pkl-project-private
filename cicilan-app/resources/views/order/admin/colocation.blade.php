@extends('layouts.template')

@section('content')
    <style>
        .card {
            border-radius: 10px;
            background:linear-gradient(to bottom,whitesmoke,rgb(218, 215, 215)) ;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 5);
        }

        a {
            color: black;
            text-decoration: none;
        }

        .card {
            border-radius: 10px;
            /* background: whitesmoke; */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 5);
        }

        .btn span.glyphicon {
            opacity: 0;
        }

        .btn.active span.glyphicon {
            opacity: 1;
        }
    </style>
     @php
     $data1 = [];
     $dataBulan = [];
        
    //  dd($data1,$productEntry['entryData'],$EntryAll,$dataBulan);

         $validate1 = data_get($data1,'0.type',1);
        
        
     if ($validate1 == 'colocation') {
         $validate = true;
         }else
         {
             $validate = true;
         }
 
     @endphp
    <div class="jumbotron  mt-2" style="padding:0px;">
        <div class="container">
            <h3><b>Penambahan Order</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="{{ route('admin.order.entryData',$userData->id) }}">DataPemesanan</a>/<a
                    href="#">TambahOrderColocation</a></p>
        </div>
    </div>
        @if($validate == true)

            <form action="{{ route('admin.order.adminStore') }}" class="card p-4 mt-5" method="POST">
               
        @csrf
        @if ($errors->any())
            {
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            }
        @endif
        <div class="mb-3 d-flex align-items-center">
            <label for="name_customer" class="form-label" style="width: 15%">Nama Pembeli :</label>
            <div class="p" style="width:85%; margin-bottom:1%;"><b>{{$userData->name }}</b></div>
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width:88%" value="{{$userData->name }}" hidden>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <label for="company" class="form-label" style="width: 12%">Perusahaan:</label>
            @if (Auth::user()->company == null)
                <input type="text" name="company" id="company" class="form-control" style="width:88%"
                    value="{{$userData->company }}"
                    placeholder="            Tidak Ada Keterangan Perusahaan 
            " disabled>
            @else
                <input type="text" name="company" id="company" class="form-control" style="width:88%"
                    value="{{$userData->company }}" disabled>
            @endif
        </div>
        @php
            $address = $userData;
        @endphp
        <div class="mb-3 d-flex align-items-center">
            @foreach ($address['address'] as $address2)
                <label for="address" class="form-label" style="width: 12%">Alamat Anda:</label>
                <input type="text" name="address" id="address" class="form-control" style="width:88%"
                    value="{{ $address2['address'] }} , {{ $address2['city'] }} City ,{{ $address2['state'] }} State"
                    disabled>

                {{-- <label for="address" class="form-label" style="width: 12%">Alamat Anda:</label>
            <input type="text" name="address" id="address" class="form-control" style="width:88%"> --}}
            @endforeach

        </div>

        <div class="mb-3 row" hidden>
            <label for="votes" class="form-label" style="width: 12%">Votes:</label>
            <input type="text" name="votes" id="votes" class="form-control" style="width:88%" value="1">
        </div>
        <div class="mb-3 row" hidden>
            <label for="data" class="form-label" style="width: 12%">data:</label>
            <input type="text" name="data" id="data" class="form-control" style="width:88%" value="1">
        </div>
        <div class="mb-3 row" hidden>
            <label for="status" class="form-label" style="width: 12%">Status:</label>
            <input type="text" name="status" id="status" class="form-control" style="width:88%" value="proses">
        </div>
        <div class="mb-3 row" hidden>
            <label for="access" class="form-label" style="width: 12%">Access:</label>
            <input type="text" name="access" id="access" class="form-control" style="width:88%" value="1">
        </div>
        <div class="mb-3 row" hidden>
            <label for="userId" class="form-label" style="width: 12%">ID user:</label>
            <input type="text" name="userId" id="userId" class="form-control" style="width:88%" value="{{ $userData->id }}">
        </div>
       

        <div class="mb-3">
            @foreach ($address['address'] as $address2)
                <div class="d-flex align-items-center">
                    {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                    {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                    <label style="width:12%;" for="products" class="form-label">Product :</label>
                    {{-- <div class="col-sm-10"> --}}
                    <select id="products" class="form-control form-select" style="width:88%" name="products[]">
                        <!-- ... opsi-opsi produk ... -->
                       
                        <option disabled hidden selected>Pilih</option>
                        @for ($i = 0; $i < 12; $i++)
                            @if ($products[$i]['type'] == 'colocation')
                                @if (
                                    $i < 12 && $i > 5
                                        )
                                    @if ($products[$i]['stock'] == 0)
                                        <option value="{{ $products[$i]['id'] }}" data-price="{{ $products[$i]['price'] }}"
                                            data-type="{{ $products[$i]['type'] }}" disabled style="background: red;">
                                            <p>(Colocation (HABIS)) {{ $products[$i]['name'] }}</p>
                                        </option>
                                    @else
                                        <option value="{{ $products[$i]['id'] }}"
                                            data-price="{{ $products[$i]['price'] }}"
                                            data-type="{{ $products[$i]['type'] }}">
                                            @if ($products[$i]['type'] == 'colocation' && $products[$i]['stock'] == 0)
                                                <p disbled style="background: red;">(Colocation (HABIS))
                                                    {{ $products[$i]['name'] }}</p>
                                            @else
                                                <p>(Colocation) {{ $products[$i]['name'] }}</p>
                                            @endif
                                        </option>
                                    @endif
                                {{-- @elseif(($address2['city'] == 'Jakarta' && $i == 9) || ($address2['city'] == 'Jakarta' && $i == 10)) --}}
                                    {{-- @if ($products[$i]['stock'] == 0)
                                        <option value="{{ $products[$i]['id'] }}"
                                            data-price="{{ $products[$i]['price'] }}"
                                            data-type="{{ $products[$i]['type'] }}" disabled style="background: red;">
                                            <p>(Colocation (HABIS)) {{ $products[$i]['name'] }}</p>
                                        </option>
                                    @else
                                        <option value="{{ $products[$i]['id'] }}"
                                            data-price="{{ $products[$i]['price'] }}"
                                            data-type="{{ $products[$i]['type'] }}">
                                            @if ($products[$i]['type'] == 'colocation' && $products[$i]['stock'] == 0)
                                                <p disbled style="background: red;">(Colocation (HABIS))
                                                    {{ $products[$i]['name'] }}</p>
                                            @else
                                                <p>(Colocation) {{ $products[$i]['name'] }}</p>
                                            @endif --}}
                                        </option>
                                    {{-- @endif --}}

                                    <p>hulum</p>
                                @endif
                            @endif
                        @endfor
                    </select>
                </div>
            @endforeach

        </div>

        <div class="mb-3" id="bulan-container" style="display: none;">
            <div class="d-flex align-items-center">

                {{-- @if ($isColocation == true) --}}
                <label style="width:12%;" for="bulan" class="form-label">Bulan Layanan
                    Product :</label>
                {{-- @else
                        <label style="width:50%;" for="bulan" class="col-sm-2 col-form-label">Bulan Layanan
                            Freeze (MAX/3) :</label> --}}
                {{-- @endif --}}
                {{-- <div class="col-sm-10"> --}}
                <select id="bulan" class="form-control form-select" style="width:88%" name="bulan">
                    {{-- @for ($i = 1; $i <= 12; $i++)
                                @if ($i == 1 || $i == 3 || $i == 6 || $i == 12)
                                    <option value="{{ $i }}">{{ $i }} Bulan - Tulisan Admin</option>
                                @endif
                            @endfor --}}
                </select>
            </div>
        </div>
        <hr>
        <h4 style="text-align: center;">Configurable Options</h4>
        <br>
        <div class="mb-3">
            <div class="d-flex align-items-center">
                {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                <label style="width:12%;" for="port" class="form-label">Port :</label>
                {{-- <div class="col-sm-10"> --}}
                <select id="port" class="form-control form-select" style="width:88%" name="port" selected>
                    <!-- ... opsi-opsi produk ... -->
                    {{-- <option disabled hidden selected>Pilih</option> --}}
                    <option value="1">Rj45 - 1 Gbps Port</option>
                    <option value="10">Gratis SFP+ 10 GBPS Port (Slot Terbatas)
                    </option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-center">
                {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                <label style="width:12%;" for="IP" class="form-label">IP :</label>
                {{-- <div class="col-sm-10"> --}}
                    <select id="IP" class="form-control form-select" style="width:88%" name="IP">
                        <option value="29" selected>/29 (5 IP,Gratis)</option>
                        <option value="28">/28 (13 IP) - Rp.240.000 IDR</option>
                        <option value="27">/27 (30 IP) - Rp.720.000 IDR</option>
                        <option value="24">/24 (256 IP) - Rp.3.500.000 IDR</option>
                    </select>
                    

            </div>

        </div>
       
        <div class="mb-3 row" style="flex-wrap:nowrap;">

            <label for="bandwidth" class="form-label" style="width: 12%">Dedicated bandwidth
                Internasional :</label>
            <input type="number" name="bandwidth" id="bandwidth" class="form-control"
                style="width:10%;height:10%;margin-right:10px;margin-left:10px;clear:left;" value="0">
            <p style="display: block;float: right;" id="bandwidth-label">

                x Mbps (Bayar per tahun Promo 20 Ribu / Mbps) <span id="bandwidth-span">Rp 30,000 IDR</span>
            </p>
        </div>
        <hr>
        <h4 style="text-align: center;">Additional Information</br><i style="color: blue;font-size:15px;">(required fields are marked with *)</i></h4>
        <br>
        {{-- label Server --}}
        
        <div class="mb-3 row">
            <label for="serverLabel" class="form-label" style="width: 12%">Label Server*:</label>
            <input type="text" name="serverLabel" id="serverLabel" class="form-control" style="width:88%" required>
        </div>
        
        <br>
        {{-- )))))))))))))))))))))))MEnu Utama ((((((((((((((((( --}}

        <h3 style="margin-bottom:10px;text-align:center;" class="mb-5 mt-3">Server Setting</h3>
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
                    <option value="1U" >
                        1U
                    </option>
                    <option value="2U" >
                        2U
                    </option>
                    <option value="tower" >
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
        
      
        <div class="mb-3 row" hidden>
            <label for="payment" class="col-sm-2 col-form-label">Pembelian:</label>
            <div class="col-sm-10">
                <select id="payment" class="form-control" name="payment">
                    <option value="bulan">
                        Perbulan
                    </option>
                    <option value="lunas"  selected>
                        lunas
                    </option>
                    
                </select>
            </div>
        </div>
    <div class="mb-3 row">
        <label for="entryDate" class="col-sm-2 col-form-label">Tanggal masuk:</label>
        <div class="col-sm-10">
            <input type="date" name="entryDate" id="entryDate" class="form-control" style="width:100%;">
        </div>
    </div>
    {{-- <div class="mb-3 row">
        <label for="serverLabel" class="col-sm-2 col-form-label">Server Label:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="serverLabel" name="serverLabel">
        </div>
    </div> --}}
    <div class="mb-3 row">
        <label for="rack" class="col-sm-2 col-form-label">Server Rack:</label>
        <div class="col-sm-10">

            <input type="text" class="form-control" id="rack" name="rack">
        </div>
    </div>

        
        <button type="submit" class="btn btn-primary">Kirim</button>

    </form>

    @else
    <div class="card p-5 mt-5 mb-5" >

    <div class="alert alert-danger mt-2 p-5" ><h3 style="text-align: center;">Pesanan Client Bukan Produk Dengan Tipe Colocation !</h3></div>

    </div>
    @endif    
@endsection
@push('script')
    <!-- ... tag-tag lainnya ... -->
    <!-- ... bagian head dan tag-tag lainnya ... -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
        // Set nilai default pada dropdown bulan menjadi 1 saat dokumen siap
        document.addEventListener("DOMContentLoaded", function() {
            var bulanDropdown = document.getElementById('bulan');
            bulanDropdown.value = 1; // Set nilai default bulan menjadi 1
        });
    </script> --}}
    <script>
        
        // Add JavaScript to dynamically update the bulan dropdown based on the selected product
        document.getElementById('products').addEventListener('change', function() {
            var selectedProduct = this.options[this.selectedIndex];
            var bulanContainer = document.getElementById('bulan-container');
            // var cartContainer = document.getElementById('cart-container');
            var bulanDropdown = document.getElementById('bulan');
            // var cartButtoner = document.getElementById('cart1');
            var productType = selectedProduct.getAttribute('data-type');
            var price = selectedProduct.getAttribute('data-price');
            var selectedBulan = selectedProduct.getAttribute('data-bulan');
            
            if (productType) {
                bulanContainer.style.display = 'block';
                // cartContainer.style.display = 'block';
                bulanDropdown.innerHTML = '';
             
                console.log(productType,bulanDropdown);

                if (productType === 'colocation') {
                    // Jika produk adalah colocation, tampilkan opsi bulan 1, 3, 6, dan 12
                    var allowedbulan = [1, 3, 6];
                    for (var i = 0; i < allowedbulan.length; i++) {
                        var option = document.createElement('option');
                        option.value = allowedbulan[i];
                        var formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format((price * allowedbulan[i]));
                        option.text = formattedPrice + ' IDR ' + allowedbulan[i] + ' bulan + Rp.500,000 IDR Biaya Setup';
                        bulanDropdown.appendChild(option);
                    }

                    var allowedbulan3 = [12];

                    for (var i = 0; i < allowedbulan3.length; i++) {
                        var option = document.createElement('option');
                        option.value = allowedbulan3[i];
                        var formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format((price * allowedbulan3[i] - 360000));
                        option.text = formattedPrice + ' IDR ' + allowedbulan3[i] + ' bulan + Rp.500,000 IDR Biaya Setup';
                        // option.text = allowedbulan2[i] + ' Bulan';
                        bulanDropdown.appendChild(option);
                    }
                        



                } 
            } else {
                bulanContainer.style.display = 'none';
                bulanDropdown.innerHTML = '';
            }
        });
    </script>
    {{-- script jauhan --}}
    <script>
        var bandwidthSpan = document.getElementById('bandwidth-span');
      
        var ipDropdown = document.getElementById('IP');

        console.log(bandwidthSpan.innerHTML);


            document.getElementById('bulan').addEventListener('change', function() {
                var selectedMonth = this.value; // Mendapatkan nilai bulan yang dipilih oleh pengguna
                console.log(selectedMonth); // Menampilkan nilai bulan yang dipilih dalam konsol
                // Di sini Anda dapat melakukan apa pun yang Anda inginkan dengan nilai bulan yang dipilih
                var price = 30000;
                if (selectedMonth == 3) {
                    var formattedPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format((price * 3));
                    bandwidthSpan.innerHTML = formattedPrice + " IDR";
                } else if (selectedMonth == 6) {
                    var formattedPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format((price * 6));
                    bandwidthSpan.innerHTML = formattedPrice + " IDR";
                } else if (selectedMonth == 12) {
                    var formattedPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(((price * 12) - 120000));
                    bandwidthSpan.innerHTML = formattedPrice + " IDR";
                } else {
                    var formattedPrice = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format((price));
                    console.log(selectedMonth)
                    bandwidthSpan.innerHTML = formattedPrice + " IDR";
                }

            });
            
        
    </script>
      <script>
        document.getElementById('bulan').addEventListener('change', function() {
            var selectedProductType = document.getElementById('products').options[document.getElementById('products').selectedIndex].getAttribute('data-type');
            var selectedMonth = this.value; // Mendapatkan nilai bulan yang dipilih oleh pengguna
            var ipDropdown = document.getElementById('IP');
            var ipPrices = [0, 240000, 720000, 3500000]; // Daftar harga IP address untuk setiap bulan
            var ipBulan = [29, 28, 27, 24]; // Daftar pilihan bulan IP
            var ipAddrress = [5, 13, 30, 256]; // Jumlah alamat IP untuk setiap opsi
            
            // Ubah harga IP address sesuai dengan bulan yang dipilih
            for (var i = 3; i >= 0; i--) {
                var formattedPrice = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format((ipPrices[i] * selectedMonth));
                console.log(ipPrices[3]);
                if (ipBulan[i] == 29) {
                    ipDropdown.options[i].text = '/' + ipBulan[i] + ' (' + ipAddrress[i] +' IP, GRATIS)';
                } else {
                    ipDropdown.options[i].text = '/' + ipBulan[i] + ' (' + ipAddrress[i] + ' IP) - ' + formattedPrice + ' IDR';
                }
                
                // Jika produk yang dipilih adalah colocation dan bulan tidak dipilih (misalnya, saat halaman dimuat), atur nilai IP dropdown sesuai dengan pilihan bulan
                if (selectedProductType === 'colocation') {
                    ipDropdown.value = ipBulan[i];
                } else if (selectedProductType !== 'colocation') {
                    ipDropdown.value = ipBulan[0]; // Jika produk yang dipilih bukan colocation, atur nilai IP dropdown sesuai dengan nilai default (29)
                }
            }
        });
    </script>
    
    
    <script>
        let no = 2;

        function addSelect() {
            let el = `
    <div class="d-flex align-items-center">
        <label for="products" class="form-label" style="width: 12%">Produk : </label>
        {{-- name dengan [] biasanya dipake buat column yang tipe datanya json/array,dan biasaanya digunakan apabila input dengan tujuan data yang sama ada banyak (dan dari input yang baanyak datanya tersebut , datanya akan diambil seluruhnya dalam bentuk array ) --}}
        <select type="text" name="products[]" id="products" class="form-control form-select" style="width:88%">
            <option selected hidden disabled>
                Pesanan 1
            </option>
            @foreach ($products as $product)
            @if ($product['type'] == 'cloud' || $product['type'] == 'dedicated')
            <option value="{{ $product['id'] }}" data-type="{{ $product['type'] }}">{{ $product['name'] }}</option>
            @endif
            @endforeach
        </select>
    </div>`;
            // gunakan jquery untuk mengambil html tempatel baru yang aka ditambahkan
            // append : menambahakn html bagian bawah 
            $("#wrap-select").append(el);
            no++;
        }
    </script>
    
  
    
    <!-- ... script-script lainnya ... -->
@endpush
