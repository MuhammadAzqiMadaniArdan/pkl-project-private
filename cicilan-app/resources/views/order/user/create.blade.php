@extends('layouts.template')

@section('content')
    <style>
        .card {
            border-radius: 10px;
            background: whitesmoke;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 5);
        }

        a {
            color: black;
            text-decoration: none;
        }

        .card {
            border-radius: 10px;
            background: whitesmoke;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 5);
        }

        .btn span.glyphicon {
            opacity: 0;
        }

        .btn.active span.glyphicon {
            opacity: 1;
        }

        .glyphicon {
            margin-left: 5px;
        }

        .range-wrap {
            position: relative;
            margin: 0 auto 3rem;
        }

        .range {
            margin-top: 10px;
            width: 100%;
        }

        .bubble {
            background: red;
            color: white;
            padding: 4px 12px;
            position: absolute;
            border-radius: 4px;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 27px;
        }

        .bubble::after {
            content: "";
            position: absolute;
            width: 2px;
            height: 2px;
            background: red;
            top: -1px;
            left: 50%;
            margin-top: 27px;

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
                    href="#">TambahOrderDedicated</a></p>
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
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width:88%"
                value="{{ Auth::user()->name }}" hidden>
        </div>
        {{-- <div class="mb-3 d-flex align-items-center" hidden>
            <label for="name_customer" class="form-label" style="width: 12%">Client</label>
            
        </div>
        <div class="mb-3 d-flex align-items-center" hidden>
            <label for="no_telp" class="form-label" style="width: 12%">NoTelp:</label>
            <input type="text" name="no_telp" id="no_telp" class="form-control" style="width:88%">
        </div> --}}
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
                    value="{{ $address2['address'] }} , {{ $address2['city'] }} City ,{{ $address2['state'] }} State , {{ $address2['country'] }} Country"
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
        <div class="mb-3">
            <div class="d-flex align-items-center">
                {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                <label style="width:12%;" for="products" class="form-label">Product :</label>
                {{-- <div class="col-sm-10"> --}}
                <select id="products" class="form-control form-select" style="width:88%" name="products[]">
                    <!-- ... opsi-opsi produk ... -->
                    <option disabled hidden selected>Pilih</option>
                    @foreach ($products as $product)
                        @if ($product['type'] == 'dedicated')
                            @if ($product['stock'] == 0)
                                <option value="{{ $product['id'] }}" data-price="{{ $product['price'] }}"
                                    data-type="{{ $product['type'] }}" disabled style="background: red;">
                                    <p>(Dedicated (HABIS)) {{ $product['name'] }}</p>
                                </option>
                            @else
                                <option value="{{ $product['id'] }}" data-price="{{ $product['price'] }}"
                                    data-type="{{ $product['type'] }}">
                                    @if ($product['type'] == 'dedicated' && $product['stock'] == 0)
                                        <p disbled style="background: red;">(Dedicated (HABIS)) {{ $product['name'] }}</p>
                                    @else
                                        <p>(Dedicated) {{ $product['name'] }}</p>
                                    @endif
                                </option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
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
        <h5 style="margin-bottom:10px;text-align:center;">Configurable Options</h5>
        <br>
        {{-- )))))))))))))))))))))))MEnu Utama ((((((((((((((((( --}}
        {{-- <div class="container">

            <div class="well well-sm mb-3">

                <label class="form-label">SSD SATA : </label>

                <div class="btn-group" data-toggle="buttons" style="width:88%;margin-left:40px;">

                    <label class="btn" style="background:linear-gradient(to right,darkblue,blue);color:white;"
                        selected="selected">
                        <input type="radio" name="SATA" id="option2" autocomplete="off" value ="0" checked>
                        <span class="glyphicon glyphicon-ok"></span> 0
                    </label>
                    <label class="btn " style="background:linear-gradient(to right,blue,darkcyan);color:white;">
                        <input type="radio" name="SATA" id="option2" autocomplete="off" value ="1">
                        <span class="glyphicon glyphicon-ok"></span> 1
                    </label>

                    <label class="btn"
                        style="background:linear-gradient(to right,darkcyan,rgb(0, 189, 189));color:white;">
                        <input type="radio" name="SATA" id="option2" autocomplete="off" value ="2">
                        <span class="glyphicon glyphicon-ok"></span> 2
                    </label>

                    <label class="btn"
                        style="background:linear-gradient(to right,rgb(0, 201, 201),cornflowerblue);color:white;">
                        <input type="radio" name="SATA" id="option2" autocomplete="off" value ="3">
                        <span class="glyphicon glyphicon-ok"></span> 3
                    </label>

                    <label
                        class="btn "style="background:linear-gradient(to right,cornflowerblue,cadetblue);color:white;">
                        <input type="radio" name="SATA" id="option2" autocomplete="off" value ="4">
                        <span class="glyphicon glyphicon-ok"></span> 4
                    </label>

                    <label class="btn " style="background:linear-gradient(to right,cadetblue,lime);color:white;">
                        <input type="radio" name="SATA" id="option2" autocomplete="off" value ="5">
                        <span class="glyphicon glyphicon-ok"></span> 5
                    </label>
                    <label class="btn " style="background:linear-gradient(to right,lime,green);color:white;">
                        <input type="radio" name="SATA" id="option2" autocomplete="off" value ="6">
                        <span class="glyphicon glyphicon-ok"></span> 6
                    </label>

                </div>


            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                
        {{-- <input type="range" value="0" min="0" max="12" oninput="rangevalue.value=value"
            name="SATA12" />
        <output id="rangevalue">0</output> --}}
        <label class="form-label">SSD SATA Tambahan per TB (Sekali bayar) :
        </label>
        <div class="range-wrap" style="width: 100%;">
            <input type="range" class="range" value="0" min="0" max="12" step="1"
                name="SATA">
            <output class="bubble"></output>
        </div>
        <label class="form-label">SSD NVME Tambahan per TB (Sekali bayar) :
        </label>
        <div class="range-wrap" style="width: 100%;">
            <input type="range" class="range" value="0" min="0" max="4" step="1"
                name="NVME">
            <output class="bubble"></output>
        </div>

        {{-- <div class="container">

            <div class="well well-sm mb-3">

                <label class="form-label">SSD NVME : </label>

                <div class="btn-group" data-toggle="buttons" style="width:88%;margin-left:30px;">

                    <label class="btn" style="background:linear-gradient(to right,darkblue,blue);color:white;">
                        <input type="radio" name="NVME" id="option2" autocomplete="off" value ="0" checked>
                        <span class="glyphicon glyphicon-ok"></span> 0
                    </label>
                    <label class="btn " style="background:linear-gradient(to right,blue,darkcyan);color:white;">
                        <input type="radio" name="NVME" id="option1" autocomplete="off" value ="1">
                        <span class="glyphicon glyphicon-ok"></span> 1
                    </label>

                    <label class="btn"
                        style="background:linear-gradient(to right,darkcyan,rgb(0, 189, 189));color:white;">
                        <input type="radio" name="NVME" id="option2" autocomplete="off" value ="2">
                        <span class="glyphicon glyphicon-ok"></span> 2
                    </label>

                    <label class="btn"
                        style="background:linear-gradient(to right,rgb(0, 201, 201),cornflowerblue);color:white;">
                        <input type="radio" name="NVME" id="option2" autocomplete="off" value ="3">
                        <span class="glyphicon glyphicon-ok"></span> 3
                    </label>

                    <label
                        class="btn "style="background:linear-gradient(to right,cornflowerblue,cadetblue);color:white;">
                        <input type="radio" name="NVME" id="option2" autocomplete="off" value ="4">
                        <span class="glyphicon glyphicon-ok"></span> 4
                    </label>

                    <label class="btn " style="background:linear-gradient(to right,cadetblue,lime);color:white;">
                        <input type="radio" name="NVME" id="option2" autocomplete="off" value ="5">
                        <span class="glyphicon glyphicon-ok"></span> 5
                    </label>
                    <label class="btn " style="background:linear-gradient(to right,lime,green);color:white;">
                        <input type="radio" name="NVME" id="option2" autocomplete="off" value ="6">
                        <span class="glyphicon glyphicon-ok"></span> 6
                    </label>

                </div>


            </div>

        </div> --}}

        <div class="mb-3">
            <div class="d-flex align-items-center">
                {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                <label style="width:12%;" for="ram" class="form-label">RAM DDR 4 Tambahan (Sekali Bayar)
                    :</label>
                {{-- <div class="col-sm-10"> --}}
                <select id="ram" class="form-control form-select" style="width:88%" name="ram">
                    <!-- ... opsi-opsi produk ... -->
                    {{-- <option disabled hidden selected>Pilih</option> --}}
                    <option value="0">Tidak Tambahan</option>
                    <option value="32">32 GB + Rp.{{ number_format(700000, 0, '.', ',') }} IDR Setup Free
                    </option>
                    <option value="64">64 GB + Rp.{{ number_format(1400000, 0, '.', ',') }} IDR Setup Free
                    </option>
                    <option value="custom">Custom</option>
                    </option>

                </select>
            </div>

        </div>
        <div class="mb-3" hidden>
            <div class="d-flex align-items-center">
                <label style="width:12%;" for="ramDefault" class="form-label">ramDefault DDR 4 Tambahan (Sekali Bayar)
                    :</label>
                <select id="ramDefault" class="form-control form-select" style="width:88%" name="ramDefault">
                    <option value="128" selected>32 GB + Rp.{{ number_format(700000, 0, '.', ',') }} IDR Setup Free
                    </option>
                </select>
            </div>

        </div>

        <!-- Field input untuk opsi "Custom" -->
        <div class="mb-3">
            <div class="row align-items-center">
                <div id="custom-field" style="display: none;">
                    <div class="col-sm-8">
                        <label for="custom_ram" class="form-label">Custom Ram Name(Masukkan Angka Saja (perGB)):</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="custom_ram" id="custom_ram" class="form-control">
                        {{-- @for ($i = 1; $i < 513; $i++)
                        @if ($i % 8 == 0)
                        <input type='number' value="{{$i}}">{{$i}} GB
                        @endif
                        <!-- ... opsi-opsi produk ... -->
                        <option disabled hidden selected>Pilih</option>
                        @endfor --}}
                        {{-- <input type="number"> --}}
                        {{-- <select name="custom_ram" id="custom_ram" class="form-control form-select" style="width:88%" >
                            @for ($i = 65; $i < 513; $i++)
                            @if ($i % 32 == 0 && $i < 224)
                            <option value="{{$i}}">{{$i}} GB</option>
                            @elseif($i == 256 && $i % 32 == 0)
                            <option value="{{$i}}">{{$i}} GB</option>
                            @elseif($i == 512 && $i % 32 == 0)
                            <option value="{{$i}}">{{$i}} GB</option>
                            @endif
                            @endfor
                        </select> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <label style="width:12%;" for="oS" class="form-label">operating System
                    :</label>
                {{-- <div class="col-sm-10"> --}}
                <select id="oS" class="form-control form-select" style="width:88%" name="oS">
                    <!-- ... opsi-opsi produk ... -->
                    {{-- <option disabled hidden selected>Pilih</option> --}}
                    <option value="centos">Centos</option>
                    <option value="ubuntu">Ubuntu</option>
                    <option value="debian">Debian</option>
                    <option value="proxmox">Proxmox</option>
                    <option value="almalinux">Almalinux</option>
                    <option value="windows">Windows Server 2016</option>
                    <option value="lainnya">lainnya (konfirmasi via CS)</option>


                </select>
            </div>

        </div>


        <div class="mb-3">
            @foreach ($address['address'] as $address2)
                <div class="d-flex align-items-center">
                    {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                    {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                    <label style="width:12%;" for="datacenter" class="form-label">Data Center :</label>
                    {{-- <div class="col-sm-10"> --}}
                    <select id="datacenter" class="form-control form-select" style="width:88%" name="datacenter">
                        <!-- ... opsi-opsi produk ... -->

                        {{-- @if ($address2['city'] == 'Bogor' || $address2['city'] == 'bogor') --}}
                        <option value="Bogor">Bogor Ring 1
                            {{-- @else --}}
                        <option value="Jakarta">Gedung Cyber,Jakarta Rp.{{ number_format(750000, 0, '.', ',') }}
                            IDR </option>
                        {{-- @endif --}}
                        </option>

                    </select>
                </div>
            @endforeach

        </div>

        {{-- Unknown Data For Dedicated ot Data Hide --}}
        <div class="mb-3" hidden>
            <div class="d-flex align-items-center" >
                <label style="width:12%;" for="IP" class="form-label">IP :</label>
                    <select id="IP" class="form-control form-select" style="width:88%" name="IP">
                        <option value="29" selected>/29 (5 IP,Gratis)</option>
                    </select>               

            </div>

        </div>
        <div class="mb-3 row" style="flex-wrap:nowrap;" hidden>
            <label for="bandwidth" class="form-label" style="width: 12%">Dedicated bandwidth
                Internasional :</label>
            <input type="number" name="bandwidth" id="bandwidth" class="form-control"
                style="width:10%;height:10%;margin-right:10px;margin-left:10px;clear:left;" value="300">
            <p style="display: block;float: right;" id="bandwidth-label">
                x Mbps (Bayar per tahun Promo 20 Ribu / Mbps) <span id="bandwidth-span">Rp 30,000 IDR</span>
            </p>
        </div>
        <div class="mb-3" hidden>
            <div class="d-flex align-items-center">
                <label style="width:12%;" for="port" class="form-label">Port :</label>
                {{-- <div class="col-sm-10"> --}}
                <select id="port" class="form-control form-select" style="width:88%" name="port">
                    <option value="1" selected>Rj45 - 1 Gbps Port</option>
                    {{-- <option value="10">Gratis SFP+ 10 GBPS Port (Slot Terbatas) --}}
                    </option>
                </select>
            </div>
        </div>

        {{--  --}}
        {{-- <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
        <option selected>Open this select menu</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select> --}}
        {{-- <div class="mb-3">
            <div class="d-flex align-items-center">
                <label for="bulan" class="form-label" style="width: 12%">Bulan : </label> --}}
        {{-- name dengan [] biasanya dipake buat column yang tipe datanya json/array,dan biasaanya digunakan apabila input dengan tujuan data yang sama ada banyak (dan dari input yang baanyak datanya tersebut , datanya akan diambil seluruhnya dalam bentuk array ) --}}
        {{-- <select type="text" name="bulan" id="bulan" class="form-control form-select"
                    style="width:88%">
                    <option disabled hidden selected>Pilih Bulan</option>
                    <option value="12">12 bulan + IDR300.000</option>
                    <option value="24">24 bulan</option>
                </select>
            </div> --}}
        {{-- karena akan ada js yg menampilkan select ketika di klik maka disediakan btempat elemen yang akan dihasilkan dari js tersebut --}}
        {{-- </div> --}}
        {{-- <div class="mb-3 d-flex align-items-center">
                <label for="no_telp" class="form-label" style="width: 12%">Bu:</label>
                <input type="text" name="no_telp" id="no_telp" class="form-control" style="width:88%">
            </div> --}}
        {{ request()->input('name_customer') }}
        <p></p>
        <p id="cart-container" style="display: none">
            {{-- @foreach ($products as $product) --}}
            @php
                $nameValue = Request::input('custom_ram');

                // dd($nameValue)

            @endphp
            <a id="cart1" class="btn btn-primary" role="button">
                Add To Cart
            </a>

        </p>

        {{-- @endforeach --}}

        <div id="wrap-select"></div>
        <p class="text-primary" style="margin-left:11.5%;cursor: pointer;" onclick="addSelect()">+ Tambah Pesanan</p>
        
        <button type="submit" class="btn btn-primary">Kirim</button>

    </form>
@endsection
@push('script')
    <!-- ... tag-tag lainnya ... -->
    <!-- ... bagian head dan tag-tag lainnya ... -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const allRanges = document.querySelectorAll(".range-wrap");
        allRanges.forEach(wrap => {
            const range = wrap.querySelector(".range");
            const bubble = wrap.querySelector(".bubble");

            range.addEventListener("input", () => {
                setBubble(range, bubble);
            });
            setBubble(range, bubble);
        });

        function setBubble(range, bubble) {
            const val = range.value;
            const min = range.min ? range.min : 0;
            const max = range.max ? range.max : 100;
            const newVal = Number(((val - min) * 100) / (max - min));
            bubble.innerHTML = val;

            // Sorta magic numbers based on size of the native UI thumb
            bubble.style.left = `calc(${newVal}% + (${8 - newVal * 0.15}px))`;
        }
    </script>
    <!-- ... tag-tag lainnya ... -->
    <script>
        // Script untuk menampilkan field input ketika opsi "Custom" dipilih
        document.getElementById('ram').addEventListener('change', function() {
            var selectedOption = this.value;
            var customField = document.getElementById('custom-field');

            if (selectedOption === 'custom') {
                customField.style.display = 'block';
            } else {
                customField.style.display = 'none';
            }
        });
    </script>

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
