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
        
       
        <h3 style="text-align: center"><b>Preview File</b></h3><br>
        @if ($status2->attachment)
        <br>
        {{-- <div id="pdf-preview-container"></div> --}}
        {{-- @dd($status2->attachment[1]) --}}
        @foreach ($status2->attachment as $item)
            
        <embed
        src={{ asset('attachments/' . $item) }}
                    type="application/pdf"
                    frameBorder="0"
                    scrolling="auto"
                    height="900px"
                    {{-- height="100%" --}}
                    width="100%"
                    {{-- padding="1200px 0px" --}}
                ></embed>
                {{-- <a style="width: 50%;display:flex;justify-content:center;margin-left:26%;" href="{{ asset('attachments/' . $status2->attachment) }}" download="{{ $status2->attachment }}" class="btn btn-primary mb-2 mt-5">
                    Download File
                </a> --}}
                <a style="width: 50%;display:flex;justify-content:center;margin-left:26%;" href="{{ asset('attachments/' . $item) }}" download="{{ $item }}" class="btn btn-primary mb-2 mt-5">
                    Download File
                </a>
                <br>
                @endforeach
                <a style="width: 20%;display:flex;justify-content:center;margin-left:40%;" href="{{ route('status.index')}}" class="btn btn-success mb-5 mt-2">
                    Back
                </a>
        @else
        <h2 style="text-align: center" class="btn btn-danger">
            Belum ada File yang Diupload
        </h2>
        <a style="width: 20%;margin-left:40%;" href="{{ route('detail_server.data')}}" class="btn btn-success mt-3">
                    Back
                </a>
    @endif
        
        
        </div>
@endsection
@push('script')
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<script src="https://mozilla.github.io/pdf.js/web/pdf_viewer.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.viewer.min.js" integrity="sha512-c1W7jRI9obk9S2IVIDDFRiIcy02IkqFG+smf2xjqbFdFjPLI9gK6rV1o2D4WuYQmJmrO9CQhQI7nrm0JdS1I4Q==" crossorigin="anonymous" defer></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
      $(".btn-success").click(function(){ 
          var html = $(".clone").html();
          $(".increment").after(html);
      });
      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".control-group").remove();
      });
    });
</script>
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
        
        
    </script>
@endpush
