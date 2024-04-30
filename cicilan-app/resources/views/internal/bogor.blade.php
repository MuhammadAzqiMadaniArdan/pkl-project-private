{{-- memanggil file template --}}
@extends('layouts.template')

{{-- isi bagian yield --}}
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
            <h3><b>Datacenter Asnet</b></h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="{{ route('admin.order.data') }}">Data Order</a>/<a
                    href="#">Datacenter Asnet </a></p>
        </div>
    </div>
    {{-- <p>hai</p> --}}


    <div class="container mt-5" hidden>
        <select class="livesearch form-control" name="livesearch" style="color:black;" ></select>

    </div>
    <br>
    <div class=" justify-content-start" style="width: 30%;height:10%;">
        <form action="{{ route('internal.search') }}" class="" method="GET"
            style="display: flex;justify-content:space-between;">
            <label for="search" class="form-label w-25" style="width:30%">Search :</label>
            <select type="number" name="search" id="search" class="w-50" style="width:100%;margin-left:5%;">
                @php
                    $uniqueRack = [];
                @endphp
                @foreach ($bogorAsnet as $item => $value)
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

            {{-- <input type="number" name="search" id="search" class="w-50" style="width:100%;margin-left:5%;"> --}}
            <button type="submit" name="searchRack" class="btn btn-primary mr-5 w-25" style="margin-left:5%;">cari</button>
            <a href="{{ route('internal.Bogor') }}" class="btn btn-danger w-25 " style="margin-left:5%;">reset</a>
        </form>
    </div>

    @php
        $orderProducts = [];
        $serverProducts = [];
        foreach ($orders as $order) {
            $serverData = data_get($order['products'], '4', 1);
            // dd($serverData);
            if ($serverData !== 1) {
                array_push($serverProducts, $serverData);
                // $orderData = data_get($order,$serverData);
                // array_push($orderProducts, $orderData);
                // $serverKe4 = $serverData;
                // array_push ($orderProducts , $serverData);
            }
            // array_push($orderProducts,[$order['id']]);
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
        @if (isset($_GET['searchRack']))
            @php $no=1; @endphp

            <div class="container-fluid">

                <div class="table-responsive">
                    <table class="table mt-5 table-striped table-bordered table-hovered">
                        <thead>
                            <tr>
                                <th>Rack</th>
                                <th>Name_customer</th>
                                <th>Server info</th>
                                {{-- <th>serialNumber</th>
                            <th>Additional</th> --}}
                                <th>Start Server</th>
                                <th>Expired Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $uniqueRack = [];
                            @endphp
                            @foreach ($bogorAsnet as $item => $value)
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
                                        $endDate = Carbon\Carbon::parse($entry)
                                            ->addDays(30)
                                            ->formatLocalized('%d %B %Y ');
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
                                    $rack = $value['datacenter'][0]['rack'];
                                @endphp
                                @if (in_array($rack, $uniqueRack))
                                    {{-- Jika sudah ditampilkan sebelumnya, lanjutkan ke iterasi berikutnya --}}
                                    @continue
                                @endif
                                <tr>
                                    <td>
                                        {{-- @dd($datacenter) --}}
                                        {{ $rack }}
                                    </td>
                                    <td>
                                        @php
                                            $uniqueCustomer = [];

                                        @endphp

                                        @foreach ($bogorAsnet as $innerOrder => $valueInner)
                                            {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                            @if (in_array($valueInner['name_customer'], $uniqueCustomer))
                                                @continue
                                            @endif
                                            @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                <li style="list-style: decimal;">
                                                    {{ $valueInner['name_customer'] }}
                                                </li>

                                                @php $uniqueCustomer[] = $valueInner['name_customer'] @endphp
                                            @else
                                            @endif
                                        @endforeach
                                    </td>

                                    <td>
                                        <ol>
                                            @foreach ($bogorAsnet as $innerOrder => $valueInner)
                                                {{-- Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini --}}
                                                @if ($valueInner['datacenter'][0]['rack'] == $rack)
                                                    @foreach ($valueInner['products'] as $product)
                                                        @if ($product['type'] == 'dell' || $product['type'] == 'HP' || $product['type'] == 'supermicro')
                                                            <li>
                                                                o Nama :{{ $product['series'] }} <br>
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

                                    {{-- @dd($serverPrdu) --}}

                                    <td>
                                        @php
                                            $arr_Data = [];
                                        @endphp
                                        {{-- @dd($bogorAsnet) --}}
                                        @foreach ($bogorAsnet as $innerOrder => $valueInner)
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
                                    {{-- <td>{{ $value['serverLabel'] }}</td> --}}
                                    {{-- @dd($arr_Data) --}}
                                    <td>
                                        @foreach ($bogorAsnet as $innerOrder => $valueInner)
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
                                        {{-- <a class="btn btn-primary" href="{{ route('detail_server.create', $item['id']) }}" style="margin:0px 10px;">Tambah</a> --}}
                                        @foreach ($bogorAsnet as $innerOrder => $valueInner)
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





                                        {{-- method delete tidak bisa digunakan di href harus pakai form --}}
                                        {{-- <form action="{{ route('detail_server.delete', $value['id']) }}" method="post"
                                        class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger form-control">Hapus</button>
                                    </form> --}}
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
            <div class="d-flex justify-content-end">
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
@push('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        let url = "{{ route('internal.search') }}";
        let placeholder = 'Select Rack';

        $('.livesearch').select2({
            placeholder: placeholder,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.order[1].datacenter,
                                id: item.order[1].rack,
                            }
                        })
                    };
                },
                cache: true
            }
        });
    </script>
@endpush
