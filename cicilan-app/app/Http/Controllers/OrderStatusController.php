<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\File;

use App\Models\Order_status;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
  
// use App\Http\Controllers\delete;
use Carbon\Carbon;
use PDF;
use App\Exports\OrderExport;
use Excel;
use Illuminate\Support\Arr;
use RealRashid\SweetAlert\Facades\Alert;
use Termwind\Components\Dd;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //With : mengambil fungsi relasi PK ke Fk atau FK ke PK dari model 
        // Isi dipetik disamakan dengan nama functionnya  di modelnya
        $order_statuses = Order_status::OrderBy('id', 'ASC')->simplePaginate(5);
        $orders = Order::with('user')->simplePaginate(100);
        return view('order.admin.index', compact('order_statuses', 'orders'));
    }

    public function rackSearch(Request $request)
    {
        // sukun
        $racks = [];
        if ($request->has('q')) {
            $search = $request->q;
            $racks = Order::select('id', 'order')
                ->whereJsonContains('order->datacenter', 'LIKE', '%"rack":"' . $search . '"%')
                ->get();
        }
        return response()->json($racks);
    }

    // searchsense
    public function selectSearch(Request $request)

    {
        $datacenter = Order::All();
        $data = [0];
        $dataBogor = Order::whereIn('datacenter', $data)->get();
        $bogorAsnet = [];
        for ($i = 0; $i < count($datacenter); $i++) {
            $get = data_get($datacenter[$i]['datacenter'], '0.datacenter', 0);
            if ($get == 'Bogor') {
                $bogor = $datacenter[$i];
                array_push($bogorAsnet, $bogor);
            }
        }

        $getData = $request->livesearch;


        // array_push();
        // maybe it hep 

        // $size = count(collect($request)->get('id'));
        // 
        $count = 0;
        $countArr = count($bogorAsnet);
        if ($bogorAsnet !== null) {

            for ($i = 0; $i < $countArr; $i++) {

                // dd(count($bogorAsnet),$bogorAsnet[$i]['datacenter'][0]['rack']);
                if ($bogorAsnet[$i]['datacenter'][0]['rack'] == $getData) {
                    // dd($datacenter['rack']);    
                    $bogor = $bogorAsnet[$i];
                    // array_push($bogorAsnet,$bogor);
                } else {
                    # code...
                    // array_splice($bogorAsnet,$i);
                    data_forget($bogorAsnet, $i);
                    // dd($bogorAsnet);    
                }
            }
        }


        $movies = [];
        if ($request->has('q')) {
            $search = $request->q;
            // foreach ($bogorAsnet as $key) {
            //     # code...
            //     $movies = Product::select('id','datacenter' )
            //     ->where('id', 'LIKE', $key['id'])
            //     ->where('name', 'LIKE', "%$search%")
            //     ->get();
            // }
            $rack = $bogorAsnet[0]['datacenter'][0]['rack'];
            $movies = Order::select('id', 'datacenter')
                ->where('datacenter', 'LIKE', "%$search%")
                ->get();
        }
        // dd($movies);
        return response()->json($movies);

        // $get = $request->livesearch;
        // dd($get);
        // $orders = Order::where('id', $get)->simplePaginate(5);


    }



    // searchSeect
    public function data()
    {
        $order_statuses = Order_status::OrderBy('id', 'ASC')->simplePaginate(5);
        $orders = Order::with('user')->simplePaginate(100);
        $orders1 = Order::with('user_id')->get();
        $orders2 = Order_status::where('id', $orders1['user_id']);

        // dd ($orders)
        return view('order.admin.index', compact('order_statuses', 'orders', 'orders2'));
    }

    public function status(Request $request,$id)
    {
        

            // dd($suspend);
            $status2 = Order_status::find($id);
            $order_id = $status2['order_id'];
            $order = Order::where('id', $order_id)->first();
            $user = User::where('role', 'user')->simplePaginate(100);
            // $userGet = user::where('id',$user_id)->first();
    
            // $existingUser = $userGet['entryData'][0][2]['serverLabel'];
            $serverLabel = last($order['products']);
            $serverLabelGet = $serverLabel['serverLabel'];
            // if($serverLabelGet == $existingUser){
            //     $serverAll = 1;
            // }
    
            $dedicMurah = [];
            $server1 = [];
            $sewaGet = [];
    
            $dedicatedDer = Order::simplePaginate(100);
            foreach ($user as $key) {
                $sewaStatus = $key['id'];
                array_push($sewaGet, $sewaStatus);
                // dd($dedicatedDer[0]['products'],$product);
                $get1 = Order_status::where('order_id', $key['id'])->first();
                $keyData = data_get($key, 'entryData', false);
                // dd($keyData);
                if ($keyData !== false) {
                    foreach ($key['entryData'] as $product) {
                        // dd($key);
                        # code...
                        $serverChecked = data_get($product, '2.serverLabel', false);
    
                        if ($serverChecked !== false) {
    
                            if ($serverChecked == $serverLabelGet) {
                                $server2 = $key;
                                array_push($server1, $server2);
                            }
                        }
                    }
                }
            }


        return view('order.admin.status', compact('status2', 'user', 'server1', 'order'));
    }


    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */

    public function new_status(Request $request, $id)
    {

        // dd($suspend);
        $status2 = Order_status::find($id);
        $order_id = $status2['order_id'];
        $order = Order::where('id', $order_id)->first();
        $user = User::where('role', 'user')->simplePaginate(100);
        // $userGet = user::where('id',$user_id)->first();

        // $existingUser = $userGet['entryData'][0][2]['serverLabel'];
        $serverLabel = last($order['products']);
        $serverLabelGet = $serverLabel['serverLabel'];
        // if($serverLabelGet == $existingUser){
        //     $serverAll = 1;
        // }

        $dedicMurah = [];
        $server1 = [];
        $sewaGet = [];

        $dedicatedDer = Order::simplePaginate(100);
        foreach ($user as $key) {
            $sewaStatus = $key['id'];
            array_push($sewaGet, $sewaStatus);
            // dd($dedicatedDer[0]['products'],$product);
            $get1 = Order_status::where('order_id', $key['id'])->first();
            $keyData = data_get($key, 'entryData', false);
            // dd($keyData);
            if ($keyData !== false) {
                foreach ($key['entryData'] as $product) {
                    // dd($key);
                    # code...
                    $serverChecked = data_get($product, '2.serverLabel', false);

                    if ($serverChecked !== false) {

                        if ($serverChecked == $serverLabelGet) {
                            $server2 = $key;
                            array_push($server1, $server2);
                        }
                    }
                }
            }
        }
        // dd($dedic1,$serverChecked,$serverLabelGet,$key,$key['entryData'],$product[2]['serverLabel']);
        // dd($server1);





        return view('order.admin.new_status', compact('status2', 'user', 'server1', 'order'));
    }

    public function single($id)
    {

        $order_statuses = Order_status::OrderBy('id', 'ASC')->simplePaginate(5);
        $orders = Order::where('user_id', $id)->simplePaginate(100);
        $status2 = Order_status::all();
        $status1 = Order::where('user_id', $id)->simplePaginate(100);
        $statusDone = Order_status::where('data', '4')->count();
        $statusSPK = Order_status::where('data', '1')->count();
        $statusTTD = Order_status::where('data', '2')->count();
        $statusWait = Order_status::where('data', '3')->count();
        $userData = User::where('id', $id)->first();

        $title = 'Delete Order!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);


        // $orders = Order::with('user')->simplePaginate(100);

        return view('order.admin.single', compact('status2', 'orders', 'order_statuses', 'status1', 'statusDone', 'statusSPK', 'statusTTD', 'statusWait', 'userData'));
    }

    public function lunasUpdate(Request $request, $id)
    {
        // 1.Similar product object untuk isset ke produk lunas yan baru
        // perakan

        $orderId = Order::where('id', $id)->first();
        $statusId = Order_status::where('order_id', $id)->first();
        $datacenter = $orderId['datacenter'][0];
        $productGet = $orderId['products'];
        $serverGet = last($productGet);

        $type = 0;
        $existingTotal = $orderId['total_price'];
        $typeProduct = $productGet[1]['type'];
        $productCount = count($productGet) - 1;
        
        $productGet[$productCount]['startDate'] = $orderId['updated_at']->addDays(30 * (int)$request->bulan);
        
        // dd($productGet[$productCount]);

        for ($i = 0; $i < $productCount; $i++) {
            $productHave = $productGet[0]['type'];
            if ($productHave == 'colocation') {

                $productHas = $productGet[$i]['type'];

                if ($productHas == "ram" || $productHas == "ssd") {
                    $dataHas = 2;
                } else {
                    $dataHas = 2;
                }
            } else {
                $dataHas = 0;
            }
        }

        // dd($dataHas,count($productGet),$productGet[3]['type'],$productCount);

        if ($productGet[0]['type'] == 'colocation') {
            $order = Order::find($id);
            $totalPrice = $this->calculateTotalPrice($order);
            $afterDedic = $productGet[0]['price_after_qty'];
            if ($dataHas == 2) {

                $totalEnd = $existingTotal + $afterDedic;
            } else {
                $totalEnd = $existingTotal + $totalPrice + 500000;
            }

            // dd($totalEnd,$existingTotal,$afterDedic);
            $orderId['total_price'] = $totalEnd;
            $type = 'colocation';
            // $orderId['votes'] 
            // dd($productGet[0]['type'],$order['total_price'],$existingTotal,$totalPrice);
        } else {
            $type = 'dedicated';
            $bulanOrder = $request->bulan;


            if ($datacenter['datacenter'] == 'Bogor') {

                $dataLocation = Product::where('id', 6)->first();
                if ($bulanOrder == "12") {
                    # code...
                    $datacenterGet = [
                        "id" => 6,
                        "label" => $serverGet['serverLabel'], // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                        "name_product" => $dataLocation['name'],
                        "type" => $dataLocation['type'],
                        "price" => $dataLocation['price'],
                        "qty" => 1,
                        //(int) memastikan dan mengubah tipe data menjadi integer
                        "price_after_qty" => (int)$bulanOrder * (int)$dataLocation['price'] - 360000,
                    ];
                } else {
                    $datacenterGet = [
                        "id" => 6,
                        "label" => $serverGet['serverLabel'], // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                        "name_product" => $dataLocation['name'],
                        "type" => $dataLocation['type'],
                        "price" => $dataLocation['price'],
                        "qty" => 1,
                        //(int) memastikan dan mengubah tipe data menjadi integer
                        "price_after_qty" => (int)$bulanOrder * (int)$dataLocation['price'],
                    ];
                    # code...
                }
            } else {
                $dataLocation = Product::where('id', 10)->first();
                if ($bulanOrder == "12") {
                    $datacenterGet = [
                        "id" => 10,
                        "label" => $serverGet['serverLabel'], // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                        "name_product" => $dataLocation['name'],
                        "type" => $dataLocation['type'],
                        "price" => $dataLocation['price'],
                        "qty" => 1,

                        //(int) memastikan dan mengubah tipe data menjadi integer
                        "price_after_qty" => (int)$bulanOrder * (int)$dataLocation['price'] - 2500000,
                        // "price_after_qty" => (int)$request->bulan * (int)$dataLocation['price'],
                    ];
                    # code...
                } else {
                    $datacenterGet = [
                        "id" => 10,
                        "label" => $serverGet['serverLabel'], // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                        "name_product" => $dataLocation['name'],
                        "type" => $dataLocation['type'],
                        "price" => $dataLocation['price'],
                        "qty" => 1,

                        //(int) memastikan dan mengubah tipe data menjadi integer
                        "price_after_qty" => (int)$bulanOrder * (int)$dataLocation['price'],
                        // "price_after_qty" => (int)$request->bulan * (int)$dataLocation['price'],
                    ];
                }
            }

            $orderId['bulan'] = $bulanOrder;
            $orderId['votes'] = $bulanOrder;
            $statusId['payment'] = $bulanOrder;


            // $endFill = 1;
            $productGet[0] = $datacenterGet;
            $totalPrice = $datacenterGet['price_after_qty'];

            $totalEnd = $existingTotal + $totalPrice;
            $orderId['total_price'] = $totalEnd;
        }

        $statusId->save();


        $orderId['products'] = $productGet;

        $orderId->save();
        if ($type == 'colocation') {

            return redirect()->route('status.colocation')->with('success', 'Pemesanan Colocation dengan Data yang Sama !');
        } else {

            return redirect()->route('status.colocation')->with('success', 'data lunas sudah terupdate');
        }
    }

    private function calculateTotalPrice(Order $order)
    {
        $totalPrice = 0;

        $product1 = $order->products[0];
        $productType = $product1['type'];
        $orderStatus = Order_status::where('order_id', $order['id'])->first();
        // dd($orderStatus,$order['id']);

        foreach ($order->products as $product) {
            $totalPrice += $product['price_after_qty'];
        }

        if ($order->bulan == 12 && $productType == 'dedicated' && $orderStatus['payment'] > 0) {
            $totalPrice += 300000;
        }

        return $totalPrice;
    }

    public function custom($id)
    {

        $orders = Order::find($id);
        $status = Order_Status::where('order_id', $id)->first();
        $colocationProducts = Product::where('type', 'colocation')->get();


        return view('order.admin.bayar', compact('orders', 'status', 'colocationProducts'));
    }
    public function show($id)
    {

        $status2 = Order_status::find($id);

        return view('order.admin.show', compact('status2'));
    }

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

        $search = $request->input('search');


        $orders = Order::whereDate('created_at', 'like', "%$search%")->simplePaginate(5);



        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(5);\
        // $orders = Order::all();
        $perPage = request('perPage', 100); // Default to 10 items per page
        // $orders = Order::simplePaginate($perPage);

        $arron = [];
        foreach ($orders as $order) {
            // $totalArr = array_count_values($order['products']);
            $countArr = last($order['products']);
            array_push($arron, $countArr);
        }
        // dd($arron[0]['type']);

        $dataCompile = [];
        for ($i = 0; $i < count($arron); $i++) {
            if ($arron[$i]['type'] == 'dell' || $arron[$i]['type'] == 'HP' || $arron[$i]['type'] == 'supermicro') {

                array_push($dataCompile, $orders[$i]);
            }
        }
        $statusData = [];
        // dd($statusData[1][]);
        for ($i = 0; $i < count($dataCompile); $i++) {

            $datastatus = $dataCompile[$i]['id'];
            $statusId = Order_status::where('order_id', $datastatus)->first();
            array_push($statusData, $statusId);
        }

        // dd
        $userData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {

            $dataUser = $dataCompile[$i]['user_id'];
            $userId = User::where('id', $dataUser)->first();
            array_push($userData, $userId);
        }

        // dd($arron,$orders,$dataCompile);
        array_push($userData, $statusData);
        // dd($userData);

        $title = 'Delete Server!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        // $product = Product::where('created_at',$order)->first();

        // format assoc dimasukkan ke array penampung sebelumnya
        $serverProducts = [];
        foreach ($orders as $order) {
            // dd($orders);
            // $serverData = data_get(last($order['products']),'', 1);
            $serverData = last($order['products']);
            $countServer = count($order['products']) - 2;
            $serverType = data_get($serverData, 'type', false);
            if ($serverType == 'freeze') {
                $freezeServer = $order['products'][$countServer];
                array_push($serverProducts, $freezeServer);
            } else {
                array_push($serverProducts, $serverData);
            }
        }
        $validate = data_get($serverProducts, '0', false);
        if($validate == false){
            $paginateProducts = $this->paginate($serverProducts);
            
        }else{
            $paginateProducts = $this->paginate($serverProducts);
            // $userData = $this->paginate($userData);
            // dd([$userData]);

        }


        return view('order.server.index', compact('products', 'orders', 'userData', 'dataCompile', 'serverProducts','statusData','validate','paginateProducts'));
        
    }

    public function searchData(Request $request)
    {
        // anomali

        $input = $request->input('search');

        $datacenter = Order::All();
        $data = [0];
        $dataBogor = Order::whereIn('datacenter', $data)->get();
        $bogorAsnet = [];
        for ($i = 0; $i < count($datacenter); $i++) {
            $get = data_get($datacenter[$i]['datacenter'], '0.datacenter', 0);
            if ($get == 'Bogor') {
                $bogor = $datacenter[$i];
                array_push($bogorAsnet, $bogor);
            }
        }
        // array_push();
        // maybe it hep 

        // $size = count(collect($request)->get('id'));
        // 
        $count = 0;
        $countArr = count($bogorAsnet);
        if ($bogorAsnet !== null) {
            
            for ($i = 0; $i < $countArr; $i++) {
                
                // dd(count($bogorAsnet),$bogorAsnet[$i]['datacenter'][0]['rack']);
                if ($bogorAsnet[$i]['datacenter'][0]['rack'] == $input) {
                    // dd($datacenter['rack']);    
                    $bogor = $bogorAsnet[$i];
                    // array_push($bogorAsnet,$bogor);
                } else {
                    # code...
                    // array_splice($bogorAsnet,$i);
                    data_forget($bogorAsnet, $i);
                    // dd($bogorAsnet);    
                }
            }
        }
        
        
        
        
        //proses ambil data
        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(5);\
        // $orders = Order::all();
        $perPage = request('perPage', 100); // Default to 10 items per page
        // $orders = Order::simplePaginate($perPage);
        $orders = Order::All();
        $arron = [];
        foreach ($orders as $order) {
            // $totalArr = array_count_values($order['products']);
            $countArr = last($order['products']);
            array_push($arron, $countArr);
        }
        // dd($arron[0]['type']);
        
        $dataCompile = [];
        for ($i = 0; $i < count($arron); $i++) {
            if ($arron[$i]['type'] == 'dell' || $arron[$i]['type'] == 'HP' || $arron[$i]['type'] == 'supermicro') {
                
                array_push($dataCompile, $orders[$i]);
            }
        }
        $statusData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {
            
            $datastatus = $dataCompile[$i]['id'];
            $statusId = Order_status::where('order_id', $datastatus)->first();
            array_push($statusData, $statusId);
        }
        
        // dd
        $userData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {
            
            $dataUser = $dataCompile[$i]['user_id'];
            $userId = User::where('id', $dataUser)->first();
            array_push($userData, $userId);
        }
        
        array_push($userData, $statusData);
        // $product = Product::where('created_at',$order)->first();
        
        // format assoc dimasukkan ke array penampung sebelumnya
        
        
        $title = 'Delete Data Server!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        // dd($bogorAsnet);
        
        return view('internal.bogor', compact('bogorAsnet', 'products', 'orders', 'userData', 'dataCompile', 'statusData'));
    }
    
    public function searchData2(Request $request)
    {
        // Anuman
        $input = $request->search;

        $datacenter = Order::All();
        $data = [0];
        $dataJakarta = Order::whereIn('datacenter', $data)->get();
        $jakartaCyber = [];
        for ($i = 0; $i < count($datacenter); $i++) {
            $get = data_get($datacenter[$i]['datacenter'], '0.datacenter', 0);
            if ($get == 'Jakarta') {
                $jakarta = $datacenter[$i];
                array_push($jakartaCyber, $jakarta);
            }
        }
        $count = 0;
        $countArr = count($jakartaCyber);
        if ($jakartaCyber !== null) {

            for ($i = 0; $i < $countArr; $i++) {

                // dd(count($jakartaCyber),$jakartaCyber[$i]['datacenter'][0]['rack']);
                if ($jakartaCyber[$i]['datacenter'][0]['rack'] == $input) {
                    // dd($datacenter['rack']);    
                    $jakarta = $jakartaCyber[$i];
                    // array_push($jakartaCyber,$jakarta);
                } else {
                    # code...
                    // array_splice($jakartaCyber,$i);
                    data_forget($jakartaCyber, $i);
                    // dd($jakartaCyber);    
                }
            }
        }




        //proses ambil data
        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(5);\
        // $orders = Order::all();
        $perPage = request('perPage', 100); // Default to 10 items per page
        // $orders = Order::simplePaginate($perPage);
        $orders = Order::All();
        $arron = [];
        foreach ($orders as $order) {
            // $totalArr = array_count_values($order['products']);
            $countArr = last($order['products']);
            array_push($arron, $countArr);
        }
        // dd($arron[0]['type']);

        $dataCompile = [];
        for ($i = 0; $i < count($arron); $i++) {
            if ($arron[$i]['type'] == 'dell' || $arron[$i]['type'] == 'HP' || $arron[$i]['type'] == 'supermicro') {

                array_push($dataCompile, $orders[$i]);
            }
        }
        $statusData = [];
        // dd($statusData[1][]);
        for ($i = 0; $i < count($dataCompile); $i++) {

            $datastatus = $dataCompile[$i]['id'];
            $statusId = Order_status::where('order_id', $datastatus)->first();
            array_push($statusData, $statusId);
        }

        // dd
        $userData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {

            $dataUser = $dataCompile[$i]['user_id'];
            $userId = User::where('id', $dataUser)->first();
            array_push($userData, $userId);
        }

        array_push($userData, $statusData);
        // $product = Product::where('created_at',$order)->first();

        // format assoc dimasukkan ke array penampung sebelumnya

        $title = 'Delete Data Server!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('internal.jakarta', compact('jakartaCyber', 'products', 'orders', 'userData', 'dataCompile', 'statusData'));
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

        // perakun

        // Cari berdasarkan id OrderStatus terus update

        $order = Order::find($id);
        $statusOrder = Order_status::where('order_id', $id)->first();

        $statusValidate = data_get($statusOrder, 'access', 0);
        // dd($statusOrder,$statusValidate);
        $isColocation = false;
        if ($statusValidate !== 0) {
            $productsGet = $order['products'];
            if ($productsGet[0]['type'] == 'colocation') {
                $isColocation = true;
            } else {
                $isColocation = false;
            }
        }

        if ($isColocation == true) {

            $productId = $request->name_product;
            // $bulanGet = $request->months;
            $productNew = Product::where('id', $request->name_product)->first();
            $newProduct = new Product;
            $newProduct->id = $request->name_product;
            $newProduct->name_product = $productNew['name'];
            $newProduct->type = $productNew['type'];
            $newProduct->price = $productNew['price'];
            $newProduct->qty = $productNew['qty'];
            $newProduct->price_after_qty = (int)$productNew['price']  + 500000;
            // $newProduct->price_after_qty = (int)$productNew['price'] * (int)$bulanGet + 500000;


            $datacenter = $order['datacenter'];
            $datacenter[0]['rack'] = $request->rack;

            if ($productId == 6 || $productId == 9 || $productId == 12) {
                $datacenter[0]['datacenter'] = "Bogor";
                $entryPrice = 0;
            } else {
                $datacenter[0]['datacenter'] = "Jakarta";
                $entryPrice = 750000;
            }

            $order['datacenter'] = $datacenter;

            $existingTotal = $order['total_price'];

            // Setting jika Prduck sebeumnya emrupakan seteah ayanan dedicated atau prduk ccatinsewa angsung
            if ($order['products'][1]['type'] == 'ram' || $order['products'][1]['type'] == 'ssd') {
                $priceHas = 0;
                $totalPrice = $priceHas;
            } else {
                # code...
                $priceHas = $this->calculateTotalPrice($order);
                $totalPrice = $priceHas - $order['products'][0]['price_after_qty'];
            }

            // menghitung Semua Tta keseuruhan 
            $totalAll = $newProduct['price_after_qty'] + $entryPrice + $existingTotal;

            $order['total_price'] = $totalAll;
            // untuk jika price pertama saja yang diupdate atau opsi satu
            // dd($order['id'],$totalPrice,$priceHas,$existingTotal,$productId,$bulanGet,$totalAll,$datacenter);

            $product = $order['products'];
            $product[0] = $newProduct;
            $order['products'] = $product;

            // $order['bulan'] = "$bulanGet";
            // $order['votes'] = $bulanGet;

            // $status['payment'] = $bulanGet;

            $order->save();

            return redirect()->route('status.colocation')->with('success', 'Berhasil Pemindahan Server!');
        } elseif ($statusValidate !== 0 && $statusOrder['access'] == 3) {
            $selectedMonths = $request->input('months');
            $selectedDedicatedProductId = $request->input('name_product');


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
            $totalPrice = $this->calculateTotalPrice($order);

            if ($order['bulan'] == 4) {

                $existingProduct = $order->products[0] ?? null;
                // $bulanPrev= $order->bulan(0) ?? null;
                // $Month = 12;
                $productsGet = $order['products'];

                $countF = count($productsGet);
                $dataCount = [];

                // dd($countF);
                for ($i = 0; $i < $countF; $i++) {

                    if ($productsGet[$i]['type'] == 'freeze') {
                        $countType = $i;
                        array_push($dataCount, $countType);
                        // dd('p');
                    }
                }

                if (count($dataCount) !== 0) {
                    for ($i = 0; $i < count($dataCount); $i++) {
                        $get = $dataCount[$i];
                        data_forget($productsGet, $get);
                    }
                }

                $freezeProducts = count($productsGet) - $countType;

                // array_splice($productsGet,$freezeProducts);
                // if($freezeProducts )
                // data_forget($productsGet,$freezeProducts)
                // dd($freezeProducts,$dataCount,$productsGet,$countType);



                $previousUpdatedAt = $order->updated_at;

                $order['products'] = $productsGet;


                $existingTotal = $order['total_price'];
                $priceOne = $order['products'][0]['price_after_qty'];

                $totalEnd = $existingTotal + $priceOne;

                // $bulanInput = $request->input('bulanFreeze');

                // $order->bulan = [$bulanInput,$bulanPrev];
                // $order->save();

                $order->update([
                    'total_price' => $totalEnd,
                    'bulan' => "$statusOrder->data",
                    'votes' => $order['votes'] + 1, // Increment votes

                ]);


                Order_status::where('order_id', $id)->update([
                    'access' => 5,
                    'data' => 5,
                    'payment' => $order['votes'],
                ]);
                //  $order->increment('votes', 1, [
                //     'updated_at' => $order->updated_at
                // ]);

                $order->save();

                return redirect()->route('status.dedicated')->with('success', 'Berhasil Mengubah Cicilan Kembali!');
            } else {



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

                if ($selectedDedicatedProductId == 1) {
                    $baseCost = $request->freezePrice;
                } elseif ($selectedDedicatedProductId == 2) {

                    $baseCost = $request->freezePrice;
                }

                $baseCosti = $baseCost - 200000;

                if ($selectedMonths == 1) {
                    $totalCost = $baseCost;
                } elseif ($selectedMonths == 2) {
                    $totalCost = ($baseCost);
                } elseif ($selectedMonths == 3) {
                    // $totalCost = ($baseCost ) + ($additionalCost * 5); // Additional cost for 5 months
                    $totalCost = ($baseCost); // Additional cost for 5 months
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
                $freezePrice = $request->freezePrice;
                if ($selectedDedicatedProductId == 1) {
                    $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                    $newProduct->id = 1;
                    $newProduct->name_product = "(FREEZE) Dedicated Bogor";
                    $newProduct->price = $freezePrice;
                    $newProduct->type = "freeze";
                    $newProduct->qty = 1;
                    $newProduct->price_after_qty = $freezePrice;
                } elseif ($selectedDedicatedProductId == 2) {
                    $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                    $newProduct->id = 2;
                    $newProduct->name_product = "(FREEZE) Dedicated Jakarta";
                    $newProduct->price = $freezePrice;
                    $newProduct->type = "freeze";
                    $newProduct->qty = 1;
                    $newProduct->price_after_qty = $freezePrice;
                }



                // $newProduct->votes = 0;
                // $newProduct->total_price = $totalCost;
                // $newProduct->bulan = $selectedMonths;

                // Simpan produk yang sudah ada dan produk baru ke dalam array produk pesanan
                // get the bulan from buian iobject no-1
                // $order->bulan = [$existingBulan,$selectedMonths];
                if ($order->bulan == 12) {
                    if ($selectedDedicatedProductId == 1) {
                        $newProduct->name_product = "(FREEZE) Dedicated Bogor";
                    } elseif ($selectedDedicatedProductId == 2) {
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


                $allExisting = $order['products'];
                $countLast = count($allExisting) - 1;
                // dd($allExisting,$countLast,data_forget($allExisting,$countLast));

                // dd(array_push($allExisting,[$newProduct]),$allExisting,$countLast,array_pop($allExisting),$allExisting); 

                if (last($allExisting)['type'] == 'freeze') {
                    // data_forget($allExisting,$countLast);
                    array_pop($allExisting);
                    array_push($allExisting, $newProduct);
                    // dd($countLast);
                    // data_forget($allExisting,$countLast);
                } else {
                    array_push($allExisting, $newProduct);
                }


                $order['products'] = $allExisting;

                // $order->products = [$existingProduct, $newProduct];
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
            return redirect()->route('status.dedicated')->with('success', 'Berhasil Melakukan Freeze!');
        } elseif ($order) {

            $existingProduct1 = isset($order->products[0]) ? $order->products[0] : null;
            $existingProduct2 = isset($order->products[1]) ? $order->products[1] : null;
            $existingProduct3 = isset($order->products[2]) ? $order->products[2] : null;
            $existingProduct4 = isset($order->products[3]) ? $order->products[3] : null;

            $price = data_get($order['products'], '1.price', 0);
            $lastData = last($order['products']);
            // dd($request->custom_qty);
            if ($existingProduct2) {
                // Mengupdate nilai produk yang ada
                $existingProduct2['price'] = $request->custom_price;
                $existingProduct2['qty'] = $request->custom_qty;
                $existingProduct2['price_after_qty'] = $request->custom_price;
                $existingProductObject2 = $this->convertArrayToStdClass($existingProduct2);
            }

            $lastDataObject = $this->convertArrayToStdClass($lastData);

            if ($existingProduct4) {
                $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);
                $existingProductObject3 = $this->convertArrayToStdClass($existingProduct3);
                $existingProductObject4 = $this->convertArrayToStdClass($existingProduct4);

                $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4, $lastDataObject];
            } elseif ($existingProduct3) {
                $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);
                $existingProductObject3 = $this->convertArrayToStdClass($existingProduct3);

                $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $lastDataObject];
            } elseif ($existingProduct2) {
                $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);

                $order->products = [$existingProductObject1, $existingProductObject2, $lastDataObject];
            }

            // dd($lastData,[$existingProductObject1, $existingProductObject2, $existingProductObject3,$lastDataObject]);
            $customTotal = $request->custom_price * $request->custom_qty;
            // Mengupdate nilai total pesanan
            $order->total_price += $customTotal;


            $order->save();

            return redirect()->route('status.dedicated')->with('success', 'Berhasil menambah nilai access!');
        } else {
            // Handle case jika order tidak ditemukan

            // Handle case jika order tidak ditemukan


            // Penanganan jika pesanan tidak ditemukan


            // Penanganan jika pesanan tidak ditemukan


            // Penanganan jika pesanan tidak ditemukan


            // Penanganan jika pesanan tidak ditemukan


            // Penanganan jika pesanan tidak ditemukan


            $status2 = Order_status::find($id);


            $request->validate([
                // ... Validasi lainnya ...
                // 'attachment' => 'required', // Maksimum 2 MB
                'attachment.*' => 'nullable|mimes:pdf,PDF,png,jpg,gif,PNG,JPG,GIF|max:2048', // Maksimum 2 MB
            ]);

            // Dapatkan order berdasarkan ID
            // $orderStatus = Order_status::findOrFail($id);

            // Perbarui data orderStatus
            // $status2->name_customer = $request->input('name_customer');
            // ... Perbarui kolom lainnya ...

            // Upload file PDF jika diunggah
            if ($request->hasFile('attachment')) {

                // foreach($request->file('attachment') as $upload){

                //     $uploadName = 'spk' . time() . '.' . $upload->getClientOriginalExtension();
                //     $data[] = $uploadName;
                $existingAttachments = data_get($status2, 'attachment', 0);

                if ($existingAttachments == 0) {
                    $attachTotal = count([$existingAttachments]) - 1;
                } else {
                    $attachTotal = count([$existingAttachments]);
                }
                // }
                // if ($status2->attachment) {
                //     $previousFilePath = public_path('attachments/' . $status2->attachment);
                for ($i = 0; $i < $attachTotal; $i++) {
                    if ($status2->attachment[$i]) {

                        $previousFilePath = public_path('attachments/' . $status2->attachment[$i]);

                        // Periksa apakah file PDF sebelumnya ada sebelum menghapus
                        if (File::exists($previousFilePath)) {
                            File::delete($previousFilePath);
                        }
                    }
                }


                foreach ($request->file('attachment') as $upload) {
                    $uploadName = 'spk' . time() . '.' . $upload->getClientOriginalExtension();
                    $upload->move(public_path('attachments'), $uploadName);
                    $data[] = $uploadName;
                }
                // dd($data)   ;
                //     // Periksa apakah file PDF sebelumnya ada sebelum menghapus
                //     if (File::exists($previousFilePath)) {
                //         File::delete($previousFilePath);
                //     }
                // }
                // dd($data);
                // $pdfFile = $request->file('attachment');
                // $pdfFileName = 'spk' . time() . '.' . $pdfFile->getClientOriginalExtension();
                // $pdfFile->storeAs('attachments', $pdfFileName, 'public'); // Simpan file di penyimpanan yang diinginkan

                // $pdfFile->move(public_path('attachments'), $pdfFileName);

                // Perbarui kolom file pada model status2
                // $status2 = new Order_status['attachment'];
                $status2->attachment = $data;

                // $status2->attachment = $pdfFileName;
                $status2->save();
                if ($request->has('endData')) {

                    return redirect()->route('status.status', ['id' => $status2->id])->with('success', 'Berhasil Menambahkan Data');
                } else {
                    return redirect()->route('status.show', ['id' => $status2->id])->with('success', 'Berhasil Mengupload Data!');
                }
            }

            // ......... kode custom produk .......

            // ... Kode lainnya ...


            // if ($status2) {
            if ($request->has('data')) {
                $status2->update([
                    'data' => $request->data,
                ]);

                // Misalnya, Anda ingin menambahkan increment data
                $status2->increment('data', 1);

                if ($status2->data == 2) {
                    $status2->update([$status2->status = 'Proses-SPK']);
                } else if ($status2->data == 3) {
                    $status2->update([$status2->status = 'Proses-TTD']);
                } else if ($status2->data == 4) {
                    $status2->update([$status2->status = 'Done']);
                }
                return redirect()->route('status.dedicated')->with('success', 'Berhasil mengubah status SPK Client!');
            } elseif ($request->has('access')) {
                // Jika ya, tambahkan nilai access
                // suspend menu
                // golden 

                if ($request->input('access') == 2 && $request->input('freeze') == 1 && $request->input('terminated') == 1) {

                    return redirect()->route('status.dedicated')->with('success', 'Tidak Mengubah Apapun');
                }

                $orderTerminated = Order::where('id', $status2['order_id'])->first();
                $userId = $orderTerminated['user_id'];
                $userData = User::where('id', $userId)->first();

                // menu suspend
                if ($request->input('access') == 3) {
                    $status2['access'] = 0;
                    $votes = $orderTerminated['votes'];
                    $dataGet = $userData['entryData'];


                    $entryVotes = data_get($dataGet, '0', false);
                    // dd($entryVotes);
                    // dd($entryVotes);
                    if ($entryVotes == false) {
                        $dataEnd = [
                            "votes" => $orderTerminated['votes'],
                        ];

                        $dataGet[0][2] = $dataEnd;

                        // dd($dataGet);
                    } else {
                    }
                    $entryData = data_get($dataGet[0], '2.votes', false);


                    if ($entryData == false) {

                        data_fill($dataGet[0][2], 'votes', $votes);
                    } else {
                        $countData = count($dataGet[0][2]);
                        foreach ($dataGet[0][2] as $key => $value) {
                            if ($key == "votes") {

                                $data1 = $key;
                                unset($dataGet[0][2][$key]);
                            }
                        }

                        // array_splice($dataGet[0][2], 2);
                        data_fill($dataGet[0][2], 'votes', $votes);

                        // dd($countData,$dataGet,$votes,$entryData);
                    }
                    $userData['entryData'] = $dataGet;

                    // dd($dataGet);


                    $userData->save();
                    $orderTerminated->save();
                    $status2->save();

                    return redirect()->route('status.dedicated')->with('success', 'Berhasil Mengubah Status ke Suspend!');
                }
                // terminated  2 menyimbolkan bahwa user akan di termintaed denga n uru atau user tidak dapayt memesan kembali
                elseif ($request->input('terminated') == 2) {
                    $status2['access'] = 2;
                    $votes = $orderTerminated['votes'];
                    $dataGet = $userData['entryData'];
                    $entryVotes = data_get($dataGet, '0', false);
                    // dd($entryVotes);
                    if ($entryVotes == false) {
                        $dataEnd = [
                            "votes" => $orderTerminated['votes'],
                        ];

                        $dataGet[0][2] = $dataEnd;

                        // dd($dataGet);
                    } else {
                    }
                    $entryData = data_get($dataGet[0], '2.votes', false);

                    if ($entryData == false) {

                        data_fill($dataGet[0][2], 'votes', $votes);
                    } else {
                        $countData = count($dataGet[0][2]);
                        foreach ($dataGet[0][2] as $key => $value) {
                            if ($key == "votes") {

                                $data1 = $key;
                                unset($dataGet[0][2][$key]);
                            }
                        }

                        // array_splice($dataGet[0][2], 2);
                        data_fill($dataGet[0][2], 'votes', $votes);

                        // dd($countData,$dataGet,$votes,$entryData);
                    }
                    $userData['entryData'] = $dataGet;

                    // dd($dataGet);


                    $userData->save();
                    $orderTerminated->save();
                    $status2->save();

                    return redirect()->route('status.dedicated')->with('success', 'Berhasil Mengubah Status ke Terminated!');
                } elseif ($request->input('terminated') == 3) {
                    // terminated 3 menyimbolkan agar user sebelumnya memulai pemesanan order dengan votes sebelumnya atau votes terakhir dari pembelian 
                    // $orderTerminated['votes'] = 
                    if ($status2['payment'] < 1) {

                        $productPrice = $orderTerminated['products'][0]['id'];

                        $productHas = Product::find($productPrice);

                        $priceUpdate = $orderTerminated['products'];
                        $priceUpdate[0]['price'] = $productHas['price'];
                        $priceUpdate[0]['price_after_qty'] = $productHas['price'];

                        $orderTerminated['products'] = $priceUpdate;

                        // dd($productPrice, $priceUpdate);

                        $dataUser = User::where('id', $request->userName)->first();

                        $dataEntry = $dataUser['entryData'];
                        $existingVotes = data_get($dataEntry[0], '2.votes', 1);
                        $orderTerminated['votes'] = $existingVotes;

                        $orderTerminated['bulan'] = "$status2->data";
                        // Perang

                        // dd($dataEntry);

                        $status2->update(['access' => 1]);
                        $status2->update(['payment' => $existingVotes]);
                        $status2->update(['data' => 4]);

                        $orderTerminated['user_id'] = $request->userName;
                        $dataUser = User::where('id', $request->userName)->first();
                        $orderTerminated['name_customer'] = $dataUser['name'];

                        $dataUser->save();
                    } else {
                        // Perang
                        $status2->update(['access' => 1]);
                    }

                    $userData->save();
                    $orderTerminated->save();


                    return redirect()->route('status.dedicated')->with('success', 'Berhasil Berlangganan Kembali dengan Cicilan Sebelumnya !');
                }


                if ($request->input('terminated') == 1 && $request->input('access') == 1) {

                    if ($status2['payment'] < 1) {
                        // if ($request->input('freeze') == 1 && $status2['payment'] < 1) {


                        $orderBulan = Order::where('id', $status2['order_id'])->first();
                        $orderBulan['bulan'] = "$status2->data";
                        $orderBulan['votes'] = 1;
                        $orderBulan->save();
                        // dd($orderBulan);

                        $access = 1;

                        $status2->update(['access' => $access]);
                        $status2->update(['payment' => 1]);
                        $status2->update(['data' => 4]);

                        $orderBulan['user_id'] = $request->userName;
                        $dataUser = User::where('id', $request->userName)->first();
                        $orderBulan['name_customer'] = $dataUser['name'];


                        $productPrice = $orderBulan['products'][0]['id'];

                        $productHas = Product::find($productPrice);

                        $priceUpdate = $orderBulan['products'];
                        $priceUpdate[0]['price'] = $productHas['price'];
                        $priceUpdate[0]['price_after_qty'] = $productHas['price'];

                        $orderBulan['products'] = $priceUpdate;

                        // dd($productPrice,$priceUpdate);
                        $existingTotal = $orderBulan['total_price'];

                        $orderBulan['total_price'] = $priceUpdate['0']['price_after_qty'];
                        // $orderBulan['total_price'] = $priceUpdate['0']['price_after_qty'] + $existingTotal;

                        $orderBulan->save();


                        return redirect()->route('status.dedicated')->with('success', 'Berhasil Mengubah Menu Sewa!');
                    } else {

                        $orderBulan = Order::where('id', $status2['order_id'])->first();
                        $orderBulan['votes'] = 1;
                        $orderBulan->save();
                        $status2->update(['payment' => 1]);
                        $status2->update(['access' => 1]);

                        return redirect()->route('status.dedicated')->with('success', 'Berhasil Mengembalikan User dengan Cicilan Awal!');
                    }
                }

                // freeze menu
                elseif ($request->input('access') == 2 && $request->input('freeze') == 2) {
                    $freeze = $status2->access = 3;

                    $status2->update(['access' => $freeze]);

                    return redirect()->route('status.dedicated')->with('success', 'Berhasil Mengubah ke Menu Freeze!');
                }

                // unfreeze menu
                elseif ($request->input('access') == 2 && $request->input('freeze') == 3) {
                    $freeze = $status2->access = 4;

                    $status2->update(['access' => $freeze]);

                    return redirect()->route('status.dedicated')->with('success', 'Mengembalikan Status Dedicated!');
                }
            }

            return redirect()->route('status.show', ['id' => $status2->id])->with('success', 'Berhasil menambah nilai access!');
        }
    }


    public function indexServer(Request $request)
    {
        //proses ambil data
        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(5);\
        // $orders = Order::all();
        $perPage = request('perPage', 5); // Default menjadi 5 items perpage
        // $orders = Order::simplePaginate($perPage);
        $orders = Order::All();

        $arron = [];
        foreach ($orders as $order) {
            // $totalArr = array_count_values($order['products']);
            $countArr = last($order['products']);
            array_push($arron, $countArr);
        }
        
        $dataCompile = [];
        for ($i = 0; $i < count($arron); $i++) {
            if ($arron[$i]['type'] == 'dell' || $arron[$i]['type'] == 'HP' || $arron[$i]['type'] == 'supermicro') {
                
                array_push($dataCompile, $orders[$i]);
            }
        }
        $statusData = [];
        // dd($statusData[1][]);
        for ($i = 0; $i < count($dataCompile); $i++) {
            
            $datastatus = $dataCompile[$i]['id'];
            $statusId = Order_status::where('order_id', $datastatus)->first();
            array_push($statusData, $statusId);
        }
        
        // dd
        $userData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {
            
            $dataUser = $dataCompile[$i]['user_id'];
            $userId = User::where('id', $dataUser)->first();
            array_push($userData, $userId);
        }
        // dd($userData);
        
        // dd($arron,$orders,$dataCompile);
        array_push($userData, $statusData);
        // dd($userData,$dataCompile,$statusData);

        $title = 'Delete Server!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        // dd($request->deletePop);
        // if(confirmDelete($title, $text)){
        //     return redirect()->route('detail_server.delete',$request->deletePop);
        // };
        $serverProducts = [];
        foreach ($orders as $order) {
            // dd($orders);
            // $serverData = data_get(last($order['products']),'', 1);
            $serverData = last($order['products']);
            $countServer = count($order['products']) - 2;
            $serverType = data_get($serverData, 'type', false);
            if ($serverType == 'freeze') {
                $freezeServer = $order['products'][$countServer];
                array_push($serverProducts, $freezeServer);
            } else {
                array_push($serverProducts, $serverData);
            }
        }
        $validate = data_get($serverProducts, '0', false);
        if($validate == false){
            $paginateProducts = $this->paginate($serverProducts);
            
        }else{
            $paginateProducts = $this->paginate($serverProducts);
        }
        
        // $serverProducts->withPath('');

        return view('order.server.index', compact('products', 'orders', 'userData', 'dataCompile', 'serverProducts','statusData','validate','paginateProducts'));
    }
/**
     * Paginate an array of items.
     *
     * @return LengthAwarePaginator         The paginated items.
     */
    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    
    public function singleServer(Request $request, $id)
    {
        //proses ambil data
        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(5);\
        // $orders = Order::all();
        $perPage = request('perPage', 100); // Default to 10 items per page
        // $orders = Order::simplePaginate($perPage);
        // $orders = Order::All();
        $orders = Order::where('user_id', $id)->get();
        // dd($orders);
        $arron = [];
        foreach ($orders as $order) {
            // $totalArr = array_count_values($order['products']);
            $lastProduct = last($order['products']);
            if ($lastProduct['type'] == "freeze") {
                $countArr = data_get($order['products'], 4, false);
            } else {
                $countArr = last($order['products']);
            }
            array_push($arron, $countArr);
        }
        // dd($arron[0]['type']);

        $dataCompile = [];
        for ($i = 0; $i < count($arron); $i++) {
            if ($arron[$i]['type'] == 'dell' || $arron[$i]['type'] == 'HP' || $arron[$i]['type'] == 'supermicro') {

                array_push($dataCompile, $orders[$i]);
            }
        }
        $statusData = [];
        // dd($statusData[1][]);
        for ($i = 0; $i < count($dataCompile); $i++) {

            $datastatus = $dataCompile[$i]['id'];
            $statusId = Order_status::where('order_id', $datastatus)->first();
            array_push($statusData, $statusId);
        }

        // dd
        $userData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {

            $dataUser = $dataCompile[$i]['user_id'];
            $userId = User::where('id', $dataUser)->first();
            array_push($userData, $userId);
        }

        // dd($arron,$orders,$dataCompile);
        array_push($userData, $statusData);
        // dd($userData);

        $title = 'Delete Server!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        // dd($request->deletePop);
        // if(confirmDelete($title, $text)){
        //     return redirect()->route('detail_server.delete',$request->deletePop);
        // };

        // dd($userData);
        // mannggil html yang ada di folder resources/views/product.index.blade.php
        //compact : mengirim data ke blade 
        return view('order.server.single', compact('products', 'orders', 'userData', 'dataCompile', 'statusData'));
    }

    public function indexBogor()
    {
        $datacenter = Order::All();
        $data = [0];
        $dataBogor = Order::whereIn('datacenter', $data)->get();
        $bogorAsnet = [];
        for ($i = 0; $i < count($datacenter); $i++) {
            $get = data_get($datacenter[$i]['datacenter'], '0.datacenter', 0);
            if ($get == 'Bogor') {
                $bogor = $datacenter[$i];
                array_push($bogorAsnet, $bogor);
            }
        }
        // $movies = [];
        //     foreach ($bogorAsnet as $key) {
        //         # code...
        //         $movies = Order::where('id',$key['id'])
        //         ->get();
        //         array_push($movies,$bogorAsnet);
        //         // ->where('name', 'LIKE', "%$search%")
        //     }
        // dd($bogorAsnet,$movies);
        // $movies = Order::select($bogorAsnet['id'],'name' )
        // 		->where('rack', 'LIKE', "%$search%")
        // 		->get();



        // dd($datacenter[0]['datacenter'][0]['datacenter'],$dataBogor,$bogorAsnet[0]['datacenter'][0]['datacenter']);

        //proses ambil data
        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(5);\
        // $orders = Order::all();
        $perPage = request('perPage', 100); // Default to 10 items per page
        // $orders = Order::simplePaginate($perPage);
        $orders = Order::All();

        $arron = [];
        foreach ($orders as $order) {
            // $totalArr = array_count_values($order['products']);
            $countArr = last($order['products']);
            array_push($arron, $countArr);
        }
        // dd($arron[0]['type']);

        $dataCompile = [];
        for ($i = 0; $i < count($arron); $i++) {
            if ($arron[$i]['type'] == 'dell' || $arron[$i]['type'] == 'HP' || $arron[$i]['type'] == 'supermicro') {

                array_push($dataCompile, $orders[$i]);
            }
        }
        $statusData = [];
        // dd($statusData[1][]);
        for ($i = 0; $i < count($dataCompile); $i++) {

            $datastatus = $dataCompile[$i]['id'];
            $statusId = Order_status::where('order_id', $datastatus)->first();
            array_push($statusData, $statusId);
        }

        // dd
        $userData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {

            $dataUser = $dataCompile[$i]['user_id'];
            $userId = User::where('id', $dataUser)->first();
            array_push($userData, $userId);
        }

        array_push($userData, $statusData);
        // dd($userData);


        $title = 'Delete Server!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        // dd($userData);
        // mannggil html yang ada di folder resources/views/product.index.blade.php
        //compact : mengirim data ke blade 
        return view('internal.bogor', compact('bogorAsnet', 'products', 'orders', 'userData', 'dataCompile', 'statusData'));
    }

    public function indexJakarta()
    {
        $racks = [];
        // if ($request->has('q')) {
        // $search = $request->q;
        $racks = Order::select('id', 'datacenter')
            //  ->whereJsonContains('order->datacenter', 'LIKE', '%"rack":"' . "usus" . '"%')
            ->get();
        // }
        // rakCommunity

        $dataRak = [];
        $dataId = [];
        $rackOriginList = [];

        foreach ($racks as $rak) {

            if ($rak["datacenter"][0]["datacenter"] == "Jakarta") {
                // foreach($rak[] as $getRack){
                array_push($rackOriginList, $rak);
                array_push($dataRak, $rak['datacenter'][0]["rack"]);
                array_push($dataId, $rak['id']);
            }
        }
        $rackHave = [$dataRak, $dataId];
        $totalData = count($dataRak) + count($dataId);
        // for($i = 0; $i < count($dataRak);$i++){

        // }
        // dd($rackOriginList,array_pop($rackOriginList[0]['datacenter']),$racks,$dataRak,$dataId,[$dataRak,$dataId],$totalData);
        // for($i = 0;$i < $totalData;$i++){
        // }
        $datacenter = Order::All();
        $data = [0];
        $dataJakarta = Order::whereIn('datacenter', $data)->get();
        $jakartaCyber = [];
        for ($i = 0; $i < count($datacenter); $i++) {
            $get = data_get($datacenter[$i]['datacenter'], '0.datacenter', 0);
            if ($get == 'Jakarta') {
                $jakarta = $datacenter[$i];
                array_push($jakartaCyber, $jakarta);
            }
        }


        // dd($datacenter[0]['datacenter'][0]['datacenter'],$dataJakarta,$jakartaCyber[0]['datacenter'][0]['datacenter']);

        //proses ambil data
        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(5);\
        // $orders = Order::all();
        $perPage = request('perPage', 100); // Default to 10 items per page
        // $orders = Order::simplePaginate($perPage);
        $orders = Order::All();

        $arron = [];
        foreach ($orders as $order) {
            // $totalArr = array_count_values($order['products']);
            $countArr = last($order['products']);
            array_push($arron, $countArr);
        }
        // dd($arron[0]['type']);

        $dataCompile = [];
        for ($i = 0; $i < count($arron); $i++) {
            if ($arron[$i]['type'] == 'dell' || $arron[$i]['type'] == 'HP' || $arron[$i]['type'] == 'supermicro') {

                array_push($dataCompile, $orders[$i]);
            }
        }
        $statusData = [];
        // dd($statusData[1][]);
        for ($i = 0; $i < count($dataCompile); $i++) {

            $datastatus = $dataCompile[$i]['id'];
            $statusId = Order_status::where('order_id', $datastatus)->first();
            array_push($statusData, $statusId);
        }

        // dd
        $userData = [];
        for ($i = 0; $i < count($dataCompile); $i++) {

            $dataUser = $dataCompile[$i]['user_id'];
            $userId = User::where('id', $dataUser)->first();
            array_push($userData, $userId);
        }

        array_push($userData, $statusData);
        // dd($userData);


        $title = 'Delete Server!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        // dd($userData);
        // mannggil html yang ada di folder resources/views/product.index.blade.php
        //compact : mengirim data ke blade 
        return view('internal.jakarta', compact('jakartaCyber', 'products', 'orders', 'userData', 'dataCompile', 'statusData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createServer($id)
    {
        //
        $products = Product::all();
        $order = Order::find($id);

        return view('order.server.create', compact('products', 'order'));
    }

    public function entryData($id)
    {
        //
        $order_statuses = Order_status::OrderBy('id', 'ASC')->simplePaginate(5);
        // $orders = Order::with('user')->simplePaginate(100);

        // dd ($orders)
        $userData = User::where('id', $id)->first();
        $dataValidate = data_get($userData, 'entryData.1', false);
        // dd($dataValidate);

        $data1 = [];
        if ($dataValidate !== false) {

            $data1 = [];
            foreach ($userData['entryData'] as $key) {
                foreach ($key[0] as $value) {
                    # code...
                    $data2 = $value;
                    array_push($data1, $data2);
                }
            }
            // dd($data1[4]);
            $lunasChecked = data_get($data1, '4', false);
            // dd($lunasChecked);
            if ($lunasChecked == false) {

                $idProduct = $data1[0]['id'];
            } else {

                $idProduct = $data1[4]['id'];
            }
            // dd($data1[6]);

        }
        // dd($data1);
        $lunasChecked = false;

        $perPage = request('perPage', 100); // Default to 10 items per page
        $orders = Order::simplePaginate($perPage);
        $users = User::where('id', $id)->first();
        $selectedProduct1 = Product::find(13);
        $selectedProduct2 = Product::find(14);
        $selectedProduct3 = Product::find(15);
        return view('order.admin.entry', compact('order_statuses', 'orders', 'users', 'userData', 'lunasChecked', 'dataValidate'));
    }

    public function editServer($id)
    {
        //
        $products = Product::all();
        $order = Order::find($id);

        $countProducts = count($order['products']) - 2 ;
        $lastOrder = last($order['products']);
        if ($lastOrder['type'] == "freeze") {
            $PO = $order['products'][$countProducts];
        } else {
            $PO = $lastOrder;
        }
        return view('order.server.edit', compact('products', 'order', 'PO'));
    }

    public function storeServer(Request $request, $id)
    {
        // validasi
        $request->validate([
            'type' => 'required',
            'series' => 'required',
            'dimension' => 'required',
            'serialNumber' => 'required',
        ]);


        // Cari berdasarkan id OrderStatus terus update

        $order = Order::find($id);



        if ($order) {


            $existingProduct1 = isset($order->products[0]) ? $order->products[0] : null;
            $existingProduct2 = isset($order->products[1]) ? $order->products[1] : null;


            $inventory = [];
            $cpuCheck = data_get([$request->processor][0], '0', 1);

            foreach ([$request->ram] as $key) {
                $ramVal = $key;

                array_push($inventory, $ramVal);
            }

            foreach ([$request->disk] as $key) {
                $diskVal = $key;

                array_push($inventory, $diskVal);
            }
            if ($cpuCheck !== 1) {

                foreach ([$request->processor] as $key) {
                    $processorVal = $key;

                    array_push($inventory, $processorVal);
                }
            } else {
            }

            $totalin = 0;
            // for ($i = 0; $i < count($inventory[0]); $i++) {

            //     $totalin += $inventory[0][$i];
            // }

            // $dettol = 0;
            // for ($i = 0; $i < count($inventory[1]); $i++) {

            //     $dettol += $inventory[1][$i];
            // }


            // dd($totalin);

            // if ($existingProduct2) {
            //     // Mengupdate nilai produk yang ada
            //     $existingProduct2['price'] = $request->custom_price;
            //     $existingProduct2['qty'] = $request->custom_qty;
            //     $existingProduct2['price_after_qty'] = $request->custom_qty * $request->custom_price;
            // }
            $idProduct2 = data_get($existingProduct2, 'id', true);

            if ($idProduct2 !== true) {
                if ($existingProduct2['id'] == "0") {
                    $existingProduct2['name_product'] = 'Tidak Tambahan ';
                    $order->save();
                } elseif ($existingProduct2['id'] == "16") {
                    $ramServer = 32;
                    $existingProduct2['name_product'] = '128 GB + ' . $totalin + $ramServer;
                    $order->save();
                } elseif ($existingProduct2['id'] == "17" || $existingProduct2['id'] == 17) {
                    $ramServer = 64;
                    $existingProduct2['name_product'] = '128 GB +  ' . $totalin + $ramServer;
                } else {

                    $ramProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai

                    $ramProduct->id = 16;
                    $ramProduct->name_product = '128 GB +  ' . $totalin . ' GB';
                    $ramProduct->type = 'ram';
                    $ramProduct->price = $totalin * 1000;
                    $ramProduct->qty = 1;
                    $ramProduct->price_after_qty = $totalin * 1000;
                }
            } else {
            }

            // dd($existingProduct2);


            // dd($inventory);
            $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
            $newProduct->id = $id;
            $newProduct->type = $request->type;
            $newProduct->series = $request->series;
            $newProduct->dimension = $request->dimension;
            $newProduct->serialNumber = $request->serialNumber;
            // $newProduct->inventory = $request->inventory;
            $newProduct->entryDate = $request->entryDate;
            $newProduct->serverLabel = $request->serverLabel;
            $newProduct->name_product = $request->series;
            $newProduct->price = 0;
            $newProduct->qty = 1;
            $newProduct->price_after_qty = 0;
            $newProduct->inventory = $inventory;
            // dd($order['created_at']);
            $startDate = ($order['created_at'])->formatLocalized('%d %B %Y %H:%M');
            // dd($nganu);   
            if ($existingProduct1['type'] == 'dedicated') {
                    $newProduct->startDate = $order['created_at']->addDays(30 * $order['bulan']);
                }

                // dd($order['created_at']->addDays(30 * $order['bulan']));
                if ($request->payment == "lunas" || $existingProduct1['type'] == 'colocation') {
                    $newProduct->endDate = $order['created_at']->addDays(30 * $order['bulan']);
                }

            $orderProducts = $order['products'];
            array_pop($orderProducts);
            if ($existingProduct1['type'] == 'dedicated') {
                // $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4, $newProduct];
                // $newProduct->qty = 1;
                array_push($orderProducts, $newProduct);
            } else {
                // $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4, $newProduct, $ramProduct];
                array_push($orderProducts, $newProduct);
            }

            $spliceArray = count($orderProducts);

            // dd($spliceArray,$orderProducts);
            // $hook = array_splice($orderProducts, $spliceArray);

            $order['products'] = $orderProducts;

            // $newProduct->price_after_qty = $totalCost;
            // $newProduct->price = 350000;

            // $customTotal = $request->custom_price * $request->custom_qty;
            // Mengupdate nilai total pesanan
            // $order->total_price += $customTotal;


            $order->save();

            Alert::success('success', 'Berhasil menambah nilai accessful!');


            return redirect()->route('detail_server.data')->with('success', 'Berhasil Mengubah Data Server!');
        }
    }

    public function updateServer(Request $request, $id)
    {
        // validasi
        // $request->validate([
        //     'data' => 'required',
        // ]);


        // Cari berdasarkan id OrderStatus terus update

        $order = Order::find($id);



        if ($order) {

            $existingProduct1 = isset($order->products[0]) ? $order->products[0] : null;
            $existingProduct2 = isset($order->products[1]) ? $order->products[1] : null;
            $existingProduct3 = isset($order->products[2]) ? $order->products[2] : null;
            $existingProduct4 = isset($order->products[3]) ? $order->products[3] : null;

            $price = data_get($order['products'], '1.price', 0);


            if ($existingProduct2) {
                // Mengupdate nilai produk yang ada
                $existingProduct2['price'] = $request->custom_price;
                $existingProduct2['qty'] = $request->custom_qty;
                $existingProduct2['price_after_qty'] = $request->custom_qty * $request->custom_price;
                $existingProductObject2 = $this->convertArrayToStdClass($existingProduct2);
            }

            if ($existingProduct4) {
                $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);
                $existingProductObject3 = $this->convertArrayToStdClass($existingProduct3);
                $existingProductObject4 = $this->convertArrayToStdClass($existingProduct4);

                $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4];
            } elseif ($existingProduct3) {
                $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);
                $existingProductObject3 = $this->convertArrayToStdClass($existingProduct3);

                $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3];
            } elseif ($existingProduct2) {
                $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);

                $order->products = [$existingProductObject1, $existingProductObject2];
            }

            $customTotal = $request->custom_price * $request->custom_qty;
            // Mengupdate nilai total pesanan
            $order->total_price += $customTotal;
            if ($existingProduct1['type'] == 'dedicated') {
                $existingProduct1->startDate = $order['created_at'];
            }


            $inventory = [];
            foreach ([$request->ram] as $key) {
                $ramVal = $key;

                array_push($inventory, $ramVal);
            }

            foreach ([$request->disk] as $key) {
                $diskVal = $key;

                array_push($inventory, $diskVal);
            }
            foreach ([$request->processor] as $key) {
                $processorVal = $key;

                array_push($inventory, $processorVal);
            }

            $totalin = 0;
            for ($i = 0; $i < count($inventory[0]); $i++) {

                $totalin += $inventory[0][$i];
            }

            $dettol = 0;
            for ($i = 0; $i < count($inventory[1]); $i++) {

                $dettol += $inventory[1][$i];
            }



            $existingProduct2['name_product'] = '128 GB + ' . $totalin;



            $order->save();

            Alert::success('success', 'Berhasil menambah nilai accessf!');


            return redirect()->route('status.dedicated')->with('success', 'Berhasil mengubah data server!');
        } else {

            $status2 = Order_status::find($id);


            $request->validate([
                // ... Validasi lainnya ...
                'attachment' => 'nullable|mimes:pdf,PDF|max:2048', // Maksimum 2 MB
            ]);

            // Dapatkan order berdasarkan ID
            // $orderStatus = Order_status::findOrFail($id);

            // Perbarui data orderStatus
            // $status2->name_customer = $request->input('name_customer');
            // ... Perbarui kolom lainnya ...

            // Upload file PDF jika diunggah
            if ($request->hasFile('attachment')) {
                $pdfFile = $request->file('attachment');
                $pdfFileName = 'spk' . time() . '.' . $pdfFile->getClientOriginalExtension();
                // $pdfFile->storeAs('attachments', $pdfFileName, 'public'); // Simpan file di penyimpanan yang diinginkan
                if ($status2->attachment) {
                    $previousFilePath = public_path('attachments/' . $status2->attachment);

                    // Periksa apakah file PDF sebelumnya ada sebelum menghapus
                    if (File::exists($previousFilePath)) {
                        File::delete($previousFilePath);
                    }
                }

                $pdfFile->move(public_path('attachments'), $pdfFileName);

                // Perbarui kolom file pada model status2
                $status2->attachment = $pdfFileName;
                $status2->save();

                return redirect()->route('status.show', ['id' => $status2->id])->with('success', 'Berhasil menambah nilai accessul!');
            }

            // ......... kode custom produk .......

            // ... Kode lainnya ...


            // if ($status2) {
            if ($request->has('data')) {
                $status2->update([
                    'data' => $request->data,
                ]);

                // Misalnya, Anda ingin menambahkan increment data
                $status2->increment('data', 1);

                if ($status2->data == 2) {
                    $status2->update([$status2->status = 'Proses-SPK']);
                } else if ($status2->data == 3) {
                    $status2->update([$status2->status = 'Proses-TTD']);
                } else if ($status2->data == 4) {
                    $status2->update([$status2->status = 'Done']);
                }
                return redirect()->route('status.dedicated')->with('success', 'Berhasil mengubah status SPK Client!');
            } elseif ($request->has('access')) {
                // Jika ya, tambahkan nilai access
                // suspend menu

                if ($request->input('access') == 2 && $request->input('freeze') == 1) {
                    $access = $status2->access = 2;

                    $status2->update(['access' => $access]);
                    $status2->update(['payment' => 1]);

                    return redirect()->route('status.dedicated')->with('success', 'Berhasil menambah nilai access1!');
                }

                // freeze menu
                elseif ($request->input('access') == 2 && $request->input('freeze') == 2) {
                    $freeze = $status2->access = 3;

                    $status2->update(['access' => $freeze]);

                    return redirect()->route('status.dedicated')->with('success', 'Berhasil menambah nilai access2!');
                }

                // unfreeze menu
                elseif ($request->input('access') == 2 && $request->input('freeze') == 3) {
                    $freeze = $status2->access = 4;

                    $status2->update(['access' => $freeze]);

                    return redirect()->route('status.dedicated')->with('success', 'Berhasil menambah nilai access3!');
                }
            }

            Alert::success('success', 'Berhasil menambah nilai access1!');

            return redirect()->route('status.show', ['id' => $status2->id])->with('success', 'Berhasil menambah nilai access!');
        }
    }
    /**
     * 
     * Remove the specified resource from storage.
     */
    public function destroyServer($id)
    {
        //
        $orderId = Order::where('id', $id)->first();
        // dd($orderId['products']);
        $data = $orderId['products'];

        //   $data = array_diff($data, ["5", "6"]);
        $countData = count($data) - 1;
        // dd($countData);


        array_splice($data, $countData);
        $orderId['products'] = $data;

        $orderId->save();


        //   dd($ord    erId);    

        //   $data::delete(array(4));

        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }
    // public function destroy(Order_status $order)
    // {
    //     //
    // }
    public function deleteSingle($id)
    {
        //
        Order::where('id', $id)->delete();
        Order_status::where('order_id', $id)->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }

    public function sewaIndex()
    {
        $get1 = Order_status::where('payment', '<', 1)->simplePaginate(100);
        $sewaEnd = [];
        foreach ($get1 as $sewa) {
            # code...
            $sewaAdd = $sewa['order_id'];
            array_push($sewaEnd, $sewaAdd);
        }

        $sewaProducts = [];
        for ($i = 0; $i < count($sewaEnd); $i++) {
            # code...
            $sewaGet = Order::where('id', $sewaEnd[$i])->first();
            array_push($sewaProducts, $sewaGet);
        }


        // dd($get1,$sewaEnd,$sewaProducts);
        

        // $user = User::OrderBy('id', 'ASC')->simplePaginate(100);
        return view('order.client.sewa', compact('get1', 'sewaProducts'));
    }
    public function sewa($id)
    {

        $order = Order::where('id', $id)->first();
        $userGet = User::where('id', $order['user_id'])->first();
        $empty = data_get($userGet, 'entryData', true);
        // dd($empty);
        $user = User::where('role', 'user')->simplePaginate(100);
        $status1 = Order_status::where('order_id', $id)->first();

        // dd($userGet);
        $entryValidate = data_get($userGet, 'entryData.0', 1);
        // dd($entryValidate);
        if ($entryValidate == 1) {
            $userEntry = 1;
        } else {
            $userEntry = 1;
            // $userEntry = $userGet['entryData'][0][1]['bulan'];
        }

        // $user = User::OrderBy('id', 'ASC')->simplePaginate(100);
        return view('order.admin.sewa', compact('order', 'userGet', 'user', 'status1', 'empty', 'userEntry'));
    }

    public function sewaUpdate(Request $request, $id)
    {

        $order = Order::where('id', $id)->first();
        $user = User::where('id', $request->userName)->first();
        // user baru
        $oldUser = User::where('id', $order['user_id'])->first();

        // $user['entryData'] = $oldUser['entryData'];

        // $user->save();
        // buat produk dengan type sewa atau status sewa ?

        $status1 = Order_status::where('order_id', $id)->first();
        $order['user_id'] = $request->userName;
        // if($status1['payment'] > 0){
        //     $order['votes'] = 1;
        // }


        // if ($status1['payment'] > 0) {

            $votes = $order['votes'];
            $dataGet = $oldUser['entryData'];
            data_fill($dataGet[0][2], 'votes', $votes);
            $oldUser['entryData'] = $dataGet;
            // $oldUser->save();
            // dd($dataGet);
            $oldUser->save();
        // }

        // dd($request->userName);
        $order['name_customer'] = $user['name'];

        // if ($status1['payment'] < 1) {

            // $dataEntry = 

            if ($status1['data'] == 12) {
                $status1['data'] = 12;
            } elseif ($status1['data'] == 24) {
                $status1['data'] = 24;
            } else {

                $status1['data'] = $order['bulan'];
            }
            $order['bulan'] = $request->bulan;
            $order['votes'] = 1;
            $products = $order['products'];
            $products[0]['price'] = $request->price;
            $products[0]['price_after_qty'] = $request->price;
            $order['products'] = $products;
            $existingTotal = $order['total_price'];
            $order['total_price'] = $existingTotal + $request->price;
            if ($request->payment == 'lunas') {
                $order['votes'] = $request->bulan;
            } else {
                $order['votes'] = 1;
            }
        // }

        $order->save();

        $status1['payment'] = -1;

        $status1->save();





        // $user = User::OrderBy('id', 'ASC')->simplePaginate(100);
        return redirect()->route('status.sewaIndex')->with('success', 'Berhasil mengupdate data');
    }

    public function sewaSearch(Request $request)
    {

        $user = User::OrderBy('name', 'Asc')->paginate(5);
        $movies = [];
        if ($request->has('q')) {
            $search = $request->q;
            // foreach ($bogorAsnet as $key) {
            //     # code...
            //     $movies = Product::select('id','datacenter' )
            //     ->where('id', 'LIKE', $key['id'])
            //     ->where('name', 'LIKE', "%$search%")
            //     ->get();
            // }
            // $rack = $user;
            $movies = User::select('id', 'name')->where('role', 'user')->where('name', 'LIKE', "%$search%")->get();
        }
        // dd($movies);
        return response()->json($movies);
    }
    
    public function votesUpdate(Request $request,$id){

        $request->validate([
            'bulanSelection' => 'required',
        ]);

        $order = Order::where('id', $id)->first();
        $orderStatus = Order_status::where('order_id', $id)->first();
        
        $votes = $request->bulanSelection;
        $votesOp = $votes - $order['votes'];
        $existingTotal = $order['total_price'];

        if($orderStatus['payment'] < 0){

            $productDc = $order['products'][0]['price'];
            $orderStatus['payment'] = $votes * -1;
            // dd($orderStatus);
    }
    else{
        
        
        if($order['bulan'] == 12){
            $productPrice = $order['products'][0]['price_after_qty'] + 300000;
            
        }else{
            $productPrice = $order['products'][0]['price_after_qty'];
        }

        if($order['datacenter'][0]['datacenter'] == "Jakarta"){
            $productDc = $productPrice + 750000;
        }
        else{
            $productDc = $productPrice;
        }
        $orderStatus['payment'] = $votes;
        
    }
        $productTotal = $productDc * $votesOp;
        
        // dd($votes,$productPrice);
        $totalEnd = $existingTotal + $productTotal;

        $order['votes'] = $votes;
        $order['total_price'] = $totalEnd;
        $order->save();
        $orderStatus->save();

        return redirect()->back()->with('success','Data Votes Terupdate !');
    }
}
