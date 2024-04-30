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
    <div class="container">
        <h3><b>Datasewa</b></h3>
        <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Datasewa</a></p>
    </div>
</div>            
{{-- @dd(data_get($sewaMake,'0',true)) --}}
@php
$sewaValidate = data_get($sewaMake,'0',true);

@endphp
@if($sewaValidate !== true)
<div class="mt-1">
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.order.downloadExcel') }}" class="btn btn-success">Export Excel</a>
    </div>
    <table class="table-stripped w-100 table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Client</th>
                <th>Pesanan</th>
                <th>Total Bayar</th>
                <th>Date</th>
                <th>Invoice</th>
                <th>Status</th>
                <th>Aksi</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $uniqueCustomers = [];
             @endphp
            @foreach ($sewaMake as $order)
                {{-- Cek apakah nama pelanggan sudah ditampilkan sebelumnya --}}
                @php
                
                $invoiceDate = Carbon\Carbon::parse($order['created_at'])
                            ->formatLocalized('%y%m%d');

                @endphp
                @if(in_array($order['name_customer'], $uniqueCustomers))
                    {{-- Jika sudah ditampilkan sebelumnya, lanjutkan ke iterasi berikutnya --}}
                    @continue
                @endif
                {{-- Jika belum pernah ditampilkan, tampilkan nama pelanggan --}}
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $order['name_customer'] }} <a href="{{route('status.single',$order['user_id'])}}"><div class="btn btn-success ml-3" style="margin-left:10px;">Detail</div></a></td>
                    <td>
                        <ol>
                            @foreach ($sewaMake as $innerOrder)
                                {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                @if ($innerOrder['name_customer'] == $order['name_customer'])
                                {{-- @dd($innerOrder['products'][0]) --}}
                                    @for ($i = 0;$i < 1;$i++)
                                        <li>{{ $innerOrder['products'][$i]['name_product'] }} <small>Rp. {{ number_format($innerOrder['products'][$i]['price'], 0, '.', ',') }}<b>(qty : {{ $innerOrder['products'][$i]['qty'] }})</b></small> = Rp. {{ number_format($innerOrder['products'][$i]['price_after_qty'], 0, '.', ',') }}</li>
                                    @endfor
                                @endif
                            @endforeach
                        </ol>
                    </td>
                    {{-- @php
                        $ppn = $order['total_price'] * 0.1;
                    @endphp --}}
                                        <td>Rp. {{ number_format($order['total_price'] , 0, '.', ',') }}</td>
                            @php
                                setLocale(LC_ALL, 'IND');

                                // ------------------DATE----------------------
                                $date = $order->created_at;
                                $startMonth = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%d %B %Y');
                                // $startMonth = $date->format('d-m-Y');
                                // 31-5-2022
                                $x = 1;

                                // Comment Set
                                    // $data = data_get($x,1.0);
                                    // if($data == true){
                                    //     echo $data."I have no enemies";
                                    // }

                                // $endMonth = Carbon\Carbon::parse($order['updated_at'])->addDays(30)->formatLocalized('%d %B %Y %H:%M');

                                $mytime = Carbon\Carbon::now();
                                // 30-6-2022
                                // $liveDate = $mytime->form atLocalized('%d %B %Y %H:%M:00');
                                $liveDate = $mytime->formatLocalized('1 Mei 2024 14:13');
                                $pauseDate = Carbon\Carbon::parse($order['created_at'])
                                    ->addDays(30 * $order['votes'])
                                    ->subDays(30)
                                    ->formatLocalized('%d %B %Y %H:%M');

                                // $liveInvoice = $mytime->formatLocalized('%y%m%d');
                                $liveInvoices = $mytime->formatLocalized('240403');
                                $liveInvoice = $mytime->formatLocalized('240509');
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
                                    if ($product['type'] === 'sewaMake') {
                                        $isColocation = true;
                                        break;
                                    }
                                }
                                // dd($order['products']);

                                // Pengaturan invoice, endInvoice, dan liveInvoice
                                $invoiceDate = $isColocation ? $order['created_at'] : $order['created_at'];

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
                                    <li style="list-style: circle;"> Start Date : {{ $unDate }}</li>
                                @else
                                    <li style="list-style: circle;">Start Date : {{ $startMonth }}</li>
                                @endif
                                <hr>
                                @if ($orderStatus->access == 3)
                                    {{-- @if ($invoice <= $liveInvoice) --}}
                                    <li style="list-style:square;">Live Date : {{ $pauseDate }}</li>
                                @else
                                    <li style="list-style:square;">Live Date : {{ $liveDate }}</li>
                                    {{-- <li>Live Date : {{ $liveDate }}</li> --}}
                                @endif
                                <hr>
                                @if ($orderStatus->access == 4)
                                    <li style="list-style:armenian;">Expired Date
                                        :
                                        {{-- {{ $freezeEnd }} --}}
                                        {{ $endMonth }}
                                    </li>
                                @else
                                    <li style="list-style:disc;">Expired Date
                                        :
                                        {{ $endMonth }}
                                    </li>
                                @endif
                                <hr>
                            @endforeach
                        </ol>
                    </td>

                    <td>Invoice
                        :{{ $invoice }} <br>
                        Terminated:
                        {{
                            $terminated
                        }}<br>
                        EndInvoice:
                        {{$endInvoice}}
                    </td>
                    
                    
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
                    @else
                    {{-- @dd($paymentMinus) --}}
                    
                    @foreach ($order->status as $orderStatus)
                   
                    {{-- @elseif($orderStatus->payment == -1 && $liveInvoice < $invoice || $order['votes'] == $order['bulan'])
                    <td>
                        <a href="{{ route('status.sewa', $order['id']) }}"
                                    class="btn btn-secondary mb-3 mt-3">Sewa Server</a>
                    </td> --}}
                        @if ($orderStatus->payment < 1 && $liveInvoice < $endInvoice && $liveInvoice < $invoice)
                        <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">
                            
                            @if($order['votes'] == $order['bulan'] || $orderStatus->payment == -1)
                            Memberikan Akses Penyewaan Server
                            
                            <hr>
                            <a href="{{ route('status.sewa', $order['id']) }}"
                            class="btn btn-secondary mb-3 mt-3">Sewa Server</a>
                            @else
                            User Sedang Melakukan Penyewaan Server
                            <hr>
                            @endif
                            <a class="btn btn-primary mb-2 mt-2" id="bayar"
                            href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                            Get Back!</a>
                            
                            
                            </td>
                        @elseif ($orderStatus->payment < 1 && $liveInvoice < $endInvoice && $liveInvoice < $invoice)
                        <td>
                            
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
                                Expired Date : {{ $liveDate }}
                                <br>

                                @foreach ($order->status as $orderStatus)
                                    @if ($orderStatus->access == 1 || $orderStatus->access >= 3)
                                        <a class="btn btn-success mb-2" id="bayar"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                            get Back! </br></a>

                                        <hr>
                                    @else
                                        <hr>
                                    @endif
                                @endforeach
                                <a href="{{ route('status.sewa', $order['id']) }}"
                                    class="btn btn-secondary mb-3 form-control">Sewa Server</a>
                            @elseif ($liveDate == $endMonth && $isColocation == true)
                                @php
                                    $liveDate = $endMonth;
                                @endphp
                            <td>
                            @elseif($paymentMinus > $order['votes'])
                            <td>
                                {{-- @dd() --}}
                                <form action="{{ route('admin.order.adminBayar', $order['id']) }}"
                                    method="post" class="mt-2 p-2 mb-2">
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
                        @elseif($liveInvoice < $endInvoice && $paymentSet < 1 && $order['votes']  == $order['bulan'])
                        <td>
                            @foreach ($order->status as $orderStatus)
                                    @if ($orderStatus->access == 1 || $orderStatus->access >= 3 || $orderStatus->access == 2)
                                        <a class="btn btn-success mb-2" id="bayar"
                                            href="{{ route('status.new_status', $orderStatus['id']) }}">Fitur
                                            get Back! </br></a>

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
                                
                                <a class="btn btn-success mb-2" id="bayar"
                                    href="#">Invoice Send
                                    <span style="color:yellow">(Gmail Sedang Dikirim) </span></a>
                                {{-- @dd() --}}
                                @if($paymentSet == -1)

                                <a href="{{ route('status.sewa', $order['id']) }}"
                                    class="btn btn-secondary mb-3 mt-3 w-100">Sewa Server</a>
                                @endif
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
                                    <button type="submit" class="btn btn-primary w-100" id="reminderAuto"> Reminder
                                        Me</button>
                                </form>
                            </td>
                        @elseif($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $paymentMinus == $order['votes'])
                            <td>
                                <a class="btn btn-success mb-2" id="bayar"
                                    href="{{ route('order.bayar', $order['id']) }}">Menunggu User membayar
                                    <span style="color:yellow">(Gmail Sudah Dikirim) </span></a>
                                    
                                <a href="{{ route('admin.order.pengirimanAdmin', $order['id']) }}
                                " class="btn btn-primary w-100">Bayar User</a>
                                {{-- @dd() --}}

                            </td>
                        @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == false)
                            <td style="padding:10px 10px;background-color:green;">User Membeli</td>
                        @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == true)
                            <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">User
                                Membeli
                                Collocation</td>
                        @elseif($order['bulan'] == $order['votes'] && $isColocation == false)
                            <td>
                                <form action="{{route('status.lunasUpdate',$order['id'])}}"

                                    method="post" class="mt-2 p-2 mb-2">
                                    @csrf
                                    @method('PATCH')
                                {{-- <a class="btn btn-success mb-2" id="bayar" href="{{route('status.lunasUpdate',$order['id'])}}">User
                                    Melakukan </br> lunas(tekan)</a> --}}
                                    <button type="submit" class="btn btn-success mb-2" id="bayar">User
                                        Melakukan </br> lunas(tekan)</button>
                                </form>
                                <form action="{{ route('status.lunasUpdate',$order['id']) }}"
                                    method="post" class="mt-2 p-2 mb-2">
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
                                <a class="btn btn-success mb-2" id="bayar"
                                    href="{{ route('order.bayar', $order['id']) }}">Menunggu User membayar</a>
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

                            {{-- <a class="btn btn-success" id="collo"
            href="{{ route('order.bayar', $order['id']) }}">Collocation</a>
        <hr>
        <button onclick="pindah(this,'orange')" class="btn btn-danger">Pindah</button> --}}
                            </td>
                            {{-- <td><a href="#" class="btn btn-success">download to excel</a></td> --}}
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
                            $serverType = $serverGet['type'];
                            // dd($serverGet['type']);
                            // 
                            if($serverType == 'dell' || $serverType == 'HP' || $serverType == 'supermicro' || $serverType == 'hp'){
                                $serverData = true;
                            }else{
                                $serverData = false;
                            }

                        @endphp
                        @if ($serverData == false)
                            <a href="{{ route('detail_server.create', $order['id']) }}"
                                class="btn btn-warning mt-3">Set Server</a>
                        @else
                            {{-- <a href="{{ route('detail_server.data', $order['id']) }}"
                                class="btn btn-info mt-3">Server Done</a> --}}
                                <a href="{{route('detail_server.single',$order['user_id'])}}" class="btn btn-info mt-2 mb-2">Server Single</a>
                        @endif
                    </td>
                    
                    <td>
                        @if($orderStatus->payment == -1)
                        <p> User Baru memulai<b> Cicilan Sewa Server
                             </b> </p>
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
                        @elseif($order['votes'] < $order['bulan'] && $isColocation == true)
                            <p> User berlangganan Collocation Selama <b> {{ $order['bulan'] }} </b> bulan</p>
                        @else
                            <div class="btn btn-success">
                                User Lunas </br>
                                Menunggu User Memilih
                            </div>
                        @endif
                    </td>

            </tr>
        @endif
        {{-- @endforeach --}}
                {{-- Tambahkan nama pelanggan ke dalam array uniqueCustomers --}}
                @php $uniqueCustomers[] = $order['name_customer']; @endphp
            @endforeach
            {{-- @dd($sewaMake) --}}
        </tbody>
    </table>
    <div class="d-flex justify-content-end mb-3">
        {{-- @if ($sewaMake->count())
            {{ $sewaMake->links() }}
        @endif --}}
    </div>
</div>
@else
<div class="card p-5 mt-5 mb-5" >

    <div class="alert alert-primary mt-2 p-5" ><h3 style="text-align: center;">Belum ada Client dengan  Pesanan Sewa ~</h3></div>

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
@endpush
