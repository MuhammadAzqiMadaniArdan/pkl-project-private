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
            <h3><b>Data Order</b></h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="#">Data Order</a></p>
        </div>
    </div>
    <div class="mt-1">
        {{-- <div class="d-flex justify-content-end">
            <a href="{{ route('admin.order.downloadExcel') }}" class="btn btn-success">Export Excel</a>
        </div> --}}
        <table class="table-stripped w-100 table mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Client</th>
                    {{-- <th>Pesanan</th> --}}
                    <th>Total Bayar</th>
                    <th>user</th>
                    <th>No-Telp</th>
                    <th>Perusahaan</th>
                    <th>Alamat</th>
                </tr>

            </thead>
            <tbody>
                @php
                    $no = 1;
                    $uniqueCustomers = [];
                @endphp
                @foreach ($orders as $order)
                    {{-- Cek apakah nama pelanggan sudah ditampilkan sebelumnya --}}
                    @php

                        $invoiceDate = Carbon\Carbon::parse($order['created_at'])->formatLocalized('%y%m%d');

                    @endphp
                    @if (in_array($order['name_customer'], $uniqueCustomers))
                        {{-- Jika sudah ditampilkan sebelumnya, lanjutkan ke iterasi berikutnya --}}
                        @continue
                    @endif
                    {{-- Jika belum pernah ditampilkan, tampilkan nama pelanggan --}}
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $order['name_customer'] }} <a href="{{ route('status.single', $order['user_id']) }}">
                                <div class="btn btn-success ml-3" style="margin-left:10px;">Detail</div>
                            </a></td>
                        {{-- <td>
                        <ol>
                            @foreach ($orders as $innerOrder)
                                Tampilkan pesanan hanya jika nama pelanggan sama dengan nama pelanggan di baris saat ini
                                @if ($innerOrder['name_customer'] == $order['name_customer'])
                                    @foreach ($innerOrder['products'] as $product)
                                        <li>{{ $product['name_product'] }} <small>Rp. {{ number_format($product['price'], 0, '.', ',') }}<b>(qty : {{ $product['qty'] }})</b></small> = Rp. {{ number_format($product['price_after_qty'], 0, '.', ',') }}</li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ol>
                    </td> --}}
                        @php
                            $ppn = $order['total_price'] * 0.1;
                        @endphp
                        <td>Rp. {{ number_format($order['total_price'] + $ppn, 0, '.', ',') }}</td>
                        <td>{{ $order['user']['name'] }} <a href="mailto:user@gmail.com">(user@gmail.com)</a></td>
                        <td>{{ $order->user->notelp }}</td>
                        <td>{{ $order->user->company }}</td>
                        @foreach ($order->user->address as $address)
                            <td>
                                <ol>
                                    <li>{{ $address['address'] }},{{ $address['city'] }}</li>
                                    <li>{{ $address['state'] }},{{ $address['country'] }}</li>
                                </ol>
                            </td>
                        @endforeach
                    </tr>
                    {{-- Tambahkan nama pelanggan ke dalam array uniqueCustomers --}}
                    @php $uniqueCustomers[] = $order['name_customer']; @endphp
                @endforeach
                {{-- @dd($orders) --}}
            </tbody>
        </table>
        <div class="d-flex justify-content-end mb-3">
            @if ($orders->count())
                {{ $orders->links() }}
            @endif
        </div>
    </div>
@endsection
