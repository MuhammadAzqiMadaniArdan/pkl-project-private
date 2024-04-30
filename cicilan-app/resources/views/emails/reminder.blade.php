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

        a{
            text-decoration: none;
        }
        body{
            color: black;
        }
    </style>
</head>

<body>
    @php
                            setLocale(LC_ALL, 'IND');

    $mytime = Carbon\Carbon::now();
    $liveDate = $mytime->formatLocalized('%d %B %Y');

@endphp
    <h1>{{ $mailData['title'] }}
        <br>
    <h5>Legalisasi App || {{$liveDate}}</h5>
</h1>
    <hr>
    <h3>(Reminder) Kepada Admin Terhormat,
    <br>
        Berikut merupakan User dengan username <span><b><a href="http://127.0.0.1:8000/status/single/13">{{ $mailData['body'] }}</a></b></span> Dinyatakan Sudah Jatuh
        Invoice.</h3>
    <br>
    <h5>Sekian Informasi Perihal Pengingat ,Terima Kasih </h5>

</body>

</html>
