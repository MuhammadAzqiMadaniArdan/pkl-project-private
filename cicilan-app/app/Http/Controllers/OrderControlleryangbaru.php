<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use App\Models\Product;
use App\Models\Order_status;
use Carbon\Carbon;

use PDF;
use App\Exports\OrderExport;
use Excel;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Excel as ExcelExcel;

use App\Mail\LunasMail;
use App\Mail\InvoiceMail;

use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    /**
     * Display a listing of tche resource.
     * 
     */


    //

    public function index()
    {
        //With : mengambil fungsi relasi PK ke Fk atau FK ke PK dari model 
        // Isi dipetik disamakan dengan nama functionnya  di modelnya
        // $orders = Order::with('user')->simplePaginate(5);
        $perPage = request('perPage', 100); // Default to 10 items per page
        $orders = Order::simplePaginate($perPage);
        $selectedProduct1 = Product::find(13);
        $selectedProduct2 = Product::find(14);
        $selectedProduct3 = Product::find(15);
        // dd ($orders) 
        return view('order.user.index', compact('orders', 'selectedProduct1', 'selectedProduct2', 'selectedProduct3',));
    }

    public function pengiriman($id)
    {
        $order = Order::find($id);

        $mailData = [
            'title' => 'Pemberitahuan Lunas',
            'body' => $order->name_customer,
        ];

        Mail::to('muhammadazqi098@gmail.com')->send(new LunasMail($mailData));

        // dd('Email send successfully.');

        return redirect()->route('order.index');
    }

    public function data()
    {
        $order_statuses = Order_status::OrderBy('id','ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(100);
        
        // dd ($orders)

        $perPage = request('perPage', 100); // Default to 10 items per page
        $orders = Order::simplePaginate($perPage);
        $selectedProduct1 = Product::find(13);
        $selectedProduct2 = Product::find(14);
        $selectedProduct3 = Product::find(15);
        return view('order.admin.index',compact('order_statuses','orders'));
    }
    // public function status() 
    // {
    //     $status1 = Order::with('user')->simplePaginate(5);
    //     return view('order.admin.status', compact('status1'));
    // }

    // public function downloadExcel() {
    //     // nama file excel ketika di download
    //     $file_name = 'Data Seluruh Pembelian.xlsx';
    //     // paggil logic exports nya
    //     return Excel::download(new OrderExport, $file_name);
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // $products = Product::all();
        $products = Product::orderBy('type', 'ASC')->paginate(100);
        $bulan = Order::all();
        return view('order.user.create', compact('products', 'bulan'));
    }

    public function colocation()
    {
        //
        // $products = Product::all();
        $products = Product::orderBy('type', 'ASC')->paginate(100);
        $bulan = Order::all();
        return view('order.user.colocation', compact('products', 'bulan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name_customer' => 'required',
            'products' => 'required',
            'bulan' => 'required',
            'votes' => 'required',
            'data' => 'required',
            'status' => 'required',
            'access' => 'nullable',
            // 'no_telp' => 'required',
            // 'address' => 'required',
            'company' => 'nullable', // Menjadikan input company sebagai field yang opsional
            'label_product' => 'nullable', // Menjadikan input company sebagai field yang opsional
            'custom_ram' => 'numeric|max:512', // Menjadikan input company sebagai field yang opsional


        ]);

        // hasilnya berbentuk : "itemnya" => "jumlah yang sama"
        // menentutak quantity (qty)

        
        $products = array_count_values($request->products);
        dd([$request->SATA]);
        // $ulu = 1;
        // if($ulu < count(session('cart'))){
        $tanda = 0;
        for($ulu = 0;$ulu < count(session('cart'));$ulu++){
            

        // dd(count(session('cart')));
        // penampung detail array berbentuk array 2 assoc dari data data yang dipilih
        $dataProducts = [];
        $keyID = [];
        // foreach ($products as $key => $value) {
            
            // }
            // dd(count(session('cart')),$keyID);
            foreach ($products as $key => $value) {
                $product = Product::where('id', $key)->first();
                $tanda = $tanda + 1;
                array_push($keyID, $key);
            if ($product['type'] == 'colocation') {
                if($request->bulan == 12){
                    $arrayAssoc = [
                        "id" => $key,
                        "label" => $request->filled('label_product') ? $request->label_product : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                        "name_product" => $product['name'],
                        "type" => $product['type'],
                        "price" => $product['price'],
                        "qty" => $value,
                        //(int) memastikan dan mengubah tipe data menjadi integer
                        "price_after_qty" => (int)$request->bulan * (int)$product['price'] - 360000,
    
                        // "price_after_qty" => (int)$value * (int)$product['price'],
                    ];
                }
                else{
                    $arrayAssoc = [
                        "id" => $key,
                        "label" => $request->filled('label_product') ? $request->label_product : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                        "name_product" => $product['name'],
                        "type" => $product['type'],
                        "price" => $product['price'],
                        "qty" => $value,
                        //(int) memastikan dan mengubah tipe data menjadi integer
                        "price_after_qty" => (int)$request->bulan * (int)$product['price'],
    
                        // "price_after_qty" => (int)$value * (int)$product['price'],
                    ];
                }
                
            } 
            else {
                $arrayAssoc = [
                    "id" => $key,
                    "name_product" => $product['name'],
                    "type" => $product['type'],
                    "price" => $product['price'],
                    "qty" => $value,
                    //(int) memastikan dan mengubah tipe data menjadi integer
                    "price_after_qty" => (int)$value * (int)$product['price'],
                    // "price_after_qty" => (int)$value * (int)$product['price'],
                ];
            }

            // format assoc dimasukkan ke array penampung sebelumnya


            // if($ulu < count(session('cart')) - 1){


                $end = $tanda - $ulu;

                if($end > $ulu){
                    $tanda = $end;
                    array_push($dataProducts, $arrayAssoc);
                    break;
                }
                elseif($end == $ulu){
                }else{

                }
                // elseif($end < $ulu)
            


        }

        // var total price awalnya 0
        $totalPrice = 0;
        $votes = 1;
        // loop data dari array penamoung yg sudah di format
        foreach ($dataProducts as $formatArray) {
            // dia bakal menambahkan  totalPrice sebelumnya ditambah data harga dari price_after_qty
            $totalPrice += (int)$formatArray['price_after_qty'];
        }
        if ($request->bulan == 12 && $arrayAssoc['type'] == 'dedicated') {
            // Tambahkan 300.000 ke total harga jika bulan adalah 12
            $totalPrice += 300000;
        }
        
        $defaultRam = 128;
        if ($request->ram == "custom") {
            $qty = 0;
            $ramPrice = 0;
            $ramAssoc = [
                "name_product" => "Additional RAM (" . $request->custom_ram . " GB )",
                "type" => "ram",
                "price" => $ramPrice,
                "qty" => $qty,
                "price_after_qty" => (int)$qty * (int)$ramPrice,
            ];
            array_push($dataProducts, $ramAssoc);
        } elseif ($request->ram > 0) {
            if ($request->ram == 32) {
                $ramId = '16';
                $qty = 1;
                $ram = Product::where('id', $ramId)->first();
                $ramAssoc = [
                    "id" => $ramId,
                    "name_product" => $defaultRam . ' GB + ' . $ram['name'],
                    "type" => $ram['type'],
                    "price" => $ram['price'],
                    "qty" => $qty,
                    "price_after_qty" => (int)$qty * (int)$ram['price'],
                ];
                array_push($dataProducts, $ramAssoc);
            } elseif ($request->ram == 64) {
                $ramId = '17';
                $qty = 1;
                $ram = Product::where('id', $ramId)->first();
                $ramAssoc = [
                    "id" => $ramId,
                    "name_product" => $defaultRam . ' GB + ' . $ram['name'],
                    "type" => $ram['type'],
                    "price" => $ram['price'],
                    "qty" => $qty,
                    "price_after_qty" => (int)$qty * (int)$ram['price'],
                ];
                array_push($dataProducts, $ramAssoc);
            }

            $totalPrice += $ram['price'];
        }


        if ($request->SATA > 0) {
            $ssdId = '18';
            $qty = $request->SATA;
            $ssdSata = Product::where('id', $ssdId)->first();
            $ssdSataAssoc = [
                "id" => $ssdId,
                "name_product" => $ssdSata['name'],
                "type" => $ssdSata['type'],
                "price" => $ssdSata['price'],
                "qty" => $qty,
                "price_after_qty" => (int)$qty * (int)$ssdSata['price'],
            ];
            array_push($dataProducts, $ssdSataAssoc);

            $totalPrice += $ssdSataAssoc['price_after_qty'];
        }
        if ($request->NVME > 0) {
            $ssdId = '19';
            $qty = $request->NVME;
            $ssdNvme = Product::where('id', $ssdId)->first();
            $ssdNvmeAssoc = [
                "id" => $ssdId,
                "name_product" => $ssdNvme['name'],
                "type" => $ssdNvme['type'],
                "price" => $ssdNvme['price'],
                "qty" => $qty,
                "price_after_qty" => (int)$qty * (int)$ssdNvme['price'],
            ];
            array_push($dataProducts, $ssdNvmeAssoc);

            $totalPrice += $ssdNvmeAssoc['price_after_qty'];
        }
        if ($request->datacenter == 'Jakarta') {
            $totalPrice += 750000;
        }
        if ($request->bandwidth > 0) {
            $bandwidthId = '21';
            $qty = $request->bandwidth;
            $bandwidth = Product::where('id', $bandwidthId)->first();
            if ($request->bulan == 1) {

                $bandwidthAssoc = [
                    "id" => $bandwidthId,
                    "name_product" => $bandwidth['name'],
                    "type" => $bandwidth['type'],
                    "price" => $bandwidth['price'],
                    "qty" => $qty,
                    "price_after_qty" => (int)$qty * (int)$bandwidth['price'],
                ];
                array_push($dataProducts, $bandwidthAssoc);
            } elseif ($request->bulan == 3) {
                $bandwidth3 = $bandwidth['price'] * 3;
                $bandwidthAssoc = [
                    "id" => $bandwidthId,
                    "name_product" => $bandwidth['name'],
                    "type" => $bandwidth['type'],
                    "price" => $bandwidth['price'] * 3,
                    "qty" => $qty,
                    "price_after_qty" => (int)$qty * $bandwidth3,
                ];
                array_push($dataProducts, $bandwidthAssoc);
            } elseif ($request->bulan == 6) {
                $bandwidth6 = $bandwidth['price'] * 6;
                $bandwidthAssoc = [
                    "id" => $bandwidthId,
                    "name_product" => $bandwidth['name'],
                    "type" => $bandwidth['type'],
                    "price" => $bandwidth['price'] * 6,
                    "qty" => $qty,
                    "price_after_qty" => (int)$qty * $bandwidth6,
                ];
                array_push($dataProducts, $bandwidthAssoc);
            } else {
                $bandwidth12 = ($bandwidth['price'] * 12) - 120000;
                $bandwidthAssoc = [
                    "id" => $bandwidthId,
                    "name_product" => $bandwidth['name'],
                    "type" => $bandwidth['type'],
                    "price" => $bandwidth12,
                    "qty" => $qty,
                    "price_after_qty" => (int)$qty * $bandwidth12,
                ];
                array_push($dataProducts, $bandwidthAssoc);
            }
            $totalPrice += $bandwidthAssoc['price_after_qty'];
        }
        if ($request->IP == 29) {
            $IP0Id = '22';
            $qty = 1;
            $IP0 = Product::where('id', $IP0Id)->first();

            $IPAssoc = [
                "id" => $IP0Id,
                "name_product" => $IP0['name'],
                "type" => $IP0['type'],
                "price" => $IP0['price'],
                "qty" => $qty,
                "price_after_qty" => (int)$qty * $IP0['price'],
            ];
            array_push($dataProducts, $IPAssoc);
        } elseif ($request->IP < 29 && $request->IP > 23) {
            $IP1Id = '23';
            $IP2Id = '24';
            $IP3Id = '25';
            $qty = 1;
            $IP1 = Product::where('id', $IP1Id)->first();
            $IP2 = Product::where('id', $IP2Id)->first();
            $IP3 = Product::where('id', $IP3Id)->first();
            $IP12 = ($IP1['price'] * 12) - 120000;



            if ($request->IP == 28) {

                $ipPrice = ($IP1['price'] * $request->bulan);


                if ($request->bulan == 1) {

                    $IPAssoc = [
                        "id" => $IP1Id,
                        "name_product" => $IP1['name'],
                        "type" => $IP1['type'],
                        "price" => $IP1['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } elseif ($request->bulan == 3) {
                    $IPAssoc = [
                        "id" => $IP1Id,
                        "name_product" => $IP1['name'],
                        "type" => $IP1['type'],
                        "price" => $IP1['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } elseif ($request->bulan == 6) {
                    $IPAssoc = [
                        "id" => $IP1Id,
                        "name_product" => $IP1['name'],
                        "type" => $IP1['type'],
                        "price" => $IP1['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } else {
                    $IPAssoc = [
                        "id" => $IP1Id,
                        "name_product" => $IP1['name'],
                        "type" => $IP1['type'],
                        "price" => $IP1['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                }
            } elseif ($request->IP == 27) {

                $ipPrice = ($IP2['price'] * $request->bulan);

                if ($request->bulan == 1) {

                    $IPAssoc = [
                        "id" => $IP2Id,
                        "name_product" => $IP2['name'],
                        "type" => $IP2['type'],
                        "price" => $IP2['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } elseif ($request->bulan == 3) {
                    $IPAssoc = [
                        "id" => $IP2Id,
                        "name_product" => $IP2['name'],
                        "type" => $IP2['type'],
                        "price" => $IP2['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } elseif ($request->bulan == 6) {
                    $IPAssoc = [
                        "id" => $IP2Id,
                        "name_product" => $IP2['name'],
                        "type" => $IP2['type'],
                        "price" => $IP2['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } else {
                    $IPAssoc = [
                        "id" => $IP2Id,
                        "name_product" => $IP2['name'],
                        "type" => $IP2['type'],
                        "price" => $IP2['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                }
            } elseif ($request->IP == 24) {
                $ipPrice = ($IP3['price'] * $request->bulan);

                if ($request->bulan == 1) {

                    $IPAssoc = [
                        "id" => $IP3Id,
                        "name_product" => $IP3['name'],
                        "type" => $IP3['type'],
                        "price" => $IP3['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } elseif ($request->bulan == 3) {
                    $IPAssoc = [
                        "id" => $IP3Id,
                        "name_product" => $IP3['name'],
                        "type" => $IP3['type'],
                        "price" => $IP3['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } elseif ($request->bulan == 6) {
                    $IPAssoc = [
                        "id" => $IP3Id,
                        "name_product" => $IP3['name'],
                        "type" => $IP3['type'],
                        "price" => $IP3['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                } else {
                    $IPAssoc = [
                        "id" => $IP3Id,
                        "name_product" => $IP3['name'],
                        "type" => $IP3['type'],
                        "price" => $IP3['price'],
                        "qty" => $qty,
                        "price_after_qty" => (int)$qty * $ipPrice,
                    ];
                    array_push($dataProducts, $IPAssoc);
                }
            }
            $totalPrice += $IPAssoc['price_after_qty'];
        }
        if ($request->port == 1) {
            $portId = '26';
            $qty = 1;
            $port = Product::where('id', $portId)->first();

            $portAssoc = [
                "id" => $portId,
                "name_product" => $port['name'],
                "type" => $port['type'],
                "price" => $port['price'],
                "qty" => $qty,
                "price_after_qty" => (int)$qty * $port['price']
            ];
            array_push($dataProducts, $portAssoc);
        } elseif ($request->port == 10) {
            $portId = '27';
            $qty = 1;
            $port = Product::where('id', $portId)->first();

            $portAssoc = [
                "id" => $portId,
                "name_product" => $port['name'],
                "type" => $port['type'],
                "price" => $port['price'],
                "qty" => $qty,
                "price_after_qty" => (int)$qty * $port['price'],
            ];
            array_push($dataProducts, $portAssoc);
        }

        $additionalCost = 500000;
        $selectedColocationProductId = $request->input('products');
        $productColocation = Product::find($selectedColocationProductId);

        // dd($productColocation);
        if ($productColocation) {

            // $typeColocation = $productColocation->type;
            if ($arrayAssoc['type'] == 'colocation') {
                // Tipe produk adalah colocation, lakukan proses tambah data

                $prosesTambahData = Order::create([
                    'name_customer' => $request->name_customer,
                    'products' => $dataProducts,
                    'total_price' => $totalPrice + $additionalCost,
                    'votes' => $request->bulan,
                    'bulan' => $request->bulan,
                    // 'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null

                    // user id menyimpan data id dari orang yang login user penanggung jawab
                    'user_id' => Auth::user()->id,
                ]);
                Order_status::create([
                    'order_id' => $prosesTambahData->id,
                    'data' => $request->data,
                    'status' => $request->status,
                    'access' => $request->filled('access') ? $request->access : null,
                ]);
            } else {

                $prosesTambahData = Order::create([
                    'name_customer' => $request->name_customer,
                    'products' => $dataProducts,
                    'total_price' => $totalPrice,
                    'votes' => $request->votes,
                    'bulan' => $request->bulan,
                    // 'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null

                    // user id menyimpan data id dari orang yang login user penanggung jawab
                    'user_id' => Auth::user()->id,
                ]);
                Order_status::create([
                    'order_id' => $prosesTambahData->id,
                    'data' => $request->data,
                    'status' => $request->status,
                    'access' => $request->filled('access') ? $request->access : null,
                ]);
            }
        }
    }

        // dd($dataProducts);

        // redirect ke halaman login
        // if($ulu >= count(session('cart'))){
            return redirect()->route('order.index', $prosesTambahData->id);
        // }

    // }
}



    /**
     * Display the specified resource.
     */
    public function strukPembelian($id)
    {
        //
        $order = Order::where('id', $id)->first();
        return view('order.user.struk', compact('order'));
    }
    // public function show(Order $order)
    // {
    //     //
    // }
    public function show($id)
    {

        $status2 = Order_status::find($id);

        return view('order.user.show', compact('status2'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }
    public function bayar($id)
    {
        $colocationProducts = Product::where('type', 'colocation')->get();
        $dedicatedProducts = Product::where('type', 'dedicated')->get();
        $order = Order::find($id);
        $status2 = Order_status::all();
        $existingProducts = $order->products[0] ?? null;

        return view('order.user.bayar', compact('order', 'colocationProducts', 'dedicatedProducts', 'status2', 'existingProducts'));
    }
    public function length($id)
    {
        $colocationProducts = Product::where('type', 'colocation')->get();
        $dedicatedProducts = Product::where('type', 'dedicated')->get();
        $order = Order::find($id);
        $status2 = Order_status::all();
        $existingProducts = $order->products[0] ?? null;

        return view('order.user.length', compact('order', 'colocationProducts', 'dedicatedProducts', 'status2', 'existingProducts'));
    }
    public function showOrderForm()
    {
        $previousProductId = session('previous_product_id');

        // Jika pengguna sudah memilih produk sebelumnya, kita tidak perlu menyertakan semua produk
        if ($previousProductId) {
            // Lakukan logika atau pengambilan data produk sesuai kebutuhan Anda
            $selectedProduct = Product::find($previousProductId);
            $selectedProducts = [$selectedProduct]; // Simpan produk sebelumnya dalam array
        } else {
            // Jika belum memilih produk sebelumnya, ambil semua produk dari database
            $selectedProducts = Product::all();
        }

        return view('order.user.bayar', [
            'previousProductId' => $previousProductId,
            'selectedProducts' => $selectedProducts,
            compact('previousProductId')
        ]);
    }

    public function processOrder(Request $request)
    {
        // Validasi formulir dan logika bisnis lainnya

        // Menyimpan ID produk yang baru dipilih ke sesi
        session(['previous_product_id' => $request->input('name_product')]);

        // Proses pesanan lainnya
        // ...

        return redirect()->route('order.bayar'); // Redirect kembali ke formulir pemesanan
    }
    public function lunas($id)
    {

        $order = Order::find($id);
        $orders = Order::with('user')->simplePaginate(5);

        return view('order.user.lunas', compact('order', 'orders'));
    }
    // public function downloadPDF($id)
    // {
    //     //get data yang akan ditampilkan pada pdf
    //     //data yang dikirim ke PDF wajib array
    //     // toArray : merubah fungsi dari model apapun menjadi sebuah array
    //     // first = mengambil data haya satu
    //     $order = Order::where('id',$id)->first()->toArray();

    //     // ketika data dipanggil di blade pdf,akan dipanggil dengan $apa
    //     view()->share('order',$order);

    //     // lokasi dan nama blade yang akan didownload ke pdf serta data yang akan ditampilkan 
    //     $pdf = PDF::loadView('order.user.download',$order);

    //     // ketika didownload nama file apa
    //     return $pdf->download('Bukti Pembelian.pdf');   
    // }

    public function search(Request $request)
    {
        // $query= "SELECT * FROM students
        //             WHERE
        //         nama LIKE '%$keyword%' OR
        //         nis LIKE '%$keyword%' OR
        //         rombel LIKE '%$keyword%' OR
        //         rayon LIKE '%$keyword%' OR
        //         status LIKE '%$keyword%'
        //     ";

        //     return query($query);

        $get = $request->input('search');

        $orders = Order::whereDate('created_at', $get)->simplePaginate(5);

        $selectedProduct1 = Product::find(13);
        $selectedProduct2 = Product::find(14);
        $selectedProduct3 = Product::find(15);
        // $product = Product::where('created_at',$order)->first();

        // format assoc dimasukkan ke array penampung sebelumnya


        return view('order.user.index', compact('orders', 'selectedProduct1', 'selectedProduct2', 'selectedProduct3'));
    }
    // }
    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Order $order)
    // {
    //     //
    // }
    public function convertArrayToStdClass(array $array)
    {
        // Mengonversi array ke objek menggunakan (object)
        return (object) $array;
    }
    public function update(Request $request, $id)
    {
        // validasi
        $request->validate([
            'name_customer' => 'required|min:3',
        ]);
        // cari berdasarkan id terus update
        $order = Order::find($id);


        // $totalPrice = $order->total_price;
        $totalPrice = $this->calculateTotalPrice($order);



        // ... (bagian lain dari metode update)

        if ($order->bulan == 12) {
            // Tambahkan 300.000 ke total harga jika bulan adalah 12
            $totalPrice += 300000;
        } elseif ($order->bulan == 24) {
            $totalPrice += $totalPrice;
            // masih unknow
        }
        // $order = Order::find($id);

        // Pilihan produk colocation dari form
        // $selectedColocationProductId = $request->input('name_product');
        // $productColocation = Product::find($selectedColocationProductId);
        // ...

        // Get the selected colocation product
        $selectedProduct = Product::find($request->input('name_product'));

        // Get the selected number of months
        $selectedMonths = $request->input('months');

        // Calculate the total cost based on the conditions
        // $baseCost = 500000; // Base cost for 1 month
        $additionalCost = 500000; // Additional cost for each additional month
        $totalCost = 0;

        // Update the order data with the new month and total cost
        // $order->name_product = $selectedProduct->name;
        // $order->months = $selectedMonths;
        // $order->total_cost = $totalCost;
        // $order->save();

        // ...

        // Update data order
        Order::where('id', $id)->update([
            'name_customer' => $request->name_customer,
            'total_price' => $totalPrice,
        ]);

        // ----------------------batas baru ----------------
        // Get the selected colocation total
        // $selectedProduct = Product::find($request->input('name_product'));

        // Get the selected number of months
        $selectedMonths = $request->input('months');

        // Calculate the total cost based on the conditions
        // $baseCost = 500000; // Base cost for 1 month
        $additionalCost = 500000; // Additional cost for each additional month
        $totalCost = 0;


        // Update the order data with the new month and total cost
        // $order->name_product = $selectedProduct->name;
        // $order->months = $selectedMonths;
        // $order->total_cost = $totalCost;
        // $order->save();
        if ($request->has('name_product')) {
            $selectedColocationProductId = $request->input('name_product');
            $selectedDedicatedProductId = $request->input('name_product');
            $productColocation = Product::find($selectedColocationProductId);
            // $productDedicated = Product::find($selectedDedicatedProductId);


            // Check if the order already has a colocation product
            // if ($this->hasColocationProduct($order)) {
            //     // If yes, update the total cost by adding the price of the new colocation product
            //     $totalPrice += $productColocation->price;
            // / Dapatkan pesanan terkait untuk produk colocation
            // $orderForColocation = Order::whereHas('products', function ($query) use ($selectedColocationProductId) {
            //     $query->where('id', $selectedColocationProductId);
            // })->first();

            // Hitung total harga untuk pesanan terkait
            // $totalPriceForColocation = $this->calculateTotalPrice($orderForColocation);

            // Redirect or return response
            
            // --------------------brother and sister-------------
            // Jika produk colocation dipilih, tambahkan data produk colocation
            if ($productColocation || $selectedDedicatedProductId) {
                // $totalPrice = $this->calculateTotalPrice($productColocation);
                // --------------------Pemisah-------------------------------

                if ($request->input('freeze') == 3) {

                    // Ambil produk yang sudah ada dari hasil query (mengasumsikan hanya ada satu produk)
                    // $existingProduct = $order->products instanceof Collection ? $order->products->first() : null;
                    // $existingProduct = $order->products->first(function ($product) {
                    //     return $product['type'] === 'dedicated';
                    // });
                    // $existingProduct = $order->products;
                    $existingProduct = $order->products[0] ?? null;
                    $existingBulan = $order->bulan;

                    // $products = $order->products;
                    // $existingProduct = array_filter($order->products, function ($products) {
                    //     // return $products['type'] == 'dedicated';
                    //     $products['type'] == 'dedicated';
                    // });
                    $endSum = $existingBulan - $selectedMonths;

                    // if($endSum == 9 || $endSum == 21 || $endSum == -1 || $endSum == 1){
                    if ($endSum == 9 || $endSum == 21) {
                        $totalBulan = 3 + 1;
                    } elseif ($endSum == 10 || $endSum == 22) {
                        $totalBulan = 2 + 1;
                    } elseif ($endSum == 11 || $endSum == 23) {
                        $totalBulan = 1 + 1;
                        // $totalBulan = 1 + 1;
                    }

                    if ($existingBulan == 2) {
                        $newBulan = 1;
                        if ($selectedMonths == 2) {
                            $totalBulan = 4;
                            // $totalBulan =+ $newBulan;
                        } else {
                            $totalBulan = 3;
                        }
                    } elseif ($existingBulan == 3) {
                        $newBulan = 1;
                        if ($selectedMonths == 1) {
                            $totalBulan = 4;
                            // $totalBulan =+ $newBulan;
                        }
                    } elseif ($existingBulan == 4) {
                        $totalBulan = 4;
                    } else {
                        $newBulan = 3;
                    }

                    if($selectedDedicatedProductId == 1 ){
                        $baseCost = 350000;
                        
                    }elseif($selectedDedicatedProductId == 2){
                        
                        $baseCost = 750000;
                    }

                    $baseCosti = $baseCost - 200000;

                    if ($selectedMonths == 1) {
                        $totalCost = $baseCost;
                    } elseif ($selectedMonths == 2) {
                        $totalCost = ($baseCost * $selectedMonths);
                    } elseif ($selectedMonths == 3) {
                        // $totalCost = ($baseCost * $selectedMonths) + ($additionalCost * 5); // Additional cost for 5 months
                        $totalCost = ($baseCost * $selectedMonths); // Additional cost for 5 months
                    } else {
                        $totalCost = $baseCost;
                    }


                    // } elseif ($selectedMonths == 3) {
                    //     // $totalCost = ($baseCost * $selectedMonths) + ($additionalCost * 5); // Additional cost for 5 months
                    //     $totalCost = ($baseCost * $selectedMonths); // Additional cost for 5 months
                    // } 
                    // elseif ($selectedMonths == 12) {
                    //     $totalCost = ($baseCost * $selectedMonths) - 360000; // Deduct 360000 for 12 months
                    // }
                    // elseif ($selectedMonths == 12) {
                    //     $totalCost = ($baseCost * $selectedMonths) - 360000; // Deduct 360000 for 12 months
                    // }


                    // Periksa apakah produk sudah ada
                    if ($existingProduct) {
                        $existingProductObject = $this->convertArrayToStdClass($existingProduct);
                        // $existingBulanObject = $this->convertArrayToStdClass($existingBulan);
                        // // Update data produk yang sudah ada
                        // $existingProduct['price'] = $existingProduct;
                        // $existingProduct['price'] = $order->price;
                        // $existingProduct['qty'] = 1;
                        // $existingProduct['price_after_qty'] = $order->price;
                        // // $existingProduct['votes'] = 0;
                        // $existingProduct['total_price'] = $totalCost;
                        // // $existingProduct['bulan'] = $selectedMonths;
                    }
                    // else {
                    //     // Jika tidak ada produk yang sudah ada, buat objek baru
                    //     $existingProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                    // }
                    // Buat objek produk baru
                    if($selectedDedicatedProductId == 1 ){
                        $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                        $newProduct->id = 1;
                        $newProduct->name_product = "(FREEZE) Dedicated Bogor" ;
                        $newProduct->price = 350000;
                        $newProduct->type = "FREEZE";
                        $newProduct->qty = 1;
                        $newProduct->price_after_qty = $totalCost;
                    }elseif($selectedDedicatedProductId == 2){
                        $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                        $newProduct->id = 2;
                        $newProduct->name_product = "(FREEZE) Dedicated Jakarta";
                        $newProduct->price = 750000;
                        $newProduct->type = "FREEZE";
                        $newProduct->qty = 1;
                        $newProduct->price_after_qty = $totalCost;
                    }
                    
                    // $newProduct->votes = 0;
                    // $newProduct->total_price = $totalCost;
                    // $newProduct->bulan = $selectedMonths;

                    // Simpan produk yang sudah ada dan produk baru ke dalam array produk pesanan
                    // get the bulan from buian iobject no-1
                    // $order->bulan = [$existingBulan,$selectedMonths];
                    if ($order->bulan == 12) {
                        if($selectedDedicatedProductId == 1){
                            $newProduct->name_product = "(FREEZE) Dedicated Bogor" ;

                        }elseif($selectedDedicatedProductId == 2){
                            $newProduct->name_product = "(FREEZE) Dedicated Jakarta";

                        }

                        Order_status::where('order_id', $id)->update([
                            'data' => 12,
                        ]);
                    } elseif ($order->bulan == 24) {
                        Order_status::where('order_id', $id)->update([
                            'data' => 24,
                        ]);
                    };

                    $order->products = [$existingProduct, $newProduct];
                    //          $bulanInput = $totalBulan;
                    //         $bulanPrev = $order->bulan;
                    // $order->bulan = [$bulanInput,$bulanPrev];

                    $order->save();

                    $existingTotal = $order->total_price;
                    $doneTotal = $existingTotal + $totalCost;

                    $order->update([
                        // 'total_price' => $productColocation->price,
                        'total_price' => $doneTotal,
                        'bulan' => $totalBulan,
                    ]);
                }

                // $existingProduct = $order->products[0] ?? null;
                // $existingProductObject = $this->convertArrayToStdClass($existingProduct);

                // if($existingProductObject['type'] == 'dedicated'){

                // }
                // else {
                //     // Update the order with the selected colocation product
                //     $order->update([
                //         'products' => [
                //             [
                //                 'id' => $productColocation->id,
                //                 'name_product' => $productColocation->name,
                //                 'price' => $productColocation->price,
                //                 'qty' => 1,
                //                 'price_after_qty' => $productColocation->price,
                //             ]
                //         ],
                //         'total_price' => $totalPrice,
                //     ]);

                //     // Increment votes
                //     $order->increment('votes', 1, [
                //         'updated_at' => $order->updated_at
                //     ]);
                // }
                // HIYA-----------------------------HIya--------------
                else {
                    // Ambil data yang diperlukan dari request
                    $selectedMonths = $request->input('months');

                    // Redirect 


                    $selectedColocationProductId = $request->input('name_product');
                    $productColocation = Product::find($selectedColocationProductId);

                    // pisahh
                    $existingTotal = $order->total_price;
                    $status2 = Order_status::where('order_id', $id)->first();
                    $baseCost = $productColocation->price;
                    $accessData = $status2->access - 6;

                    if ($status2->access < 6  && $status2->access > 0 || $status2->access == 1) {

                        $existingVotes = 0;

                        if ($selectedMonths == 1) {
                            $totalCost = $baseCost;
                        } elseif ($selectedMonths == 3) {
                            $totalCost = $baseCost * $selectedMonths;
                        } elseif ($selectedMonths == 6) {
                            // $totalCost = ($baseCost * $selectedMonths) + ($additionalCost * 5); // Additional cost for 5 months
                            $totalCost = ($baseCost * $selectedMonths); // Additional cost for 5 months
                        } elseif ($selectedMonths == 12) {
                            $totalCost = ($baseCost * $selectedMonths) - 360000; // Deduct 360000 for 12 months
                        };
                    } else {
                        $existingVotes = $order->votes;
                        if ($selectedMonths == 1) {
                            $totalCost = $baseCost;
                        } elseif ($selectedMonths == 3) {
                            $totalCost = $baseCost * $selectedMonths;
                        } elseif ($selectedMonths == 6) {
                            // $totalCost = ($baseCost * $selectedMonths) + ($additionalCost * 5); // Additional cost for 5 months
                            $totalCost = ($baseCost * $selectedMonths); // Additional cost for 5 months
                        } elseif ($selectedMonths == 12) {
                            $totalCost = ($baseCost * $selectedMonths) - 360000; // Deduct 360000 for 12 months
                        };
                    }

                    $existingProduct = $order->products[0] ?? null;
                    $existingProduct1 = isset($order->products[1]) ? $order->products[1] : null;
                    $existingProduct2 = isset($order->products[2]) ? $order->products[2] : null;
                    $existingProduct3 = isset($order->products[3]) ? $order->products[3] : null;

                    // ------------------------------------=====================------
                    $Ha = $totalCost;
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    $totalEnd = $Ha + $existingTotal;

                    // oooooooooooooooooooooooooooo
                    // // 
                    // if($productCollocation == false){
                    //     $colloacation == true;
                    //     $colloCost == $collo + $additionalCost;
                    // }
                    $order->update([
                        // 'products' => [
                        //     [
                        //         'id' => $productColocation->id,
                        //         'name_product' => $productColocation->name,
                        //         'price' => $productColocation->price,
                        //         'type' => $productColocation->type,
                        //         'qty' => 1,
                        //         'price_after_qty' => $productColocation->price * $selectedMonths,
                        //     ]
                        // ],
                        'bulan' => $selectedMonths, // Pastikan $selectedMonths dikonversi ke bilangan bulat
                        // 'bulan' => (int)$selectedMonths, // Pastikan $selectedMonths dikonversi ke bilangan bulat
                        'votes' => $selectedMonths,
                        // 'total_price' => $productColocation->price,
                        'total_price' => $totalEnd,
                        // 'total_price' => $totalCost,
                    ]);

                    $order->increment('votes', $existingVotes, [
                        'updated_at' => $order->updated_at
                    ]);

                    if ($status2->access < 0) {
                        $status2->decrement('access', 1);
                    } elseif ($status2->access == 1) {

                        $status2->decrement('access', 2);
                    } elseif ($status2->access == 5) {
                        $status2->increment('access', 2);
                    } else {
                        $status2->increment('access', 1);
                    }
                    // case All Dedicated

                    if ($status2->access < 0 && $order['bulan'] == 12) {

                        if ($status2->access < -1) {
                            $status2->increment('access', 1);
                            $status2->decrement('access', 1);
                        } else {
                            $status2->increment('access', 2);
                            $status2->decrement('access', 2);
                        }
                    } elseif ($status2->access < -1) {
                        $status2->increment('access', 1);
                    } elseif ($status2->access == -1) {
                        $status2->increment('access', 0);
                    } elseif ($status2->access >= 6 && $order['bulan'] == 12) {
                        $status2->decrement('access', 1);

                        if ($status2->access == 5) {
                            $status2->increment('access', 2);
                        } else {
                            $status2->increment('access', 1);
                        }
                    } else {
                        $status2->decrement('access', 1);
                    }

                    // Produk Perpanjangan ----------------------------
                    // if ($existingProduct1) {
                    //     $priceBulan = Product::find($request->bandwidth)->first();
                    //     // // Mengupdate nilai produk yang ada
                    //     $qty = $existingProduct1['qty'];
                    //     if ($selectedMonths == 12) {
                    //         $existingProduct1['price'] = ($priceBulan * $selectedMonths) - 120000;
                    //         $existingProduct1['price_after_qty'] = (($priceBulan * $selectedMonths) - 120000) * $qty;
                    //     } else {
                    //         $existingProduct1['price'] = ($priceBulan * $selectedMonths);
                    //         $existingProduct1['price_after_qty'] = ($priceBulan * $selectedMonths) * $qty;
                    //     }

                    //     $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);
                    // }
                    // if ($existingProduct2) {
                    //     $priceBulan = Product::find($request->IP)->first();
                    //     // // Mengupdate nilai produk yang ada
                    //     $existingProduct2['price'] = $priceBulan * $selectedMonths;
                    //     $existingProduct1['price_after_qty'] = ($priceBulan * $selectedMonths);

                    //     $existingProductObject2 = $this->convertArrayToStdClass($existingProduct2);
                    // }
                    // if ($existingProduct3) {
                    //     $existingProductObject1 = $this->convertArrayToStdClass($existingProduct);
                    //     $existingProductObject2 = $this->convertArrayToStdClass($existingProduct2);
                    //     $existingProductObject3 = $this->convertArrayToStdClass($existingProduct3);

                    //     $order->products = [$existingProductObject, $existingProductObject1, $existingProductObject2, $existingProductObject3];
                    // } elseif ($existingProduct2) {
                    //     $existingProductObject1 = $this->convertArrayToStdClass($existingProduct);
                    //     $existingProductObject2 = $this->convertArrayToStdClass($existingProduct2);

                    //     $order->products = [$existingProductObject, $existingProductObject1, $existingProductObject2];
                    // } elseif ($existingProduct2) {
                    //     $existingProductObject1 = $this->convertArrayToStdClass($existingProduct);

                    //     $order->products = [$existingProductObject, $existingProductObject1];
                    // }
                    // Produk Perpindahan ----------------------------
                    $dataProducts = [];
                    $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                    $newProduct->id = $productColocation->id;
                    $newProduct->name_product = $productColocation->name;
                    $newProduct->price = $productColocation->price;
                    $newProduct->type = $productColocation->type;
                    $newProduct->qty = 1;
                    $newProduct->price_after_qty = $totalCost;


                    if ($existingProduct['id'] == $newProduct['id']) {
                        // Perbarui harga produk pertama (produk colocation) sesuai dengan bulan yang dipilih
                        $totalPrice = 0;
                        $existingProduct = $order->products[0];
                        if($selectedMonths == 12){

                            $existingProduct['price_after_qty'] = ($existingProduct['price'] * $selectedMonths) - 360000;
                        }
                        else{
                            $existingProduct['price_after_qty'] = $existingProduct['price'] * $selectedMonths;
                        }
                        $existingProductObject1 = $this->convertArrayToStdClass($existingProduct);

                        // Perbarui harga produk kedua (misalnya, bandwidth)
                        $existingProduct1 = $order->products[1];
                        $bandwidthId = '21';
                        $bandwidth = Product::where('id', $bandwidthId)->first();
                        $bandwidthPrice = $bandwidth['price']; // Harga asli bandwidth
                        $existingProduct1['price'] = $bandwidthPrice * $selectedMonths;
                        $existingProduct1['price_after_qty'] = $existingProduct1['price'] * $existingProduct1['qty'];
                        $existingProductObject2 = $this->convertArrayToStdClass($existingProduct1);

                        $existingProduct2 = $order->products[2];
                        $IpPrice = $existingProduct2['price']; // Harga asli Ip
                        $existingProduct2['price_after_qty'] = $existingProduct2['price'] * $selectedMonths;
                        $existingProductObject3 = $this->convertArrayToStdClass($existingProduct2);

                        $existingProduct3 = $order->products[3];
                        $existingProductObject4 = $this->convertArrayToStdClass($existingProduct3);

                        // Perbarui harga produk lainnya
                        // for ($i = 1; $i < count($order->products); $i++) {
                        //     $existingProduct = $order->products[$i];
                        //     $existingProduct['price_after_qty'] = $existingProduct['price'] * $selectedMonths;
                        // }

                        // Hitung total harga dari semua produk
                        $totalPrice = $existingProduct['price_after_qty'] +     $existingProduct1['price_after_qty'] + $existingProduct2['price_after_qty'] + $existingProduct3['price_after_qty'];
                        // dd(count($order->products));
                        // for ($i = 1; $i < count($order->products); $i++) {
                        //     $totalPrice += $order->products[$i]['price_after_qty'];
                        // }
                        // dd($existingTotal);
                        $order->total_price = $totalPrice + $existingTotal;
                        // Perbarui total harga pesanan
                        
                        $order->bulan = $selectedMonths; // Update jumlah bulan pada pesanan
                        
                        $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4];

                        $order->save();

                        // beda alammm:>

                    } else {

                        // array_push($dataProducts, $newProduct);

                        $totalPrice = 0;
                        
                        // dd($newProduct);

                        $existingProduct = $newProduct;
                        if($selectedMonths == 12){
                            $newProduct['price_after_qty'] = ($newProduct['price'] * $selectedMonths) - 360000 ;
                        }else{
                            $newProduct['price_after_qty'] = $newProduct['price'] * $selectedMonths;

                        }

                        $newProductObject1 = $newProduct;


                        // foreach ($dataProducts as $formatArray) {
                        //     // dia bakal menambahkan  totalPrice sebelumnya ditambah data harga dari price_after_qty
                        //     $totalPrice += (int)$formatArray['price_after_qty'];
                        // }

                        $existingProduct1 = $order->products[1];
                        $bandwidthId = '21';
                        $bandwidth = Product::where('id', $bandwidthId)->first();
                        $bandwidthPrice = $bandwidth['price']; // Harga asli bandwidth
                        $existingProduct1['price'] = $bandwidthPrice * $selectedMonths;
                        $existingProduct1['price_after_qty'] = $existingProduct1['price'] * $existingProduct1['qty'];
                        $existingProductObject2 = $this->convertArrayToStdClass($existingProduct1);

                        $existingProduct2 = $order->products[2];
                        $IpPrice = $existingProduct2['price']; // Harga asli Ip
                        $existingProduct2['price_after_qty'] = $existingProduct2['price'] * $selectedMonths;
                        $existingProductObject3 = $this->convertArrayToStdClass($existingProduct2);

                        $existingProduct3 = $order->products[3];
                        $existingProductObject4 = $this->convertArrayToStdClass($existingProduct3);

                         // Hitung total harga dari semua produk
                         $totalPrice = $newProduct['price_after_qty'] +     $existingProduct1['price_after_qty'] + $existingProduct2['price_after_qty'] + $existingProduct3['price_after_qty'];
                         // dd(count($order->products));
                         // for ($i = 1; $i < count($order->products); $i++) {
                         //     $totalPrice += $order->products[$i]['price_after_qty'];
                         // }
                         // dd($existingTotal);
                         $order->total_price = $totalPrice + $existingTotal;
                         // Perbarui total harga pesanan
                         
                         $order->bulan = $selectedMonths; // Update jumlah bulan pada pesanan
                         
                         $order->products = [$newProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4];
 
                         
                        // $order->products = [$existingProduct, $newProduct];
                        // if ($request->bandwidth > 0) {
                        //     $bandwidthId = '21';
                        //     $qty = $request->bandwidth;
                        //     $bandwidth = Product::where('id', $bandwidthId)->first();
                        //     if ($request->months == 1) {

                        //         $bandwidthAssoc = [
                        //             "id" => $bandwidthId,
                        //             "name_product" => $bandwidth['name'],
                        //             "type" => $bandwidth['type'],
                        //             "price" => $bandwidth['price'],
                        //             "qty" => $qty,
                        //             "price_after_qty" => (int)$qty * (int)$bandwidth['price'],
                        //         ];
                        //         array_push($dataProducts, $bandwidthAssoc);
                        //     } elseif ($request->months == 3) {
                        //         $bandwidth3 = $bandwidth['price'] * 3;
                        //         $bandwidthAssoc = [
                        //             "id" => $bandwidthId,
                        //             "name_product" => $bandwidth['name'],
                        //             "type" => $bandwidth['type'],
                        //             "price" => $bandwidth['price'] * 3,
                        //             "qty" => $qty,
                        //             "price_after_qty" => (int)$qty * $bandwidth3,
                        //         ];
                        //         array_push($dataProducts, $bandwidthAssoc);
                        //     } elseif ($request->months == 6) {
                        //         $bandwidth6 = $bandwidth['price'] * 6;
                        //         $bandwidthAssoc = [
                        //             "id" => $bandwidthId,
                        //             "name_product" => $bandwidth['name'],
                        //             "type" => $bandwidth['type'],
                        //             "price" => $bandwidth['price'] * 6,
                        //             "qty" => $qty,
                        //             "price_after_qty" => (int)$qty * $bandwidth6,
                        //         ];
                        //         array_push($dataProducts, $bandwidthAssoc);
                        //     } else {
                        //         $bandwidth12 = ($bandwidth['price'] * 12) - 120000;
                        //         $bandwidthAssoc = [
                        //             "id" => $bandwidthId,
                        //             "name_product" => $bandwidth['name'],
                        //             "type" => $bandwidth['type'],
                        //             "price" => $bandwidth12,
                        //             "qty" => $qty,
                        //             "price_after_qty" => (int)$qty * $bandwidth12,
                        //         ];
                        //         array_push($dataProducts, $bandwidthAssoc);
                        //     }
                        //     $totalPrice += $bandwidthAssoc['price_after_qty'];
                        // }
                        // if ($request->IP == 29) {
                        //     $IP0Id = '22';
                        //     $qty = 1;
                        //     $IP0 = Product::where('id', $IP0Id)->first();

                        //     $IPAssoc = [
                        //         "id" => $IP0Id,
                        //         "name_product" => $IP0['name'],
                        //         "type" => $IP0['type'],
                        //         "price" => $IP0['price'],
                        //         "qty" => $qty,
                        //         "price_after_qty" => (int)$qty * $IP0['price'],
                        //     ];
                        //     array_push($dataProducts, $IPAssoc);
                        // } elseif ($request->IP < 29 && $request->IP > 23) {
                        //     $IP1Id = '23';
                        //     $IP2Id = '24';
                        //     $IP3Id = '25';
                        //     $qty = 1;
                        //     $IP1 = Product::where('id', $IP1Id)->first();
                        //     $IP2 = Product::where('id', $IP2Id)->first();
                        //     $IP3 = Product::where('id', $IP3Id)->first();
                        //     $IP12 = ($IP1['price'] * 12) - 120000;



                        //     if ($request->IP == 28) {

                        //         $ipPrice = ($IP1['price'] * $request->months);


                        //         if ($request->months == 1) {

                        //             $IPAssoc = [
                        //                 "id" => $IP1Id,
                        //                 "name_product" => $IP1['name'],
                        //                 "type" => $IP1['type'],
                        //                 "price" => $IP1['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } elseif ($request->months == 3) {
                        //             $IPAssoc = [
                        //                 "id" => $IP1Id,
                        //                 "name_product" => $IP1['name'],
                        //                 "type" => $IP1['type'],
                        //                 "price" => $IP1['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } elseif ($request->months == 6) {
                        //             $IPAssoc = [
                        //                 "id" => $IP1Id,
                        //                 "name_product" => $IP1['name'],
                        //                 "type" => $IP1['type'],
                        //                 "price" => $IP1['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } else {
                        //             $IPAssoc = [
                        //                 "id" => $IP1Id,
                        //                 "name_product" => $IP1['name'],
                        //                 "type" => $IP1['type'],
                        //                 "price" => $IP1['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         }
                        //     } elseif ($request->IP == 27) {

                        //         $ipPrice = ($IP2['price'] * $request->months);

                        //         if ($request->months == 1) {

                        //             $IPAssoc = [
                        //                 "id" => $IP2Id,
                        //                 "name_product" => $IP2['name'],
                        //                 "type" => $IP2['type'],
                        //                 "price" => $IP2['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } elseif ($request->months == 3) {
                        //             $IPAssoc = [
                        //                 "id" => $IP2Id,
                        //                 "name_product" => $IP2['name'],
                        //                 "type" => $IP2['type'],
                        //                 "price" => $IP2['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } elseif ($request->months == 6) {
                        //             $IPAssoc = [
                        //                 "id" => $IP2Id,
                        //                 "name_product" => $IP2['name'],
                        //                 "type" => $IP2['type'],
                        //                 "price" => $IP2['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } else {
                        //             $IPAssoc = [
                        //                 "id" => $IP2Id,
                        //                 "name_product" => $IP2['name'],
                        //                 "type" => $IP2['type'],
                        //                 "price" => $IP2['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         }
                        //     } elseif ($request->IP == 24) {
                        //         $ipPrice = ($IP3['price'] * $request->months);

                        //         if ($request->months == 1) {

                        //             $IPAssoc = [
                        //                 "id" => $IP3Id,
                        //                 "name_product" => $IP3['name'],
                        //                 "type" => $IP3['type'],
                        //                 "price" => $IP3['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } elseif ($request->months == 3) {
                        //             $IPAssoc = [
                        //                 "id" => $IP3Id,
                        //                 "name_product" => $IP3['name'],
                        //                 "type" => $IP3['type'],
                        //                 "price" => $IP3['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } elseif ($request->months == 6) {
                        //             $IPAssoc = [
                        //                 "id" => $IP3Id,
                        //                 "name_product" => $IP3['name'],
                        //                 "type" => $IP3['type'],
                        //                 "price" => $IP3['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         } else {
                        //             $IPAssoc = [
                        //                 "id" => $IP3Id,
                        //                 "name_product" => $IP3['name'],
                        //                 "type" => $IP3['type'],
                        //                 "price" => $IP3['price'],
                        //                 "qty" => $qty,
                        //                 "price_after_qty" => (int)$qty * $ipPrice,
                        //             ];
                        //             array_push($dataProducts, $IPAssoc);
                        //         }
                        //     }
                        //     $totalPrice += $IPAssoc['price_after_qty'];
                        // }
                        // if ($request->port == 1) {
                        //     $portId = '26';
                        //     $qty = 1;
                        //     $port = Product::where('id', $portId)->first();

                        //     $portAssoc = [
                        //         "id" => $portId,
                        //         "name_product" => $port['name'],
                        //         "type" => $port['type'],
                        //         "price" => $port['price'],
                        //         "qty" => $qty,
                        //         "price_after_qty" => (int)$qty * $port['price']
                        //     ];
                        //     array_push($dataProducts, $portAssoc);
                        // } elseif ($request->port == 10) {
                        //     $portId = '27';
                        //     $qty = 1;
                        //     $port = Product::where('id', $portId)->first();

                        //     $portAssoc = [
                        //         "id" => $portId,
                        //         "name_product" => $port['name'],
                        //         "type" => $port['type'],
                        //         "price" => $port['price'],
                        //         "qty" => $qty,
                        //         "price_after_qty" => (int)$qty * $port['price'],
                        //     ];
                        //     array_push($dataProducts, $portAssoc);
                        // }
                        
                    // $order->total_price = $totalPrice + $existingTotal;
                    // $order->products = $dataProducts;

                    $order->save();
                    }

                    // loop data dari array penamoung yg sudah di format
                    // Perbarui total harga pesanan
                    // dd($totalPrice);

                }
            }
        } elseif ($request->input('next') == 3) {

            $existingProduct = $order->products[0] ?? null;
            // $Month = $order->bulan[0] ?? null;
            // $Month = 12;
            // $bulanPrev= $order->bulan ?? null;

            $previousUpdatedAt = $order->updated_at;

            if ($existingProduct) {
                $existingProductObject = $this->convertArrayToStdClass($existingProduct);
            }

            $order->products = [$existingProductObject];
            // $bulanInput = $request->input('bulanFreeze');

            // $order->bulan = [$bulanInput,$bulanPrev];
            $order->save();

            // $status2 = Order_status::where('order_id', $id)->first();
            // Menggunakan first() untuk mengambil model pertama yang cocok

            // $status3 = Order_status::find($id);
            // if ($status2) {
            //     $order->update([
            //         'bulan' => $status2->data, // Pastikan $status2 tidak null sebelum mengakses properti data
            //         'votes' => $order->votes + 1, // Increment votes
            //     ]);
            // } else {
            //     // Handle kasus ketika data tidak ditemukan
            // }
            // if ($status2 && $status2->order_id == $id) {
            //     $order->update([
            //         'total_price' => $totalPrice,
            //         'votes' => $order->votes + 1,
            //         'bulan' => $status2->data, // Make sure 'data' is an integer type in the database
            //     ]);
            // }
            // $status2 = Order_status::find($id);

            $status2 = Order_status::where('order_id', $id)->first();
            $inAll = Order::all();

            // 9999999999999999999999999999999
            $existingTotal = $order->total_price;
            foreach ($order->products as $product) {
                if ($status2['data'] == 12) {
                    $Ha = $product['price'] + 300000;
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    $totalEnd = $Ha + $existingTotal;
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                } else {
                    $Ha = $product['price'];
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    $totalEnd = $Ha + $existingTotal;
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo

                }

                $order->update([
                    'total_price' => $totalEnd,

                ]);
            }
            // 9999999999999999999999999999999
            if ($status2 && $status2->order_id == $id) {
                // $validMonths = $order['bulan'];
                $validMonths = range(1, 24); // Nilai valid berupa angka 1-12
                // dd($status2->data,$validMonths);
                // Periksa apakah 'data' dari $status2->data adalah salah satu nilai yang valid

                if (in_array($status2->data, $validMonths)) {
                    $order->update([
                        // 'total_price' => $totalPrice,
                        'votes' => $order->votes + 1,
                        'bulan' => "$status2->data",
                    ]);
                }
                // else {
                //     // Handle kesalahan jika nilai 'data' tidak valid
                //     // Contoh: Set nilai default atau tangani sesuai kebutuhan aplikasi Anda
                //     $defaultBulan = 'Januari'; // Ganti dengan nilai default yang sesuai
                //     $order->update([
                //         'total_price' => $totalPrice,
                //         'votes' => $order->votes + 1,
                //         'bulan' => $defaultBulan,
                //     ]);
                // }
            }

            // $order->update([
            //     'total_price' => $totalPrice,
            //     'bulan' => $bulanData,
            //     'votes' => $order->votes + 1, // Increment votes

            // ]);


            Order_status::where('order_id', $id)->update([
                'access' => 5,
                'data' => 5,
            ]);
            //  $order->increment('votes', 1, [
            //     'updated_at' => $order->updated_at
            // ]);

            $order->save();
        } elseif ($request->input('suspend') == 2) {
            // $product = Product::find($id);

            $status2 = Order_status::find($id);

            // If yes, set votes to -1
            $order->update([
                'votes' => 0,
                'total_price' => $totalPrice,

            ]);
            $order->increment('votes', 1, [
                'updated_at' => $order->updated_at
            ]);
            Order_status::where('order_id', $id)->update([
                'access' => 1,
            ]);
            // $status2->decrement('access', 1, [
            //     'updated_at' => $status2->updated_at
            // ]);

            // $status2->decrement('access', 1);


        } else if ($request->input('unfreeze') == 4) {

            $existingProduct = $order->products[0] ?? null;
            // $bulanPrev= $order->bulan(0) ?? null;
            // $Month = 12;

            $previousUpdatedAt = $order->updated_at;

            if ($existingProduct) {
                $existingProductObject = $this->convertArrayToStdClass($existingProduct);
            }
            $order->products = [$existingProductObject];
            // $bulanInput = $request->input('bulanFreeze');

            // $order->bulan = [$bulanInput,$bulanPrev];
            // $order->save();

            $order->update([
                'total_price' => $totalPrice,
                'bulan' => $request->input('bulanFreeze'),
                'votes' => $order->votes + 1, // Increment votes

            ]);


            Order_status::where('order_id', $id)->update([
                'access' => 5,
                'data' => 5,
            ]);
            //  $order->increment('votes', 1, [
            //     'updated_at' => $order->updated_at
            // ]);

            $order->save();
        } elseif ($request->input('after') >= 5) {
            // $product = Product::find($id);

            // $previousUpdatedAt = $order->updated_at;
            $status2 = Order_status::where('order_id', $id)->first();


            // If yes, set votes to -1
            $existingTotal = $order->total_price;
            foreach ($order->products as $product) {
                if ($order['bulan'] == 12) {
                    $Ha = $product['price'] + 300000;
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    $totalEnd = $Ha + $existingTotal;
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                } else {
                    $Ha = $product['price'];
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    $totalEnd = $Ha + $existingTotal;
                    // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo

                }
                $order->update([
                    'total_price' => $totalEnd,

                ]);
            }

            $order->increment('votes', 1, [
                'updated_at' => $order->updated_at
            ]);

            $order->save();

            $status2->increment('data', 1);


            $status2->save();
        } elseif ($request->input('vote_status') == 3) {
            // $product = Product::find($id);


            // If yes, set votes to -1
            $order->update([
                'votes' => 1,
                'total_price' => $totalPrice,

            ]);
        } else {
            $existingProduct = $order->products[0] ?? null;
            $existingTotal = $order->total_price;

            // if($order->p)
            $productType = data_get($order->products, '1.type', 0);
            $price = data_get($order->products, '1.price', 0);
            if ($productType == "ram") {

                if ($existingProduct) {
                    $existingProductObject = $this->convertArrayToStdClass($existingProduct);
                }

                $order->products = [$existingProductObject];

                $order->save();
            }

            foreach ($order->products as $product) {
                if ($existingProduct['type'] == 'colocation') {
                    if ($order['bulan'] == 12) {
                        $Ha = $product['price'];
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                        $totalEnd = $Ha + $existingTotal;
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    } else {
                        $Ha = $product['price'];
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                        $totalEnd = $Ha + $existingTotal;
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo

                    }
                } else {
                    if ($order['bulan'] == 12) {
                        $Ha = $product['price'] + 300000;
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                        $totalEnd = $Ha + $existingTotal;
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    } else {
                        $Ha = $product['price'];
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                        $totalEnd = $Ha + $existingTotal;
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo

                    }
                }

                $order->update([
                    'total_price' => $totalEnd,

                ]);
            }
            $order->increment('votes', 1, [
                'updated_at' => $order->updated_at
            ]);
        }
        // -----------------------------------avacav-------------------

        // Pastikan produk yang dipilih adalah tipe colocation




        // Order::where('id', $id)->update([
        //     'total_price' => $totalPrice,
        // ]);
        // // / Tambahkan votes
        // $order->increment('votes', 1, [
        //     'updated_at' => $order->updated_at
        // ]);



        // $intDate = Product::where('id',$id)
        // ->increment('count', 1, ['increased_at' => Carbon::now()]);
        // redirect ke html order data

        $status2 = Order_status::where('order_id', $id)->first();

        // route digunakan untuk memindahkan suatu ke page yang lain jika ingin menambahkan notif ke tempat lain bisa di ganti ke order.tambah atau order.edit
        // return redirect()->route('order.index')->with('success','Berhasil mengubah data produk!')->compact('dateInc');
        $accessMail = $status2->access;
        if ($order['votes'] == $order['bulan'] && $accessMail == 1 || $order['votes'] == $order['bulan'] && $accessMail == 5) {

            return redirect()->route('order.pengiriman', $id)->with('success', 'Berhasil mengubah data produk!');
        } else {

            return redirect()->route('order.index')->with('success', 'Berhasil mengubah data produk!');
        }
    }

    // public function update(Request $request, $id)
    // {
    //     // Validasi
    //     $request->validate([
    //         'name_customer' => 'required|min:3',
    //     ]);

    //     // Cari order berdasarkan id
    //     $order = Order::find($id);

    //     // Validasi apakah order memiliki produk colocation
    //     if ($this->hasColocationProduct($order)) {
    //         return redirect()->route('order.index')->with('error', 'Produk colocation sudah dipilih, tidak dapat mengubah order ini.');
    //     }

    //     // Update data order
    //     $totalPrice = $this->calculateTotalPrice($order);

    //     Order::where('id', $id)->update([
    //         'name_customer' => $request->name_customer,
    //         'total_price' => $totalPrice,
    //     ]);



    //     return redirect()->route('order.index')->with('success', 'Berhasil mengubah data produk!');
    // }

    /**
     * Check if the order has colocation product.
     *
     * @param  \App\Models\Order  $order
     * @return bool
     */
    private function hasColocationProduct(Order $order)
    {
        foreach ($order->products as $product) {
            if ($product['type'] == 'colocation') {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate the total price for the order.
     *
     * @param  \App\Models\Order  $order
     * @return int
     */
    private function calculateTotalPrice(Order $order)
    {
        $totalPrice = 0;

        $product1 = $order->products[0];
        $productType = $product1['type'];

        foreach ($order->products as $product) {
            $totalPrice += $product['price_after_qty'];
        }

        if ($order->bulan == 12) {
            $totalPrice += 300000;
        }

        return $totalPrice;
    }

    /**
     * Remove the specified resource from storage.
     */
    // OrderController.php

    // public function chooseCollocation($id)
    // {
    //     // Lakukan validasi atau logika lainnya sesuai kebutuhan

    //     // Misalnya, set product menjadi collocation dan reset votes dan bulan
    //     $order = Order::find($id);
    //     $productCollocation = Product::where('type', 'colocation')->first();

    //     // Pastikan produk yang dipilih adalah tipe collocation
    //     if ($productCollocation->type != 'colocation') {
    //         return redirect()->route('order.index')->with('error', 'Produk yang dipilih bukan tipe collocation.');
    //     }

    //     // Ambil tanggal terakhir kali produk diubah menjadi tipe collocation
    //     $lastUpdatedDate = $order->updated_at;

    //     // Generate invoice baru berdasarkan tanggal terakhir kali produk diubah menjadi tipe collocation
    //     $newInvoice = Carbon::parse($lastUpdatedDate)->formatLocalized('%y%m%d');

    //     // Update order
    //     $order->update([
    //         'products' => [
    //             [
    //                 'id' => $productCollocation->id,
    //                 'name_product' => $productCollocation->name,
    //                 'price' => $productCollocation->price,
    //                 'qty' => 1,
    //                 'price_after_qty' => $productCollocation->price,
    //             ]
    //         ],
    //         'total_price' => $productCollocation->price,
    //         'votes' => 0,
    //         'bulan' => $productCollocation->bulan,
    //         'invoice' => $newInvoice,
    //     ]);

    //     // Redirect atau berikan respons sesuai kebutuhan
    //     return redirect()->route('order.index')->with('success', 'Layanan Collocation berhasil dipilih.');
    // }

    public function destroy(Order $order)
    {
        //
    }
}
