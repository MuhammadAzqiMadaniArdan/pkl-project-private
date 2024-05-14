@extends('layouts.template')

@section('content')
    <style>
        table {
            background: whitesmoke;
            border-radius: 5px;
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
    </style>
    <div class="jumbotron mt-2" style="padding:0px;">
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
            <h3><b>DataColocation</b></h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">DataColocation</a></p>
        </div>
    </div>
    <form action="{{ route('status.colocationSearch') }}" method="GET">

        <div class="form-inline">
        
            <div class="input-group searchPage " data-widget="sidebar-search">
            <div class="input-group w-50" data-widget="sidebar-search">
                {{-- <div class="search"> --}}
                <input class=" form-control" name="search" type="date" placeholder="Search" aria-label="Search">
                {{-- <div class="input-group-append"> --}}
                    <button class="btn btn-sidebar" style="background: whitesmoke;">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                {{-- </div> --}}
            </div>
                <a href="{{ route('status.colocation') }}" class="btn btn-danger ms-2" style="border-radius:5px;">reset</a>
            </div>
        </div>
    </form>
    @if ($coloValidate !== false)
        <div class="mt-1">
            {{-- <div class="d-flex justify-content-end">
                <a href="{{ route('admin.order.downloadExcel') }}" class="btn btn-success">Export Excel</a>
            </div> --}}
            <table class="table-stripped w-100 table mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Client</th>
                        <th>Pesanan</th>
                        <th>Total Bayar</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $uniqueCustomers = [];
                    @endphp
                    @foreach ($colocation as $order)
                        {{-- Cek apakah nama pelanggan sudah ditampilkan sebelumnya --}}
                        @php

                            $invoiceDate = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%y%m%d');

                        @endphp

                        {{-- Jika belum pernah ditampilkan, tampilkan nama pelanggan --}}
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $order['name_customer'] }} <a href="{{ route('status.single', $order['user_id']) }}">
                                    <div class="btn btn-success ml-3" style="margin-left:10px;">Detail</div>
                                </a></td>
                            <td>
                                <ol>
                                    {{-- @foreach ($colocation as $innerOrder) --}}
                                    {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                    {{-- @if ($innerOrder['name_customer'] == $order['name_customer']) --}}
                                    {{-- @dd($innerOrder['products'][0]) --}}
                                    @for ($i = 0; $i < 1; $i++)
                                        <li>{{ $order['products'][$i]['name_product'] }} <small>Rp.
                                                {{ number_format($order['products'][$i]['price'], 0, '.', ',') }}<b>(qty :
                                                    {{ $order['products'][$i]['qty'] }})</b></small> = Rp.
                                            {{ number_format($order['products'][$i]['price_after_qty'], 0, '.', ',') }}</li>
                                    @endfor
                                    {{-- @endif --}}
                                    {{-- @endforeach --}}
                                </ol>
                            </td>
                            <td>Rp. {{ number_format($order['total_price'], 0, '.', ',') }}</td>
                            @php
                                setLocale(LC_ALL, 'IND');

                                // ------------------DATE----------------------
                                $date = $order->created_at;
                                $startMonth = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%d %B %Y');
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
                                $invoiceDate = $isColocation ? $order['created_at'] : $order['created_at'];
                                // $Extime = (Carbon\Carbon::parse($invoiceDate))->diffInDays($invoiceDate);
                                // -------------------end Month-sorangan--------------------
                                // dd($liveInvoice,$Extime);

                                $col_invoice = $isColocation
                                    ? Carbon\Carbon::parse($invoiceDate)
                                        ->addDays(30 * $order['bulan'])
                                        ->subDays(10)
                                        ->diffInDays($invoiceDate)
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
                                        ? Carbon\Carbon::parse($order['updated_at'])
                                            ->addDays(30 * $order['bulan'])
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
                            Terminated:
                            {{ $terminated }}<br>
                            EndInvoice:
                            {{ $endInvoice }}
                        </td> --}}


                    @php
                        $ramType = data_get($order['products'], '1.type', 0);
                        $ramPrice = data_get($order['products'], '1.price', 1);
                        $ramId = data_get($order['products'], '1.id', true);

                    @endphp
                    @php
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
                    @if ($ramType == 'ram' && $ramPrice == 0 && $ramId == true)
                        <td>
                            <div class="alert alert-success"> Custom</br>RAM User
                                <hr>
                                <a class="btn btn-success mb-2" id="bayar"
                                    href="{{ route('status.custom', $order['id']) }}">Custom</a>
                            </div>
                        </td>
                    @else
                        {{-- @dd($paymentMinus) --}}

                        @foreach ($order->status as $orderStatus)
                            @if ($liveInvoice >= $endInvoice && $liveInvoice >= $terminated)
                                <td>
                                    <div class="alert alert-danger">
                                        Layanan Colocation melewati waktu tempo
                                    </div>
                                </td>
                            @elseif ($orderStatus->payment < 1 && $liveInvoice < $endInvoice && $liveInvoice < $invoice)
                                <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">
                                    User Sedang Melakukan Penyewaan Server

                                    <hr>
                                    <a href="{{ route('status.sewa', $order['id']) }}"
                                        class="btn btn-secondary mb-3 mt-3">Sewa Server</a>
                                    <a class="btn btn-primary mb-2 mt-2" id="bayar"
                                        href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                        untuk
                                        Tenggat </br> Pembayaran</a>


                                </td>
                            @elseif (
                                ($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $isColocation == true) ||
                                    $liveDate == $freezeEnd ||
                                    $order['votes'] == $order['bulan']
                            )
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

                                    <button type="button" class="btn btn-info mb-2 w-100 perpanjang" data-toggle="modal"
                                        data-target="#Length" style="color:white;" data-id="{{ $order->id }}"
                                        id="buttonModal" data-url="{{ route('admin.order.lengthed', $order->id) }}">
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
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                                untuk
                                                Tenggat </br> Pembayaran</a>

                                            <hr>
                                        @elseif($orderStatus->access == 2)
                                            <a class="btn btn-success mb-2" id="bayar">User Sudah Diberi </br>
                                                Akses
                                                Membayar</a>
                                        @else
                                            <hr>
                                        @endif
                                    @endforeach
                                    <button onclick="suspend(this,'green')" class="btn btn-danger mb-3 form-control">
                                        User di Suspend</button>
                                    <a href="{{ route('status.sewa', $order['id']) }}"
                                        class="btn btn-secondary mb-3 form-control">Sewa Server</a>
                                    <button onclick="konfirmasiFreeze()"
                                        class="btn btn-primary form-control">Freeze</button>
                                @elseif ($liveDate == $endMonth && $isColocation == true)
                                    @php
                                        $liveDate = $endMonth;
                                    @endphp
                                <td>
                                @elseif($liveInvoice < $endInvoice && $paymentSet < 1 && $order['votes'] == $order['bulan'])
                                <td>
                                    @foreach ($order->status as $orderStatus)
                                        @if ($orderStatus->access == 1 || $orderStatus->access >= 3)
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                                untuk
                                                Tenggat </br> Pembayaran</a>

                                            <hr>
                                        @elseif($orderStatus->access == 2)
                                            <a class="btn btn-success mb-2" id="bayar">User Sudah Diberi </br>
                                                Akses
                                                Membayar</a>
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
                                    <form action="{{ route('admin.order.pengirimanAdmin', $order['id']) }}" method="get"
                                        class="mt-2 p-2 mb-2">
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
                                        <button type="submit" class="btn btn-primary" id="reminderAuto"> Reminder
                                            Me</button>
                                    </form>
                                </td>
                            @elseif($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $paymentMinus == $order['votes'])
                                <td>
                                    <a class="btn btn-success mb-2" id="bayar">Menunggu User membayar
                                        <span style="color:yellow">(Gmail Sudah Dikirim) </span></a>

                                    <a href="{{ route('admin.order.pengirimanAdmin', $order['id']) }}"
                                        class="btn btn-primary w-100">Bayar User</a>

                                </td>
                            @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == false)
                                <td style="padding:10px 10px;background-color:green;">User Membeli</td>
                            @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == true)
                                <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">User
                                    Membeli
                                    Collocation</td>
                            @elseif($order['bulan'] == $order['votes'] && $isColocation == false)
                                <td>
                                    <form action="{{ route('status.lunasUpdate', $order['id']) }}" method="post"
                                        class="mt-2 p-2 mb-2">
                                        @csrf
                                        @method('PATCH')
                                        {{-- <a class="btn btn-success mb-2" id="bayar" href="{{route('status.lunasUpdate',$order['id'])}}">User
                                    Melakukan </br> Pembayaran lunas(tekan)</a> --}}
                                        <button type="submit" class="btn btn-success mb-2" id="bayar">User
                                            Melakukan </br> Pembayaran lunas(tekan)</button>
                                    </form>
                                    <form action="{{ route('status.lunasUpdate', $order['id']) }}" method="post"
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
                                        <button type="submit" class="btn btn-primary"> unas</button>
                                    </form>
                                </td>
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
                                // $serverData = data_get($order['products'], '4', 1);
                                $serverGet = last($order['products']);
                                $serverType = $serverGet['type'];
                                // dd($serverGet['type']);
                                //
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
                            @if ($orderStatus->payment < 1 && $order['votes'] == $order['bulan'])
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
                                <p> User berlangganan Collocation Selama <b> {{ $order['bulan'] }} </b> bulan</p>
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
                        </td>

                        </tr>
                    @endif
                    {{-- @endforeach --}}
                    {{-- Tambahkan nama pelanggan ke dalam array uniqueCustomers --}}
    @endforeach
    {{-- @dd($colocation) --}}
    </tbody>
    </table>
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

    <div class="d-flex justify-content-end mb-3">
        {{-- @if ($colocation->count())
            {{ $colocation->links() }}
        @endif --}}
    </div>
    </div>
@else
    <div class="card p-5 mt-5 mb-5">

        <div class="alert alert-warning mt-2 p-5">
            <h3 style="text-align: center;">Belum ada Client dengan Pesanan Colocation ~</h3>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let uhuy = $("#Lengthed").attr("action", $(this).data('action'));

        let lengthed = document.getElementById('Lengthed');
        let action = document.getElementById('buttonModal');

        $(document).on('click', '.perpanjang', function(e) {

            e.preventDefault();

            const id = $(this).data('id');
            const url = $(this).data('url');
            // alert(id);
            $('#Lengthed').attr('action', url);

            console.log(id, lengthed, url);
        })

        // let selectedAction  = this.options[this.selectedIndex];
        let dataSelect = action.getAttribute('dataKaction');;
        console.log(lengthed, dataSelect, action, uhuy);
    </script>
@endpush
