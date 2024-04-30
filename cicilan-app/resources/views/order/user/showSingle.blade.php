@extends('layouts.template')

@section('content')
<style>
    .card{
        border-radius: 10px;
        background: whitesmoke;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 5); 
    }
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
            <h3><b>Lihat SPK</b> </h3>
            <p class="lead"><a href="/dashboard">Home</a>/<a href="{{route('order.index')}}">DataOrder</a>/<a href="#">LihatSPK</a></p>
        </div>
    </div>{{-- <h2 style="text-align: center">
    Surat SPK
</h2> --}}
        {{--sebagai-token-akses-database --}}
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
        
        
    <div class="card bg-light mt-5 p-5 mb-5" style="height: 100%">
        {{--sebagai-token-akses-database --}}
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
        
       
        @if ($status2->attachment)
        <h3 style="text-align: center"><b>Preview PDF SPK</b></h3>
        <br>
        {{-- <div id="pdf-preview-container"></div> --}}
        <embed
                    src={{ asset('attachments/' . $status2->attachment) }}
                    type="application/pdf"
                    frameBorder="0"
                    scrolling="auto"
                    {{-- height="900px" --}}
                    height="900px"
                    width="100%"
                    {{-- padding="1200px 0px" --}}
                ></embed>
                <a style="width: 50%;display:flex;justify-content:center;margin-left:26%;" href="{{ asset('attachments/' . $status2->attachment) }}" download="{{ $status2->attachment }}" class="btn btn-primary mb-2 mt-5">
                    Download PDF
                </a>
                <a style="width: 20%;display:flex;justify-content:center;margin-left:40%;" href="{{ route('order.index')}}" class="btn btn-success mb-5 mt-2">
                    Back
                </a>
        @else
        <h2 style="text-align: center" class="btn btn-danger mb-2">
            Belum ada File yang Diupload
        </h2>
    @endif
        
        </div>
@endsection
@push('script')
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<script src="https://mozilla.github.io/pdf.js/web/pdf_viewer.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.viewer.min.js" integrity="sha512-c1W7jRI9obk9S2IVIDDFRiIcy02IkqFG+smf2xjqbFdFjPLI9gK6rV1o2D4WuYQmJmrO9CQhQI7nrm0JdS1I4Q==" crossorigin="anonymous" defer></script>
    <script>
        
        function setFreezeStatus() {
            document.getElementById('freeze').value = 2;
        }
        function setUnfreezeStatus() {
            document.getElementById('freeze').value = 3;
        }
        function setSuspendStatus() {
            document.getElementById('freeze').value = 1;
        }
        
        
        document.addEventListener('DOMContentLoaded', function () {
            const pdfPreviewContainer = document.getElementById('pdf-preview-container');
            const pdfAttachment = "{{ asset('attachments/' . $status2->attachment) }}";

            // Tentukan ukuran tampilan pratinjau PDF
            const viewerContainer = document.createElement('div');
            viewerContainer.style.width = '100%';
            viewerContainer.style.height = '500px'; // Sesuaikan tinggi tampilan pratinjau

            pdfPreviewContainer.appendChild(viewerContainer);

            // Muat tampilan pratinjau PDF menggunakan PDF.js
            const loadingTask = pdfjsLib.getDocument(pdfAttachment);
            loadingTask.promise.then(function (pdfDocument) {
                const viewer = new pdfjsViewer.PDFViewer({
                    container: viewerContainer,
                });
                viewer.setDocument(pdfDocument);
            });
        });
    </script>
@endpush
