<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HAPPY</title>
    <style>
        .align {
            text-align: center;
        }

        span {
            color: blue;
        }
        a{
            text-decoration: none;
            color: blue;
            font-family: sans-serif;
            font-weight: bold;
            font-size: 30px;
        }
    </style>
</head>

<body>
    @php
        $mytime = Carbon\Carbon::now();
        $nowWithoutSeconds = Carbon\Carbon::now()->format('Y-m-d H:i');
        $journalName = str_replace(' ', '+', $name_customer);

    @endphp
    <h1 style="text-align: center;background:black;color:white;padding:10px;">Lunas Reminder</h1>
    <hr>                   
    <div class="align">                                                    
    <h3>Pemberitahuan kepada Admin,<br>
        {{-- Berikut merupakan User dengan username </h3><a href="http://127.0.0.1:8000/status/single/{{$idUser}}">{{ $name_customer }}</a> --}}
        Berikut merupakan User dengan username </h3><a href="{{route('status.single',$idUser)}}">{{ $name_customer }}</a>
        <h3 style="color:green; ">Product : {{$name_product}}</h3>
        @if($sisaWaktu == "terlewati")
        <h3> dinyatakan Melebihi Waktu Lunas dan masih Berstatus Dedicated ({{$sisaWaktu}}).</h3>
        @else
        <h3> dinyatakan Akan Lunas Masa Dedicated dalam Waktu {{$sisaWaktu}}.</h3>
    @endif
    </br>
</div>  
    <h5>Terimakasih</h5><br>
    <h5>Tanggal Pengiriman : {{$nowWithoutSeconds}} ||</h5>
    <h5>Tanggal Akhir Bulan : {{$akhirBulan}}</h5>
    
</body>

</html>
