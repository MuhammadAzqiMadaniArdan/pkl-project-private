@extends('layouts.template')

@section('content')

    <form action="{{ route('status.update', $status2['id']) }}" method="post" class="card bg-light mt-5 p-5"
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
        @if (Session::get('success'))
            <br>
            @include('sweetalert::alert')
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        {{-- @php
        $mytime = Carbon\Carbon::now();
            $pauseDate = Carbon\Carbon::parse($order['created_at'])
                ->addDays(30 * $order['votes'])
                ->subDays(30)
                ->formatLocalized('%d %B %Y %H:%M');

            $liveInvoices = $mytime->formatLocalized('240403');
            $liveInvoice = $mytime->formatLocalized('240503');
            $liveInvoiced = $mytime->formatLocalized('%H');

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
            $endInvoice = $isFreeze
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
                        : Carbon\Carbon::parse($order['updated_at'])
                            ->addDays(30 * $order['votes'])
                            ->formatLocalized('%y%m%d')));

        @endphp --}}
        {{-- jika data ingin  --}}
        {{-- <p>{{$endInvoice}}</p> --}}
        @if ($status2['data'] < 4)
            <div class="mb-3 row" hidden>
                <label for="data" class="col-sm-2 col-form-label">Data :</label>
                <div class="col-sm-10">
                    <input hidden type="text" class="form-control" id="data" name="data"
                        value="{{ $status2['data'] }}">
                </div>
            </div>
        @endif
        @if ($status2['data'] >= 4)
            <div class="mb-3 row">
                <label for="access" class="col-sm-2 col-form-label">Suspend :</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="access" name="access" value="2" min="1"
                        max="3" required>
                </div>
            </div>
            @if ($status2['access'] == 2)
                <div class="mb-3 row" hidden>
                    <label for="freeze" class="col-sm-2 col-form-label">Freeze :</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="freeze" name="freeze" value="1"
                            min="1" max="3" required>
                    </div>
                </div>
            @else
                <div class="mb-3 row">
                    <label for="freeze" class="col-sm-2 col-form-label">Freeze :</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="freeze" name="freeze" value="1"
                            min="1" max="3" required>
                    </div>
                </div>
            @endif
            <div class="mb-3 row">
                <label for="terminated" class="col-sm-2 col-form-label">Terminated :</label>
                <div class="col-sm-10">
                    <input type="number" min="1" max="3" class="form-control" id="terminated"
                        name="terminated" value="1" required>
                </div>
            </div>
        @endif
        @if ($status2['data'] == 3)
            <input type="number" name="endData" id="endData" value="3" hidden>
            @if($status2->attachment == null)
            <div class="alert alert-danger mt-2 p-5 ">
                <h3 style="text-align: center;">File SPK User Belum Diupload !</h3>
            </div>
            <button type="submit" class="btn btn-primary mt-2" disabled>Confirm Status user</button>
            @else
            <div class="alert alert-success mt-2 p-5 ">
                <h3 style="text-align: center;">File SPK User Sudah Diupload!</h3>
            </div>
            <button type="submit" class="btn btn-primary mt-2 ">Confirm Status user</button>
            @endif
            <br>
            <form action="{{ route('status.update', $status2['id']) }}" method="post" class="card bg-light mt-5 p-5 mb-5"
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
                    @include('sweetalert::alert')
                @endif


                {{-- @if ($status2['data'] >= 4) --}}
                {{-- table inventory upload file --}}

                <table class="table table-bordered" id="dynamicUpload">
                    <tr>
                        <th>Upload (PDF & JPG requirements<span style="color: red">*</span>)</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>
                            @if($status2->attachment)
                            <input type="file" class="form-control" id="attachment" name="attachment[]" multiple>
                            @else
                            <input type="file" class="form-control" id="attachment" name="attachment[]" multiple required>
                            @endif

                        </td>
                        <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button>
                        </td>
                    </tr>
                </table>
                {{-- table inventory upload file --}}
                {{-- <div class="mb-3 row">
                <label for="attachment" class="col-sm-2 col-form-label">Upload File :</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="attachment" name="attachment[]" multiple>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="attachment" class="col-sm-2 col-form-label">Upload File :</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="attachment" name="attachment[]" multiple>
                </div> --}}
                <button type="submit" class="btn btn-success">Confirm File Upload</button>
                </div>
                {{-- @endif --}}
            </form>

            <div class="card bg-light mt-5 p-5 mb-5" style="height: 100%">
                {{-- sebagai-token-akses-database --}}

                <h3 style="text-align: center"><b>Preview File</b></h3><br>
                @if ($status2->attachment)
                    <br>
                    {{-- <div id="pdf-preview-container"></div> --}}
                    {{-- @dd($status2->attachment[1]) --}}
                    @foreach ($status2->attachment as $item)
                        <embed src={{ asset('attachments/' . $item) }} type="application/pdf" frameBorder="0"
                            scrolling="auto" height="900px" {{-- height="100%" --}} width="100%"
                            {{-- padding="1200px 0px" --}}></embed>
                        <a style="width: 50%;display:flex;justify-content:center;margin-left:26%;"
                            href="{{ asset('attachments/' . $item) }}" download="{{ $item }}"
                            class="btn btn-primary mb-2 mt-5" >
                            Download File
                        </a>
                        <br>
                        @endforeach
                        @else
                        <h2 style="text-align: center" class="btn btn-danger">
                            Belum ada File yang Diupload
                        </h2>
                        @endif
                        
                        
                    </div>
                    
        @elseif($status2['data'] < 4)
            {{-- <div class="card p-5 mt-5 mb-5" style="background-image:linear-gradient(to bottom,red,blue)"> --}}

            <div class="alert alert-danger mt-2 p-5 ">
                <h3 style="text-align: center;">SPK User Belum Lengkap !</h3>
            </div>


            <button type="submit" class="btn btn-primary mt-2">Confirm Status user</button>

        @endif

        @if ($status2['access'] == 3)
            <button type="button" class="btn btn-success" onclick="setUnfreezeStatus()">UnFreeze User</button>
        @endif

        @if ($status2['data'] >= 4)
            <div class="btn-group mt-3" role="group" aria-label="Status User">
                @if ($status2['access'] == 2 || $status2['access'] == 0)
                @else
                    <button type="button" class="btn btn-primary w-50" style="border-radius:5px"
                        onclick="setFreezeStatus()">Freeze User</button>
                @endif
                {{-- @if ($liveInvoice == $endInvoice) --}}
                {{-- <button type="button" class="btn btn-primary w-50" style="margin-left: 2%;border-radius:5px" onclick="suspendAfter()">Pembayaran Awal</button>
@else
<button type="button" class="btn btn-danger" onclick="setContinue()">Suspend User </button> --}}
                {{-- @endif --}}

                {{-- <p>{{$liveInvoice,$endInvoice}}</p> --}}
                {{-- @if ($status2['access'] == 2 && $liveInvoice == $endInvoice) --}}
                @if ($status2['access'] == 0)
                    <button type="button" class="btn btn-primary w-50" style="border-radius:5px;margin-left:20px"
                        onclick="setContinue()">Melanjutkan Pembayaran</button>
                    <button type="button" class="btn btn-primary w-50" style="margin-left: 2%;border-radius:5px"
                        onclick="suspendAfter()">Pembayaran Awal</button>
                @elseif($status2['access'] == 2)
                    @if ($status2['payment'] < 0)
                        <button type="button" class="btn btn-primary w-50" style="margin-left: 2%;border-radius:5px;"
                            onclick="setSuspendStatus()">Akses Awal</button>
                        {{-- <button type="button" class="btn btn-primary w-50" style="border-radius:5px;margin-left:20px" onclick="setContinue()">Akses Meneruskan</button> --}}
                    @elseif($status2['data'] > 4)
                        {{-- 
                <button type="button" class="btn btn-primary w-50" style="border-radius:5px;margin-left:20px" onclick="setContinue()">Melanjutkan Pembayaran</button> --}}
                        <button type="button" class="btn btn-primary w-50" style="margin-left: 2%;border-radius:5px"
                            onclick="suspendAfter()">Pembayaran AwalNya</button>
                    @else
                        {{-- <button type="button" class="btn btn-primary w-50" style="border-radius:5px;margin-left:20px" onclick="setContinue()">Melanjutkan Pembayaran</button> --}}
                        <button type="button" class="btn btn-primary w-50" style="margin-left: 2%;border-radius:5px"
                            onclick="setSuspendStatus()">Pembayaran Awal</button>
                    @endif
                @else
                    <button type="button" class="btn btn-primary w-50" style="border-radius:5px;margin-left:20px"
                        onclick="setTerminatedStatus()">Terminated User</button>

                    <button type="button" class="btn btn-primary w-50" style="border-radius:5px;margin-left:20px"
                        onclick="setSuspendFirst()">Suspend User</button>
                @endif
                {{-- @if ($status2['access'] == 3)
                <button type="button" class="btn btn-primary w-25" onclick="setUnfreezeStatus()">UnFreeze User</button>
                @endif --}}
                {{-- @if ($status2['payment'] > 0)
                @else --}}
                {{-- <button type="button" class="btn btn-primary w-50" style="margin-left: 2%;border-radius:5px;" onclick="setSuspendStatus()">Berikan akses dedicated</button>
                 --}}

                {{-- @endif --}}
                @if ($status2['access'] == 2)
                    <button type="button" class="btn btn-danger " style="border-radius:5px;margin-left:20px;"
                        onclick="afterFreeze()">Reset</button>
                @else
                    <button type="button" class="btn btn-danger " style="border-radius:5px;margin-left:20px;"
                        onclick="setResetStatus()">Reset</button>
                @endif
            </div>
            @if ($status2['payment'] < 1)
                {{-- @dd($server1) --}}
                    <div class="mb-3 mt-3">
                        <div class="d-flex align-items-center">
                           <label style="width:12%;" for="userName" class="form-label">User Sewa
                                :
                            </label>
            
                    <div class="container">
                        <select class="form-control form-select" id="livesearch" name="userName" style="color:black;"></select>
            
                    </div>
                    </div>

                </div>
            @else
            @endif
            <div class="mt-3">
                <button type="submit" class="btn btn-success w-100">Confirm Akses User</button>
            </div>
        @endif

    </form>
@endsection

@push('script')
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script src="https://mozilla.github.io/pdf.js/web/pdf_viewer.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.viewer.min.js"
        integrity="sha512-c1W7jRI9obk9S2IVIDDFRiIcy02IkqFG+smf2xjqbFdFjPLI9gK6rV1o2D4WuYQmJmrO9CQhQI7nrm0JdS1I4Q=="
        crossorigin="anonymous" defer></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var i = 0;

        $("#add-btn").click(function() {

            ++i;


            $("#dynamicUpload").append(
                '<tr><td><input type="file" class="form-control" id="attachment" name="attachment[]" multiple></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
            );
        });

        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".btn-success").click(function() {
                var html = $(".clone").html();
                $(".increment").after(html);
            });
            $("body").on("click", ".btn-danger", function() {
                $(this).parents(".control-group").remove();
            });
        });
    </script>
    <script>
        function setFreezeStatus() {
            document.getElementById('freeze').value = 2;
            document.getElementById('terminated').value = 1;
            document.getElementById('access').value = 2;

        }

        function setUnfreezeStatus() {
            document.getElementById('freeze').value = 3;
        }

        function setSuspendStatus() {
            document.getElementById('access').value = 1;
            document.getElementById('freeze').value = 1;
            document.getElementById('terminated').value = 1;
        }

        function suspendAfter() {
            document.getElementById('access').value = 1;
            document.getElementById('terminated').value = 1;
        }

        function setTerminatedStatus() {
            document.getElementById('terminated').value = 2;
            document.getElementById('freeze').value = 1;
            document.getElementById('access').value = 2;

        }

        function setContinue() {
            document.getElementById('terminated').value = 3;
            document.getElementById('freeze').value = 1;
        }

        function setResetStatus() {
            document.getElementById('access').value = 2;
            document.getElementById('freeze').value = 1;
            document.getElementById('terminated').value = 1;
        }

        function afterFreeze() {
            document.getElementById('access').value = 2;
            document.getElementById('terminated').value = 1;
        }

        function setSuspendFirst() {
            document.getElementById('access').value = 3;
            document.getElementById('terminated').value = 1;
            document.getElementById('freeze').value = 1;
        }
    </script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        let url = "{{ route('status.sewaSearch') }}";
        let placeholder = 'Select User';


        $('#livesearch').select2({
            placeholder: placeholder,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },

                cache: true
            }
        });
    </script>
@endpush
