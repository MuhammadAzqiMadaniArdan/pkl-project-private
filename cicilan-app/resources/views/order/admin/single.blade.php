@extends('layouts.template')

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, rgb(202, 202, 255), white);
        }

        /* .table{
                                                background: #fff;
                                              } */
        a {
            color: black;
            text-decoration: none;
        }

        table {
            background: whitesmoke;
            border-radius: 10px;
            padding: 10px 5px;
        }

        .table {
            padding: 0.5rem 1.5rem;
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
        tr{
            text-align: center;
        }
    </style>
    <div class="jumbotron  mt-2" style="padding:0px;">
        @if (Session::get('success'))
            <br>
            @include('sweetalert::alert')
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::get('deleted'))
            <br>
            <div class="alert alert-success">
                {{ Session::get('deleted') }}
            </div>
        @endif
        <div class="container mb-5">
            {{-- @if (Session::get('failed'))
                <div class="alert alert-danger">{{Session::get('failed')}}</div>
                @endif --}}
            <h3><b>Data Single</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Single</a></p>
        </div>
    </div>

    <body>
        @php
            $statusGet = data_get($status1, '0.id', 0);
            // dd($status1[0]['id']);
            if ($statusGet == 0) {
                # code...
                $Validate = false;
            } else {
                foreach ($status1 as $order) {
                    $Validate = data_get($order['products'], '0', false);
                }
            }
        @endphp
        {{-- @dd($Validate); --}}
        @if ($Validate !== false)
            <div class="mt-2">
                {{-- <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.order.downloadExcel') }}" class="btn btn-success">Export Excel</a> --}}
                </div>
                <br>
                <div class="d-flex justify-content-end mb-3">
                </div>
                <table class="table-stripped table mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Client</th>
                            <th>Order</th>
                            <th>Total Bayar</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                            $uniqueCustomers = [];
                        @endphp
                        @foreach ($status1 as $order)
                            {{-- Cek apakah nama pelanggan sudah ditampilkan sebelumnya --}}
                            @php

                                $invoiceDate = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%y%m%d');

                            @endphp

                            {{-- Jika belum pernah ditampilkan, tampilkan nama pelanggan --}}
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $order['name_customer']}}  </td>
                                @php
                                    $freezeProducts = last($order['products']);
                                    if ($freezeProducts['type'] == 'freeze') {
                                        $hasFreeze = true;
                                        // dd($freezeProducts['type']);
                                    } else {
                                        $hasFreeze = false;
                                    }
                                @endphp
                                <td>
                                    <ol>
                                        {{-- @foreach ($dedic1 as $innerOrder) --}}
                                        {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                        {{-- @if ($innerOrder['name_customer'] == $order['name_customer']) --}}
                                        {{-- @dd($innerOrder['products'][0]) --}}
                                        @if ($hasFreeze == false)
                                            @for ($i = 0; $i < 1; $i++)
                                                <li>{{ $order['products'][$i]['name_product'] }} <small>Rp.
                                                        {{ number_format($order['products'][$i]['price'], 0, '.', ',') }}<b>(qty
                                                            :
                                                            {{ $order['products'][$i]['qty'] }})</b></small> = Rp.
                                                    {{ number_format($order['products'][$i]['price_after_qty'], 0, '.', ',') }}
                                                </li>
                                            @endfor
                                        @else
                                            <li>{{ $freezeProducts['name_product'] }}
                                                <small>Rp.{{ number_format($freezeProducts['price']) }}</small><b>(qty :
                                                    {{ $freezeProducts['qty'] }})</b></small> = Rp.
                                                {{ number_format($freezeProducts['price_after_qty'], 0, '.', ',') }}</li>
                                        @endif
                                        {{-- @endif --}}
                                        {{-- @endforeach --}}
                                    </ol>
                                </td>
                                {{-- @php
                            $ppn = $order['total_price'] * 0.1;
                        @endphp --}}
                                <td>Rp. {{ number_format($order['total_price'], 0, '.', ',') }}</td>
                                @php
                                    setLocale(LC_ALL, 'IND');

                                    // ------------------DATE----------------------
                                    $date = $order->created_at;
                                    $startMonth = Carbon\Carbon::parse($order['created_at'])->formatLocalized(
                                        '%d %B %Y',
                                    );
                                    // $startMonth = $date->format('d-m-Y');
                                    // 31-5-2022
                                    $x = 1;

                                    // $endMonth = Carbon\Carbon::parse($order['updated_at'])->addDays(30)->formatLocalized('%d %B %Y %H:%M');

                                    $mytime = Carbon\Carbon::now();
                                    // 30-6-2022
                                    // $liveDate = $mytime->form atLocalized('%d %B %Y %H:%M:00');
                                    $liveDate = $mytime->formatLocalized('%d %B %Y %H:%M');
                                    $pauseDate = Carbon\Carbon::parse($order['created_at'])
                                        ->addDays(30 * $order['votes'])
                                        ->subDays(30)
                                        ->formatLocalized('%d %B %Y %H:%M');

                                    $liveInvoice = $mytime->formatLocalized('%y%m%d');
                                    $liveInvoices = $mytime->formatLocalized('%y%m%d');
                                    $liveInvoiced = $mytime->formatLocalized('%H');
                                    // terminated = seminggu
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

                                    $isAfter = false;
                                    foreach ($order->status as $orderStatus) {
                                        if ($orderStatus->data >= 5) {
                                            $isAfter = true;
                                            break;
                                        }
                                    }
                                    $isFreezeEnd = false;
                                    foreach ($order->status as $orderStatus) {
                                        if ($orderStatus->data == 3) {
                                            $isFreezeEnd = true;
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
                                    // dd($order['products']);

                                    // Pengaturan invoice, endInvoice, dan liveInvoice
                                    $invoiceDate = $isColocation ? $order['created_at'] : $order['updated_at'];

                                    // -------------------end Month-sorangan--------------------
                                    $col_invoice = $isColocation
                                        ? Carbon\Carbon::parse($invoiceDate)
                                            ->addDays(30 * $order['bulan'])
                                            ->subDays(10)
                                            ->diffInDays($liveInvoice)
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

                                    $upInvoice = Carbon\Carbon::parse($order['updated_at'])->addDays(
                                        30 * ($isFive - 4) + 93,
                                    );
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
                                                            ->subDays()
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
                                                : ($isPayment
                                                    ? Carbon\Carbon::parse($order['updated_at'])->formatLocalized(
                                                        '%d %B %Y %H:%M',
                                                    )
                                                    : ($isAfter
                                                        ? Carbon\Carbon::parse($order['updated_at'])
                                                            ->addDays(30 * ($isFive - 5))
                                                            ->formatLocalized('%d %B %Y %H:%M')
                                                        : Carbon\Carbon::parse($order['created_at'])->formatLocalized(
                                                            '%d %B %Y %H:%M',
                                                        ))));

                                    // ------------------
                                    // $endInvoice1 = $isColocation  && $order['votes'] == 1 ?  Carbon\Carbon::parse($invoiceDate)
                                    // ->addDays(30 * $order['votes'] * 12);

                                    // $endInvoice = Carbon\Carbon::parse($order['created_at'])

                                    $unDate = Carbon\Carbon::parse($order['updated_at'])->formatLocalized(
                                        '%d %B %Y %H:%M',
                                    );

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
                                                        ->subDays(10)
                                                        ->formatLocalized('%y%m%d')
                                                    : ($isPayment
                                                        ? Carbon\Carbon::parse($order['updated_at'])
                                                            ->addDays(30 * $order['votes'])
                                                            ->subDays()
                                                            ->formatLocalized('%y%m%d')
                                                        : ($isAfter
                                                            ? Carbon\Carbon::parse($order['updated_at'])
                                                                ->addDays(30 * ($isFive - 4))
                                                                ->subDays()
                                                                ->formatLocalized('%y%m%d')
                                                            : Carbon\Carbon::parse($invoiceDate)
                                                                ->addDays(30 * $order['votes'])
                                                                ->formatLocalized('%y%m%d')))));

                                    // $collocation_invoice = $invoice - $liveInvoice;

                                    $invoice = $isColocation
                                        ? Carbon\Carbon::parse($invoiceDate)
                                            ->addDays(30 * $order['bulan'])
                                            ->subDays(14)
                                            ->formatLocalized('%y%m%d')
                                        : ($isFreeze
                                            ? Carbon\Carbon::parse($order['updated_at'])
                                                ->addDays(30)
                                                ->subDays(14)
                                                ->formatLocalized('%y%m%d')
                                            : ($isPayment
                                                ? Carbon\Carbon::parse($order['updated_at'])
                                                    ->addDays(30 * $order['votes'])
                                                    ->subDays(14)
                                                    ->formatLocalized('%y%m%d')
                                                : ($isAfter
                                                    ? Carbon\Carbon::parse($order['updated_at'])
                                                        ->addDays(30 * ($isFive - 4))
                                                        ->subDays(14)
                                                        ->formatLocalized('%y%m%d')
                                                    : Carbon\Carbon::parse($invoiceDate)
                                                        ->addDays(30 * $order['votes'])
                                                        ->subDays(14)
                                                        ->formatLocalized('%y%m%d'))));

                                    $terminated = $isColocation
                                        ? Carbon\Carbon::parse($invoiceDate)
                                            ->addDays(30 * $order['bulan'])
                                            ->addDays(14)
                                            ->formatLocalized('%y%m%d')
                                        : ($isFreeze
                                            ? Carbon\Carbon::parse($order['updated_at'])
                                                ->addDays(30)
                                                ->addDays(14)
                                                ->formatLocalized('%y%m%d')
                                            : ($isPayment
                                                ? Carbon\Carbon::parse($order['updated_at'])
                                                    ->addDays(30 * $order['votes'])
                                                    ->addDays(14)
                                                    ->formatLocalized('%y%m%d')
                                                : ($isAfter
                                                    ? Carbon\Carbon::parse($order['updated_at'])
                                                        ->addDays(30 * ($isFive - 4))
                                                        ->addDays(14)
                                                        ->formatLocalized('%y%m%d')
                                                    : Carbon\Carbon::parse($invoiceDate)
                                                        ->addDays(30 * $order['votes'])
                                                        ->addDays(14)
                                                        ->formatLocalized('%y%m%d'))));

                                    $ins = $order->whereMonth('updated_at', '=', date('m'));
                                    $month = Carbon\Carbon::parse('2022/06/12')->format('MM');
                                    $endShip = Carbon\Carbon::parse($order['updated_at'])
                                        ->addDays(30 * $order['bulan'])
                                        ->addDays(3)
                                        ->formatLocalized('%y%m%d');
                                    $startInpov = Carbon\Carbon::parse($order['updated_at'])
                                        ->addDays(30 * $order['bulan'])
                                        ->subDays(10)
                                        ->formatLocalized('%y%m%d');
                                @endphp

                                <td>
                                    <ol>
                                        @foreach ($order->status as $orderStatus)
                                            @if ($orderStatus->access == 4)
                                                {{-- <td>Start Date : {{ $pauseUnfreeze }}</td> --}}
                                                <li style="list-style: circle;"> {{ $unDate }}</li>
                                            @else
                                                <li style="list-style: circle;">{{ $startMonth }}</li>
                                            @endif
                                            
                                </td>
                                <td>

                                    @if ($orderStatus->access == 4)
                                        <li style="list-style:armenian;">
                                            {{-- {{ $freezeEnd }} --}}
                                            {{ $endMonth }}
                                        </li>
                                    @else
                                        <li style="list-style:disc;">
                                            {{ $endMonth }}
                                        </li>
                                    @endif
                                    
                        @endforeach
                        </ol>
                        </td>

                        {{-- <td>Invoice
                                    :{{ $invoice }} <br>
                                    EndInvoice:
                                    {{ $endInvoice }}<br>
                                    StartInpov:
                                    {{$startInpov}}
                                </td> --}}

                        @php
                            $ramType = data_get($order['products'], '1.type', 0);
                            $ramPrice = data_get($order['products'], '1.price', 1);
                            $ramId = data_get($order['products'], '1.id', true);

                            // dd($ramId);
                            $paymentSet = $orderStatus->payment;
                            if ($paymentSet > 0) {
                                $paymentMinus = $orderStatus->payment - 1;
                            } else {
                                $paymentSewa = $paymentSet * -1;
                                // dd($paymentSewa);
                                if ($paymentSet == 0) {
                                    $paymentMinus = 0;
                                } else {
                                    $paymentMinus = $paymentSewa - 1;
                                }
                            }
                            $paymentAdd = $paymentMinus + 1;

                        @endphp
                        <input id="paymentData" type="text" value="{{ $paymentMinus }}" hidden>
                        <input id="votesData" type="text" value="{{ $order['votes'] }}" hidden>

                        @if ($ramType == 'ram' && $ramPrice == 0 && $ramId == true)
                            <td>
                                <div class="alert alert-success"> Custom</br>RAM User
                                    <hr>
                                    <a class="btn btn-success mb-2" id="bayar"
                                        href="{{ route('status.custom', $order['id']) }}">Custom</a>
                                </div>
                            </td>
                            {{-- @elseif($ramType == 'ram' && $ramPrice == 0 && $ramId == "0")
                        <td>
                            <div class="alert alert-success"> </br>RAM User
                                <hr>
                                <a class="btn btn-success mb-2" id="bayar"
                                    href="{{ route('status.custom', $order['id']) }}">Custom</a>
                            </div>
                        </td> --}}
                        @else
                            {{-- @dd($paymentMinus) --}}

                            @foreach ($order->status as $orderStatus)
                                @if ($orderStatus->payment < 1)
                                    <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">


                                        <p class="" style="text-align: center">User Sedang Melakukan Penyewaan Server
                                        </p>
                                        <hr>
                                        @if ($order['votes'] == $order['bulan'])
                                            <a href="{{ route('status.sewa', $order['id']) }}"
                                                class="btn btn-secondary mb-3  w-100">Sewa Kembali</a>
                                        @else
                                            {{-- <a href="{{ route('status.sewa', $order['id']) }}"
                            class="btn btn-secondary mb-3  w-100" disabled>Sewa Kembali</a> --}}
                                        @endif
                                        <a class="btn btn-primary mb-2 mt-2 w-100" id="bayar"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                            Get Back!</a>
                                        <button type="button" class="btn btn-info mb-2 w-100 votesBulan"
                                            style="color: white;" data-toggle="modal" data-target="#bulanModal"
                                            style="" data-id="{{ $order->id }}" id="bulanButton"
                                            data-url="{{ route('status.votesUpdate', $order['id']) }}"
                                            data-bulan="{{ $order->bulan }}">
                                            Pilih Bulan
                                        </button>


                                    </td>
                                @elseif ($orderStatus->access == 3 && $startInpov >= $liveInvoice && $order['bulan'] == 4)
                                    <td>
                                        <div class="alert alert-success"> Lanjutkan</br> Pembayaran
                                            <hr>
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('status.custom', $order['id']) }}">Bayar</a>
                                        </div>
                                    </td>
                                @elseif($orderStatus->access == 3 && $liveInvoice < $endInvoice && $order['bulan'] == 4)
                                    <td>
                                        <a class="btn btn-primary mb-2" id="bayar" href="#">User sudah
                                            Beralangganan Freeze
                                            </br> dengan Batas MAX </a>
                                        <hr>
                                    </td>
                                @elseif($orderStatus->access == 3)
                                    {{-- @if ($orderStatus->access == 3 && $liveInvoice < $endInvoice) --}}
                                    <td>
                                        <div class="alert alert-primary">Anda di FREEZE</a>
                                            <hr>
                                            <a class="btn btn-primary mb-2" id="bayar"
                                                href="{{ route('status.custom', $order['id']) }}">Bayar</a>
                                        </div>
                                    </td>
                                @elseif($order['bulan'] == $order['votes'] && $isColocation == false)
                                    <td>
                                        <a class="btn btn-success mb-2" id="bayar">Cicilan User Lunas
                                        </a>

                                        <div class="alert alert-primary">
                                            <p>Tekan Tombol Dibawah Untuk Konfirmasi</p>
                                        </div>
                                        <button type="button" class="btn btn-info mb-2 w-100 perpanjang"
                                            data-toggle="modal" data-target="#LunasModal" style="color:white;"
                                            data-id="{{ $order->id }}" id="buttonLunas"
                                            data-url="{{ route('status.lunasUpdate', $order['id']) }}">
                                            Confirm Access
                                        </button>
                                    </td>
                                @elseif ($isColocation == true && $order['votes'] == $order['bulan'])
                                    <td>
                                        @php
                                            $existingProducts = $order['products'][0];
                                            if ($existingProducts['type'] == 'colocation') {
                                                $isColocation = true;
                                            } else {
                                                $isColocation = false;
                                            }

                                        @endphp
                                        <p class="alert alert-success" style="padding:5px 30px;">Fitur
                                            Colocation
                                        </p>
                                        {{-- <button id="layanan" class="alert alert-primary w-100">Pilih Layanan </button> --}}
                                        <hr>
                                        <a class="btn btn-success w-100 mb-2" id="collo"
                                            href="{{ route('status.custom', $order['id']) }}">Pindah Lokasi </a>

                                        <button type="button" class="btn btn-info mb-2 w-100 perpanjang"
                                            data-toggle="modal" data-target="#Length" style="color:white;"
                                            data-id="{{ $order->id }}" id="buttonModal"
                                            data-url="{{ route('admin.order.lengthed', $order->id) }}">
                                            Perpanjang Layanan
                                        </button>

                                        <form action="{{ route('status.lunasUpdate', $order['id']) }}" method="post"
                                            class="mb-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" href="{{ route('status.lunasUpdate', $order['id']) }}"
                                                class="btn btn-primary w-100">Layanan Kembali </button>
                                        </form>


                                    </td>
                                @elseif(($liveInvoice >= $endInvoice && $liveInvoice >= $terminated) || $orderStatus->access == 2)
                                    <td>


                                        @if ($orderStatus->access == 2)
                                            <div class="alert alert-danger ">
                                                Pesanan User Diterminated
                                            </div>
                                            {{-- <input type="number" name="suspend" value="12"> --}}
                                            <a href="{{ route('status.sewa', $order['id']) }}"
                                                class="btn btn-secondary mb-3 mt-2 w-100">Sewa Server</a>
                                            <a class="btn btn-success mb-2 mt-2 w-100" id="bayar"
                                                href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur </br>
                                                Get Back !</a>
                                        @elseif($orderStatus->access == 0)
                                            <div class="alert alert-danger ">
                                                Pesanan User Disuspend
                                            </div>
                                            <a class="btn btn-success mb-2 mt-2 w-100" id="bayar"
                                                href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur </br>
                                                Get Back !</a>
                                        @else
                                            <div class="alert alert-danger ">
                                                Pesanan User melewati Jatuh Tempo
                                            </div>
                                            <a class="btn btn-danger mb-2 mt-2 w-100" id="bayar"
                                                href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur </br>
                                                Jatuh Tempo</a>
                                            {{-- <input type="text" name="suspend" value="{{$order['id']}}"> --}}
                                        @endif




                                    </td>
                                @elseif ($orderStatus->payment < 1 && $liveInvoice < $endInvoice && $liveInvoice < $invoice)
                                    <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">
                                        User Sedang Melakukan Penyewaan Server

                                        <hr>
                                        @if ($order['votes'] == $order['bulan'])
                                            <a href="{{ route('status.sewa', $order['id']) }}"
                                                class="btn btn-secondary mb-3 mt-3">Sewa Server</a>
                                        @endif
                                        <a class="btn btn-primary mb-2 mt-2" id="bayar"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur Tenggat
                                        </a>


                                    </td>
                                @elseif (($liveDate == $endMonth && $isColocation == false) || ($liveInvoice >= $endInvoice && $isColocation == false))
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
                                        End Date : {{ $liveDate }}
                                        <br>
                                        <hr>

                                        @foreach ($order->status as $orderStatus)
                                            @if ($orderStatus->access == 1 || $orderStatus->access >= 3)
                                                <a class="alert alert-success mb-2" id="bayar"
                                                    href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                                    Tenggat </br> </a>

                                                <hr>
                                            @elseif($orderStatus->access == 2)
                                                <a class="btn btn-success mb-2 w-100" id="bayar"
                                                    href="{{ route('status.new_status', $orderStatus['id']) }}">Terminated
                                                    Fitur</a>


                                                <a href="{{ route('admin.order.pengirimanAdmin', $order['id']) }}
                                    "
                                                    class="btn btn-primary w-100">Bayar User</a>
                                            @else
                                                <hr>
                                            @endif
                                        @endforeach
                                        {{-- @dd($orderStatus) --}}
                                        @if ($orderStatus->access == 2)
                                        @else
                                            {{-- <button onclick="suspend(this,'green')"
                                                        class="btn btn-danger mb-3 form-control">
                                                        User di Suspend</button> --}}
                                            {{-- <a href="{{ route('status.sewa', $order['id']) }}"
                                                        class="btn btn-secondary mb-3 form-control">Sewa Server</a> --}}
                                            <a href="{{ route('status.new_status', $orderStatus['id']) }}"
                                                class="btn btn-primary form-control">Status Fitur</a>
                                        @endif
                                    @elseif ($liveDate == $endMonth && $isColocation == true)
                                        @php
                                            $liveDate = $endMonth;
                                        @endphp
                                    <td>
                                    @elseif($paymentMinus > $order['votes'])
                                    <td>
                                        {{-- @dd() --}}
                                        <form action="{{ route('admin.order.adminBayar', $order['id']) }}" method="post"
                                            class="mt-2 p-2 mb-2">
                                            @csrf
                                            {{-- jika terjadi error di validasi, akan ditampilkan bagian error nya : --}}
                                            @method('PATCH')
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
                                            <button type="submit" class="btn btn-primary"> Bayar</button>
                                        </form>
                                    </td>
                                    {{-- @elseif($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $paymentMinus < $order['votes']) --}}
                                @elseif($liveInvoice < $endInvoice && $paymentSet < 1 && $order['votes'] == $order['bulan'])
                                    <td>
                                        @foreach ($order->status as $orderStatus)
                                            @if ($orderStatus->access == 1 || $orderStatus->access >= 3)
                                                <a class="btn btn-success mb-2" id="bayar"
                                                    href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                                    Tenggat </br> Pembayaran</a>

                                                <hr>
                                            @else
                                                <hr>
                                            @endif
                                        @endforeach

                                        <a href="{{ route('status.sewa', $order['id']) }}"
                                            class="btn btn-secondary mb-3 form-control">Sewa Server</a>
                                    </td>
                                @elseif($liveInvoice < $endInvoice && $liveInvoice >= $invoice && $paymentMinus < $order['votes'])
                                    <td>
                                        <a class="btn btn-success mb-2" id="bayar">Invoice Send
                                            <span style="color:yellow">(Gmail Sedang Dikirim) </span></a>
                                        {{-- @dd() --}}
                                        <form action="{{ route('admin.order.pengirimanAdmin', $order['id']) }}"
                                            method="get" class="mt-2 p-2 mb-2">
                                            @csrf
                                            <div hidden>

                                                <input type="text" id="liveInvoice" value="{{ $liveInvoice }}">
                                                <input type="text" id="invoice" value="{{ $invoice }}">
                                                <input type="text" id="endInvoice" value="{{ $endInvoice }}">
                                            </div>

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
                                            <button type="submit" class="btn btn-primary" id="reminderAuto">
                                                Reminder
                                                Me</button>
                                        </form>
                                    </td>
                                @elseif($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $paymentMinus == $order['votes'])
                                    <td>
                                        <a class="btn btn-success mb-2" id="bayar" href="#">Menunggu User
                                            membayar
                                            <span style="color:yellow">(Gmail Sudah Dikirim) </span></a>
                                        {{-- @dd() --}}
                                        <a href="{{ route('admin.order.pengirimanAdmin', $order['id']) }}
                                    "
                                            class="btn btn-primary w-100">Bayar User</a>

                                    </td>
                                @elseif ($isColocation == false && $orderStatus->access == 1)
                                    <td style="padding:10px 10px;background-color:green;color:yellow;">
                                        <p>User Berlanggangan Dedicated</p>
                                        <button type="button" class="btn btn-info mb-2 mt-2 w-100 votesBulan"
                                            style="color: white;" data-toggle="modal" data-target="#bulanModal"
                                            style="" data-id="{{ $order->id }}" id="bulanButton"
                                            data-url="{{ route('status.votesUpdate', $order['id']) }}"
                                            data-bulan="{{ $order->bulan }}">
                                            Pilih Bulan
                                        </button>
                                        <a class="btn btn-primary w-100"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">
                                            Fitur Tenggat
                                        </a>
                                    </td>
                                    @elseif($orderStatus->access == 0)
                                    <td>
                                        <div class="alert alert-danger ">
                                            Pesanan User Disuspend
                                        </div>
                                        <a class="btn btn-success mb-2 mt-2 w-100" id="bayar"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur </br>
                                            Get Back</a>
                                    </td>
                                @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == false)
                                    <td style="padding:10px 10px;background-color:green;color:yellow;">User Membeli</td>
                                @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == true)
                                    <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">User
                                        Membeli
                                        Collocation</td>
                                @elseif($invoice <= $liveInvoice && $liveInvoice < $endInvoice)
                                    <td>
                                        <a class="btn btn-success mb-2" id="bayar">Menunggu User membayar</a>
                                    </td>
                                @elseif($orderStatus->access == 3 && $maxEnd == $startMax)
                                    <td>
                                        <a class="btn btn-danger mb-2" id="bayar" href="#">User di Suspend
                                        </a>
                                        <hr>
                                    </td>
                                @elseif($orderStatus->access == 3 && $maxDate == $startMax)
                                    <td>
                                        <a class="btn btn-primary mb-2" id="bayar" href="#">Waktu MAX </br>
                                            FREEZE
                                        </a>
                                        <a class="btn btn-danger mb-2" id="bayar" href="#">User akan di
                                            Suspend
                                            </br>dalam
                                            3 hari </a>
                                        <a class="btn btn-success mb-2" id="bayar"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">Hentikan
                                            Pembekuan </br> User</a>
                                        <hr>
                                        <hr>
                                        tgl end = {{ $maxDate }}
                                        <hr>
                                        3 bulan freeze = {{ $maxFreeze }}
                                        <hr>
                                        end date + 3 days = {{ $maxEnd }}
                                        <hr>
                                        mulai Expired:{{ $startMax }}
                                        <hr>
                                    </td>

                                    <p class="alert alert-success" style="padding:5px 30px;">Masa Collocation </br>
                                        User
                                        Sudah Habis</p>
                                    <button id="layanan" class="btn btn-primary">Menuggu User memlih
                                        </br>Layanan</button>
                                    <hr>
                                    @foreach ($order->status as $orderStatus)
                                        @if ($orderStatus->access == 3)
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('status.new_status', $orderStatus['id']) }}">Hentikan
                                                Pembekuan </br> User</a>
                                            <hr>
                                        @endif
                                    @endforeach

                                    </td>
                                @elseif($order['votes'] >= 2 && $isColocation == false)
                                    <td style="padding:10px 10px;background-color:darkblue;color:white;">User
                                        berlangganan Kembali</td>
                                @else
                                    <td style="padding:10px 10px;background-color:darkcyan;color:white;">User
                                        berlangganan Collocation Kembali</td>
                                @endif
                            @endforeach
                            {{-- The 01 --}}
                            <td style="padding:10px 10px;">
                                @foreach ($order->status as $orderStatus)
                                    @if ($orderStatus->data == 1)
                                        <a class="btn btn-primary SPK-submit"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">
                                            Confirm SPK User
                                        </a>
                                    @elseif($orderStatus->data == 2)
                                        @php
                                            $orderStatus->status = 'proses SPK';
                                        @endphp
                                        <a class="btn btn-success SPK-submit"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">
                                            Confirm TandaTangan SPK
                                        </a>
                                    @elseif($orderStatus->data == 3)
                                        <a class="btn btn-danger SPK-submit"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">
                                            Confirm Semuanya
                                        </a>
                                    @else
                                        <div class="btn btn-success SPK-submit" href="#">
                                            Semua Data Done
                                        </div>
                                        <a class="btn btn-primary mt-2"
                                            href="{{ route('status.show', $orderStatus['id']) }}">Fitur PDF</a>
                                    @endif
                                @endforeach
                                @php
                                    $serverGet = last($order['products']);
                                    $countFreeze = count($order['products']) - 2;
                                    // $dataType = [];
                                    $dataType = $order['products'];
                                    if ($serverGet['type'] == 'freeze') {
                                        $serverType = $dataType[$countFreeze]['type'];
                                    } else {
                                        $serverType = $serverGet['type'];
                                    }
                                    if (
                                        $serverType == 'dell' ||
                                        $serverType == 'HP' ||
                                        $serverType == 'supermicro' ||
                                        $serverType == 'hp'
                                    ) {
                                        $serverData = true;
                                    } else {
                                        $serverData = false;
                                    }

                                @endphp
                                @if ($serverData == false)
                                    <a href="{{ route('detail_server.create', $order['id']) }}"
                                        class="btn btn-warning mt-3">Set Server</a>
                                @else
                                    <a href="{{ route('detail_server.single', $order['user_id']) }}"
                                        class="btn btn-info mt-3">Server Single</a>
                                @endif
                            </td>


                            <td>
        
                                @if ($orderStatus->access == 2)
                                    <p class="alert alert-warning"> User Dalam Kondisi Terminated </p>
                                @elseif ($orderStatus->access == 3 && $isColocation == false)
                                    @if ($order['bulan'] == 12 || $order['bulan'] == 24)
                                        <p> User akan mulai berlangganan FREEZE <b>
                                                (MAX:3 Bulan)
                                            </b> </p>
                                    @else
                                        <p> Anda telah berlangganan FREEZE selama <b> {{ $order['bulan'] - 1 }}
                                            </b>
                                            bulan (MAX:3 Bulan)</p>
                                    @endif
                                @elseif ($orderStatus->payment < 1 && $order['votes'] == $order['bulan'])
                                    <p> Masa penyewaan User Sudah Habis Selama<b>
                                            {{ $order['votes'] }}/{{ $order['bulan'] }} </b>
                                        bulan </p>
                                @elseif ($orderStatus->payment < 1)
                                    <p> User berlangganan Cicilan Sewa Server dari<b>
                                            {{ $order['votes'] }}/{{ $order['bulan'] }} </b>
                                        bulan </p>
                                @elseif ($order['votes'] < $order['bulan'] && $isColocation == false)
                                    <p> User berlangganan Cicilan Dedicated dari <b>
                                            {{ $order['votes'] }}/{{ $order['bulan'] }} </b>
                                        bulan </p>
                                @elseif($order['votes'] == $order['bulan'] && $isColocation == true)
                                    <p class="alert alert-info"> User berlangganan Collocation Selama <b> {{ $order['bulan'] }} </b> bulan</p>

                                @elseif($isColocation == false)
                                    <div class="btn btn-success">
                                        User Lunas </br>
                                        Menunggu User Memilih
                                    </div>
                                @else
                                    <div>
                                        <div class="btn btn-primary">
                                            User Dalam Kondisi Colocation
                                        </div>
    
                                    </div>
                                @endif
                                <ol>
                                    @foreach ($order['datacenter'] as $product)
                                        {{-- tampilan yang ingin ditampilkan : --}}
                                        {{-- 1. Nama obat Rp. 1.000 (qty 2) = Rp. 2.000 --}}
                                        <li style="list-style-type:circle;" class="mt-3"> Data center
                                            :{{ $product['datacenter'] }} <br>
                                            Rak : {{ $product['rack'] }}
                                        </li>
                                        <hr>
                                    @endforeach
                                </ol>
                                <ol class="mt-3 mb-2 w-100 "
                                    style="max-width: 100%;display:flex;justify-content:center;text-align:center;">
                                    <a href="{{ route('status.deleteSingle', $order['id']) }}"class="btn mt-2 ms-1 p-2"
                                        data-confirm-delete="true" style="background: darkred;color:white;">Delete</a>

                                </ol>
                            </td>


                            </tr>
                        @endif
                        {{-- @endforeach --}}
                        {{-- Tambahkan nama pelanggan ke dalam array uniqueCustomers --}}
        @endforeach
        {{-- @dd($dedic1) --}}
        </tbody>
        </table>
        {{-- table mt-5 table-striped table-bordered table-hovered --}}

        <div class="d-flex justify-content-end">
            @if ($status1->count())
                {{ $status1->links() }}
            @endif
        </div>

        {{-- Modal Pemisah --}}
        <div class="modal fade" id="LunasModal" tabindex="-1" role="dialog" aria-labelledby="LunasLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    {{-- <form action="{{route('admin.order.lunas',$order['id'])}}" --}}
                    <form method="post" class="mb-2" id="lunas">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title" id="LunasLabel">lunas Pilihan:</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h4>Bulan: </h4>
                            @php
                                $bulanSelected = [1, 3, 6];
                            @endphp
                            {{-- <input type="text" name="id" id="orderId"> --}}
                            <select name="bulan" id="bulan" class="form-control">
                                @for ($i = 0; $i < count($bulanSelected); $i++)
                                    {{-- <li>{{ $order['products'][$i]['name_product'] }} <small>Rp. {{ number_format($order['products'][$i]['price'], 0, '.', ',') }}<b>(qty : {{ $order['products'][$i]['qty'] }})</b></small> = Rp. {{ number_format($order['products'][$i]['price_after_qty'], 0, '.', ',') }}</li> --}}

                                    <option value="{{ $bulanSelected[$i] }}">IDR {{ $bulanSelected[$i] }} Bulan

                                    </option>
                                @endfor
                                <option value="12"> IDR Tahunan
                                </option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Modal Select Bulan --}}
        <div class="modal fade" id="bulanModal" tabindex="-1" role="dialog" aria-labelledby="bulanLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" class="mb-2" id="bulanOrder">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title" id="bulanLabel">Bulan Pilihan:</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- <input type="text" name="id" id="bulanId"> --}}

                            <select name="bulanSelection" id="bulanSelection" class="form-select mb-2">
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Modal Select Bulan --}}
        {{-- Modal perpanjang coloc --}}
        <div class="modal fade" id="Length" tabindex="-1" role="dialog" aria-labelledby="LengthLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <form method="post" class="mb-2" id="Lengthed">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title" id="LengthLabel">Perpanjang Layanan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <h4>@php

                            @endphp</h4>
                            @php
                                $bulanSelected = [1, 3, 6];

                            @endphp

                            <select name="bulan" id="bulan" class="form-control">

                                @for ($i = 0; $i < count($bulanSelected); $i++)
                                    <option value="{{ $bulanSelected[$i] }}">
                                        {{ $bulanSelected[$i] }} Bulan

                                    </option>
                                @endfor
                                <option value="12">
                                    IDR Tahunan
                                </option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Modal perpanjang coloc --}}

    </body>
@else
    <div class="card p-5 mt-5 mb-5">

        <div class="alert alert-primary mt-2 p-5">
            <h3 style="text-align: center;">Client Belum Memesan Apapun</h3>
        </div>

    </div>
    @endif
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
        let liveInvoice = document.getElementById('liveInvoice');
        let invoice = document.getElementById('invoice');
        let endInvoice = document.getElementById('endInvoice');
        let paymentMinus = document.getElementById('paymentData');
        let votesOrder = document.getElementById('votesData');


        let liveVal = liveInvoice.value;
        let invoiceVal = invoice.value;
        let endVal = endInvoice.value;
        let payment = paymentMinus.value;
        let votes = votesOrder.value;

        console.log(liveVal, invoiceVal, endVal);
        if (liveVal >= invoiceVal && liveVal < endVal && payment < votes) {

            window.onload = function() {
                var button = document.getElementById('reminderAuto');
                button.form.submit();

            }
        }
    </script>
    <script>
        function konfirmasiFreeze(freezeUrl) {
            // Tampilkan dialog konfirmasi sebelum mengirimkan formulir
            if (confirm('Apakah Anda yakin ingin membekukan pesanan ini?')) {
                // Buat formulir secara dinamis
                var formulir = document.createElement('form');
                formulir.action = freezeUrl;
                formulir.method = 'GET';
                formulir.style.display = 'none';

                // Sisipkan input token CSRF
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                formulir.appendChild(csrfToken);

                // Sisipkan formulir ke tubuh dokumen dan kirimkan
                document.body.appendChild(formulir);
                formulir.submit();
            }
        }
        // Wait for the DOM to be ready
        document.addEventListener("DOMContentLoaded", function() {
            // Get all sidebar links with the class "sidebar-link"
            var sidebarLinks = document.querySelectorAll('.sidebar-link');

            // Add a click event listener to each sidebar link
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    // Get the parent element (li) of the clicked link
                    var parentLi = link.closest('.sidebar-item');

                    // Get the collapse element associated with the link
                    var collapseElement = document.querySelector(link.getAttribute(
                        'data-bs-target'));

                    // Toggle the "collapse" class on the collapse element
                    if (collapseElement.classList.contains('show')) {
                        collapseElement.classList.remove('show');
                    } else {
                        // Close any other open collapse elements
                        var openCollapses = document.querySelectorAll('.sidebar-dropdown.show');
                        openCollapses.forEach(function(openCollapse) {
                            openCollapse.classList.remove('show');
                        });

                        // Open the clicked collapse element
                        collapseElement.classList.add('show');
                    }
                });
            });
        });
    </script>
    <script>
        let uhuy = $("#lunas").attr("action", $(this).data('action'));

        let bulan = document.getElementById('bulanModal');

        let lengthed = document.getElementById('Lengthed');

        let lunas = document.getElementById('LunasModal');
        let action = document.getElementById('buttonLunas');



        $(document).on('click', '.perpanjang', function(e) {

            e.preventDefault();

            const id = $(this).data('id');
            const url = $(this).data('url');
            // alert(id);
            $('#lunas').attr('action', url);
            $('#orderId').attr('value', url);

            console.log("modal 1", id);
        });
        $(document).on('click', '.votesBulan', function(e) {

            e.preventDefault();

            const id = $(this).data('id');
            const url = $(this).data('url');
            const bulan = $(this).data('bulan');
            const selection = $('#bulanSelection');
            console.log(selection, bulan)

            $("#bulanOrder").attr('action', url);
            $("#bulanId").attr('value', id);

            selection.children().remove();

            if (bulan == 12) {

                for (let x = 1; x < 13; x++) {

                    var option = document.createElement('option');
                    option.value = x;
                    option.text = x + ' bulan';
                    selection.append(option);
                }
            } else {

                for (let y = 1; y < 25; y++) {

                    var option = document.createElement('option');
                    option.value = y;
                    option.text = y + ' bulan';
                    selection.append(option);
                }

            }

        });



        $(document).on('click', '.perpanjang', function(e) {

            e.preventDefault();

            const id = $(this).data('id');
            const url = $(this).data('url');
            // alert(id);
            $('#Lengthed').attr('action', url);

            console.log(id, lengthed, url);
        })

        console.log(lengthed, dataSelect, action, uhuy);
    </script>
    <script></script>
@endpush
