{{-- memanggil file template --}}
@extends('layouts.template')

{{-- isi bagian yield --}}
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        <div class="container">
            <h3><b>Datacenter Cyber</b></h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Datacenter Cyber </a></p>
        </div>
    </div>
    <br>
    <div class=" justify-content-start" style="width: 60%;height:10%;">
        <form action="{{ route('internal.search2') }}" class="" method="GET"
            style="display: flex;justify-content:space-between;">
            <label for="search" class="form-label w-25" style="width:30%">Search :</label>
            <select type="number" name="search" id="search" class="form-select w-100" style="width:100%;margin-left:5%;">
                @php
                    $uniqueRack = [];
                @endphp
                @foreach ($jakartaCyber as $item => $value)
                    @if (in_array($value['datacenter'][0]['rack'], $uniqueRack))
                        {{-- Jika sudah ditampilkan sebelumnya, lanjutkan ke iterasi berikutnya --}}
                        @continue
                    @endif
                    @php
                        $rack = $value['datacenter'][0]['rack'];
                    @endphp

                    <option value="{{ $rack }}">{{ $rack }}</option>
                    @php $uniqueRack[] = $rack; @endphp
                @endforeach
            </select>
            <button type="submit" name="searchRack" class="btn btn-primary mr-5 " style="margin-left:5%;">cari</button>
            
            <a href="{{ route('internal.Jakarta') }}" class="btn btn-danger " style="margin-left:2%;text-align:center;">reset</a>
        </form>
    </div>
    @php
        $orderProducts = [];
        $serverProducts = [];
        foreach ($orders as $order) {
            $serverGet = last($order['products']);
            $countProducts = count($order['products']) - 2;
            if ($serverGet['type'] == 'freeze') {
                $serverData = $order['products'][$countProducts];
            } else {
                $serverData = last($order['products']);
            }

            array_push($serverProducts, $serverData);
        }
        // dd($serverProducts);
        $validate = data_get($serverProducts, '0', false);
        // dd($orderProducts)
        // $find = Order::find($serverProducts['id']);
        // dd($serverProducts);
    @endphp



    {{-- <div class="d-flex justify-content-end">
    <a class="btn btn-primary" href="{{ route('detail_server.create', $products[1]['id']) }}">Tambah data</a>
</div> --}}
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
    @php
    @endphp
    @if ($validate !== false)
        @if (isset($_GET['searchRack']))
            @php $no=1; @endphp

            <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table mt-5 table-striped table-bordered table-hovered">
                        <thead>
                            <tr>

                                <th>Rack</th>
                                <th>Name_customer</th>
                                <th>Series</th>

                                <th>Start Server</th>
                                <th>End Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $uniqueRack = [];
                            @endphp
                            @foreach ($jakartaCyber as $item => $value)
                                {{-- @foreach ($dataCompile as $item) - -}}
                            {{-- @php
                    @endphp --}}

                                @if (in_array($rack, $uniqueRack))
                                    {{-- Jika sudah ditampilkan sebelumnya, lanjutkan ke iterasi berikutnya --}}
                                    @continue
                                @endif

                                <tr>
                                    <td>

                                        {{-- @dd($datacenter) --}}
                                        <li>
                                            {{ $rack }}
                                        </li>

                                    </td>
                                    <td>
                                        @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                            {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                <li style="list-style: decimal;">
                                                    {{ $valueInner['name_customer'] }}
                                                </li>
                                            @else
                                            @endif
                                        @endforeach
                                    </td>


                                    @php

                                        $mytime = Carbon\Carbon::now();
                                        $liveDate = $mytime->formatLocalized('25 February 2026 09:03');
                                        $havestart = [];
                                        $start = data_get($serverProducts, "$item.startDate", 2);
                                        array_push($havestart, $start);
                                        // dd($serverProducts);

                                        // dd($start);
                                        $entry = data_get($serverProducts, "$item.entryDate", 2);
                                        // dd($serverProducts);
                                        $inventory = data_get($serverProducts, "$item.inventory", 2);
                                        $endPayment = data_get($serverProducts, "$item.endDate", 2);
                                        // dd(count($value['inventory']),count($serverProducts[2]['inventory']));
                                        // dd($serverProducts);
                                        // Arr::get($my_arr, '*.lower'); // null
                                        if ($endPayment !== 2 && $start !== 2) {
                                            $endDate = Carbon\Carbon::parse($endPayment)->formatLocalized(
                                                '%d %B %Y %H:%M',
                                            );
                                        } elseif ($endPayment !== 2 && $start == 2) {
                                            $endDate = Carbon\Carbon::parse($endPayment)->formatLocalized(
                                                '%d %B %Y %H:%M',
                                            );
                                            // ->addDays(30 * )
                                        } elseif ($endPayment == 2 && $start == 2) {
                                            $endDate = Carbon\Carbon::parse($entry)
                                                ->addDays(30)
                                                ->formatLocalized('%d %B %Y ');
                                        } else {
                                            $endDate = Carbon\Carbon::parse($start)
                                                ->addDays(30)
                                                ->formatLocalized('%d %B %Y %H:%M');
                                        }
                                        if ($entry !== 2 && $start == 2) {
                                            $startDate = Carbon\Carbon::parse($entry)->formatLocalized(
                                                '%d %B %Y %H:%M',
                                            );
                                        } else {
                                            $startDate = Carbon\Carbon::parse($start)->formatLocalized(
                                                '%d %B %Y %H:%M',
                                            );
                                        }
                                        $rack = $value['datacenter'][0]['rack'];
                                    @endphp
                                    <td>
                                        <ol>
                                            @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                                {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                                @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                    @foreach ($valueInner['products'] as $product)
                                                        @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                            <li>
                                                                o Series :{{ $product['series'] }} <br>
                                                                o Type : {{ $product['type'] }} <br>
                                                                o Serial Number : {{ $product['serialNumber'] }} <br>
                                                                o Label : {{ $product['serverLabel'] }}
                                                                <hr>
                                                                {{-- <a href="" class="btn btn-primary">{{$product['serverLabel']}}</a> --}}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @else
                                                @endif
                                            @endforeach
                                        </ol>
                                    </td>
                                    {{--                                 
                                <td>
                                
                                        @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                           
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                @foreach ($valueInner['products'] as $product)
                                                @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                <li style="list-style:decimal;">
                                                    {{ $product['type'] }} <br>
                                                </li>
                                                @endif
                                                @endforeach
                                            @else
                                            @endif
                                        @endforeach
                                    
                                </td> 
                                <td>
                                
                                        @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                @foreach ($valueInner['products'] as $product)
                                                @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                <li style="list-style:decimal;">
                                                    {{ $product['serialNumber'] }} <br>
                                                </li>
                                                @endif
                                                @endforeach
                                            @else
                                            @endif
                                        @endforeach
                                    
                                </td> 
                                <td>
                                        @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                          
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                @foreach ($valueInner['products'] as $product)
                                                @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                <li style="list-style:decimal;">
                                                    {{ $product['serverLabel'] }} <br>
                                                </li>
                                                @endif
                                                @endforeach
                                            @else
                                            @endif
                                        @endforeach
                                </td>  --}}

                                    {{-- @dd($serverPrdu) --}}
                                    <td>
                                        @php
                                            $arr_Data = [];
                                        @endphp
                                        {{-- @dd($jakartaCyber) --}}
                                        @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                            {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                @foreach ($valueInner['products'] as $product)
                                                    @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                        {{-- <td>{{ $value['entryDate'] }}</td> --}}
                                                        @php
                                                            // dd($product);
                                                            $startGet = data_get($product, 'startDate', 2);
                                                            $entryGet = data_get($product, 'entryDate', 2);
                                                            if ($startGet == 2) {
                                                                $startData = Carbon\Carbon::parse(
                                                                    $entryGet,
                                                                )->formatLocalized('%d %B %Y %H:%M');
                                                            } else {
                                                                $startData = Carbon\Carbon::parse(
                                                                    $startGet,
                                                                )->formatLocalized('%d %B %Y %H:%M');
                                                            }
                                                        @endphp
                                                        {{-- @for ($i = 0; $i < count($arr_Data); $i++) --}}
                                                        <p>{{ $startData }}</p>
                                                        <hr>

                                                        {{-- @endfor --}}
                                                        {{-- @if ($start !== 2 && $endPayment == 2)
                                                            <td>{{ $endDate }} <br>(Pembelian Perbulan) </td>
                                                        @elseif($start !== 2 && $endPayment !== 2)
                                                            @if ($liveDate == $endDate)
                                                                <td>{{ $endDate }} (Dalam Sebulan akan beralih ke
                                                                    <b>Colocation</b>)</td>
                                                            @else
                                                                <td>{{ $endDate }} (Pembelian Lunas)</td>
                                                            @endif
                                                        @else
                                                            <td>{{ $startDate }}</td>
                                                            <td>{{ $endDate }} (Pembayaran Colocation)</td>
                                                        @endif --}}
                                                        {{-- <td>{{ $value['serverLabel'] }}</td> --}}
                                                    @endif
                                                @endforeach
                                            @else
                                            @endif
                                        @endforeach
                                    </td>
                                    {{-- @dd($arr_Data) --}}
                                    <td>
                                        @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                            {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                @foreach ($valueInner['products'] as $product)
                                                    @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                        @php
                                                            $endServer = data_get($product, 'endDate', 2);
                                                            $startServer = data_get($product, 'startDate', 2);
                                                            // dd($startServer);
                                                            if ($endServer == 2) {
                                                                $expDate = Carbon\Carbon::parse($startServer)
                                                                    ->addDays(30 * $valueInner['votes'])
                                                                    ->formatLocalized('%d %B %Y %H:%M');
                                                            } else {
                                                                $expDate = Carbon\Carbon::parse($endServer)
                                                                    ->addDays(30)
                                                                    ->formatLocalized('%d %B %Y %H:%M');
                                                            }
                                                        @endphp

                                                        <p>{{ $expDate }}</p>

                                                        <hr>
                                                    @endif
                                                @endforeach
                                            @else
                                            @endif
                                        @endforeach
                                    </td>
                                    {{-- @foreach ($userData as $user) --}}
                                    {{-- Tambahkan aksi sesuai kebutuhan --}}
                                    {{-- <td class="d-flex"> --}}
                                    <td>
                                        @foreach ($jakartaCyber as $innerOrder => $valueInner)
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                @foreach ($valueInner['products'] as $product)
                                                    @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                        <a href="{{ route('detail_server.edit', $valueInner['id']) }}"
                                                            class="btn btn-success form-control">Edit
                                                            {{ $product['serverLabel'] }}</a>
                                                        <br>

                                                        <a href="{{ route('detail_server.delete', $valueInner['id']) }}"
                                                            class="btn btn-danger form-control mt-2"
                                                            data-confirm-delete="true">Delete
                                                            {{ $product['serverLabel'] }}</a>
                                                        <hr>
                                                    @endif
                                                @endforeach
                                            @else
                                            @endif
                                        @endforeach

                                    </td>
                                    {{-- @foreach ($item->status as $orderStatus) --}}
                                    {{-- <td>

                    
                            </td> --}}
                                    {{-- @endforeach --}}

                                </tr>
                                @php $uniqueRack[] = $rack; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- @dd($havestart) --}}
            {{-- <div class="container-fluid">
            <div class="table-responsive">
                <table class="table mt-5 table-striped table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Client Information</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                            $countUser = count($userData) - 1;
                            $x = count($userData) - 1;
                        @endphp

                        @for ($i = 0; $i < $countUser; $i++)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <li style="list-style-type:circle;">
                                        Name: {{ $userData[$i]['name'] }}
                                    </li>
                                    <li style="list-style-type:circle;">
                                        Client Id: {{ $no }}
                                    </li>
                                    <li style="list-style-type:circle;">
                                        No Hp: {{ $userData[$i]['notelp'] }}
                                    </li>
                                    <li style="list-style-type:circle;">
                                        Email: {{ $userData[$i]['email'] }}
                                    </li>
                                </td>
                                @csrf
                                <td>
                                    <a class="btn btn-primary mt-2"
                                        href="{{ route('status.show', $userData[$x][$i]['id']) }}">
                                        Upload</a>
                                </td>


                            </tr>
                        @endfor

                    </tbody>
                </table>
            </div>
        </div> --}}
            <div class="d-flex justify-content-end mt-3">
                @if ($products->count())
                    {{ $products->links() }}
                @endif
            </div>
        @else
            <div class="card p-5 mt-5 mb-5">

                <div class="alert alert-primary mt-2 p-5">
                    <h3 style="text-align: center;">Silahkan Cari Data Rak User</h3>
                </div>

            </div>
        @endif
    @else
        <div class="card p-5 mt-5 mb-5">

            <div class="alert alert-primary mt-2 p-5">
                <h3 style="text-align: center;">Belum ada Server Yang Di Input</h3>
            </div>

        </div>
    @endif
@endsection
