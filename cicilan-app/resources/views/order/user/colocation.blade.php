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
    {{-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> --}}
    <div class="jumbotron  mt-2" style="padding:0px;">
        <div class="container">
            {{-- @if (Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
            @endif --}}
            <h3><b>Penambahan Order</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="{{ route('order.index') }}">DataOrder</a>/<a
                    href="#">TambahOrderColocation</a></p>
        </div>
    </div>
    <form id="cart" action="{{ route('order.addToCart', Auth::user()->id) }}" class="card p-4 mt-5" method="GET">

    {{-- <form action="{{ route('order.store') }}" class="card p-4 mt-5" method="POST"> --}}
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
            <label for="name_customer" class="form-label" style="width: 15%">Client :</label>
            <div class="p" style="width:85%; margin-bottom:1%;"><b>{{ Auth::user()->name }}</b></div>
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width:88%" value="{{ Auth::user()->name }}" hidden>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <label for="company" class="form-label" style="width: 12%">Perusahaan:</label>
            @if (Auth::user()->company == null)
                <input type="text" name="company" id="company" class="form-control" style="width:88%"
                    value="{{ Auth::user()->company }}"
                    placeholder="            Tidak Ada Keterangan Perusahaan 
            " disabled>
            @else
                <input type="text" name="company" id="company" class="form-control" style="width:88%"
                    value="{{ Auth::user()->company }}" disabled>
            @endif
        </div>
        {{-- <div class="mb-3 d-flex align-items-center" hidden>
            <label for="name_customer" class="form-label" style="width: 12%">Client</label>
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width:88%">
        </div>
        <div class="mb-3 d-flex align-items-center" hidden>
            <label for="no_telp" class="form-label" style="width: 12%">NoTelp:</label>
            <input type="text" name="no_telp" id="no_telp" class="form-control" style="width:88%">
        </div>
        <div class="mb-3 d-flex align-items-center" hidden>
            <label for="company" class="form-label" style="width: 12%">Perusahaan:</label>
            <input type="text" name="company" id="company" class="form-control" style="width:88%">
        </div> --}}
        @php
            $address = Auth::user();
        @endphp
        {{-- @foreach ($address['address'] as $address2)
        <li style="list-style-type:circle;">

                                {{ $address2['address'] }} <small>
                                    {{$address2['city']}} City
                                    <b>(State : {{ $address2['state'] }})</b>
                                </small>
                            </li>
        @endforeach --}}
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
       

        {{-- <div class="mb-3"> --}}
        {{-- <div class="d-flex align-items-center">
                <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
        {{-- name dengan [] biasanya dipake buat column yang tipe datanya json/array,dan biasaanya digunakan apabila input dengan tujuan data yang sama ada banyak (dan dari input yang baanyak datanya tersebut , datanya akan diambil seluruhnya dalam bentuk array ) --}}
        {{-- <select type="text" name="products[]" id="products" class="form-control form-select"
                    style="width:88%">
                    <option selected hidden disabled>
                        Pesanan 1
                    </option>
                    @foreach ($products as $product)
                       
                        @if ($product['type'] == 'cloud' || $product['type'] == 'dedicated' || $product['type'] == 'colocation')
                            <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div> --}}
        {{-- karena akan ada js yg menampilkan select ketika di klik maka disediakan btempat elemen yang akan dihasilkan dari js tersebut --}}
        {{-- </div> --}}
        {{-- @foreach($products as $product)
        @php

            dd($products[11])
    
        @endphp
        @endforeach --}}
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
                                {{-- @if (
                                    ($address2['city'] == 'Bogor' && $i == 7) ||
                                        ($address2['city'] == 'Bogor' && $i == 8) ||
                                        ($address2['city'] == 'Bogor' && $i == 11) ||
                                        ($address2['city'] == 'bogor' && $i == 11) ||
                                        $address2['city'] == 'bogor') --}}
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
                <select id="port" class="form-control form-select" style="width:88%" name="port">
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
                        <option value="29">/29 (5 IP,Gratis)</option>
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
            <label for="label_product" class="form-label" style="width: 12%">Label Server:</label>
            <input type="text" name="label_product" id="label_product" class="form-control" style="width:88%">
        </div>
        <br>
        {{-- )))))))))))))))))))))))MEnu Utama ((((((((((((((((( --}}
        {{-- <div class="container">

            <div class="well well-sm mb-3">

                <label class="form-label">SSD SATA : </label>

                <div class="btn-group" data-toggle="buttons" style="width:88%;margin-left:40px;">

                    <label class="btn btn-success active">
                        <input type="radio" name="options" id="option2" autocomplete="off" chacked>
                        <span class="glyphicon glyphicon-ok"></span>
                    </label>

                    <label class="btn btn-primary">
                        <input type="radio" name="options" id="option1" autocomplete="off">
                        <span class="glyphicon glyphicon-ok"></span>
                    </label>

                    <label class="btn btn-info">
                        <input type="radio" name="options" id="option2" autocomplete="off">
                        <span class="glyphicon glyphicon-ok"></span>
                    </label>

                    <label class="btn btn-default">
                        <input type="radio" name="options" id="option2" autocomplete="off">
                        <span class="glyphicon glyphicon-ok"></span>
                    </label>

                    <label class="btn btn-warning">
                        <input type="radio" name="options" id="option2" autocomplete="off">
                        <span class="glyphicon glyphicon-ok"></span>
                    </label>

                    <label class="btn btn-danger">
                        <input type="radio" name="options" id="option2" autocomplete="off">
                        <span class="glyphicon glyphicon-ok"></span>
                    </label>

                </div>


            </div>

        </div> --}}
        {{--  --}}

        {{-- <div id="wrap-select"></div>
        <p class="text-primary" style="margin-left:11.5%;cursor: pointer;" onclick="addSelect()">+ Tambah Pesanan</p> --}}
        <button type="submit" class="btn btn-primary">Kirim</button>

    </form>
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
            var cartButton = document.getElementById('cart');
            var productType = selectedProduct.getAttribute('data-type');
            var price = selectedProduct.getAttribute('data-price');

            if (productType) {
                bulanContainer.style.display = 'block';
                // cartContainer.style.display = 'block';
                bulanDropdown.innerHTML = '';
                // cartButton.innerHTML = '';
                var valueP = selectedProduct.getAttribute('value');

                var cart = document.createElement("A");
                const t = document.createTextNode("Tutorials");
                console.log(valueP);

                cart.setAttribute("class", "btn btn-primary");
                cart.appendChild(t);
                // jquery
                let id = $('#id').val();

                // let url ="{{ route('product.stock.update', 'id') }}";
                // url = url.replace ('id',valueP);
                // jquery
                cartButton.action = "";
                let cartoButton = "{{ route('order.addToCart', 'id') }}";
                cartoButton = cartoButton.replace('id', valueP);
                cartButton.action = cartoButton;
                console.log(cartButton);

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
                        option.text = formattedPrice + ' ' + allowedbulan[i] + ' bulan';
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
                        option.text = formattedPrice + ' ' + allowedbulan3[i] + ' bulan';
                        // option.text = allowedbulan2[i] + ' Bulan';
                        bulanDropdown.appendChild(option);
                    }

                } else if (productType === 'dedicated' || productType === 'cloud') {
                    // Jika produk adalah dedicated atau cloud, tampilkan opsi bulan 12 dan 24
                    var allowedbulan = [12];

                    for (var i = 0; i < allowedbulan.length; i++) {
                        var option = document.createElement('option');
                        option.value = allowedbulan[i];
                        var formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format((300000));
                        // option.text = formattedPrice + ' ' + allowedbulan[i] + ' bulan';
                        option.text = allowedbulan[i] + ' Bulan + ' + formattedPrice;
                        bulanDropdown.appendChild(option);
                    }

                    var allowedbulan2 = [24];

                    for (var i = 0; i < allowedbulan2.length; i++) {
                        var option = document.createElement('option');
                        option.value = allowedbulan2[i];
                        option.text = allowedbulan2[i] + ' Bulan';
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
