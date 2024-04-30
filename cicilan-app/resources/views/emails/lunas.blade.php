<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HAPPY</title>
    <style>
        h3 {
            text-align: center;
        }

        span {
            color: blue;
        }
    </style>
</head>

<body>
    @php
        $mytime = Carbon\Carbon::now();

    @endphp
    <h1 style="text-align: center;background:black;color:white;padding:10px;">Lunas Reminder</h1>
    <hr>                                                                         
    <h3>Pemberitahuan kepada Admin,<br>
        Berikut merupakan User dengan username <span>{{ $name_customer }}</span> dinyatakan Akan Lunas Masa Dedicated dalam Waktu Sebulan.</h3>
    
    </br>
    <h5>Terimakasih</h5>

</body>

</html>
