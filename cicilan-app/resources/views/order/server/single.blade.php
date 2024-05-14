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
            <h3><b>Data Server</b></h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a
                    href="#">Data Server</a></p>
        </div>
    </div>
    @php
        $orderProducts = [];
        $serverProducts = [];
        foreach ($orders as $order) {
            // $serverData = data_get(last($order['products']),'', 1);
            $serverData = last($order['products']);
            $serverType = data_get($serverData, 'series', false);
            // dd(last($order['products']));
            // dd($serverData);
            if ($serverData['type'] == 'freeze') {
                $serverGet = data_get($order['products'], '4', false);
                array_push($serverProducts, $serverGet);
            } else {
                // elseif $serverType !== false) {
                array_push($serverProducts, $serverData);
                // $orderData = data_get($order,$serverData);
                // array_push($orderProducts, $orderData);
                // $serverKe4 = $serverData;
                // array_push ($orderProducts , $serverData);
            }
            // array_push($orderProducts,[$order['id']]);
            // dd($serverType);
        }
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

    @if ($validate !== false)
        @php $no=1; @endphp

        <div class="container-fluid">
            <div class="table-responsive">
                <table class="table mt-5 table-striped table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Series</th>
                            <th>Type</th>
                            <th>Dimensi</th>
                            <th>serialNumber</th>
                            <th>Additional</th>
                            <th>Start Server</th>
                            <th>End Date</th>
                            <th>Label</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
$no = 1;

                                                                    @endphp ?>
                        @foreach ($serverProducts as $item => $value)
                            {{-- @foreach ($dataCompile as $item) --}}
                            {{-- @php
                    @endphp --}}
                            @php

                                $mytime = Carbon\Carbon::now();
                                $liveDate = $mytime->formatLocalized('25 February 2026 09:03');

                                $start = data_get($serverProducts, "$item.startDate", 1);
                                $entry = data_get($serverProducts, "$item.entryDate", 1);
                                // dd($serverProducts);
                                $inventory = data_get($serverProducts, "$item.inventory", 1);
                                $endPayment = data_get($serverProducts, "$item.endDate", 1);
                                // dd(count($value['inventory']),count($serverProducts[2]['inventory']));
                                // dd($serverProducts);
                                // Arr::get($my_arr, '*.lower'); // null
                                if ($endPayment !== 1 && $start !== 1) {
                                    $endDate = Carbon\Carbon::parse($endPayment)->formatLocalized('%d %B %Y %H:%M');
                                } elseif ($endPayment !== 1 && $start == 1) {
                                    $endDate = Carbon\Carbon::parse($endPayment)->formatLocalized('%d %B %Y %H:%M');
                                    // ->addDays(30 * )
                                } elseif ($endPayment == 1 && $start == 1) {
                                    $endDate = Carbon\Carbon::parse($entry)->addDays(30)->formatLocalized('%d %B %Y ');
                                } else {
                                    $endDate = Carbon\Carbon::parse($start)
                                        ->addDays(30)
                                        ->formatLocalized('%d %B %Y %H:%M');
                                }
                                if ($entry !== 1 && $start == 1) {
                                    $startDate = Carbon\Carbon::parse($entry)->formatLocalized('%d %B %Y %H:%M');
                                } else {
                                    $startDate = Carbon\Carbon::parse($start)->formatLocalized('%d %B %Y %H:%M');
                                }

                            @endphp

                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $value['series'] }}</td>
                                <td>{{ $value['type'] }}</td>
                                {{-- @dd($serverPrdu) --}}
                                <td>{{ $value['dimension'] }}</td>
                                <td>{{ $value['serialNumber'] }}</td>
                                <td class="p-2" style="max-width: 100%;">
                                    @if (count($value['inventory']) !== 0)
                                        <div class="list-content p-1 " style="margin:0px 0px 0px 0px">
                                            @for ($i = 0; $i < count($value['inventory']); $i++)
                                                <p> add ({{ $i + 1 }}) </p>
                                                <li class="d-flex"
                                                    style="list-style:circle;display:block;margin:0px 10px 0px 0px;">
                                                    @for ($x = 0; $x < count($value['inventory'][$i]); $x++)
                                                        {{-- <li>
                                        <p>add ({{ $x + 1 }})</p>
                                        + {{ $value['inventory'][0][$x] }} GB (ram) <br>

                                        + {{ $value['inventory'][1][$x] }} Disk (DISK) <br>

                                        + {{ $value['inventory'][2][$x] }} Processor (PR)
                                    </li> --}}
                                                        {{-- @dd($value['inventory'][$i][$x]) --}}
                                                        @if ($i == 0)
                                                            {{-- {{$value['inventory'][$i][$x]}} Gb (ram)  --}}
                                                            {{-- @else --}}
                                                            <div class="row" style="flex-wrap:unset;margin:0px 0px;">

                                                                <p>(+) {{ $value['inventory'][$i][$x] }} Gb (ram) </p>
                                                            </div>
                                                        @elseif($i == 1)
                                                            <div class="row" style="flex-wrap:unset;margin:0px 0px;">
                                                                <p class="" style="">(+)
                                                                    {{ $value['inventory'][$i][$x] }} (Disk) </p>
                                                            </div>
                                                        @elseif($i == 2)
                                                            <div class="row" style="flex-wrap:unset;margin:0px 0px;">
                                                                <p class="">(+) {{ $value['inventory'][$i][$x] }}
                                                                    (CPU)
                                                                </p>
                                                            </div>
                                                        @endif

                                                        {{-- @php
                                            dd(count($value['inventory']),$value['inventory'][0][0]);
                                            @endphp
                                            {{ $inventory }} --}}
                                                    @endfor
                                                </li>
                                                <hr style="margin:0.5rem 0;">
                                            @endfor

                                        </div>
                                    @else
                                        <li>
                                            <p>add (0)</p>
                                            Tidak ada penambahan produk
                                        </li>
                                    @endif
                                </td>
                                {{-- <td>{{ $value['entryDate'] }}</td> --}}
                                @if ($start !== 1 && $endPayment == 1)
                                    <td>{{ $startDate }}</td>
                                    <td>{{ $endDate }} 
                                        {{-- (Pembelian Perbulan) --}}
                                    </td>
                                @elseif($start !== 1 && $endPayment !== 1)
                                    <td>{{ $startDate }}</td>
                                    @if ($liveDate == $endDate)
                                        <td>{{ $endDate }}
                                             {{-- (Dalam Sebulan akan beralih ke <b>Colocation</b>) --}}
                                            </td>
                                    @else
                                        <td>{{ $endDate }} 
                                            {{-- (Pembelian Lunas) --}}
                                        </td>
                                    @endif
                                @else
                                    <td>{{ $startDate }}</td>
                                    <td>{{ $endDate }} 
                                        {{-- (Pembayaran Colocation) --}}
                                    </td>
                                @endif
                                <td>{{ $value['serverLabel'] }}</td>

                                {{-- @foreach ($userData as $user) --}}
                                {{-- Tambahkan aksi sesuai kebutuhan --}}
                                {{-- <td class="d-flex"> --}}
                                <td>
                                    {{-- <a class="btn btn-primary" href="{{ route('detail_server.create', $item['id']) }}" style="margin:0px 10px;">Tambah</a> --}}
                                    <a href="{{ route('detail_server.edit', $value['id']) }}"
                                        class="btn btn-success form-control">Edit</a>
                                    <a href="{{ route('detail_server.delete', $value['id']) }}"
                                        class="btn btn-danger form-control mt-2" data-confirm-delete="true">Delete</a>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container-fluid">
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
                            // dd($userData);
                        @endphp

                        @for ($i = 0; $i < $countUser; $i++)
                            <tr>
                                {{-- <td>@php $no++ @endphp</td> --}}
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
                                {{-- <td>{{ $orders->user()->id }}</td> --}}
                                {{-- @foreach ($userData as $user) --}}
                                {{-- Tambahkan aksi sesuai kebutuhan --}}
                                {{-- <td class="d-flex"> --}}
                                {{-- <a class="btn btn-primary" href="{{ route('detail_server.create', $item['id']) }}" style="margin:0px 10px;">Tambah</a> --}}
                                {{-- <a href="{{ route('detail_server.edit', $['id']) }}" class="btn btn-success">Edit</a> --}}
                                {{-- method delete tidak bisa digunakan di href harus pakai form --}}
                                {{-- <form action="{{ route('product.delete', $item['id']) }}" method="post" class="ms-3"> --}}
                                @csrf
                                {{-- Menimpa atau mengubah method post menjadi method DELETE sesuai dengan method route(::delete) --}}
                                {{-- @method('DELETE') --}}
                                {{-- <button type="submit" class="btn btn-danger">Hapus</button> --}}
                                {{-- </form> --}}
                                {{-- </td> --}}
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
        </div>
        <div class="d-flex justify-content-end mt-3">
            @if ($products->count())
                {{ $products->links() }}
            @endif
        </div>
    @else
        <div class="card p-5 mt-5 mb-5">

            <div class="alert alert-primary mt-2 p-5">
                <h3 style="text-align: center;">Belum ada Server Yang Di Input</h3>
            </div>

        </div>
    @endif
@endsection
