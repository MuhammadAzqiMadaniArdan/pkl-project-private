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
   
    {{-- @dd($productEntry['entryData'],$data1[0],$validate,$dataBula) --}}

    {{-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> --}}
    <div class="jumbotron  mt-2" style="padding:0px;">
        <div class="container">
            {{-- @if (Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
            @endif --}}
            <h3><b>Penambahan Sewa</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="{{ route('status.sewaIndex') }}">DataSewa</a>/<a
                href="#">SistemSewa</a></p>
            </div>
        </div>
    {{-- <form id="cart" action="{{ route('order.addToCart', $userData->id) }}" class="card p-4 mt-5" method="GET"> --}}
        <form action="{{ route('status.sewaUpdate',$order['id']) }}" class="card p-4 mt-5" method="POST">
            {{-- <h1 style="margin-bottom:10px;text-align:center;"> Order</h5> --}}
            @csrf
            @method('PATCH')
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
            <label for="name_customer" class="form-label" style="width: 15%">Client Saat Ini :</label>
            <div class="p" style="width:85%; margin-bottom:1%;"><b>{{ $userGet['name'] }}</b></div>
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width:88%"
                value="{{ $userGet['name'] }}" hidden>
        </div>
       
       

        



        <hr>
        <h5 style="margin-bottom:10px;text-align:center;">Configurable Options</h5>
        <br>
        

        
        <div class="mb-3">
            <div class="d-flex align-items-center">
                {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                <label style="width:12%;" for="userName" class="form-label">User Sewa
                    :
                </label>

        <div class="container">
            <select class="form-control form-select" id="livesearch" name="userName" style="color:black;"></select>

        </div>
        </div>
        <br>
        @if($status1['payment'] < 1)
        <div class="mb-3">
            <div class="d-flex align-items-center">
                {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                <label style="width:12%;" for="bulan" class="form-label">Bulan Sewa
                    :</label>
                    {{-- <div class="col-sm-10"> --}}
                <select id="bulan" class="form-control form-select" style="width:88%" name="bulan">
                    
                    @php
                    $HJ = 900000;
                    @endphp
                    <option value="1" selected>1 Bulan 
                    </option>
                    <option value="2" >2 Bulan </option>
                    <option value="3" >3 Bulan </option>
                    <option value="6" >6 Bulan </option>
                    <option value="12" >12 Bulan </option>
                   
                   
                </select>
                {{-- <select id="bulan" class="form-control form-select" style="width:88%" name="bulan">
                    
                    
                    <option value="{{$userEntry}}" selected>{{$userEntry}} Bulan 
                    </option>
                   
                   
                </select> --}}
            </div>

        </div>
        <!-- Field input untuk opsi "Custom" -->
       

        {{-- @endforeach --}}
        <hr>
        <div class="mb-3 d-flex align-items-center">
            <label for="price" class="form-label" style="width: 15%">Tentukan Harga :</label>
            <input type="number" name="price" id="price" class="form-control" style="width:88%">
        </div>

        <div class="mb-3 d-flex align-items-center" hidden>
            <label for="payment" class="form-label" style="width: 15%" hidden>Pembayaran :</label>
            <select id="payment" class="form-control form-select" style="width:88%" name="payment" hidden>
                    
                 
                <option value="perbulan" selected>Perbulan 
                <option value="lunas" >lunas
                </option>   
               
               
            </select>        
        </div>
        
        @endif
    {{-- <div class="mb-3 row">
        <label for="entryDate" class="col-sm-2 col-form-label">Tanggal masuk:</label>
        <div class="col-sm-10">
            <input type="date" name="entryDate" id="entryDate" class="form-control" style="width:100%;">
        </div>
    </div>
     --}}
        <div class="c2 mt-4 w-100"  style="display: flex;justify-content:center;">
        <button type="submit" class="btn btn-primary w-100" >Kirim</button>
    </div>
    </form>
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
                console.log(productType,bulanDropdown);
               
                if (productType === productType || productType === 'cloud') {
                    // Jika produk adalah dedicated atau cloud, tampilkan opsi bulan 12 dan 24
                    if(selectedBulan == 12){

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
                }
                else {

                    
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
            let el = ``;
            // gunakan jquery untuk mengambil html tempatel baru yang aka ditambahkan
            // append : menambahakn html bagian bawah 
            $("#wrap-select").append(el);
            no++;
        }
    </script>
    <!-- ... script-script lainnya ... -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script type="text/javascript">
        let url ="{{route('status.sewaSearch')}}";
        let placeholder = 'Select User';
        
        
    $('#livesearch').select2({
        placeholder: placeholder,
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            },
            
            cache: true
        }
    });

    cnseg
</script>
@endpush
