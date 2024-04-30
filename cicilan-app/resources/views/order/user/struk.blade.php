<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bukti Pembelian</title>
    <style>
        #back-wrap {
            margin: 30px auto 0 auto;
            width: 700px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-back {
            width: fit-content;
            padding: 8px 15px;
            color: #fff;
            background: #666;
            border-radius: 5px;
            text-decoration: none;
        }
        #receipt {
            box-shadow: 5px 10px 15px rgba(0, 0, 0, 0.5);
            padding: 20px;
            margin: 30px auto 0 auto;
            width: 700px;
            /* margin: 40px; */
            background: #FFF;
        }
        h2 {
            font-size: .9rem;
        }
        p {
            font-size: .8rem;
            color: #666;
            line-height: 1.2rem;
        }
        #top {
            margin-top: 25px;
        }
        #top .info {
            text-align: left;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px 0 5px 15px;
            border: 1px solid #EEE
        }
        .tabletitle {
            font-size: .5rem;
            background: #EEE;
        }
        .service {
            border-bottom: 1px solid #EEE;
        }
        .itemtext {
            font-size: .7rem;
        }
        #legalcopy {
            margin-top: 15px;
        }
        .btn-print {
            float: right;
            color: #333;
        }
    </style>
</head>

<body>
    <div id="back-wrap">
        <a href="{{ route('order.index') }}" class="btn-back">Kembali</a>
    </div>
    <div id="receipt">
    <a href="{{route('order.download-pdf',$order['id'])}}" class="btn-print">Cetak (.pdf)</a>
        <center id="top">
            <div class="info">
                <h2>Apotek Jaya Abadi</h2>
            </div>
        </center>
        <div id="mid">
            <div class="info">
                <p>
                    Alamat : sepanjang jalan kenangan</br>
                    Email : apotekjayaabadi@gmail.com</br>
                    Phone : 000-111-2222</br>
                </p>
            </div>
        </div>
        <div id="bot">
            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td class="item">
                            <h2>Produk</h2>
                        </td>
                        <td class="item">
                            <h2>Total</h2>
                        </td>
                        <td class="Rate">
                            <h2>Harga</h2>
                        </td>
                    </tr>
                    
                    @foreach ($order['products'] as $product)
                    <tr class="service">
                        <td class="tableitem">
                            <p class="itemtext">{{ $product['name_product'] }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">{{ $product['qty'] }}</p>
                        </td>
                        <td class="tableitem">
                            <p class="itemtext">Rp. {{ number_format($product['price'],0,',','.') }}</p>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="tabletitle">
                        <td></td>
                        <td class="Rate">
                            <h2>PPN (10%)</h2>
                        </td>
                        @php
                            $ppn = $order['total_price'] * 0.1;
                        @endphp
                        <td class="payment">
                            <h2>Rp. {{ number_format($ppn,0,',','.') }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td></td>
                        <td class="Rate">
                            <h2>Total Harga</h2>
                        </td>
                        <td class="payment">
                            @php
                            $totalBayar = $order['total_price'] + $ppn;
                       @endphp
                            <h2>Rp. {{ number_format($totalBayar,0,',','.') }}</h2>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="legalcopy">
                    <embed
                    src="http://infolab.stanford.edu/pub/papers/google.pdf#toolbar=0&navpanes=0&scrollbar=0"
                    type="application/pdf"
                    frameBorder="0"
                    scrolling="auto"
                    height="200%"
                    width="100%"
                    padding="1200px 0px"
                ></embed>
                <p class="legal">

                    <strong>Terima kasih atas pembelian Anda!</strong>  Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores natus et numquam ducimus dolorum tenetur.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
