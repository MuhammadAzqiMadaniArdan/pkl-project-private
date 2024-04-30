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
    
    @php
    $data1 = [];
    $dataBulan = [];
        
    $validate1 = data_get($data1,'0.type',1);
    if ($validate1 == 'dedicated') {
        $validate = true;
        }else
        {
            $validate = true;
        }

    @endphp
    {{-- @dd($productEntry['entryData'],$data1[0],$validate,$dataBula) --}}

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
        @if($validate == true)
    {{-- <form id="cart" action="{{ route('order.addToCart', $userData->id) }}" class="card p-4 mt-5" method="GET"> --}}
        <form action="{{ route('admin.order.adminStore') }}" class="card p-4 mt-5" method="POST">
            {{-- <h1 style="margin-bottom:10px;text-align:center;"> Order</h5> --}}
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
            <div class="p" style="width:85%; margin-bottom:1%;"><b>{{ $userData->name }}</b></div>
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width:88%"
                value="{{ $userData->name }}" hidden>
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
            @if ($userData->company == null)
                <input type="text" name="company" id="company" class="form-control" style="width:88%"
                    value="{{ $userData->company }}"
                    placeholder="            Tidak Ada Keterangan Perusahaan 
            " disabled>
            @else
                <input type="text" name="company" id="company" class="form-control" style="width:88%"
                    value="{{ $userData->company }}" disabled>
            @endif
        </div>
        @php
            $address = $userData;
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
        <div class="mb-3 row" hidden>
            <label for="userId" class="form-label" style="width: 12%">ID user:</label>
            <input type="text" name="userId" id="userId" class="form-control" style="width:88%" value="{{ $userData->id }}">
        </div>
        {{-- @dd($userData->id) --}}


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
                    <option value="32">32 GB + Rp.{{ number_format(800000, 0, '.', ',') }} IDR Setup Free
                    </option>
                    <option value="64">64 GB + Rp.{{ number_format(1600000, 0, '.', ',') }} IDR Setup Free
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
        {{--  --}}
        <div class="mb-3 row" >
            <label for="rack" class="form-label" style="width: 12%;">Rack:</label>
            <input type="text" name="rack" id="rack" class="form-control" style="width:88%" >
        </div>
        
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
        <hr>
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
                    <option value="1U" selected>
                        1U
                    </option>
                    
                </select>
            </div>
        </div>
        {{-- Hidden Product Type  --}}
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
                style="width:10%;height:10%;margin-right:10px;margin-left:10px;clear:left;" value="0">
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
        {{-- Hidden End --}}
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
                <td><input type="number" name="ramServer[]" placeholder="Enter ram" class="form-control" id="ramServer" value="128"/></td>
                <td><input type="text" name="disk[]" placeholder="Enter disk" class="form-control" value="256" /></td>
                <td><input type="text" name="processor[]" placeholder="Enter processor" class="form-control" id="cpu" value="2 x Intel Xeon E5-2640v4 3.2 Ghz, Total 20 Core, 40 Thread/CPU                    " /></td>
                {{-- <td><input type="text" name="inventory[0][ram]" placeholder="Enter ram" class="form-control" /></td>
                <td><input type="text" name="inventory[0][disk]" placeholder="Enter disk" class="form-control" /></td>
                <td><input type="text" name="inventory[0][processor]" placeholder="Enter processor" class="form-control" /></td> --}}
                <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button></td>
            </tr>
        </table>
        <div class="mb-3 row">
            <label for="payment" class="col-sm-2 col-form-label">Pembelian:</label>
            <div class="col-sm-10">
                <select id="payment" class="form-control" name="payment">
                    <option value="bulan" selected>
                        Perbulan
                    </option>
                    <option value="lunas">
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
    <div class="mb-3 row">
        <label for="serverLabel" class="col-sm-2 col-form-label">Server Label:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="serverLabel" name="serverLabel">
        </div>
    </div>

        {{-- <div id="wrap-select"></div>
        <p class="text-primary" style="margin-left:11.5%;cursor: pointer;" onclick="addSelect()">+ Tambah Pesanan</p> --}}
        
        <button type="submit" class="btn btn-primary" >Kirim</button>
    </form>

    @else
    <div class="card p-5 mt-5 mb-5" >

    <div class="alert alert-danger mt-2 p-5" ><h3 style="text-align: center;">Pesanan Client Bukan Produk Dengan Tipe Dedicated !</h3></div>

    </div>
    @endif
@endsection
@push('script')
    <!-- ... tag-tag lainnya ... -->
    <!-- ... bagian head dan tag-tag lainnya ... -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>

<script type="text/javascript">
    var i = 0;

    $("#add-btn").click(function() {

        ++i;

        $("#dynamicAddRemove").append('<tr><td><input type="number" name="ramServer[]" placeholder="Enter ram" class="form-control" /></td><td><input type="text" name="disk[]" placeholder="Enter disk" class="form-control" /></td><td><input type="text" name="processor[]" placeholder="Enter processor" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
            );
    });

    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });
</script>
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
            var productType = selectedProduct.getAttribute('data-type');
            var selectedBulan = selectedProduct.getAttribute('data-bulan');
            var price = selectedProduct.getAttribute('data-price');
            var cpu = document.getElementById('cpu');
            var ram = document.getElementById('ramServer');

            if (productType) {
                bulanContainer.style.display = 'block';
                // cartContainer.style.display = 'block';
                bulanDropdown.innerHTML = '';
                // cartButton.innerHTML = '';


                var valueP = selectedProduct.getAttribute('value');

                console.log(valueP);

                // jquery

                // let url ="{{ route('product.stock.update', 'id') }}";
                // url = url.replace ('id',valueP);
                // jquery
                cpu.value = "";
                ram.value = "";
                let cpuSet = ["2 x Intel Xeon E5-2640v4 @3.2 Ghz, Total 20 Core, 40 Thread/CPU","2 x Intel Xeon E5-2650v4 @2.9 Ghz, Total 24 Core, 48 Thread/CPU","2 x Intel Xeon E5-2690v4 @3.5 Ghz, Total 28 Core, 56 Thread/CPU","2 x Intel Xeon Gold 6138 @3.7 Ghz, Total 40 Core, 80 Thread/CPU"];
                let ramSet = [96,128];
                
                
                if(valueP == 20 ){
                    cpu.value = cpuSet[0];
                }
                else if(valueP == 13 ){
                    cpu.value = cpuSet[1];
                }else if(valueP == 14){
                    cpu.value = cpuSet[2];
                }else{
                    cpu.value = cpuSet[3];
                }
                
                if (valueP == 20) {
                    ram.value = ramSet[0];
                    
                }else{
                    ram.value = ramSet[1];

                }
                console.log(cpu.value);
                console.log(ram.value);
                
                // console.log(cartButton);
                
                
                // let url ="{{ route('product.stock.update', 'id') }}";
                // url = url.replace ('id',valueP);
                // jquery
                console.log(productType,bulanDropdown,selectedBulan);
               
                if (productType === productType || productType === 'cloud') {
                    // Jika produk adalah dedicated atau cloud, tampilkan opsi bulan 12 dan 24
                    
                    if (productType === 'dedicated' || productType === 'cloud') {
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
