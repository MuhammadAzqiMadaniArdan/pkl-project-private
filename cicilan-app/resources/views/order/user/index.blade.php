{{-- memanggil file template --}}
@extends('layouts.template')

{{-- isi bagian yield --}}
@section('content')
    <style>
        table {
            background: whitesmoke;
            border-radius: 10px;
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

        svg {
            width: 10px;
        }

        .text-sm {
            margin-top: 10px;
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
            <h3><b>Data Order</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Order</a></p>
        </div>
    </div>
    {{-- Add the form for selecting items per page --}}
    <form action="{{ route('order.index') }}" method="GET" class="form-inline mb-3 w-50">
        <label for="perPage" class="mr-2"><b>Items per page:</b></label>
        <select name="perPage" style="margin-top:10px;" id="perPage" class="form-control mr-2"
            onchange="this.form.submit()">
            <option value="3" {{ request('perPage') == 3 ? 'selected' : '' }}>3</option>
            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ request('perPage') == 15 ? 'selected' : '' }}>15</option>
            <!-- Add more options as needed -->
        </select>
    </form>
    <!-- Button trigger modal -->


    <!-- Modal -->
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
                    <a type="button" class="btn btn-success" href="{{ route('order.create') }}"
                        style="width: 49%">Dedicated</a>
                    <a type="button" class="btn btn-primary" href="{{ route('order.colocation') }}"
                        style="width: 49%">Colocation</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-25" data-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2">


        <div class=" justify-content-start" style="width: 30%;height:10%;">
            <form action="{{ route('order.search') }}" class="" method="GET"
                style="display: flex;justify-content:space-between;">
                <label for="search" class="form-label" style="width:30%">Search :</label>
                <input type="date" name="search" id="search" class="" style="width:100%;margin-left:5%;">
                <button type="submit" class="btn btn-primary mr-5" style="margin-left:5%;">cari</button>
            </form>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-primary mr-5" href="{{ route('order.index') }}" style="margin-right:2%;">Reset</a>
            <a class="btn btn-primary mr-5" href="{{ route('order.cart') }}" style="margin-right:2%;">Cart</a>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                Pembelian Baru
            </button> {{-- search page --}}
        </div>
        @foreach ($orders as $order)
    @if (Auth::user()->name == $order['name_customer'])
   

    @foreach ($order->status as $orderStatus)
    @if($orderStatus->payment < 1)
    <a class="btn btn-primary mr-5" href="{{ route('order.sewa',$order['id']) }}" style="margin-right:2%;">Sewa Server!</a>
    
@endif
@endforeach
@endif
@endforeach

        <table class="table mt-3 table-striped w-100 table-bordered table-hovered">
            {{-- <div class="box-body table-responsive no-padding">
                <table class="table table-hover"> --}}
    </div>

    
    @php
     $value = data_get($orders, '0', 0);

    @endphp
    @if($value !== 0)

    <thead>
        <tr>
            <th>No</th>
            <th>Client</th>
            <th>Pesanan</th>
            <th>Total Bayar</th>
            {{-- <th>user</th> --}}
            <th>Tanggal</th>
            <th>Tanggal Live</th>
            <th>Tanggal Expired</th>
            <th>Invoice</th>
            <th>Aksi</th>
            <th>Cicilan</th>
            <th>Status</th>
        </tr>
    </thead>
    @php
        $nomor = 1;
    @endphp
    <tbody>
        @foreach ($orders as $order)
            @if (Auth::user()->name == $order['name_customer'])
                @php
                    // foreach ($order['products'] as $product) {
                    //     dd(Auth::user()->name == $order['name_customer']);
                    //     # code...
                    // }
                    // dd(count($orders[3]['products']));
                @endphp

                {{-- @php
        $no = 1
        @endphp --}}

                <tr>
                    {{-- current page : ambil posisi di page keberapa -1 (misal udah klik next udah di page 2 berari jadi 2-1 = 1)
            perpage : mengambil jumlah data yang ditampilkjan di page nya berapa (ada dicontroller di bagian paginate atau simple paginate ,misal 5) 
            loop->index : mengambil index dari (array mulai dari 0) + 1 --}}
                    @php
                        $isColocation = false;
                        foreach ($order['products'] as $product) {
                            if ($product['type'] === 'colocation') {
                                $isColocation = true;
                                break;
                            }

                        }
                        // dd($isColocation);
                        $isDedicated = false;
                        foreach ($order['products'] as $product) {
                            if ($product['type'] === 'dedicated') {
                                $isDedicated == true;
                                break;
                            }
                        }
                    @endphp
                    {{-- <td>{{ ($orders->currentPage() - 1) * $orders->perpage() + $loop->index + 1 }}</td> --}}
                    <td>{{ $nomor }}</td>
                    @php $nomor++ @endphp
                    <td>{{ $order['name_customer'] }}</td>
                    {{-- <td> --}}
                        @php
                            $ramType = data_get($order['products'], '1.type', 0);
                            $ramPrice = data_get($order['products'], '1.price', 1);
                            $dataProduct = data_get($order['products'], '0.label', 0);
                            $serverData = data_get($order['products'], '4', 1);
                            $ramId = data_get($order['products'], '1.id', true);
                            // dd($ramId);

                        @endphp
                        {{-- @if ($ramType == 'ram' && $ramPrice == 0)
                            <div class="alert alert-success">
                                <li>Terimakasih telah membeli yaa~~ </br>Menunggu Custom Harga Dari Admin '-' </li>
                            </div>
                        @else --}}
                            {{-- <ol> --}}
                                {{-- Nested Loop : Looping didalam looping --}}
                                {{-- karena column products pada table orders tipe datanya json , jadi aksesnya bitih looping --}}
                                {{-- @foreach ($order['products'] as $product) --}}
                                    {{-- tampilan yang ingin ditampilkan : --}}
                                    {{-- output: 1. nama_obat Rp.3.000 (qty2) --}}
                                    {{-- @foreach ($order->status as $orderStatus)
                                @if ($orderStatus->access == 3)
                                    <li style="list-style-type:square;">
                                        <p>'FREEZE'</p>
                                        </small>
                                @endif
                            @endforeach --}}
{{--                                    
                            @if ($serverData == 1 && $dataProduct == 0)
                                    <li style="list-style-type:circle;">

                                        {{ $product['name_product'] }},<small>Rp.
                                            {{ number_format($product['price'], 0, '.', ',') }}
                                            <b>(qty : {{ $product['qty'] }})</b>
                                        </small>
                                        = Rp.{{ number_format($product['price_after_qty'], 0, '.', ',') }}
                                        <small>Type : {{ $product['type'] }}</small>
                                    </li>
                                    <hr>
                                    @elseif ($serverData !== 1 && $dataProduct == 0)
                                    <li style="list-style-type:circle;">

                                        {{ $product['name_product'] }} <small>Rp.
                                            {{ number_format($product['price'], 0, '.', ',') }}
                                            <b>(qty : {{ $product['qty'] }})</b>
                                        </small>
                                        = Rp.{{ number_format($product['price_after_qty'], 0, '.', ',') }}
                                        <small>Type : {{ $product['type'] }}</small>
                                    </li>
                                    <hr>
                                    @elseif($serverData == 1 && $dataProduct !== 0)
                                    <li style="list-style-type:circle;">

                                        {{ $product['name_product'] }} <small>Rp.
                                            {{ number_format($product['price'], 0, '.', ',') }}
                                            <b>(qty : {{ $product['qty'] }})</b>
                                        </small>
                                        = Rp.{{ number_format($product['price_after_qty'], 0, '.', ',') }}
                                        <small>Type : {{ $product['type'] }}</small>
                                        Label:{{ $order['products'][0]['label'] }}
                                    </li>
                                    <hr>
                                    @else

                                    <li style="list-style-type:circle;">

                                        {{ $product['name_product'] }} <small>Rp.
                                            {{ number_format($product['price'], 0, '.', ',') }}
                                            <b>(qty : {{ $product['qty'] }})</b>
                                        </small>
                                        = Rp.{{ number_format($product['price_after_qty'], 0, '.', ',') }}
                                        <small>Type : {{ $product['type'] }}</small>
                                        Label:{{ $order['products'][0]['label'] }}

                                    </li>
                                        <hr>
                                        @endif 

                                   
                                @endforeach
                            </ol>
                        @endif --}}
                            {{-- <ol>
                                    @for ($i = 0;$i < 1;$i++)
                                    <li> {{ $order['products'][$i]['name_product'] }} <small>Rp. {{ number_format($order['products'][$i]['price'], 0, '.', ',') }}<b>(qty : {{ $order['products'][$i]['qty'] }})</b></small> = Rp. {{ number_format($order['products'][$i]['price_after_qty'], 0, '.', ',') }}</li>
                                @endfor
                            </ol> --}}
                            {{-- @endforeach --}}
                    {{-- </td> --}}
                    <td>
                        {{-- @foreach ($order['products'] as $product) --}}
                        {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                        {{-- @dd($innerOrder['products'][0]) --}}
                            @for ($i = 0;$i < 1;$i++)
                                <li>{{ $order['products'][$i]['name_product'] }} <small>Rp. {{ number_format($order['products'][$i]['price'], 0, '.', ',') }}<b>(qty : {{ $order['products'][$i]['qty'] }})</b></small> = Rp. {{ number_format($order['products'][$i]['price_after_qty'], 0, '.', ',') }}</li>
                            @endfor
                    {{-- @endforeach --}}
                    </td>
                    @foreach ($order->status as $orderStatus)
                        @foreach ($order['products'] as $product)
                            @php

                                $secondProduct = $order->products[1] ?? $order->products[0];
                                $newProduct = $order->products[1] ?? null;
                                // $secondProduct = $order->products[1] ?? $order->products[0];
                                // $name = $order['name_customer'];
                                // if($name == 'Aaron'){

                                //     dd($name);

                                // };
                                $TpriceDedicated1 = $selectedProduct1['price'] * 12;
                                $TpriceDedicated2 = $selectedProduct2['price'] * 12;
                                $TpriceDedicated3 = $selectedProduct3['price'] * 12;

                                $ppn = $order['total_price'] * 0.1;
                                // $col_bul = $order['total_price'] / $order['bulan'];
                                $freeze_bul = $order['bulan'] - 1;

                                $freeze1 = $order['total_price'] - $secondProduct['price'] * $freeze_bul;
                                // ___________________colocationtheme______________________
                                // ____________________12_Theme____________________________
                                $Hdiskon = 0;
                                if ($orderStatus->access >= 6) {
                                    $Hdiskon = $orderStatus->access - 6;
                                }
                                if ($orderStatus->access < 0) {
                                    $Hdiskon = $orderStatus->access * -1;
                                }

                                $Bdiskon = 360000 * $Hdiskon;
                                // ___________________12_End________________________
                                $VLi = $product['price'];
                                // 01--------------------------------------------------
                                if ($order['votes'] == $order['bulan'] && $order['bulan'] == 12) {
                                    $VL = $VLi * $order['votes'] - $Bdiskon + 500000;
                                } elseif ($order['votes'] == $order['bulan']) {
                                    $VL = $VLi * $order['votes'];
                                } elseif ($newProduct && $newProduct['type'] == 'colocation') {
                                    $VL = $VLi * $order['bulan'];
                                } elseif ($orderStatus->access >= 6 || $orderStatus->access < -1) {
                                    $VL = $VLi * $order['votes'] - $Bdiskon;
                                }
                                //  elseif($orderStatus->access >= 6 || $orderStatus->access < 0) {
                                //     $VL = $VLi * $order['votes'] - 500000;
                                // }
                                else {
                                    $VL = $VLi * $order['votes'];
                                }

                                // ________________________colocation_________________________

                                // if(update per 3bln aka update [er 4 bln])
                                $totalAwal = $order['total_price'] - $VL;

                                if ($freeze1) {
                                    $freeze1 = $secondProduct['price'] * $freeze_bul;
                                }

                                if (
                                    ($orderStatus->access == 3 && $order['bulan'] == 12) ||
                                    ($orderStatus->access == 3 && $order['bulan'] == 24)
                                ) {
                                    $dedicated1 = $order['total_price'];
                                } else {
                                    $dedicated1 = $order['total_price'] - $secondProduct['price'] * $freeze_bul;
                                }

                                if ($order['bulan'] == 12 && $isColocation == false) {
                                    $productBulan = ($product['price'] + 300000) * $order['votes'];
                                } elseif ($order['bulan'] == 24 && $isColocation == false) {
                                    $productBulan = $product['price'] * $order['votes'];
                                } else {
                                    $productBulan = $product['price'] * $order['bulan'];
                                }

                                $freezeP1 = 0;
                                $collo1 = 0;

                                $productCol = $product['price'] * $order['bulan'];
                                // case DEDICATED_____________________________________
                                // case FREEZE_____________________________________
                                if ($order['total_price'] - 2250000 == $productBulan) {
                                    $freezeP1 = 750000 * 3;
                                } elseif ($order['total_price'] - 1050000 == $productBulan) {
                                    $freezeP1 = 350000 * 3;
                                    // case COLLOCATION_________________________________________
                                    // freeze 225 dan dedicated 1
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (2250000 + 6000000) == 0 &&
                                    $order['votes'] == $order['bulan'] &&
                                    $order['bulan'] == 12
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 750000 * 3;
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (2250000 + 6000000) == 0 &&
                                    $order['votes'] == $order['bulan']
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 750000 * 3;
                                } elseif ($isColocation == true && $totalAwal - (2250000 + 6000000 + 500000) == 0) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 750000 * 3;
                                }
                                // kondisi 12 bulan
                                // elseif ($isColocation == true && $totalAwal - (2250000 + 6000000 + 500000) == 0 ) {
                                //     $collo1 = $productBulan;
                                //     $freezeP1 = 750000 * 3;
                                // }
                                // freeze 105 dan dedicated 1
                                elseif (
                                    $isColocation == true &&
                                    $totalAwal - (1050000 + 6000000) == 0 &&
                                    $order['votes'] == $order['bulan'] &&
                                    $order['bulan'] == 12
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (1050000 + 6000000) == 0 &&
                                    $order['votes'] == $order['bulan']
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                }
                                // case 12 bulan after
                                elseif (
                                    $isColocation == true &&
                                    $totalAwal - (1050000 + 6000000 + 500000) == 0 &&
                                    $order['bulan'] == 12
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                } elseif ($isColocation == true && $totalAwal - (1050000 + 6000000 + 500000) == 0) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                    // END FREEZE____________________________________________
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - 6000000 == 0 &&
                                    $order['bulan'] == $order['votes']
                                ) {
                                    $collo1 = $productBulan;
                                } elseif ($isColocation == true && $totalAwal - (6000000 + 500000) == 0) {
                                    $collo1 = $productBulan;
                                }
                                // END COLLOCATION____________________________________________
                                // END Dedicated_______________________________________________

                                // >>>>>>>>>>>>>>>>>>>>>>>>>>StartsPemisah<<<<<<<<<<<<<<<<<<<<<<<

                                $productCol = $product['price'] * $order['bulan'];
                                // case DEDICATED_____________________________________
                                // case FREEZE_____________________________________
                                if ($order['total_price'] - 2250000 == $productBulan) {
                                    $freezeP1 = 750000 * 3;
                                } elseif ($order['total_price'] - 1050000 == $productBulan) {
                                    $freezeP1 = 350000 * 3;
                                    // case COLLOCATION_________________________________________
                                    // freeze 225 dan dedicated 1
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (2250000 + $TpriceDedicated1) == 0 &&
                                    $order['votes'] == $order['bulan'] &&
                                    $order['bulan'] == 12
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 750000 * 3;
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (2250000 + $TpriceDedicated1) == 0 &&
                                    $order['votes'] == $order['bulan']
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 750000 * 3;
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (2250000 + $TpriceDedicated1 + 500000) == 0
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 750000 * 3;
                                }
                                // kondisi 12 bulan
                                // elseif ($isColocation == true && $totalAwal - (2250000 + $TpriceDedicated1 + 500000) == 0 ) {
                                //     $collo1 = $productBulan;
                                //     $freezeP1 = 750000 * 3;
                                // }
                                // freeze 105 dan dedicated 1
                                elseif (
                                    $isColocation == true &&
                                    $totalAwal - (1050000 + $TpriceDedicated1) == 0 &&
                                    $order['votes'] == $order['bulan'] &&
                                    $order['bulan'] == 12
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (1050000 + $TpriceDedicated1) == 0 &&
                                    $order['votes'] == $order['bulan']
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                }
                                // case 12 bulan after
                                elseif (
                                    $isColocation == true &&
                                    $totalAwal - (1050000 + $TpriceDedicated1 + 500000) == 0 &&
                                    $order['bulan'] == 12
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - (1050000 + $TpriceDedicated1 + 500000) == 0
                                ) {
                                    $collo1 = $productBulan;
                                    $freezeP1 = 350000 * 3;
                                    // END FREEZE____________________________________________
                                } elseif (
                                    $isColocation == true &&
                                    $totalAwal - $TpriceDedicated1 == 0 &&
                                    $order['bulan'] == $order['votes']
                                ) {
                                    $collo1 = $productBulan;
                                } elseif ($isColocation == true && $totalAwal - (6000000 + 500000) == 0) {
                                    $collo1 = $productBulan;
                                }
                                // >>>>>>>>>>>>>>>>>>>>>>>>>>EndPemidah<<<<<<<<<<<<<<<<<<<<<<<<<

                                // elseif($isColocation == true && $order['total_price'] - (2250000 + $TpriceDedicated1 + 500000 + $productBulan) == $productBulan)
                                // {
                                //     $collo1 = $productBulan;
                                //     $freezeP1 = 350000 * 3;
                                // }
                                // $dedicated2 = $order['total_price'] - ($freezeP1 + $collo1);

                                $freeze2 = $freezeP1;
                                if (
                                    $order['votes'] == $order['bulan'] &&
                                    $orderStatus->access > 5 &&
                                    $order['bulan'] == 12
                                ) {
                                    $dedicated2 = $order['total_price'] - ($VL + $freeze2);
                                } elseif ($order['votes'] == $order['bulan'] && $orderStatus->access > 5) {
                                    $dedicated2 = $order['total_price'] - ($VL + $freeze2);
                                }
                                // elseif($orderStatus->access >= 6 )
                                //  {
                                //     $dedicated2 = $order['total_price'] - ($VL + $freeze2 + 500000);
                                // }
                                elseif ($orderStatus->access == 5) {
                                    $dedicated2 = $order['total_price'] - $freeze2;
                                } elseif ($orderStatus->access >= 6) {
                                    $dedicated2 = $order['total_price'] - ($VL + $freeze2 + 500000);
                                } elseif (
                                    $order['votes'] == $order['bulan'] &&
                                    $orderStatus->access < 0 &&
                                    $order['bulan'] == 12
                                ) {
                                    $dedicated2 = $order['total_price'] - $VL;
                                } elseif ($order['votes'] == $order['bulan'] && $orderStatus->access < 2) {
                                    $dedicated2 = $order['total_price'] - $VL;
                                } elseif ($orderStatus->access < -1) {
                                    $dedicated2 = $order['total_price'] - ($VL + 500000);
                                } elseif ($orderStatus->access < 0) {
                                    $dedicated2 = $order['total_price'] - $VL;
                                } else {
                                    $dedicated2 = $order['total_price'] - ($VL + 500000);
                                }

                                // $collo2 = $order['total_price'] - ($freezeP1 + $dedicated2 + $collo1);
                                // $col1 = $order['total_price'] - (2250000 + 6000000 + 500000);
                                // $collo1 = $order['total_price'] - $productBulan;
                                // dd($productBulan);
                                $ucup1 = $totalAwal - (2250000 + 6000000);
                                $ucup2 = $totalAwal - (2250000 + 6000000 + 500000);
                                $VLafter = $VL + 500000;
                                $col_bul = $VL / $order['votes'];

                            @endphp
                        @endforeach
                        @if ($ramType == 'ram' && $ramPrice == 0 && $ramId == true)
                        <td>
                                <div class="alert alert-success">
                                    <li>Sabar yaa~~ </br>Menunggu Custom Harga Dari Admin '-' </li>
                                </div>
                            </td>
                        @else
                            @if ($isColocation == true)
                                @if ($newProduct && $newProduct['type'] == 'colocation')
                                    <td>Harga Total :
                                        Rp.{{ number_format($order['total_price'], 0, '.', ',') }}
                                        || Perbulan
                                        Rp.{{ number_format($col_bul, 0, '.', ',') }}
                                        <hr>
                                        || Pembayaran Bulan ini :
                                        Rp.{{ number_format($VL, 0, '.', ',') }}


                                    </td>
                                @elseif($isColocation == true && $order['total_price'] == 1750000)
                                    <td>Harga Total :
                                        Rp.{{ number_format($order['total_price'], 0, '.', ',') }}
                                        || Perbulan
                                        Rp.{{ number_format($col_bul, 0, '.', ',') }}
                                        <hr>

                                    </td>
                                @elseif ($orderStatus->access >= 5)
                                    <td>Harga Total :
                                        Rp.{{ number_format($order['total_price'], 0, '.', ',') }}
                                        || Perbulan
                                        Rp.{{ number_format($col_bul, 0, '.', ',') }}
                                        <hr>
                                        @if ($order['votes'] == $order['bulan'])
                                            <hr>
                                            || Biaya Collocation :
                                            Rp.{{ number_format($VL, 0, '.', ',') }}
                                            <hr>
                                        @else
                                            || Biaya Collocation :
                                            Rp.{{ number_format($VLafter, 0, '.', ',') }}
                                            <hr>
                                        @endif
                                        || Biaya Dedicated : Rp.{{ number_format($dedicated2, 0, '.', ',') }}
                                        <hr>
                                        || Biaya Freeze :
                                        Rp.{{ number_format($freeze2, 0, '.', ',') }}
                                        <hr>
                                        || Biaya TotalAwal :
                                        Rp.{{ number_format($totalAwal, 0, '.', ',') }}
                                        <hr>
                                    </td>
                                @else
                                    <td>Harga Total :
                                        Rp.{{ number_format($order['total_price'], 0, '.', ',') }}
                                        || Perbulan
                                        Rp.{{ number_format($col_bul, 0, '.', ',') }}
                                        <hr>
                                        @if ($order['votes'] == $order['bulan'])
                                            <hr>
                                            || Biaya Collocation :
                                            Rp.{{ number_format($VL, 0, '.', ',') }}
                                            <hr>
                                        @else
                                            || Biaya Collocation :
                                            Rp.{{ number_format($VLafter, 0, '.', ',') }}
                                            <hr>
                                        @endif
                                        || Biaya Dedicated : Rp.{{ number_format($dedicated2, 0, '.', ',') }}
                                        <hr>
                                        || Biaya Total Awal :
                                        Rp.{{ number_format($totalAwal, 0, '.', ',') }}
                                        <hr>
                                    </td>
                                @endif
                            @elseif(($orderStatus->access == 3 && $order['bulan'] == 24) || ($orderStatus->access == 3 && $order['bulan'] == 12))
                                {
                                <td>Harga Total :
                                    Rp.{{ number_format($order['total_price'], 0, '.', ',') }}
                                    <hr>
                                    || Biaya Dedicated : Rp.{{ number_format($dedicated1, 0, '.', ',') }}
                                    <hr>
                                </td>
                                }
                            @elseif($orderStatus->access == 3)
                                <td>Harga Total :
                                    Rp.{{ number_format($order['total_price'], 0, '.', ',') }}
                                    <hr>
                                    || Biaya Dedicated : Rp.{{ number_format($dedicated1, 0, '.', ',') }}
                                    <hr>
                                    || Biaya Freeze :
                                    Rp.{{ number_format($freeze1, 0, '.', ',') }}
                                    <hr>
                                </td>
                            @elseif($orderStatus->access >= 5)
                                <td>Harga Total :
                                    Rp.{{ number_format($order['total_price'], 0, '.', ',') }}
                                    <hr>
                                    Dedicated:
                                    Rp.{{ number_format($dedicated2, 0, '.', ',') }}
                                    <hr>
                                    Freeze:
                                    Rp.{{ number_format($freeze2, 0, '.', ',') }}
                                    <hr>
                                </td>
                            @else
                                <td> Harga Dedicated :
                                    Rp.{{ number_format($order['total_price'], 0, '.', ',') }}</td>
                            @endif
                        @endif
                    @endforeach
                    {{-- <td>Rp.{{number_format($order['total_price'] + $ppn,0,'.',',')}}</td> --}}
                    {{-- <td>{{ $order['user']['name'] }} <a href="mailto:User@gmail.com">(User@gmail.com)</a></td> --}}
                    @php
                        setLocale(LC_ALL, 'IND');

                        // ------------------DATE----------------------
                        $date = $order->created_at;
                        // $startMonth = $date->format('d-m-Y');
                        // 31-5-2022
                        $x = 1;

                        // $endMonth = Carbon\Carbon::parse($order['updated_at'])->addDays(30)->formatLocalized('%d %B %Y %H:%M');

                        $mytime = Carbon\Carbon::now();
                        // 30-6-2022
                        //                 if ($lastAccessChangeTime && $access == 3) {
                        // $liveDate = Carbon\Carbon::parse($lastAccessChangeTime)->format('d F Y H:i:s');

                        // $liveDate = $mytime->formatLocalized('%d %B %Y %H:%M');
                        $liveDate = $mytime->formatLocalized('01 Maret 2025 14:49');
                        $pauseDate = Carbon\Carbon::parse($order['created_at'])
                            ->addDays(30 * $order['votes'])
                            ->subDays(30)
                            ->formatLocalized('%d %B %Y %H:%M');
                        // $liveDate = $mytime->formatLocalized('11 Agustus 2025 11:45');
                        // ---------------------INVOICE-COLLO-COND---------------------
                        $isPayment = false;
                        foreach ($order->status as $orderStatus) {
                            if ($orderStatus->payment < 1) {
                                $isPayment = true;
                                break;
                            }
                        }

                        $isFreeze = false;
                        foreach ($order->status as $orderStatus) {
                            if ($orderStatus->access == 4) {
                                $isFreeze = true;
                                break;
                            }
                        }

                        $isFreezeEnd = false;
                        foreach ($order->status as $orderStatus) {
                            if ($orderStatus->access == 3 && $order['bulan'] < 12) {
                                $isFreezeEnd = true;
                                break;
                            }
                        }

                        $isAfter = false;
                        foreach ($order->status as $orderStatus) {
                            if ($orderStatus->data >= 5) {
                                $isAfter = true;
                                break;
                            }
                        }
                        $isFive = 1;
                        foreach ($order->status as $orderStatus) {
                            if ($orderStatus->data >= 5) {
                                $isFive = $orderStatus->data;
                                break;
                            }
                        }
                        $isColocation = false;
                        foreach ($order['products'] as $product) {
                            if ($product['type'] === 'colocation') {
                                $isColocation = true;
                                break;
                            }
                        }

                        // Pengaturan invoice, endInvoice, dan liveInvoice
                        $invoiceDate = $isColocation ? $order['created_at'] : $order['created_at']
                        ;
                        // $invoiceDate = $isColocation ? $order['updated_at'] : $order['created_at'];
                        // memiliki arti jika bernilai true maka akan menghasilkan $product['created_at']
                        // jika false maka akna menghasilkan $order['created_at']
                        // $liveInvoice = $mytime->formatLocalized('240630');
                        // $liveInvoice = $mytime->formatLocalized('240620');
                        $liveInvoices = $mytime->formatLocalized('240125');
                        // $liveInvoice = $mytime->formatLocalized('%y%m%d');
                        $liveInvoice = $mytime->formatLocalized('240914');
                        $liveInvoiced = $mytime->formatLocalized('%H');

                        // -------------------end Month-sorangan--------------------
                        $col_invoice = $isColocation
                            ? Carbon\Carbon::parse($invoiceDate)
                                ->addDays(30 * $order['bulan'])
                                ->subDays(10)
                                ->diffInDays($liveInvoices)
                            : 0;

                        $ctFreeze = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%y%m%d');

                        $ctFreezod = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%H');

                        $FM = $liveInvoice - $ctFreeze;

                        $FH = $liveInvoiced - $ctFreezod;

                        $freezeMonthInvoice = Carbon\Carbon::parse($order['created_at'])
                            ->addDays($FM)
                            ->formatLocalized('%y%m%d');

                        $pauseUnfreeze = Carbon\Carbon::parse($order['created_at'])
                            ->addDays(30 * $order['votes'] + $FM)
                            ->subDays($FM + 30)
                            ->formatLocalized('%d %B %Y %H:%M');
                        // $freezeMonth= Carbon\Carbon::parse($order['created_at'])->addDays($FM)->formatLocalized('%d %B %Y %H:%M');
                        $freezeMonth = Carbon\Carbon::parse($order['created_at'])
                            ->addDays($FM)
                            ->addHours($FH)
                            ->formatLocalized('%d %B %Y %H');

                        $freezeEnd = Carbon\Carbon::parse($order['created_at'])
                            ->addDays(30 * $order['votes'] + $FM)
                            ->formatLocalized('%d %B %Y %H:%M');

                        // _____________________________________________________________
                        // maks Condition
                        $startMax = Carbon\Carbon::parse($order['updated_at'])
                            ->addDays(30 * ($isFive - 4) + 93)
                            ->subDays(93)
                            ->formatLocalized('%d %B %Y');

                        $upInvoice = Carbon\Carbon::parse($order['updated_at'])->addDays(30 * ($isFive - 4) + 93);
                        // $upInvoice =  Carbon\Carbon::parse($order['updated_at'])->addDays(30 * ($isFive - 4) + 93);

                        // $upInvoice =  $mytime->addDays(30 * ($isFive - 4) + 93);
                        // $stInvoice = $upInvoice->addDays(90);
                        $myMax = Carbon\Carbon::now();

                        $maxFreeze = $upInvoice->subDays(0)->formatLocalized('%d %B %Y');

                        // $maxEnd = $upInvoice
                        //     ->subDays(93)
                        //     ->addDays(3)
                        //     ->formatLocalized('%d %B %Y');
                        // ->subDays(94)
                        // fungsi ASLIIIII
                        // maxEnd adalah hari terakhir batas Maximal 3 hari ?tenggat freeze
                        $maxEnd = $mytime->subDays(90)->addDays(3)->formatLocalized('%d %B %Y');

                        // fungsi ASLIIIII
                        // maxDate adalah hari setelah nunggu 3bulan atau tenggat waktu
                        $maxDate = $myMax->subDays(3)->formatLocalized('%d %B %Y');
                        // $maxDate = $upInvoice->subDays(3)->formatLocalized('%d %B %Y');

                        // $maxDate = $mytime->subDays(90)->formatLocalized('%d %B %Y %H:%M');

                        $endMonth =
                            $isColocation && $order['votes'] <= $order['bulan']
                                ? Carbon\Carbon::parse($invoiceDate)
                                    // ->addDays(30 * $order['votes'] )
                                    ->addDays(30 * $order['bulan'] + $col_invoice)
                                    ->formatLocalized('%d %B %Y %H:%M')
                                : ($isFreezeEnd
                                    ? Carbon\Carbon::parse($order['updated_at'])
                                        ->addDays(30 * $order['bulan'])
                                        ->formatLocalized('%d %B %Y %H:%M')
                                    : ($isFreeze
                                        ? Carbon\Carbon::parse($order['updated_at'])
                                            ->addDays(30)
                                            ->formatLocalized('%d %B %Y %H:%M')
                                            
                                            : ($isPayment
                                                    ? Carbon\Carbon::parse($order['updated_at'])
                                                    ->addDays(30 * $order['votes'])
                                                        ->formatLocalized('%d %B %Y %H:%M')
                                        : ($isAfter
                                            ? Carbon\Carbon::parse($order['updated_at'])
                                                ->addDays(30 * ($isFive - 4))
                                                ->formatLocalized('%d %B %Y %H:%M')
                                            : Carbon\Carbon::parse($order['created_at'])
                                                ->addDays(30 * $order['votes'])
                                                ->formatLocalized('%d %B %Y %H:%M')))));

                        $startMonth =
                            $isColocation && $order['votes'] <= $order['bulan']
                                ? Carbon\Carbon::parse($invoiceDate)
                                    // ->addDays($FM)
                                    // ->addDays(30 * $order['bulan'] + $col_invoice)
                                    ->formatLocalized('%d %B %Y %H:%M')
                                : ($isFreeze
                                    ? Carbon\Carbon::parse($order['updated_at'])
                                        ->addDays(30)
                                        ->formatLocalized('%d %B %Y %H:%M')
                                    : ($isFreezeEnd
                                        ? Carbon\Carbon::parse($order['updated_at'])
                                            ->addDays(30)
                                            ->formatLocalized('%d %B %Y %H:%M')
                                            
                                            : ($isPayment
                                                    ? Carbon\Carbon::parse($order['updated_at'])
                                                        ->formatLocalized('%d %B %Y %H:%M')
                                        : ($isAfter
                                            ? Carbon\Carbon::parse($order['updated_at'])
                                                ->addDays(30 * ($isFive - 5))
                                                ->formatLocalized('%d %B %Y %H:%M')
                                            : Carbon\Carbon::parse($order['updated_at'])->formatLocalized(
                                                '%d %B %Y %H:%M',
                                            )))));

                        // ------------------
                        // $endInvoice = Carbon\Carbon::parse($invoiceDate)
                        //     ->addDays(30 * $order['votes'])
                        //     ->formatLocalized('%y%m%d');
                        // $liveInvoice = Carbon\Carbon::parse($invoiceDate)->formatLocalized('%y%m%d');
                        // ----------------------INVOICE-----------------------
                        // $liveInvoice = $mytime->formatLocalized('%y%m%d');
                        // $liveInvoice = $mytime->formatLocalized('240217');
                        // $invoice = Carbon\Carbon::parse($order['created_at'])
                        // ->addDays(10)
                        // ->formatLocalized('%y%m%d');

                        // $ended = Carbon\Carbon::parse($order['created_at'])
                        // $endInvoice1 = $isColocation  && $order['votes'] == 1 ?  Carbon\Carbon::parse($invoiceDate)
                        // ->addDays(30 * $order['votes'] * 12);

                        // $endInvoice = Carbon\Carbon::parse($order['created_at'])

                        $unDate = Carbon\Carbon::parse($order['updated_at'])->formatLocalized('%d %B %Y %H:%M');

                        $unMonth = Carbon\Carbon::parse($order['updated_at'])
                            ->addDays(30)
                            ->formatLocalized('%d %B %Y %H:%M');

                        // $unMonth = Carbon\Carbon::parse($order['updated_at'])->addDays(30 * $order['votes'])->formatLocalized('%d %B %Y %H:%M');

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
                                    : ($isFreeze
                                        ? Carbon\Carbon::parse($order['updated_at'])
                                            ->addDays(30)
                                            ->formatLocalized('%y%m%d')
                                        : ($isFreezeEnd
                                            ? Carbon\Carbon::parse($order['updated_at'])
                                                ->addDays(30 * $order['bulan'])
                                                ->formatLocalized('%y%m%d')
                                                : ($isPayment
                                                ? Carbon\Carbon::parse($order['updated_at'])
                                                    ->addDays(30 * $order['votes'])
                                                    ->subDays()
                                                    ->formatLocalized('%y%m%d')
                                            : ($isAfter
                                                ? Carbon\Carbon::parse($order['updated_at'])
                                                    ->addDays(30 * ($isFive - 4))
                                                    ->formatLocalized('%y%m%d')
                                                : Carbon\Carbon::parse($invoiceDate)
                                                    ->addDays(30 * $order['votes'])
                                                    ->formatLocalized('%y%m%d'))))));

                        // $collocation_invoice = $invoice - $liveInvoice;

                        $invoice = $isColocation
                            ? Carbon\Carbon::parse($invoiceDate)
                                ->addDays(30 * $order['bulan'])
                                ->subDays(10)
                                ->formatLocalized('%y%m%d')
                            : ($isFreeze
                                ? Carbon\Carbon::parse($order['updated_at'])
                                    ->addDays(30)
                                    ->subDays(10)
                                    ->formatLocalized('%y%m%d')
                                : ($isFreezeEnd
                                    ? Carbon\Carbon::parse($order['updated_at'])
                                        ->addDays(30 * $order['bulan'])
                                        ->subDays(10)
                                        ->formatLocalized('%y%m%d')
                                        : ($isPayment
                                                    ? Carbon\Carbon::parse($order['updated_at'])
                                                        ->addDays(30 * $order['votes'])
                                                        ->subDays(10)
                                                        ->formatLocalized('%y%m%d')
                                    : ($isAfter
                                        ? Carbon\Carbon::parse($order['updated_at'])
                                            ->addDays(30 * ($isFive - 4))
                                            ->subDays(10)
                                            ->formatLocalized('%y%m%d')
                                        : Carbon\Carbon::parse($invoiceDate)
                                            ->addDays(30 * $order['votes'])
                                            ->subDays(10)
                                            ->formatLocalized('%y%m%d')))));

                        $ins = $order->whereMonth('updated_at', '=', date('m'));
                        $month = Carbon\Carbon::parse('2022/06/12')->format('MM');
                        $cuy = Carbon\Carbon::parse($order['updated_at'])->addDays(30 * $order['bulan']);
                        $endShip = Carbon\Carbon::parse($order['updated_at'])
                            ->addDays(30 * $order['bulan'])
                            ->addDays(3)
                            ->formatLocalized('%y%m%d');
                        $startInpov = Carbon\Carbon::parse($order['updated_at'])
                            ->addDays(30 * $order['bulan'])
                            ->subDays(10)
                            ->formatLocalized('%y%m%d');
                    @endphp
                    @foreach ($order->status as $orderStatus)
                        @if ($orderStatus->access == 4)
                            {{-- <td>Start Date : {{ $pauseUnfreeze }}</td> --}}
                            <td>Start Date : {{ $unDate }}</td>
                        @else
                            <td>Start Date : {{ $startMonth }}</td>
                        @endif
                    @endforeach
                    @foreach ($order->status as $orderStatus)
                        @if ($orderStatus->access == 3)
                            {{-- @if ($invoice <= $liveInvoice) --}}
                            {{-- <td>Live Date : {{ $pauseDate }}</td> --}}
                            <td>Live Date : {{ $liveDate }}</td>
                        @else
                            <td>Live Date : {{ $liveDate }}</td>
                            {{-- <td>Live Date : {{ $liveDate }}</td> --}}
                        @endif
                    @endforeach
                    <td>INVOICE : {{ $invoice }} </td>
                    @foreach ($order['products'] as $product)
                        @php
                            $value = data_get($order['products'], '1.type', 0);
                            $price = data_get($order['products'], '1.price', 0);
                            // $value = object_flatten($product);
                            // dd($value,$price);
                        @endphp
                    @endforeach
                    @foreach ($order->status as $orderStatus)
                        @if ($orderStatus->access == 4)
                            <td>Expired Date
                                :
                                {{-- {{ $freezeEnd }} --}}
                                {{ $endMonth }}
                            </td>
                        @else
                            <td>Expired Date
                                :
                                {{ $endMonth }} </br>
                                ({{ $endShip }})
                            </td>
                        @endif
                        {{-- {{$endMonth}} --}}
                    @endforeach

                    @if ($ramType == 'ram' && $ramPrice == 0 && $ramId == true)
                        <td>
                            <div class="alert alert-success">
                                <li>Terimakasih Telah Membeli :>
                                    <hr>Menunggu Custom Harga Dari Admin '-'
                                </li>
                            </div>
                        </td>
                    @else
                        @foreach ($order->status as $orderStatus)
                        @php
                        $paymentSet = $orderStatus->payment;
                            if($paymentSet > 0){

                                $paymentMinus = $orderStatus->payment - 1;
                            }else{
                                $paymentSewa = $paymentSet * -1 ;
                                // dd($paymentSewa);
                                if($paymentSet == 0){
                                    $paymentMinus = 0;
                                }else{
                                    
                                    $paymentMinus = $paymentSewa - 1;
                                }
                            }
                    @endphp
                                        {{-- @dd($paymentMinus) --}}

                    @if($orderStatus->payment < 1 && $liveInvoice < $endInvoice && $liveInvoice < $invoice)
                    <td style="padding:10px 10px;background-color:rgb(6, 10, 6);color:#f5f5f5;">
                        User Sedang Melakukan Penyewaan Server
                        {{-- <ol>
                            @foreach ($order['datacenter'] as $product)
                               1. Nama obat Rp. 1.000 (qty 2) = Rp. 2.000
                                <li style="list-style-type:circle;" class="mt-3"> Data center
                                    :{{ $product['datacenter'] }} <br>
                                    Rak : {{ $product['rack'] }}
                                </li>
                                <hr>
                            @endforeach
                            
                            </ol> --}}

                    </td>
                    @elseif($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $paymentMinus == $order['votes'])
                                <td>
                                    <a class="btn btn-success mb-2" id="bayar"
                                    href="{{ route('order.bayar', $order['id']) }}">Pembayaran</a>
                                </td>
                                @elseif($paymentMinus >= $order['votes'])
                                <td>
                                    {{-- @dd() --}}
                                    <a class="btn btn-primary mb-2" id="bayar" href="#">Menunggu Response Admin </a>
                                    <hr>
                                </td>
                            @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == false)
                                <td style="padding:10px 10px;background-color:lime;">Terimakasih Sudah Membeli</td>
                            @elseif ($invoice >= $liveInvoice && $isColocation == true)
                                <td style="padding:10px 10px;background-color:rgb(0, 221, 0);color:#f5f5f5;">Terimakasih
                                    Sudah
                                    Membeli
                                    Collocation</td>
                                {{-- @elseif($orderStatus->access == 3 && $maxEnd >= $startMax) --}}
                            @elseif($orderStatus->access == 3 && $liveInvoice >= $endShip)
                                <td>
                                    <a class="btn btn-warning mb-2" id="bayar" href="#">Cicilan di Terminated </a>
                                    <hr>
                                </td>
                                
                                {{-- @elseif($orderStatus->access == 3 && $maxDate == $startMax) --}}
                            @elseif($orderStatus->access == 3 && $liveInvoice >= $endInvoice)
                                <td>
                                    <a class="btn btn-primary mb-2" id="bayar" href="#">Waktu MAX </br> FREEZE
                                    </a>
                                    <a class="btn btn-danger mb-2" id="bayar" href="#">Anda akan di Terminated
                                        </br>dalam
                                        3 hari </a>
                                    <hr>
                                </td>
                                {{-- @elseif($orderStatus->access == 3 && $maxDate < $startMax && $order['bulan'] == 3) --}}
                                
                            @elseif($orderStatus->access == 3 && $startInpov >= $liveInvoice && $order['bulan'] == 4)
                                <td>
                                    <div class="alert alert-success"> Lanjutkan</br> Pembayaran
                                        <hr>
                                        <a class="btn btn-success mb-2" id="bayar"
                                            href="{{ route('order.bayar', $order['id']) }}">Bayar</a>
                                    </div>

                                </td>
                            @elseif($orderStatus->access == 3 && $liveInvoice < $endInvoice && $order['bulan'] == 4)
                                <td>
                                    <a class="btn btn-primary mb-2" id="bayar" href="#">Anda Sudah
                                        Beralangganan Freeze
                                        </br> dengan Batas MAX </a>
                                    <hr>
                                </td>
                            @elseif($orderStatus->access == 3 && $liveInvoice < $endInvoice)
                                <td>
                                    <div class="alert alert-primary">Anda di FREEZE</a>
                                        <hr>
                                        <a class="btn btn-primary mb-2" id="bayar"
                                            href="{{ route('order.bayar', $order['id']) }}">Bayar</a>
                                    </div>
                                </td>
                            @elseif($orderStatus->access >= 4 && $invoice <= $liveInvoice && $liveInvoice < $endInvoice)
                                <td>
                                    <a class="btn btn-success mb-2" id="bayar"
                                        href="{{ route('order.bayar', $order['id']) }}">Bayar</a>
                                </td>
                            @elseif($invoice <= $liveInvoice && $liveInvoice < $endInvoice && $isColocation == false)
                                <td>
                                    <a class="btn btn-success mb-2" id="bayar"
                                        href="{{ route('order.bayar', $order['id']) }}">Bayar</a>
                                </td>
                            {{-- @elseif($invoice <= $liveInvoice && $liveInvoice < $endInvoice && $isColocation == true)
                                <td>
                                    <a class="btn btn-success mb-2" id="bayar"
                                        href="{{ route('order.bayar', $order['id']) }}">Bayar</a>
                                </td> --}}
                                @elseif (($liveDate == $endMonth && $isColocation == true) || $liveInvoice >= $invoice && $isColocation == true ||  $liveDate == $freezeEnd)
                                @php
                                    $liveDate = $endMonth;
                                @endphp
                                <td>
                                    <p class="alert alert-success" style="padding:5px 30px;">Masa Collocation Sudah Habis
                                    </p>
                                    <button id="layanan" class="alert alert-primary">pilih layanan</button>
                                    <hr>
                                    <a class="btn btn-success w-100 mb-3" id="collo"
                                        href="{{ route('order.bayar', $order['id']) }}">Pindah Collocation</a>
                                    <a class="btn btn-info w-100 mb-2" href="{{ route('order.length', $order['id']) }}"
                                        style="color:white;">Perpanjang Layanan </a>

                                    <button onclick="pindah(this,'orange')" class="btn btn-danger w-100 mb-2">Pindah</button>
                            </td>
                            @elseif ($liveDate >= $endMonth && $isColocation == false || $liveInvoice > $endInvoice)
                                @php
                                    $liveDate = $endMonth;

                                    // for($i=0;$i < $order['votes'] ;$i++){
                                    // $update = $startMonth->addDays(30);
                                    // }

                                @endphp
                                <td>
                                    <div class="alert alert-danger">
                                        Tenggat Waktu
                                    </div>
                                    Expired Date : {{ $liveDate }}
                                    <br>
                                    <hr>
                                    @foreach ($order->status as $orderAccess)
                                        @if ($orderAccess->access == 1)
                                            <button onclick="suspend(this,'green')" class="btn btn-danger">Anda telah
                                                </br>diSuspend</button>
                                            <hr>
                                        @elseif ($orderAccess->access == 2)
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('order.bayar', $order['id']) }}">Bayar</a>
                                        @elseif($orderAccess->access == 3)
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('order.bayar', $order['id']) }}">Anda di </br> FREEZE</a>
                                            <a class="btn btn-primary mb-2" id="bayar"
                                                href="{{ route('order.bayar', $order['id']) }}">Bayar </br> FREEZE</a>
                                        @elseif($orderAccess->access == 4)
                                            <a class="btn btn-primary mb-2" id="bayar"
                                                href="{{ route('order.bayar', $order['id']) }}">Bayar UNFREEZE</a>
                                        @endif
                                    @endforeach
                                    {{--                              
                                <a class="btn btn-success mb-2" id="bayar" href="{{route('order.bayar', $order['id'])}}">Bayar</a> --}}
                                </td>
                                {{-- <td><a href="#" class="btn btn-success">download to excel</a></td> --}}
                            

                            @elseif($order['votes'] >= 2 && $isColocation == false)
                                <td style="padding:10px 10px;background-color:aqua;">User berlangganan Kembali</td>
                            @else
                                <td style="padding:10px 10px;background-color:rgb(0, 224, 224);color:white;">User
                                    berlangganan
                                    Collocation Kembali
                                    {{-- <a class="btn btn-success mb-2" id="bayar"
                            href="{{ route('order.bayar', $order['id']) }}">Bayar</a> --}}
                                </td>
                            @endif
                        @endforeach
                    @endif

                    {{-- <td>{{Carbon\Carbon::parse($order['created_at'])->formatLocalized('%d %B %Y')}}</td> --}}
                    <td>
                        {{-- <td style="background:linear-gradient(to bottom,rgb(99, 100, 100) 40%,blue 50%);"> --}}
                        {{-- <div class="d-flex justify-content-end mt-3">
                mengambil column dari relasi, $ variabel ["namaFunctionDiModel],[namFunctionDiClass]
                    <a href="{{route('order.download-pdf',$order['id'])}}" class="btn btn-success">Cetak (.pdf)</a>
                
                </div> --}}
                @if ($ramType == 'ram' && $ramPrice == 0 && $ramId == true)
                <div class="alert alert-success">
                                <li>Terimakasih telah Membeli yaa~~ </br>Menunggu Custom Harga Dari Admin '-' </li>
                            </div>
                        @else
                            @foreach ($order->status as $orderStatus)
                                @if ($orderStatus->access == 3 && $isColocation == false)
                                    @if ($order['bulan'] == 12 || $order['bulan'] == 24)
                                        <p> Anda akan mulai berlangganan FREEZE <b>
                                            </b> (MAX:3 Bulan)</p>
                                    @else
                                        <p> Anda telah berlangganan FREEZE selama <b> {{ $order['bulan'] - 1 }}
                                            </b>
                                            bulan (MAX:3 Bulan)</p>
                                    @endif
                                @elseif ($orderStatus->access == 2 && $order['votes'] < $order['bulan'] && $isColocation == false)
                                    <p class="alert alert-danger"> Anda belum berlangganan Cicilan Dedicated
                                         </p>
                                @elseif ($order['votes'] < $order['bulan'] && $isColocation == false)
                                    <p> Anda berlangganan Cicilan Dedicated dari <b>
                                            {{ $order['votes'] }}/{{ $order['bulan'] }}
                                        </b>
                                        bulan </p>
                                @elseif($order['votes'] < $order['bulan'] && $isColocation == true)
                                    <p> Anda berlangganan Collocation Selama <b> {{ $order['bulan'] }} </b> </p>
                                @elseif($order['votes'] == $order['bulan'] && $isColocation == false)
                                    <p class="btn btn-success" style="padding:5px 30px;">lunas</p>
                                    <button id="layanan" onclick="suspend(this,'green')" class="btn btn-">pilih
                                        layanan</button>
                                    <hr>
                                    <a class="btn btn-success" id="collo"
                                        href="{{ route('order.bayar', $order['id']) }}">Collocation</a>
                                    <hr>
                                    <button onclick="pindah(this,'orange')" class="btn btn-danger">Pindah</button>
                                @else
                                    <p> INI ELSE Anda berlangganan Collocation Selama <b> {{ $order['bulan'] }} </b> bulan
                                    </p>
                                @endif
                            @endforeach
                    </td>
            @endif
            <td style="padding:10px 10px;">
                @foreach ($order->status as $orderStatus)
                    @if ($orderStatus->data == 1)
                        <div class="btn btn-primary SPK-submit">
                            Menunggu Confirm </br>SPK
                        </div>
                    @elseif($orderStatus->data == 2)
                        @php
                            $orderStatus->status = 'proses SPK';
                        @endphp
                        <div class="btn btn-primary SPK-submit">
                            Menunggu confirm </br>TandaTangan SPK
                        </div>
                    @elseif($orderStatus->data == 3)
                        <div class="btn btn-primary    SPK-submit">
                            Menunggu Confirm </br>Semuanya
                        </div>
                    @else
                        <div class="btn btn-success SPK-submit" href="#">
                            Semua Data Done
                        </div>
                        <hr>
                        <a class="btn btn-primary" href="{{ route('order.show', $orderStatus['id']) }}">Lihat
                            SPK!</a>
                    @endif
                @endforeach

            </td>
            {{-- <td style="padding:10px 10px;background-color:lightblue ;">
                    @foreach ($order->status as $orderAccess)
                        @if ($orderAccess->access == 1)
                            <div class="btn btn-primary SPK-submit">
                                Menunggu Confirm </br>SPK
                            </div>
                        @elseif($orderAccess->access == 2)
                            @php
                                $orderAccess->status = 'proses SPK';
                            @endphp
                            <div class="btn btn-primary SPK-submit">
                                Menunggu confirm </br>TandaTangan SPK
                            </div>
                        @elseif($orderAccess->access == 3)
                            <div class="btn btn-primary    SPK-submit">
                                Menunggu Confirm </br>Semuanya
                            </div>
                        @else
                            <div class="btn btn-success SPK-submit" href="#">
                                Semua Data Done
                            </div>
                        @endif
                    @endforeach
                </td> --}}
            </tr>
        @endif
        @endforeach
    </tbody>
    @else
    <div class="card">
        <h5 class="card-header">Featured</h5>
        <div class="card-body">
          <h5 class="card-title" style="text-align:center;padding:10%;">Tidak Ada Pesanan</h5>
          
        </div>
      </div>

    @endif

    </table>
    <div class="d-flex justify-content-start">
        {{-- @if ($orders->count())
            {{ $orders->links() }}
        @endif --}}

        {{-- {{ $orders->withQueryString()->links() }} --}}
        {{ $orders->links() }}
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
    <script>
        const bayar = document.getElementById("bayar");
        var freeze = document.getElementById("freeze");
        let layanan = document.getElementById("layanan");
        var collo = document.getElementById("collo");

        function suspend(element, color) {
            element.style.backgroundColor = color;
            element.innerHTML = "Anda sudah </br> tidak berlangganan"
            bayar.remove()
            if (freeze.style.visibility === 'visible') {
                freeze.style.visibility = 'hidden';
            } else {
                freeze.style.visibility = 'visible';
            }
        }

        function pindah(element, color) {
            element.style.backgroundColor = color;

            element.innerHTML = "Anda sudah </br> tidak berlangganan"
            bayar.remove()
            if (layanan.style.visibility === 'hidden') {
                layanan.style.visibility = 'visible';
                collo.style.visibility = 'visible';
            } else {
                collo.style.visibility = 'hidden';
                layanan.style.visibility = 'hidden';
            }
        }
    </script>
@endpush
