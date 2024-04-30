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
        <div class="container">
            {{-- @if (Session::get('failed'))
                <div class="alert alert-danger">{{Session::get('failed')}}</div>
                @endif --}}
            <h3><b>Data Single</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Single</a></p>
        </div>
    </div>

    <body>
        {{-- Modal Pembelian --}}
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
                        <a type="button" class="btn btn-success"
                            href="{{ route('admin.order.createDedicated', $userData->id) }}"
                            style="width: 49%">Dedicated</a>
                        <a type="button" class="btn btn-primary"
                            href="{{ route('admin.order.createColocation', $userData->id) }}"
                            style="width: 49%">Colocation</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary w-25" data-dismiss="modal">Close</button>
                        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- <h2>Data Status</h2> --}}
        {{-- <div class="jumbotron  mt-2" style="padding:0px;">
            <div class="container">
                    @if (Session::get('failed'))
                    <div class="alert alert-danger">{{Session::get('failed')}}</div>
                    @endif
                    <h3><b>Data Status</b> </h3>
                    <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Status</a></p>
                </div>
            </div> --}}
        @php
        $statusGet = data_get($status1,'0.id',0);
        // dd($status1[0]['id']);
        if ($statusGet == 0) {
            # code...
            $Validate = false;

        }else
        {

            foreach ($status1 as $order) {
                $Validate = data_get($order['products'],'0',false);
            }
            
        }
        @endphp
        {{-- @dd($Validate); --}}
        @if($Validate !== false)
        <div class="mt-2">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.order.downloadExcel') }}" class="btn btn-success">Export Excel</a>
            </div>
            <br>
            <div class="d-flex justify-content-end mb-3">
                <a class="btn btn-primary mr-5" href="{{ route('order.index') }}" style="margin-right:2%;">Reset</a>
                <a class="btn btn-primary mr-5" href="{{ route('order.cart') }}" style="margin-right:2%;">Cart</a>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                    Pembelian Baru
                </button> {{-- search page --}}
            </div>
            {{-- table mt-5 table-striped table-bordered table-hovered --}}
            <table class="table table-stripped w-100 table">
                {{-- <table class="table mt-3 table-striped w-100 table-bordered table-hovered"> --}}

                {{-- <table class="table mt-5 table-striped table-bordered table-hovered"> --}}
                <thead class="p-5">
                    <tr>
                        <th>No</th>
                        <th>Client</th>
                        <th>Pesanan</th>
                        <th>Total Bayar</th>
                        {{-- <th>user</th> --}}
                        <th>tanggal</th>
                        <th>Invoice</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        <th>Cicilan</th>
                    </tr>
                </thead>
                <tbody>
                    @php$no = 1;
                                                                        // dd($status1);
                                                            @endphp ?>
                    @foreach ($status1 as $order)
                        <tr>
                            {{-- currentpage : ambil posisi ada di page keberapa - 1 (misal dua klik next ada di page 2 berarti menjadi 2-1 = 1), perpage : mengambil jumlah data yg ditampilkan per pagenya berapa (ada di controller bagian paginate.simpelPaginate, misal 5), loop->index : mengambil index dr array (mulai dr 0)+1 --}}
                            {{-- jadi : (2-1) x 5 + 1 = 6 (dimulai dr angka 6 di page ke 2 nya) --}}
                            <td>{{ ($status1->currentPage() - 1) * $status1->perpage() + $loop->index + 1 }}</td>
                            <th>{{ $order['name_customer'] }}</th>
                            {{-- nested loop : looping di dalam looping --}}
                            {{-- karena colunm products pada table status1 tipe datanya json, jd untuk akses nya perlu looping --}}
                            <td>
                                <ol>
                                        @for ($i = 0;$i < 1;$i++)
                                        <li> {{ $order['products'][$i]['name_product'] }} <small>Rp. {{ number_format($order['products'][$i]['price'], 0, '.', ',') }}<b>(qty : {{ $order['products'][$i]['qty'] }})</b></small> = Rp. {{ number_format($order['products'][$i]['price_after_qty'], 0, '.', ',') }}</li>
                                    @endfor
                                    {{-- @endforeach --}}
                                </ol>
                            </td>
                            @php
                                $ppn = $order['total_price'] * 0.1;
                            @endphp
                            {{-- <td>Rp. {{ number_format($order['total_price'] + $ppn, 0, '.', ',') }}</td> --}}
                            <td>Rp. {{ number_format($order['total_price'], 0, '.', ',') }}</td>
                            {{-- mengambil column dari relasi, $variable['namaFunctionModel'] --}}
                            {{-- namaColumnDiDBRelasi --}}
                            {{-- <td>{{ $order['user']['name'] }} <a href="mailto:user@gmail.com">(user@gmail.com)</a> </td> --}}
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
                                // $liveDate = $mytime->formatLocalized('%d %B %Y %H:%M:00');
                                $liveDate = $mytime->formatLocalized('29 Maret 2024 17:02');
                                $pauseDate = Carbon\Carbon::parse($order['created_at'])
                                    ->addDays(30 * $order['votes'])
                                    ->subDays(30)
                                    ->formatLocalized('%d %B %Y %H:%M');

                                // $liveInvoice = $mytime->formatLocalized('%y%m%d');
                                $liveInvoices = $mytime->formatLocalized('240403');
                                $liveInvoice = $mytime->formatLocalized('240514');
                                // dd($liveInvoice);
                                $liveInvoiced = $mytime->formatLocalized('%H');

                                // ---------------------INVOICE-COLLO-COND---------------------
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
                                

                                // dd($isColocation);
                                // dd($order);
                                // dd($orders[3]['products']);

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
                                                : ($isAfter
                                                    ? Carbon\Carbon::parse($order['updated_at'])
                                                        ->addDays(30 * ($isFive - 4))
                                                        ->formatLocalized('%d %B %Y %H:%M')
                                                    : Carbon\Carbon::parse($order['created_at'])
                                                        ->addDays(30 * $order['votes'])
                                                        ->formatLocalized('%d %B %Y %H:%M'))));

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
                                            : ($isAfter
                                                ? Carbon\Carbon::parse($order['updated_at'])
                                                    ->addDays(30 * ($isFive - 5))
                                                    ->formatLocalized('%d %B %Y %H:%M')
                                                : Carbon\Carbon::parse($order['created_at'])->formatLocalized(
                                                    '%d %B %Y %H:%M',
                                                )));

                                $unDate = Carbon\Carbon::parse($order['updated_at'])->formatLocalized('%d %B %Y %H:%M');

                                $unMonth = Carbon\Carbon::parse($order['updated_at'])
                                    ->addDays(30)
                                    ->formatLocalized('%d %B %Y %H:%M');

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
                                                : ($isAfter
                                                    ? Carbon\Carbon::parse($order['updated_at'])
                                                        ->addDays(30 * ($isFive - 4))
                                                        ->subDays()
                                                        ->formatLocalized('%y%m%d')
                                                    : Carbon\Carbon::parse($invoiceDate)
                                                        ->addDays(30 * $order['votes'])
                                                        ->formatLocalized('%y%m%d'))));

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
                                        : ($isAfter
                                            ? Carbon\Carbon::parse($order['updated_at'])
                                                ->addDays(30 * ($isFive - 4))
                                                ->subDays(10)
                                                ->formatLocalized('%y%m%d')
                                            : Carbon\Carbon::parse($invoiceDate)
                                                ->addDays(30 * $order['votes'])
                                                ->subDays(10)
                                                ->formatLocalized('%y%m%d')));

                                $ins = $order->whereMonth('updated_at', '=', date('m'));
                                $month = Carbon\Carbon::parse('2022/06/12')->format('MM');
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
                                :{{ $invoice }}
                            </td>
                            {{-- @foreach ($order['products'] as $product)
                            @php
                            $value = array_get($product, 'type.ram');
                            dd($value);
                            @endphp
                            @endforeach --}}
                            @php
                                $ramType = data_get($order['products'], '1.type', 0);
                                $ramPrice = data_get($order['products'], '1.price', 1);
                                $ramId = data_get($order['products'], '1.id', true);

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
                                @foreach ($order->status as $orderStatus)
                                    @php
                                        $paymentSet = $orderStatus->payment;
                                        if($paymentSet > 0){

                                            $paymentMinus = $orderStatus->payment - 1;
                                        }else{
                                            $paymentSewa = $paymentSet * -2;
                                            if($paymentSet == 0){
                                                $paymentMinus = 0;
                                            }else{
                                                
                                                $paymentMinus = $paymentSet - 1;
                                            }
                                        }

                                    @endphp
                                    <input id="paymentData" type="text" value="{{ $paymentMinus }}" hidden>
                                    <input id="votesData" type="text" value="{{ $order['votes'] }}" hidden>
                                    @if ($orderStatus->access == 3 && $startInpov >= $liveInvoice && $order['bulan'] == 4)
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
                                    @elseif($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $paymentMinus < $order['votes'])
                                        <td>
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('order.bayar', $order['id']) }}">Invoice Send
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
                                                <button type="submit" class="btn btn-primary" id="reminderAuto"> Reminder
                                                    Me</button>
                                            </form>
                                        </td>
                                    @elseif($liveInvoice >= $invoice && $liveInvoice < $endInvoice && $paymentMinus == $order['votes'])
                                        <td>
                                            <a class="btn btn-success mb-2" id="bayar"
                                                href="{{ route('order.bayar', $order['id']) }}">Menunggu User membayar
                                                <span style="color:yellow">(Gmail Sudah Dikirim) </span></a>
                                            {{-- @dd() --}}

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
                                    
                                    @elseif ($order['votes'] == $order['bulan'] && $invoice > $liveInvoice && $isColocation == true)
                                        <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">User
                                            Membeli
                                            Collocation</td>
                                    @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == false)
                                        <td style="padding:10px 10px;background-color:green;">User Membeli golden</td>
                                    @elseif ($invoice > $liveInvoice && $order['votes'] == 1 && $isColocation == true)
                                        <td style="padding:10px 10px;background-color:green;color:#f5f5f5;">User
                                            Membeli
                                            Collocation</td>
                                    @elseif($order['bulan'] == $order['votes'])
                                        <td>
                                            <form action="{{route('status.lunasUpdate',$order['id'])}}"

                                                method="post" class="mt-2 p-2 mb-2">
                                                @csrf
                                                @method('PATCH')
                                            {{-- <a class="btn btn-success mb-2" id="bayar" href="{{route('status.lunasUpdate',$order['id'])}}">User
                                                Melakukan </br> Pembayaran lunas(tekan)</a> --}}
                                                <button type="submit" class="btn btn-success mb-2" id="bayar">User
                                                    Melakukan </br> Pembayaran lunas(tekan)</button>
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
                                    @elseif ($liveDate == $endMonth && $isColocation == false)
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
                                            <button onclick="suspend(this,'green')" class="btn btn-danger">User di
                                                Suspend</button>
                                            <button onclick="konfirmasiFreeze('{{ route('order.bayar', $order['id']) }}')"
                                                class="btn btn-primary">Freeze</button>
                                        @elseif ($liveDate == $endMonth && $isColocation == true)
                                            @php
                                                $liveDate = $endMonth;
                                            @endphp
                                        <td>
                                            <p class="alert alert-success" style="padding:5px 30px;">Masa Collocation
                                                </br>
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
                                                @if($orderStatus->access == 2)
                                                <div class="btn btn-danger mt-2 mb-3" href="#" >
                                                    User Tersuspend</span>
                                                </div>
                                        @else 
                                        @endif
                                    @endforeach
                                    @php
                                        $serverGet = last($order['products']);
                                        $countFreeze = count($order['products']) - 2;
                                        // $dataType = [];
                                        $dataType = $order['products'];
                                        if($serverGet['type'] == 'freeze'){
                                            
                                            $serverType = $dataType[$countFreeze]['type'];
                                        }else{

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
                                <td >
                                    @if ($order['votes'] < $order['bulan'] && $isColocation == false)
                                        <p> User berlangganan Cicilan Dedicated dari <b>
                                                {{ $order['votes'] }}/{{ $order['bulan'] }} </b>
                                            bulan </p>
                                    @elseif($order['votes'] == $order['bulan'] && $isColocation == true)
                                        <p class="alert alert-success"> User berlangganan Collocation Selama <b> {{ $order['bulan'] }} </b> bulan</p>
                                    {{-- @elseif($order['votes'] == $order['bulan'] && $isColocation ==) --}}
                                    @else
                                        <div class="btn btn-success">
                                            User Lunas </br>
                                            Menunggu User Memilih
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
                                    <ol class="mt-3 mb-2">
                                        <form action="{{ route('status.deleteSingle', $order['id']) }}" method="post"
                                        class="mt-2">
                                        @csrf
                                        {{-- Menimpa atau mengubah method post menjadi method DELETE sesuai dengan method route(::delete) --}}
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger form-control">Hapus</button>
                                    </form>
                                    </ol>
                                </td>

                        </tr>
                    @endif
                    @endforeach
                    {{-- @foreach ($status1 as $order)
                    <!-- Tampilkan hasil pencarian di sini -->
                    <p>{{ $status1->search }} - {{ $order->created_at }}</p>
                 --}}
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                @if ($status1->count())
                    {{ $status1->links() }}
                @endif
            </div>
            

    </body>
    @else
    <div class="card p-5 mt-5 mb-5" >

    <div class="alert alert-primary mt-2 p-5" ><h3 style="text-align: center;">Client Belum Memesan Apapun</h3></div>

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
@endpush
