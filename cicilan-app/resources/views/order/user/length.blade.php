@extends('layouts.template')

@section('content')
<style>
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
<div class="jumbotron  mt-2" style="padding:0px;">      
    <div class="container">
            {{-- @if(Session::get('failed'))
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
            $liveDate = $mytime->formatLocalized('02 Februari 2025 10:21');
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
            $liveInvoice = $mytime->formatLocalized('240807');

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
$ramType1= data_get($order['products'], '1.id',0);
$ramType2= data_get($order['products'], '2.id',0);
$ramType3= data_get($order['products'], '3.id',0);
$ramQty1= data_get($order['products'], '1.qty',0);
$ramPrice= data_get($order['products'], '1.price',1);
$dataProduct= data_get($order['products'], '0.label',0);
// dd($ramType3)
// dd();

@endphp


<hr>
  {{-- <input type="number" name="bandwidth" value="{{$ramType1}}" placeholder="siap" disabled>
  <input type="number" name="IP" value="{{$ramType2}}" placeholder="siapp" disabled>
  <input type="number" name="port" value="{{$ramType3}}" placeholder="siapp!" disabled>
  <hr>
  <input type="number" name="bandwidth_Qty" value="{{$ramQty1}}" placeholder="siapp!" disabled> --}}


        @if ($liveDate == $endMonth || $liveDate > $endMonth)
            <input type="number" name="vote_status" value="-2" hidden>
        @endif
        @foreach ($order->status as $orderStatus)
            @if ($orderStatus->access == 2)
                <input type="number" name="suspend" value="{{ $orderStatus->access }}">
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
                $secondProduct = $order->products[1] ?? null;
                @endphp
        @foreach ($order->status as $orderStatus)
        @if (
            $order['bulan'] == 4 && $orderStatus->access == 3 
            // $orderStatus->access == 3
            )
                    <button type="submit" class="btn btn-success">Bayar Cicilan</button>
                    
                    
                    {{-- Pemisah untuk user yangsudah mempunayai produk kedua --}}
                    
                @elseif (
                    $order['bulan'] == $order['votes'] ||
                    // $isColocation == true && $liveDate < $endMonth||
                    ($isColocation == true && $orderStatus->access == 1) ||
                    $orderStatus->access == 3 && $isColocation == true || $isColocation == true )
                <div class="mb-3" style="width: 100%; margin-left:30px;">
                  
                    <label style="width:50%;" for="name_product" class="col-sm-2 col-form-label">Pilih Paket
                        Collocation:</label>
                    <div class="col-sm-10">
                        <select id="name_product" class="form-control" name="name_product" >
                                <option value="{{ $existingProducts['id'] }}" data-months="{{ $order['bulan'] }}"
                                    data-price="{{ $existingProducts['price'] }}" data-access="{{ $orderStatus->access }}" value="{{$existingProducts['name_product']}}" selected >
                                    {{$existingProducts['name_product']}}
                                </option>
                        </select>
                    </div>
                </div>
                <div class="mb-3" id="months-container" style="width: 100%; margin-left:30px;">
                    @if($isColocation == true)
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
                            @for ($i = 1; $i <= 12; $i++)
                                @if ($i == 1 || $i == 3 || $i == 6)
                                    <option value="{{ $i }}">Rp. {{ number_format($existingProducts['price'] * $i, 0, '.', ',') }} - {{ $i }} Bulan  </option>
                                @elseif($i == 12)
                                <option value="{{ $i }}">Rp. {{ number_format($existingProducts['price'] * $i - 360000, 0, '.', ',') }} - {{ $i }} Bulan  </option>
                                @endif
                            @endfor
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Confirm</button>
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
            @elseif ($liveDate >= $endMonth && $orderStatus->access == 3)
                <button type="submit" class="btn btn-primary">Bayar Tunggakan Setelah Freeze</button>
            @elseif ($liveDate >= $endMonth && $orderStatus->access == 2)
                <button type="submit" class="btn btn-primary">Bayar Tunggakan Non konfirmasi</button>
            @else
                <button type="submit" class="btn btn-primary">Bayar Cicilan</button>
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
    <script>
        // Add JavaScript to dynamically update the months dropdown based on the selected product
        document.getElementById('name_product').addEventListener('change', function() {
            var selectedProduct = this.options[this.selectedIndex];
            var monthsContainer = document.getElementById('months-container');
            var monthsDropdown = document.getElementById('months');
            // var accessValue = selectedProduct.dataset.access;
            var accessValue = parseInt(selectedProduct.dataset.access);
            console.log('cuy',accessValue)


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
                if(accessValue == 3){
                for (var i = 1; i <= 6; i++) {
                    // if()
                    if (i == 1 && accessValue == 3 && months == 1 || i == 1 && accessValue == 3 && months == 2 || i == 1 && accessValue == 3 && months == 3 || i == 2 && accessValue == 3 && months == 1 || i == 2 && accessValue == 3 && months == 2 || i == 3 && accessValue == 3 && months == 1) {
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
                    else if (i == 1 && accessValue == 3 && months == 12 || i == 1 && accessValue == 3 && months == 24 || i == 2 && accessValue == 3 && months == 12 || i == 2 && accessValue == 3 && months == 24 || i == 3 && accessValue == 3 && months == 12 || i == 3 && accessValue == 3 && months == 24) {
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
                }}else{
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
                    }
                     else if (i == 1 && accessValue < 0 || i == 1 && accessValue > 5 || i == 3 && accessValue < 0  || i == 3 && accessValue > 5  || i == 6 && accessValue < 0 || i == 6 && accessValue > 5) {
                        var option = document.createElement('option');
                        option.value = i;
                        var formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(price * i);
                        option.text = formattedPrice + ' ' + i + ' bulan';
                        // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                        monthsDropdown.appendChild(option);
                    }
                     else if (i == 1 || i == 3 || i == 6 ) {
                        var option = document.createElement('option');
                        option.value = i;
                        var formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(price * i);
                        option.text = formattedPrice + ' ' + i + ' bulan';
                        // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                        monthsDropdown.appendChild(option);
                    }
                    else if(i == 12 && accessValue > 5 || i == 12 && accessValue < 0){
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
                    else if(i == 12 && accessValue > 3 || i == 12 && accessValue < 2){
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
@endpush