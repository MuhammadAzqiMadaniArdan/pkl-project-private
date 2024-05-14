<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order_status;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
// Paginator

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //proses ambil data
        $products = Product::orderBy('type', 'ASC')->simplePaginate(5);
        // mannggil html yang ada di folder resources/views/product.index.blade.php
        //compact : mengirim data ke blade 

        $title = 'Delete Product!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi
        // 'name_input' => 'validasi1/validasi2'
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        // simpan data ke db : 'nama_column' => $request->name_input
        Product::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // abis simpen, arahin ke halaman mana
        return redirect()->back()->with('succes', 'berhasil menambahkan data produk!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        // mengembalikan bentuk json dikirim data yang diambil dari response status code 200
        // response status code api :
        // 200 -> success/ok
        // 400 an -> errror kode/validasi input
        // 419 ->error token csrf
        // 500 an -> error server hosting
        return response()->json($product, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // mengambil data yang belum dimunculkan
        // find: mencari berdasarkan column
        // bisa jkuga : where ('id',$id)->first()
        $product = Product::find($id);

        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // validasi
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);
        // cari berdasarkan id terus update
        Product::where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
        ]);
        // redirect ke html product data
        // route digunakan untuk memindahkan suatu ke page yang lain jika ingin menambahkan notif ke tempat lain bisa di ganti ke product.tambah atau product.edit
        return redirect()->route('product.data')->with('success', 'Berhasil mengubah data produk!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        Product::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }

    public function stockData()
    {
        $products = Product::orderBy('type', 'ASC')->simplePaginate(5);
        return view('product.stock', compact('products'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|numeric',
        ], [
            "stock_required" => "Input stock harus diisi!",
        ]);

        $productBefore = Product::where('id', $id)->first();
        // if ($request->stock >= $productBefore['stock']){
        //     return response()->json(['message' => 'stock tidak boleh lebih/sama dengan stock sebelumnya serta kurang!'],400);
        // }

        // kalau gamasuk ke if
        $productBefore->update(['stock' => $request->stock]);
        return response()->json('berhasil', 200);
    }

    public function dedicatedIndex()
    {
        $dedicMurah = [];
        $dedicated1 = Product::where('type', 'dedicated')->simplePaginate(100);
        $dedic1 = [];
        $sewaGet = [];
    
        $dedicatedDer = Order::simplePaginate(100);
        foreach ($dedicatedDer as $key) {
            $sewaStatus = $key['user_id'];
            array_push($sewaGet, $sewaStatus);
            $get1 = Order_status::where('order_id', $key['id'])->first();
            foreach ($key['products'] as $product) {
                if ($product['type'] == 'dedicated' && $get1['payment'] > 0) {
                    $dedic2 = $key;
                    array_push($dedic1, $dedic2);
                } elseif ($product['type'] == 'colocation') {
                    $dedic3 = $key;
                    array_push($dedicMurah, $dedic3);
                }
            }
        }
    
        // Memastikan $dedicValidate tetap memiliki nilai yang valid
        $dedicValidate = data_get($dedic1, '0', false);

        if($dedicValidate == false){
            $dedic1 = $this->paginate($dedic1);            
        }else{
            $dedic1 = $this->paginate($dedic1);       
        }
        // Paginasi $dedic1 dengan 2 item per halaman
    
        return view('order.client.dedicated', compact('dedicatedDer', 'dedicMurah', 'dedic1', 'dedicated1','dedicValidate'))->with('success', 'Dedicated Theme!');
    }
    
    /**
     * Paginate an array of items.
     *
     * @return LengthAwarePaginator         
     * The paginated items.
     */
    private function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function dedicatedSearch(Request $request){

        $search = $request->input('search');


        
        $dedicMurah = [];
        $dedicated1 = Product::where('type', 'dedicated')->simplePaginate(100);
        $dedic1 = [];
        $sewaGet = [];

        $dedicatedDer = Order::simplePaginate(100);
        foreach ($dedicatedDer as $key) {
            $sewaStatus = $key['user_id'];
            array_push($sewaGet, $sewaStatus);
            // dd($dedicatedDer[0]['products'],$product);
            $get1 = Order_status::where('order_id', $key['id'])->first();
            // dd($key['id'],$get1['payment'] );
            // dd($dedicatedDer[1]['products']);
            foreach ($key['products'] as $product) {
                # code...
                if ($product['type'] == 'dedicated' && $get1['payment'] > 0) {
                    $dedic2 = $key;
                    array_push($dedic1, $dedic2);
                } elseif ($product['type'] == 'colocation') {
                    # code...
                    $dedic3 = $key;
                    array_push($dedicMurah, $dedic3);
                }
            }
        }

        $orders = Order::whereDate('created_at', 'like', "%$search%")->simplePaginate(5);
        
        if($dedic1 == null){
            $dedic1 = [];
        }else{
            $dedicatedPush = [];
            foreach($orders as $dedicated){
                if($dedicated['products'][0]['type'] == "dedicated"){
                    array_push($dedicatedPush,$dedicated);
                }
            }
            $dedic1 = $dedicatedPush;
        }

        $dedicValidate = data_get($dedic1, '0', false);

        if($dedicValidate == false){
            $dedic1 = $this->paginate($dedic1);            
        }else{
            $dedic1 = $this->paginate($dedic1);       
        }

        return view('order.client.dedicated', compact('dedicatedDer', 'dedicMurah', 'dedic1', 'dedicated1','dedicValidate'))->with('success', 'login berhasil!');
   

    }

    public function colocationIndex()
    {

        $colocation = [];
        $dedicated1 = Product::where('type', 'dedicated')->simplePaginate(100);
        $dedic1 = [];
        $sewaGet = [];

        $dedicatedDer = Order::simplePaginate(100);
        foreach ($dedicatedDer as $key) {
            $sewaStatus = $key['user_id'];
            array_push($sewaGet, $sewaStatus);
            // dd($dedicatedDer[0]['products'],$product);
            $get1 = Order_status::where('order_id', $key['id'])->first();
            // dd($key['id'],$get1['payment'] );

            foreach ($key['products'] as $product) {
                # code...
                if ($product['type'] == 'dedicated' && $get1['payment'] > 0) {
                    $dedic2 = $key;
                    array_push($dedic1, $dedic2);
                } elseif ($product['type'] == 'colocation' && $get1['payment'] > 0) {
                    # code...
                    $dedicMurah = $key;
                    array_push($colocation, $dedicMurah);
                }
            }
        }
        $coloValidate = data_get($colocation, '0', false);



        // dd($dedicated1,$dedic1,$colocation);
        return view('order.client.colocation', compact('dedicatedDer', 'colocation', 'dedic1', 'dedicated1','coloValidate'))->with('success', 'login berhasil!');
    }
    // cart Sessions
    
    public function colocationSearch(Request $request)
    {

        $colocation = [];
        $dedicated1 = Product::where('type', 'dedicated')->simplePaginate(100);
        $dedic1 = [];
        $sewaGet = [];

        $dedicatedDer = Order::simplePaginate(100);
        foreach ($dedicatedDer as $key) {
            $sewaStatus = $key['user_id'];
            array_push($sewaGet, $sewaStatus);
            // dd($dedicatedDer[0]['products'],$product);
            $get1 = Order_status::where('order_id', $key['id'])->first();
            // dd($key['id'],$get1['payment'] );

            foreach ($key['products'] as $product) {
                # code...
                if ($product['type'] == 'dedicated' && $get1['payment'] > 0) {
                    $dedic2 = $key;
                    array_push($dedic1, $dedic2);
                } elseif ($product['type'] == 'colocation' && $get1['payment'] > 0) {
                    # code...
                    $dedicMurah = $key;
                    array_push($colocation, $dedicMurah);
                }
            }
        }

        $search = $request->input('search');


        $orders = Order::whereDate('created_at', 'like', "%$search%")->simplePaginate(5);
        
        if($colocation == null){
            $colocation = [];
        }else{
            $colocationPush = [];
            foreach($orders as $colocated){
                if($colocated['products'][0]['type'] == "colocation"){
                    array_push($colocationPush,$colocated);
                }
            }
            $colocation = $colocationPush;
        }

        $coloValidate = data_get($colocation, '0', false);



        // dd($dedicated1,$dedic1,$colocation);
        return view('order.client.colocation', compact('dedicatedDer', 'colocation', 'dedic1', 'dedicated1','coloValidate'))->with('success', 'login berhasil!');
    }

    // delete or remove product of choose in cart
   

    public function search(Request $request)
    {
        $search = $request->input('search');


        $products = Product::where('name', 'like', "%$search%")->simplePaginate(5);


        return view('product.index', compact('products'));
    }

    public function searchStock(Request $request)
    {
        $search = $request->input('search');


        $products = Product::where('name', 'like', "%$search%")->simplePaginate(5);


        return view('product.stock', compact('products'));
    }

    public function sewaDate(Request $request)
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

        $search = $request->input("search");
        // dd($get1,$sewaEnd,$sewaProducts);
        

        $orders = Order::whereDate('created_at', 'like', "%$search%")->simplePaginate(5);

        if($sewaProducts == null){
            $sewaProducts = [];
        }else{
            $sewaHas = [];
            foreach($orders as $sewa){
                // dd($orders);
                $orderStatus = Order_status::where('order_id',$sewa['id'])->first();
                if($orderStatus['payment'] < 1){
                    array_push($sewaHas,$sewa);
                }
            }
            $sewaProducts = $sewaHas;
        }

        $sewaValidate = data_get($sewaProducts, '0', true);

        // $user = User::OrderBy('id', 'ASC')->simplePaginate(100);
        return view('order.client.sewa', compact('get1', 'sewaProducts'));
    }
}
