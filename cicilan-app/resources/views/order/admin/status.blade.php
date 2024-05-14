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

        <hr>
        @if ($status2['data'] < 4)
            <div class="mb-3 row" hidden>
                <label for="data" class="col-sm-2 col-form-label">Data :</label>
                <div class="col-sm-10">
                    <input hidden type="text" class="form-control" id="data" name="data"
                        value="{{ $status2['data'] }}">
                </div>
            </div>
        @endif
        
        @if ($status2['data'] == 3)
            <input type="number" name="endData" id="endData" value="3" hidden>
            @if ($status2->attachment == null)
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
                            @if ($status2->attachment)
                                <input type="file" class="form-control" id="attachment" name="attachment[]" multiple>
                            @else
                                <input type="file" class="form-control" id="attachment" name="attachment[]" multiple
                                    required>
                            @endif

                        </td>
                        <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button>
                        </td>
                    </tr>
                </table>
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
                    {{-- @dd($item) --}}
                        <embed src={{ asset('attachments/' . $item) }} type="application/pdf" frameBorder="0"
                            scrolling="auto" height="900px" {{-- height="100%" --}} width="100%"
                            {{-- padding="1200px 0px" --}}></embed>
                        <a style="width: 50%;display:flex;justify-content:center;margin-left:26%;"
                            href="{{ asset('attachments/' . $item) }}" download="{{ $item }}"
                            class="btn btn-primary mb-2 mt-5">
                            Download File
                        </a>
                        <br>
                    @endforeach
                @else
                    <h2 style="text-align: center" class="btn btn-danger">
                        Belum ada File yang Diupload
                    </h2>
                @endif
            @else
                <button class="btn btn-success w-100" type="submit">Confirm SPK User </button>
            @endif
        </div>
        
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
