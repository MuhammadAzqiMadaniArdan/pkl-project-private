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
            @if (Session::get('success'))
                @include('sweetalert::alert')
            @endif
            {{-- @if (Session::get('failed'))
            <div class="alert alert-danger">{{Session::get('failed')}}</div>
            @endif --}}
            <h3><b>Page Pembayaran</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Page Pembayaran</a></p>
        </div>
    </div>
    <form action="{{ route('order.update', $order['id']) }}" method="post" class="card bg-light mt-5 p-5">
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
        <div class="mb-3 row" hidden>
            <label for="name_customer" class="col-sm-2 col-form-label">Nama Produk :</label>
            <div class="col-sm-10">
                <input hidden type="text" class="form-control" id="name_customer" name="name_customer"
                    value="{{ $order['name_customer'] }}">
            </div>
        </div>


        @php
            setLocale(LC_ALL, 'IND');

            // ------------------DATE----------------------
            $date = $order->created_at;
            $startMonth = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%d %B %Y');
            // $startMonth = $date->format('d-m-Y');
            // 31-5-2022
            // $x = 1;

            // $endMonth = Carbon\Carbon::parse($order['updated_at'])->addDays(30)->formatLocalized('%d %B %Y %H:%M');

            $mytime = Carbon\Carbon::now();
            // 30-6-2022
            // $liveDate = $mytime->formatLocalized('%d %B %Y %H:%M:00');
            $liveDate = $mytime->formatLocalized('25 Desember 2024 11:45');
            // ---------------------INVOICE-COLLO-COND---------------------
            $isColocation = false;
            foreach ($order['products'] as $product) {
                if ($product['type'] === 'colocation') {
                    $isColocation = true;
                    break;
                }
            }

            // Pengaturan invoice, endInvoice, dan liveInvoice
            $invoiceDate = $isColocation ? $order['updated_at'] : $order['created_at'];
            // memiliki arti jika bernilai true maka akan menghasilkan $product['created_at']
            // jika false maka akna menghasilkan $order['created_at']
            $liveInvoice = $mytime->formatLocalized('241225');

            // -------------------end Month-sorangan--------------------
            $col_invoice = $isColocation
                ? Carbon\Carbon::parse($invoiceDate)
                    ->addDays(30 * $order['bulan'])
                    ->subDays(10)
                    ->diffInDays($liveInvoice)
                : 0;

            $endMonth =
                $isColocation && $order['votes'] <= $order['bulan']
                    ? Carbon\Carbon::parse($invoiceDate)
                        ->addDays(30 * $order['bulan'] + $col_invoice)
                        ->formatLocalized('%d %B %Y %H:%M')
                    : Carbon\Carbon::parse($order['created_at'])
                        ->addDays(30 * $order['votes'])
                        ->formatLocalized('%d %B %Y %H:%M');
            // ------------------

            $endInvoice =
                $isColocation && $order['votes'] == 1
                    ? Carbon\Carbon::parse($invoiceDate)
                        ->addDays(30 * $order['bulan'] + $col_invoice)
                        ->formatLocalized('%y%m%d')
                    : ($isColocation && $order['votes'] <= $order['bulan']
                        ? Carbon\Carbon::parse($invoiceDate)
                            ->addDays(30 * $order['bulan'])
                            ->subDays(10)
                            ->formatLocalized('%y%m%d')
                        : Carbon\Carbon::parse($invoiceDate)
                            ->addDays(30 * $order['votes'])
                            ->formatLocalized('%y%m%d'));

            // $collocation_invoice = $invoice - $liveInvoice;

            $invoice = $isColocation
                ? Carbon\Carbon::parse($invoiceDate)
                    ->addDays(30 * $order['bulan'] + $col_invoice)
                    ->subDays(10)
                    ->formatLocalized('%y%m%d')
                : Carbon\Carbon::parse($invoiceDate)
                    ->addDays(30 * $order['votes'])
                    ->subDays(10)
                    ->formatLocalized('%y%m%d');

            $ins = $order->whereMonth('updated_at', '=', date('m'));
            $month = Carbon\Carbon::parse('2022/06/12')->format('MM');

            $newProduct = $order->products[1] ?? null;
        @endphp
        @php
            $ramType1 = data_get($order['products'], '1.id', 0);
            $ramType2 = data_get($order['products'], '2.id', 0);
            $ramType3 = data_get($order['products'], '3.id', 0);
            $ramQty1 = data_get($order['products'], '1.qty', 0);
            $ramPrice = data_get($order['products'], '1.price', 1);
            $dataProduct = data_get($order['products'], '0.label', 0);
            // dd($ramType3)
            // dd();
        @endphp
        {{-- <div class="mb-3 row" style="flex-wrap:nowrap;">

    <label for="bandwidth" class="form-label" style="width: 12%">Dedicated bandwidth
        Internasional :</label>
    <input type="number" name="bandwidth" id="bandwidth" class="form-control"
        style="width:10%;height:10%;margin-right:10px;margin-left:10px;clear:left;">
    <p style="display: block;float: right;" id="bandwidth-label">

        x Mbps (Bayar per tahun Promo 20 Ribu / Mbps) <span id="bandwidth-span">Rp 30,000 IDR</span>
    </p>
</div> --}}

        <hr>

        @if ($liveDate == $endMonth || $liveDate > $endMonth)
            <input type="number" name="vote_status" value="-2" hidden>
        @endif
        @foreach ($order->status as $orderStatus)
            @if ($orderStatus->access == 2)
                <input type="number" name="suspend" value="{{ $orderStatus->access }}" hidden>
            @endif
            @if ($orderStatus->access == 4)
                <input type="number" name="unfreeze" value="{{ $orderStatus->access }}" hidden>
                <input type="number" name="bulanFreeze" value="12" hidden>
            @endif
            @if ($orderStatus->access == 3 && $order['bulan'] == 4)
                <input type="number" name="next" value="{{ $orderStatus->access }}" hidden>
                <input type="number" name="bulanFreeze" value="12" hidden>
            @elseif ($orderStatus->access == 3)
                <input type="number" name="freeze" value="{{ $orderStatus->access }}" hidden>
            @endif

            @if ($orderStatus->access >= 5)
                <input type="number" name="after" value="{{ $orderStatus->access }}" hidden>
            @endif
        @endforeach
        @php
            $lastProduct = last($order['products']);
            if ($lastProduct['type'] == 'freeze') {
                $secondProduct = $lastProduct;
            } else {
                $secondProduct = false;
            }
            // $secondProduct = $order->products[1] ?? null;
            // dd($secondProduct);
        @endphp
        @foreach ($order->status as $orderStatus)
            @if ($order['bulan'] == 4 && $orderStatus->access == 3)
                <button type="submit" class="btn btn-success">Bayar Cicilan</button>

                {{-- Pemisah untuk user yangsudah mempunayai produk kedua --}}
            @elseif (
                ($order['bulan'] == $order['votes'] && $orderStatus->access == 3) ||
                    // $isColocation == true && $liveDate < $endMonth||
                    $orderStatus->access == 3)
                <div class="mb-3" style="width: 100%; margin-left:30px;">
                    <label style="width:50%;" for="name_product" class="col-sm-2 col-form-label">Pilih Paket
                        @if ($secondProduct)
                            <span> Produk Freeze Ke-2: {{ $secondProduct['name_product'] }}</span>
                        @else
                            <span> Produk Freeze:</span>
                        @endif
                    </label>
                    <div class="col-sm-10">
                        <select id="name_product" class="form-control" name="name_product">
                            <option disabled hidden selected>Pilih</option>
                            @if ($secondProduct !== false)
                                {{-- Jika pengguna sudah memiliki produk kedua, hanya tampilkan produk kedua --}}
                                <option value="{{ $secondProduct['id'] }}" data-months="{{ $order['bulan'] }}"
                                    data-price="{{ $secondProduct['price'] }}" data-access="{{ $orderStatus->access }}">
                                    {{ $secondProduct['name_product'] }}
                                </option>
                            @else
                                {{-- Loop melalui produk dedikasi --}}
                                {{-- @foreach ($dedicatedProducts as $product) --}}
                                <option value="1" data-months="{{ $order['bulan'] }}" data-price="350000"
                                    data-access="{{ $orderStatus->access }}">
                                    Layanan Freeze Bogor
                                </option>
                                <option value="2" data-months="{{ $order['bulan'] }}" data-price="750000"
                                    data-access="{{ $orderStatus->access }}">
                                    Layanan Freeze Jakarta
                                </option>
                                {{-- @endforeach --}}
                            @endif
                        </select>
                    </div>
                </div>

                <div class="mb-3" id="months-container" style="width: 100%; margin-left:30px; display: none;">
                    @if ($isColocation == true)
                        <label style="width:50%;" for="months" class="col-sm-2 col-form-label">Bulan Layanan
                            Colocation:</label>
                    @elseif($orderStatus->access == 3)
                        <label style="width:50%;" for="months" class="col-sm-2 col-form-label">Bulan Layanan
                            Freeze (MAX/3) :</label>
                    @else
                        <label style="width:50%;" for="months" class="col-sm-2 col-form-label">Bulan Layanan
                            Colocation:</label>
                    @endif
                    <div class="col-sm-10">
                        <select id="months" class="form-control" name="months">
                            {{-- @for ($i = 1; $i <= 12; $i++)
                                @if ($i == 1 || $i == 3 || $i == 6 || $i == 12)
                                    <option value="{{ $i }}">{{ $i }} Bulan - Tulisan Admin</option>
                                @endif
                            @endfor --}}
                        </select>
                    </div>
                </div>


                <hr>
                <button type="submit" class="btn btn-primary">Confirm</button>
            @elseif ($liveDate >= $endMonth && $orderStatus->access == 3)
                <button type="submit" class="btn btn-primary">Bayar Tunggakan Setelah Freeze</button>
            @elseif ($liveDate >= $endMonth && $orderStatus->access == 2)
                {{-- @elseif ($liveDate >= $endMonth && $orderStatus->access == 2) --}}
                <button type="submit" class="btn btn-primary">Bayar Tunggakan Non konfirmasi</button>
            @elseif (
                $order['bulan'] == $order['votes'] ||
                    // $isColocation == true && $liveDate < $endMonth||
                    ($isColocation == true && $orderStatus->access == 1) ||
                    ($orderStatus->access == 3 && $isColocation == true) ||
                    $isColocation == true)
                <div class="mb-3" style="width: 100%; margin-left:30px;">
                    <label style="width:50%;" for="name_product" class="col-sm-2 col-form-label">Pilih Paket
                        Collocation:</label>
                    <div class="col-sm-10">
                        <select id="name_product" class="form-control" name="name_product">
                            <option disabled hidden selected>Pilih</option>
                            @if ($existingProducts['type'] == 'colocation')
                                @for ($i = 0; $i < 13; $i++)
                                    {{-- ?<<<<<<<<<Pemiihan 1U datacenter --}}
                                    @if (
                                        ($existingProducts['id'] == 6 && $i == 0) ||
                                            ($existingProducts['id'] == 6 && $i == 2) ||
                                            ($existingProducts['id'] == 10 && $i == 2) ||
                                            ($existingProducts['id'] == 10 && $i == 0))
                                        {{-- @dd($colocationProducts,$existingProducts ) --}}
                                        <option value="{{ $colocationProducts[$i]['id'] }}"
                                            data-months="{{ $order['bulan'] }}"
                                            data-price="{{ $colocationProducts[$i]['price'] }}"
                                            data-access="{{ $orderStatus->access }}"
                                            data-type="{{ $colocationProducts[$i]['type'] }}">
                                            {{ $colocationProducts[$i]['name'] }}
                                        </option>
                                        {{-- Pemeihan 2U datacenter --}}
                                    @elseif(
                                        ($existingProducts['id'] == 9 && $i == 1) ||
                                            ($existingProducts['id'] == 9 && $i == 3) ||
                                            ($existingProducts['id'] == 11 && $i == 1) ||
                                            ($existingProducts['id'] == 11 && $i == 3))
                                        <option value="{{ $colocationProducts[$i]['id'] }}"
                                            data-months="{{ $order['bulan'] }}"
                                            data-price="{{ $colocationProducts[$i]['price'] }}"
                                            data-access="{{ $orderStatus->access }}"
                                            data-type="{{ $colocationProducts[$i]['type'] }}">
                                            {{ $colocationProducts[$i]['name'] }}
                                        </option>
                                    @elseif($existingProducts['id'] == 11 && $i == 4)
                                        <option value="{{ $colocationProducts[$i]['id'] }}"
                                            data-months="{{ $order['bulan'] }}"
                                            data-price="{{ $colocationProducts[$i]['price'] }}"
                                            data-access="{{ $orderStatus->access }}"
                                            data-type="{{ $colocationProducts[$i]['type'] }}">
                                            {{ $colocationProducts[$i]['name'] }}
                                        </option>
                                    @endif
                                @endfor
                            @else
                                @foreach ($colocationProducts as $product)
                                    <option value="{{ $product['id'] }}" data-months="{{ $order['bulan'] }}"
                                        data-price="{{ $product['price'] }}" data-access="{{ $orderStatus->access }}"
                                        data-type="{{ $product['type'] }}">
                                        {{-- data-price="{{ $product['price'] }}" data-access="{{ $orderStatus->access }}" data-type="{{ $colocationProducts[$i]['type'] }}"> --}}
                                        {{ $product['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="mb-3" id="months-container" style="width: 100%; margin-left:30px; display: none;">
                    @if ($isColocation == true)
                        <label style="width:50%;" for="months" class="col-sm-2 col-form-label">Bulan Layanan
                            Colocation:</label>
                    @elseif($orderStatus->access == 3)
                        <label style="width:50%;" for="months" class="col-sm-2 col-form-label">Bulan Layanan
                            Freeze (MAX/3) :</label>
                    @else
                        <label style="width:50%;" for="months" class="col-sm-2 col-form-label">Bulan Layanan
                            Colocation:</label>
                    @endif
                    <div class="col-sm-10">
                        <select id="months" class="form-control" name="months">
                            {{-- @for ($i = 1; $i <= 12; $i++)
                                @if ($i == 1 || $i == 3 || $i == 6 || $i == 12)
                                    <option value="{{ $i }}">{{ $i }} Bulan - Tulisan Admin</option>
                                @endif
                            @endfor --}}
                        </select>

                    </div>
                    <div class="mb-3 mt-3">
                        <div class="d-flex align-items-center">
                            {{-- <label for="products" class="form-label" style="width: 12%">Produk : </label> --}}
                            {{-- <div class="mb-3" style="width: 100%; margin-left:30px;"> --}}
                            <label style="width:12%;" for="port" class="form-label">Port :</label>
                            {{-- <div class="col-sm-10"> --}}
                            <select id="port" class="form-control form-select" style="width:88%;margin-left;20px;"
                                name="port">
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
                    <h4 style="text-align: center;">Additional Information</br><i
                            style="color: blue;font-size:15px;">(required fields are marked with *)</i></h4>
                    <br>
                    {{-- label Server --}}
                    <div class="mb-3 row">
                        <label for="label_product" class="form-label" style="width: 12%">Label Server:</label>
                        <input type="text" name="label_product" id="label_product" class="form-control"
                            style="width:88%">
                    </div>
                    <br>


                    <br>

                    <button type="submit" class="btn btn-primary form-control">Confirm</button>

                    {{-- <h4 style="text-align: center;">Configurable Options</h4>


                <hr>
                                    <div class="mb-3 mt-3">
                                        <div class="d-flex align-items-center">
                                            <label for="port" class="form-label" style="width: 12%">Port :</label>
                                            <select id="port" class="form-control form-select" style="width:88%" name="port">
                                                <!-- ... opsi-opsi produk ... -->
                                                {{-- <option disabled hidden selected>Pilih</option> --}}
                    {{-- <option value="1">Rj45 - 1 Gbps Port</option>
                                                <option value="10">Gratis SFP+ 10 GBPS Port (Slot Terbatas)
                                                </option>
                                            </select>
                                        </div> --}}
                    {{-- </div> --}}
                    {{-- <div class="mb-3"> --}}
                    {{-- <div class="d-flex align-items-center">
                                            <label for="products" class="form-label" style="width: 12%">Produk : </label>
                                            <div class="mb-3" style="width: 100%; margin-left:30px;">
                                            <label style="width:12%;" for="IP" class="form-label">IP :</label>
                                            <div class="col-sm-10">
                                                <select id="IP" class="form-control form-select" style="width:88%" name="IP">
                                                    <option value="29">/29 (5 IP,Gratis)</option>
                                                    <option value="28">/28 (13 IP) - Rp.240.000 IDR</option>
                                                    <option value="27">/27 (30 IP) - Rp.720.000 IDR</option>
                                                    <option value="24">/24 (256 IP) - Rp.3.500.000 IDR</option>
                                                </select>
                                                
                            
                                        </div> --}}

                    {{-- </div> --}}
                    {{-- <div class="mb-3 row" style="flex-wrap:nowrap;"> --}}
                    {{-- <label for="bandwidth" class="form-label" style="width: 12%">Dedicated bandwidth
                                            Internasional :</label>
                                        <input type="number" name="bandwidth" id="bandwidth" class="form-control"
                                            style="width:10%;height:10%;margin-right:10px;margin-left:10px;clear:left;">
                                        <p style="display: block;float: right;" id="bandwidth-label">
                            
                                            x Mbps (Bayar per tahun Promo 20 Ribu / Mbps) <span id="bandwidth-span">Rp 30,000 IDR</span>
                                        </p> --}}
                    {{-- </div> --}}
                    {{-- </div>  --}}
                    {{-- <label style="width:50%;" for="months" class="col-sm-2 col-form-label">Bulan Layanan Colocation:</label>
                <div class="col-sm-10">
                    <select id="months" class="form-control" name="months">

                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $monthsArray = [1, 3, 6, 12]; // Array bulan yang diinginkan
                            @endphp

                            @if (in_array($i, $monthsArray))
                                @if ($orderStatus->access == 3)
                                    @foreach ($order['products'] as $product)
                                        @if ($order['bulan'] == $i)
                                            <option value="{{ $i }}">{{ $product['price'] }} - FREEZE
                                                {{ $i }} Bulan - Tulisan Admin</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="{{ $i }}">{{ $i }} Bulan - Tulisan Admin</option>
                                @endif
                            @endif
                        @endfor
                    </select>
                </div> --}}
                    {{-- <p>Tujuan</p> --}}
                @else
                    <button type="submit" class="btn btn-primary">Bayar Cicilan</button>
                    {{-- b ini merupakan pemayaran dalam status tertentu  --}}
            @endif
        @endforeach

        {{-- <div class="mb-3 row">
            <label for="type" class="col-sm-2 col-form-label">Type Produk :</label>
            <div class="col-sm-10">
                <select id="type" class="form-control" name="type">
                    <option disabled hidden selected>Pilih</option>
                    <option value="cloud"{{$product['type'] == 'cloud' ? 'selected' : ''}}>Cloud</option>
                    <option value="dedicated"{{$product['type'] == 'dedicated' ? 'selected' : ''}}>Dedicated</option>
                    <option value="colocation"{{$product['type'] == 'colocation' ? 'selected' : ''}}>Colocation</option>
                </select>
            </div>
        </div> --}}
        {{-- <div class="mb-3 row">
            <label for="price" class="col-sm-2 col-form-label">Harga Produk :</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="price" name="price" value="{{$product['price']}}">
            </div>
        </div> --}}
        {{-- <div class="mb-3 row">
            <label for="stock" class="col-sm-2 col-form-label">Stock Awal:</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="stock" name="stock">
            </div>
        </div> --}}

    </form>

@endsection

@push('script')
    {{-- <script>
    document.getElementById('name_product').addEventListener('change', function() {
    var selectedProduct = this.options[this.selectedIndex];
    var monthsContainer = document.getElementById('months-container');
    var monthsDropdown = document.getElementById('months');
    var portDropdown = document.getElementById('port');
    var ipDropdown = document.getElementById('IP');
    var bandwidthInput = document.getElementById('bandwidth');
    var bandwidthLabel = document.getElementById('bandwidth-label');

    // Hapus semua opsi port dan IP yang ada

    if (selectedProduct.getAttribute('data-months')) {
        // Tampilkan container bulan
        monthsContainer.style.display = 'block';
        monthsDropdown.innerHTML = '';

      
    } else {
        // Sembunyikan container bulan
        monthsContainer.style.display = 'none';
    }
});

    </script> --}}

    <script>
        // Add JavaScript to dynamically update the months dropdown based on the selected product
        document.getElementById('name_product').addEventListener('change', function() {
            var selectedProduct = this.options[this.selectedIndex];
            var monthsContainer = document.getElementById('months-container');
            var monthsDropdown = document.getElementById('months');
            // var accessValue = selectedProduct.dataset.access;
            var accessValue = parseInt(selectedProduct.dataset.access);
            console.log('cuy', accessValue)


            // Melakukan sesuatu dengan nilai 'data-access', misalnya menampilkannya di konsol
            console.log('Nilai data-access:', monthsDropdown);

            if (selectedProduct.getAttribute('data-months')) {
                monthsContainer.style.display = 'block';
                monthsDropdown.innerHTML = '';

                var months = parseInt(selectedProduct.getAttribute('data-months'));
                var akses = parseInt(selectedProduct.getAttribute('data-access'));
                var valueProduct = parseInt(selectedProduct.getAttribute('selectedValue'));

                //         document.addEventListener('DOMContentLoaded', function () {
                //     var selectedProductId = "{{ $order['selected_product_id'] }}";
                //     if (selectedProductId) {
                //         var nameProductSelect = document.getElementById('name_product');
                //         var options = nameProductSelect.options;

                //         for (var i = 0; i < options.length; i++) {
                //             if (options[i].value === selectedProductId) {
                //                 options[i].selected = true;
                //                 // Trigger change event if necessary
                //                 nameProductSelect.dispatchEvent(new Event('change'));
                //                 break;
                //             }
                //         }
                //     }
                // });
                var price = selectedProduct.getAttribute('data-price');
                var access = selectedProduct.getAttribute('data-access');
                // var opts = {
                //     minimumFractionDigits: 2
                // };
                // var value = (price).toLocaleString(
                //     undefined, // leave undefined to use the visitor's browser 
                //     // locale or a string like 'en-US' to override it.
                //     {
                //         minimumFractionDigits: 2
                //     }
                // );
                console.log(accessValue);
                console.log(valueProduct);
                if (accessValue == 3) {
                    for (var i = 1; i <= 6; i++) {
                        // if()
                        if (i == 1 && accessValue == 3 && months == 1 || i == 1 && accessValue == 3 && months ==
                            2 || i == 1 && accessValue == 3 && months == 3 || i == 2 && accessValue == 3 &&
                            months == 1 || i == 2 && accessValue == 3 && months == 2 || i == 3 && accessValue ==
                            3 && months == 1) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            // }).format((price - 200000) * i);
                            option.text = 'FREEZE ' + formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'FREEZE Rp.' + (price - 200000) * i + ' ' + i + ' bulan';
                            monthsDropdown.appendChild(option);
                        } else if (i == 1 && accessValue == 3 && months == 12 || i == 1 && accessValue == 3 &&
                            months == 24 || i == 2 && accessValue == 3 && months == 12 || i == 2 && accessValue ==
                            3 && months == 24 || i == 3 && accessValue == 3 && months == 12 || i == 3 &&
                            accessValue == 3 && months == 24) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            // }).format((price - 200000) * i);
                            option.text = 'FREEZE ' + formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'FREEZE Rp.' + (price - 200000) * i + ' ' + i + ' bulan';
                            monthsDropdown.appendChild(option);
                        }
                        // else if (i == 1 && accessValue == 3 || i == 2 && accessValue == 3 || i == 3 && accessValue == 3) {
                        //     var option = document.createElement('option');
                        //     option.value = i;
                        //     var formattedPrice = new Intl.NumberFormat('id-ID', {
                        //         style: 'currency',
                        //         currency: 'IDR'
                        //     }).format(price * i);
                        //     // }).format((price - 200000) * i);
                        //     option.text = 'FREEZE ' + formattedPrice + ' ' + i + ' bulan';
                        //     // option.text = 'FREEZE Rp.' + (price - 200000) * i + ' ' + i + ' bulan';
                        //     monthsDropdown.appendChild(option);
                        // }
                    }
                } 
                else {
                    for (var i = 1; i <= 12; i++) {

                        if (i == 12 && accessValue == 3) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(((price - 200000) * i - 360000));
                            option.text = 'FREEZE ' + formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'FREEZE Rp.' + ((price - 200000) * i - 360000) + ' ' + i + ' bulan';
                            monthsDropdown.appendChild(option);
                        } else if (i == 1 && accessValue < 0 || i == 1 && accessValue > 5 || i == 3 && accessValue <
                            0 || i == 3 && accessValue > 5 || i == 6 && accessValue < 0 || i == 6 && accessValue > 5
                        ) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            option.text = formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                            monthsDropdown.appendChild(option);
                        } else if (i == 1 || i == 3 || i == 6) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            option.text = formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                            monthsDropdown.appendChild(option);
                        } else if (i == 12 && accessValue > 5 || i == 12 && accessValue < 0) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format((price * i - 360000));
                            option.text = formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                            monthsDropdown.appendChild(option);
                        } else if (i == 12 && accessValue > 3 || i == 12 && accessValue < 2) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format((price * i - 360000));
                            option.text = formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                            monthsDropdown.appendChild(option);
                        }
                    }
                }

            } else {
                monthsContainer.style.display = 'none';
                monthsDropdown.innerHTML = '';
            }
        });
    </script>
    <script>
        var bandwidthSpan = document.getElementById('bandwidth-span');

        var ipDropdown = document.getElementById('IP');

        console.log(bandwidthSpan.innerHTML);


        document.getElementById('months').addEventListener('change', function() {
            var selectedMonth = this.value; // Mendapatkan nilai months yang dipilih oleh pengguna
            console.log(selectedMonth); // Menampilkan nilai months yang dipilih dalam konsol
            // Di sini Anda dapat melakukan apa pun yang Anda inginkan dengan nilai months yang dipilih
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
        document.getElementById('months').addEventListener('change', function() {
            var selectedProductType = document.getElementById('name_product').options[document.getElementById(
                'name_product').selectedIndex].getAttribute('data-type');
            var selectedMonth = this.value; // Mendapatkan nilai months yang dipilih oleh pengguna
            var ipDropdown = document.getElementById('IP');
            var ipPrices = [0, 240000, 720000, 3500000]; // Daftar harga IP address untuk setiap months
            var ipBulan = [29, 28, 27, 24]; // Daftar pilihan months IP
            var ipAddrress = [5, 13, 30, 256]; // Jumlah alamat IP untuk setiap opsi

            // Ubah harga IP address sesuai dengan months yang dipilih
            for (var i = 3; i >= 0; i--) {
                var formattedPrice = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format((ipPrices[i] * selectedMonth));
                if (ipBulan[i] == 29) {
                    ipDropdown.options[i].text = '/' + ipBulan[i] + ' (' + ipAddrress[i] + ' IP, GRATIS)';
                } else {
                    ipDropdown.options[i].text = '/' + ipBulan[i] + ' (' + ipAddrress[i] + ' IP) - ' +
                        formattedPrice + ' IDR';
                }

                // Jika produk yang dipilih adalah colocation dan months tidak dipilih (misalnya, saat halaman dimuat), atur nilai IP dropdown sesuai dengan pilihan months
                if (selectedProductType === 'colocation') {
                    ipDropdown.value = ipBulan[i];
                    console.log(ipDropdown.value, 'kuyy')
                } else if (selectedProductType !== 'colocation') {
                    ipDropdown.value = ipBulan[
                        0
                        ]; // Jika produk yang dipilih bukan colocation, atur nilai IP dropdown sesuai dengan nilai default (29)
                    console.log(ipDropdown.value)
                }
            }
        });
    </script>
@endpush
