@extends('layouts.template')

@section('content')

    <form action="{{ route('status.update', $orders['id']) }}" method="post" class="card bg-light mt-5 p-5"
        enctype="multipart/form-data">
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
        @php
            $existingProducts = $orders['products'][0];
            if ($existingProducts['type'] == 'colocation') {
                $isColocation = true;
            } else {
                $isColocation = false;
            }

        @endphp

        @if ($status['access'] == 3)
            @php
                $lastProduct = last($orders['products']);
                if ($lastProduct['type'] == 'freeze') {
                    $secondProduct = $lastProduct;
                } else {
                    $secondProduct = false;
                }
                if ($orders['products'][0]['type'] == 'colocation') {
                    $isColocation = true;
                } else {
                    $isColocation = false;
                }
                // $secondProduct = $orders->products[1] ?? null;
                // dd($secondProduct);
            @endphp
            @if ($orders['bulan'] == 4)
            @else
                @foreach ($orders->status as $orderStatus)
                    <div class="mb-3" style="width: 100%; margin-left:30px;">
                        <label style="width:50%;" for="name_product" class="col-sm-2 col-form-label">Pilih Paket
                            @if ($secondProduct)
                                <span> Produk Freeze Ke-2: {{ $secondProduct['name_product'] }}</span>
                            @else
                                <span> Produk Freeze:</span>
                            @endif
                        </label>
                        <div class="col-sm-10">
                            <select id="name_product" class="form-control" name="name_product">
                                <option disabled hidden selected>Pilih</option>
                                @if ($secondProduct !== false)
                                    {{-- Jika pengguna sudah memiliki produk kedua, hanya tampilkan produk kedua --}}
                                    <option value="{{ $secondProduct['id'] }}" data-months="{{ $orders['bulan'] }}"
                                        data-price="{{ $secondProduct['price'] }}" data-access="{{ $orderStatus->access }}">
                                        {{ $secondProduct['name_product'] }}
                                    </option>
                                @else
                                    {{-- Loop melalui produk dedikasi --}}
                                    {{-- @foreach ($dedicatedProducts as $product) --}}
                                    <option value="1" data-months="{{ $orders['bulan'] }}" data-price="350000"
                                        data-access="{{ $orderStatus->access }}">
                                        Layanan Freeze Bogor
                                    </option>
                                    <option value="2" data-months="{{ $orders['bulan'] }}" data-price="750000"
                                        data-access="{{ $orderStatus->access }}">
                                        Layanan Freeze Jakarta
                                    </option>
                                    {{-- @endforeach --}}
                                @endif
                            </select>
                        </div>
                    </div>


                    {{-- </div>
            @if ($orderStatus->access == 3)
            <div class="mb-3" id="bulanSelect" style="width: 100%; margin-left:30px; ">
                <label style="width:50%;" for="name_product" class="col-sm-2 col-form-label">Pilih Bulan
                </label>
                <div class="col-sm-10">
                    <select id="bulanSelect" class="form-control" name="months">
                        <option value="1">1 bulan</option>
                        <option value="2">2 bulan</option>
                        <option value="3">3 bulan</option>
                    </select>
                </div>
            </div>
            @else --}}

                    <div class="mb-3 mt-2" id="months-container" style="width: 100%; margin-left:30px; display: none;">
                        @if ($isColocation == true)
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
                                {{-- @for ($i = 1; $i <= 12; $i++)
                                    @if ($i == 1 || $i == 3 || $i == 6 || $i == 12)
                                        <option value="{{ $i }}">{{ $i }} Bulan - Tulisan Admin</option>
                                    @endif
                                @endfor --}}
                            </select>
                        </div>
                        <div class="mt-2" style="width: 100%;">
                            <label style="width:50%;" for="name_product" class="col-sm-2 col-form-label">Input
                                @if ($secondProduct)
                                    <span> Price ke-2: {{ $secondProduct['name_product'] }}</span>
                                @else
                                    <span> Price :</span>
                                @endif
                            </label>
                            <div class="col-sm-10">
                                <input type="number" name="freezePrice" class="form-control form-input"
                                    placeholder="Freeze Price" required>
                            </div>
                        </div>
                    </div>


                    <hr>
                    {{-- @endif --}}
                @endforeach
            @endif
        @elseif (
            // $isColocation == true && $liveDate < $endMonth||
            ($isColocation == true && $status['access'] == 1) ||
                ($status['access'] == 3 && $isColocation == true) ||
                $isColocation == true)
            <div class="mb-3" style="width: 100%; margin-left:30px;">
                <label style="width:50%;" for="name_product" class="col-sm-2 col-form-label">Pilih Paket
                    Collocation:</label>
                <div class="col-sm-10">
                    <select id="name_product" class="form-control w-100" name="name_product">
                        <option disabled hidden selected>Pilih</option>
                        @if ($existingProducts['type'] == 'colocation')
                            @for ($i = 0; $i < 13; $i++)
                                {{-- ?<<<<<<<<<Pemiihan 1U datacenter --}}
                                @if (
                                    ($existingProducts['id'] == 6 && $i == 0) ||
                                        ($existingProducts['id'] == 6 && $i == 2) ||
                                        ($existingProducts['id'] == 10 && $i == 2) ||
                                        ($existingProducts['id'] == 10 && $i == 0))
                                    {{-- @dd($colocationProducts,$existingProducts ) --}}
                                    <option value="{{ $colocationProducts[$i]['id'] }}"
                                        data-months="{{ $orders['bulan'] }}"
                                        data-price="{{ $colocationProducts[$i]['price'] }}"
                                        data-access="{{ $status['access'] }}"
                                        data-type="{{ $colocationProducts[$i]['type'] }}">
                                        {{ $colocationProducts[$i]['name'] }}
                                    </option>
                                    {{-- Pemeihan 2U datacenter --}}
                                @elseif(
                                    ($existingProducts['id'] == 9 && $i == 1) ||
                                        ($existingProducts['id'] == 9 && $i == 3) ||
                                        ($existingProducts['id'] == 11 && $i == 1) ||
                                        ($existingProducts['id'] == 11 && $i == 3))
                                    <option value="{{ $colocationProducts[$i]['id'] }}"
                                        data-months="{{ $orders['bulan'] }}"
                                        data-price="{{ $colocationProducts[$i]['price'] }}"
                                        data-access="{{ $status['access'] }}"
                                        data-type="{{ $colocationProducts[$i]['type'] }}">
                                        {{ $colocationProducts[$i]['name'] }}
                                    </option>
                                @elseif($existingProducts['id'] == 12 && $i == 4)
                                    <option value="{{ $colocationProducts[$i]['id'] }}"
                                        data-months="{{ $orders['bulan'] }}"
                                        data-price="{{ $colocationProducts[$i]['price'] }}"
                                        data-access="{{ $status['access'] }}"
                                        data-type="{{ $colocationProducts[$i]['type'] }}">
                                        {{ $colocationProducts[$i]['name'] }}
                                    </option>
                                @endif
                            @endfor
                        @else
                            @foreach ($colocationProducts as $product)
                                <option value="{{ $product['id'] }}" data-months="{{ $orders['bulan'] }}"
                                    data-price="{{ $product['price'] }}" data-access="{{ $status['access'] }}"
                                    data-type="{{ $product['type'] }}">
                                    {{-- data-price="{{ $product['price'] }}" data-access="{{ $status['access'] }}" data-type="{{ $colocationProducts[$i]['type'] }}"> --}}
                                    {{ $product['name'] }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="mb-3" id="months-container" style="width: 100%; margin-left:30px; display: none;">
                <div class="col-sm-10">
                </div>

                <br>
                <div class="mb-3 row">
                    <label for="rack" class="form-label" style="width: 10%">Rack:</label>
                    <input type="text" name="rack" id="rack" class="form-control" style="width:80%">
                </div>
            </div>
        @else
            <div class="mb-3 row">
                <label for="custom_name" class="col-sm-2 col-form-label">Nama Produk :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="custom_name" name="custom_name"
                        value="{{ $orders['products'][1]['name_product'] }}" disabled>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="custom_price" class="col-sm-2 col-form-label">Price :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="custom_price" name="custom_price">
                </div>
            </div>
            <div class="mb-3 row" hidden>
                <label for="custom_qty" class="col-sm-2 col-form-label">Qty:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="custom_qty" name="custom_qty" value="1">
                </div>
            </div>
        @endif



        <div class="mt-3">
            <button type="submit" class="btn btn-success w-100">Confirm User</button>
        </div>

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
            console.log('cuy', accessValue)


            // Melakukan sesuatu dengan nilai 'data-access', misalnya menampilkannya di konsol
            console.log('Nilai data-access:', monthsDropdown);

            if (selectedProduct.getAttribute('data-months')) {
                monthsContainer.style.display = 'block';
                monthsDropdown.innerHTML = '';

                var months = parseInt(selectedProduct.getAttribute('data-months'));
                var akses = parseInt(selectedProduct.getAttribute('data-access'));
                var valueProduct = parseInt(selectedProduct.getAttribute('selectedValue'));

                //         document.addEventListener('DOMContentLoaded', function () {
                //     var selectedProductId = "{{ $orders['selected_product_id'] }}";
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
                if (accessValue == 3) {
                    for (var i = 1; i <= 6; i++) {
                        // if()
                        if (i == 1 && accessValue == 3 && months == 1 || i == 1 && accessValue == 3 && months ==
                            2 || i == 1 && accessValue == 3 && months == 3 || i == 2 && accessValue == 3 &&
                            months == 1 || i == 2 && accessValue == 3 && months == 2 || i == 3 && accessValue ==
                            3 && months == 1) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            // }).format((price - 200000) * i);
                            option.text = 'FREEZE ' + ' ' + i + ' bulan';
                            // option.text = 'FREEZE Rp.' + (price - 200000) * i + ' ' + i + ' bulan';
                            monthsDropdown.appendChild(option);
                        } else if (i == 1 && accessValue == 3 && months == 12 || i == 1 && accessValue == 3 &&
                            months == 24 || i == 2 && accessValue == 3 && months == 12 || i == 2 && accessValue ==
                            3 && months == 24 || i == 3 && accessValue == 3 && months == 12 || i == 3 &&
                            accessValue == 3 && months == 24) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            // }).format((price - 200000) * i);
                            option.text = 'FREEZE ' + ' ' + i + ' bulan';
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
                    }
                } else {
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
                        } else if (i == 1 && accessValue < 0 || i == 1 && accessValue > 5 || i == 3 && accessValue <
                            0 || i == 3 && accessValue > 5 || i == 6 && accessValue < 0 || i == 6 && accessValue > 5
                        ) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            option.text = formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                            monthsDropdown.appendChild(option);
                        } else if (i == 1 || i == 3 || i == 6) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(price * i);
                            option.text = formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                            monthsDropdown.appendChild(option);
                        } else if (i == 12 && accessValue > 5 || i == 12 && accessValue < 0) {
                            var option = document.createElement('option');
                            option.value = i;
                            var formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format((price * i - 360000));
                            option.text = formattedPrice + ' ' + i + ' bulan';
                            // option.text = 'Rp.' + price * i + ' ' + i + ' bulan + 500.000 IDR Biaya Setup';
                            monthsDropdown.appendChild(option);
                        } else if (i == 12 && accessValue > 3 || i == 12 && accessValue < 2) {
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
