@extends('layouts.template')

@section('content')
    <style>
        table {
            background: whitesmoke;
            border-radius: 10px;
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

        svg {
            width: 10px;
        }

        .text-sm {
            margin-top: 10px;
        }

        .list-Tambah li {
            font-size: 10px;
            list-style: square;
        }
    </style>
    <form action="{{ route('order.store') }}" class="card p-4 mt-5" method="POST">

        @csrf

        <table id="cart" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th style="width:50%">Product</th>
                    <th style="width:10%">Price</th>
                    <th style="width:8%">Quantity</th>
                    <th style="width:22%" class="text-center">Subtotal</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>
            <tbody>

                <?php $total = 0; ?>
                <!-- by this code session get all product that user chose -->
                @if (session('cart'))
                    @foreach (session('cart') as $id => $details)
                        <?php $total += $details['price'] * $details['quantity']; ?>

                        <tr>
                            <td data-th="Product">
                                <div class="row">
                                    <div class="col-sm-3 hidden-xs">
                                        <img
                                            src="https://dihostingin.com/wp-content/uploads/2021/11/vcpu.png" width="100"
                                            height="100" class="img-responsive" /></div>
                                    <div class="col-sm-9">
                                        <h3 class="nomargin">{{ $details['name'] }}</h3>
                                        <h4 class="nomargin">{{ $details['type'] }} Server</h4>
                                        <div class="list-Tambah">
                                            @if ($details['type'] == 'dedicated')
                                                <ol>
                                                    <li class="nomargin">SSD SATA Tambahan Per TB (Sekali Bayar):
                                                        {{ $details['SATA']['qty'] }} x TB</li>
                                                    <li class="nomargin">SSD NVME Tambahan Per TB (Sekali Bayar):
                                                        {{ $details['NVME']['qty'] }} x TB</li>
                                                    <li class="nomargin">RAM DDR 4 Tambahan (Sekali Bayar):
                                                        {{ $details['ram']['name'] }}</li>
                                                    <li class="nomargin"> Pilih tempo hak kepemilikan server:
                                                        {{ $details['bulan'] }}</li>
                                                        
                                                <li class="nomargin">Port :
                                                    Local Speed up to 
{{ $details['port']['name'] }} Gbps</li>
                                                <li class="nomargin">IP :
                                                    5 IP Publik, subnet /
{{ $details['IP']['name'] }} </li>
                                                <li class="nomargin">Dedicated Bandwidth : 

                                                    {{ $details['bandwidth']['qty'] }}  Mbps</li>
                                                    <li class="nomargin">Datacenter: {{ $details['datacenter'] }}</li>
                                                    <li class="nomargin">Operating System: {{ $details['OS'] }}</li>
                                                </ol>
                                                <input type="text" name="SATA[]" value="{{ $details['SATA']['qty'] }}"
                                                    hidden>
                                                <input type="text" name="NVME[]" value="{{ $details['NVME']['qty'] }}"
                                                    hidden>
                                                <input type="text" name="ram[]" value="{{ $details['ram']['name'] }}"
                                                    hidden>
                                                <input type="text" name="bulan[]" value="{{ $details['bulan'] }}"
                                                    hidden>
                                                <input type="text" name="datacenter[]"
                                                    value="{{ $details['datacenter'] }}" hidden>
                                                <input type="text" name="oS[]" value="{{ $details['OS'] }}" hidden>
                                                <input type="text" name="port[]" value="{{ $details['port']['name'] }}"
                                                    hidden>
                                                <input type="text" name="IP[]" value="{{ $details['IP']['name'] }}"
                                                    hidden>
                                                <input type="text" name="bandwidth[]" value="{{ $details['bandwidth']['qty'] }}"
                                                    hidden>
                                            @else
                                            <ol>
                                                {{-- @dd($details['port']['name']) --}}

                                                <li class="nomargin">Port :
                                                    {{ $details['port']['name'] }} </li>
                                                <li class="nomargin">IP :
                                                    {{ $details['IP']['name'] }} IP</li>
                                                <li class="nomargin">Dedicated Bandwidth :
                                                    {{ $details['bandwidth']['qty'] }} X Mbps</li>
                                                <li class="nomargin"> Sewa Colocation :
                                                    {{ $details['bulan'] }} Bulan</li>
                                                <li class="nomargin"> Label Name :
                                                    {{ $details['label'] }} </li>
                                            </ol>
                                            <input type="text" name="port[]" value="{{ $details['port']['name'] }}"
                                                    hidden>
                                                <input type="text" name="IP[]" value="{{ $details['IP']['name'] }}"
                                                    hidden>
                                                <input type="text" name="bandwidth[]" value="{{ $details['bandwidth']['qty'] }}"
                                                    hidden>
                                                <input type="text" name="bulan[]" value="{{ $details['bulan'] }}"
                                                    hidden>
                                                <input type="text" name="label[]" value="{{ $details['label'] }}"
                                                    hidden>
                                               
                                            @endif
                                        </div>
                                        <input type="text" name="name_customer" id="name_customer" class="form-control"
                                            style="width:88%" value="{{ Auth::user()->name }}" hidden>
                                        <input type="text" value="{{ $id }}" class="form-control"
                                            name="products[]" hidden />
                                        @if (Auth::user()->company == null)
                                            <input type="text" name="company" id="company" class="form-control"
                                                style="width:88%" value="{{ Auth::user()->company }}"
                                                placeholder="            Tidak Ada Keterangan Perusahaan 
                                        "
                                                disabled>
                                        @else
                                            <input type="text" name="company" id="company" class="form-control"
                                                style="width:88%" value="{{ Auth::user()->company }} Company" disabled>
                                        @endif
                                        <div class="mb-3 row" hidden>
                                            <label for="votes" class="form-label" style="width: 12%">Votes:</label>
                                            <input type="text" name="votes" id="votes" class="form-control"
                                                style="width:88%" value="1">
                                        </div>
                                        <div class="mb-3 row" hidden>
                                            <label for="data" class="form-label" style="width: 12%">data:</label>
                                            <input type="text" name="data" id="data" class="form-control"
                                                style="width:88%" value="1">
                                        </div>
                                        <div class="mb-3 row" hidden>
                                            <label for="status" class="form-label" style="width: 12%">Status:</label>
                                            <input type="text" name="status" id="status" class="form-control"
                                                style="width:88%" value="proses">
                                        </div>
                                        <div class="mb-3 row" hidden>
                                            <label for="access" class="form-label" style="width: 12%">Access:</label>
                                            <input type="text" name="access" id="access" class="form-control"
                                                style="width:88%" value="1">
                                        </div>

                                        @php
                                            // the point is session cart
                                            // for looping the session into new ERA
                                            // dd(count(session('cart')));
                                            // for see a spesificant for add a cart in id
                                            // dd(session('cart')[$id]);
                                        @endphp
                                    </div>
                                </div>
                            </td>
                            @php
                            $priceEnd = 0;
                                $priceEnd += session('cart')[$id]['price_after_qty'];
                                if($details['type'] == 'dedicated'){
                                    $priceEnd += session('cart')[$id]['SATA']['price_after_qty'];
                                $priceEnd += session('cart')[$id]['NVME']['price_after_qty'];
                                }
                               
                                $subTotal = $details['price'] * $details['quantity'];
                            @endphp
                            <td data-th="Price">Rp {{ number_format($priceEnd, 0, '.', ',') }} IDR</td>
                            {{-- <td data-th="Price">${{ $details['price'] }}</td> --}}
                            <td data-th="Quantity">
                                <input type="number" value="{{ $details['quantity'] }}"
                                    class="form-control quantity" />
                            </td>
                            <td data-th="Subtotal" class="text-center">Rp {{ number_format($subTotal, 0, '.', ',') }} IDR
                            </td>
                            <td class="actions" data-th="">
                                <!-- this button is to update card -->
                                <button class="btn btn-info btn-sm update-cart" data-id="{{ $id }}"><i
                                        class="fa fa-refresh"></i></button>
                                <!-- this button is for update card -->
                                <button class="btn btn-danger btn-sm remove-from-cart delete"
                                    data-id="{{ $id }}"><i class="fa fa-trash-o"></i>delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>
            <tfoot>

                <tr>
                    <td><a href="{{ url('/order') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue
                            Shopping</a></td>
                    <td colspan="2" class="hidden-xs"></td>
                    <td class="hidden-xs text-center"><strong>Total ${{ $total }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <button type="submit" class="btn btn-primary">CheckOut</button>

    </form>
@endsection


@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        // this function is for update card
        $(".update-cart").click(function(e) {
            e.preventDefault();

            var ele = $(this);

            $.ajax({
                url: '{{ url('order/update-cart') }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele.attr("data-id"),
                    quantity: ele.parents("tr").find(".quantity").val()
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        });

        $(".remove-from-cart").click(function(e) {
            e.preventDefault();

            var ele = $(this);

            if (confirm("Are you sure")) {
                $.ajax({
                    url: '{{ url('order/remove-from-cart') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.attr("data-id")
                    },
                    success: function(response) {
                        window.location.reload();

                    }
                });
            }
        });
    </script>
@endpush
