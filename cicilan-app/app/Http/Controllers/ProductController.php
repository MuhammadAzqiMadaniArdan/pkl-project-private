<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order_status;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //proses ambil data
        $products = Product::orderBy('name', 'ASC')->simplePaginate(5);
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
        $products = Product::orderBy('stock', 'ASC')->simplePaginate(5);
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

    public function dedicatedNew()
    {

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

        // dd($dedicated1,$dedic1,$dedicMurah);
        return view('order.client.dedicated', compact('dedicatedDer', 'dedicMurah', 'dedic1', 'dedicated1'))->with('success', 'login berhasil!');
    }
    public function colocationNew()
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


        // dd($dedicated1,$dedic1,$colocation);
        return view('order.client.colocation', compact('dedicatedDer', 'colocation', 'dedic1', 'dedicated1'))->with('success', 'login berhasil!');
    }
    // cart Sessions

    // this function is to show cart of product because we wanna show result of choose product by user in this page
    public function cart()
    {
        return view('order.user.cart');

        // $products = Product::orderBy('type', 'ASC')->paginate(100);
        // $bulan = Order::all();
        // return view('order.user.create', compact('products', 'bulan'));
    }



    public function addToCart(Request $request, $id) // by this function we add product of choose in card
    {
        $product = Product::find($id);
        $totalPrice = 0;



        if (!$product) {

            abort(404);
        }
        // what is Session:
        //Sessions are used to store information about the user across the requests.
        // Laravel provides various drivers like file, cookie, apc, array, Memcached, Redis, and database to handle session data. 
        // so cause write the below code in controller and tis code is fix
        $cart = session()->get('cart');

        $products = array_count_values($request->products);

        // foreach ($products as $key => $value) {
        //     $product = Product::where('id', $key)->first();

        //     if ($product['type'] == 'colocation') {
        //         if ($request->bulan == 12) {
        //             $priceEnd = 1 * (int)$product['price'];
        //         } else {
        //         }
        //     } else {
        //     }
        // }

        if (!$cart) {
            if ($product['type'] == 'colocation') {
                if ($request->bulan == 12) {
                    $cart = [
                        $id => [
                            "name" => $product->name,
                            "type" => $product->type,
                            "quantity" => 1,
                            "price" => $product->price,
                            "price_after_qty" => 1 * (int)$product->price - 360000,
                            "photo" => $product->photo,
                            "port" => [
                                "name" => $request->port,
                            ],
                            "IP" => [
                                "name" => $request->IP,
                                "price_after_qty" => $request->SATA * 900000,
                            ],
                            "bandwidth" => [
                                "qty" => $request->bandwidth,
                                "price_after_qty" => (int)$request->bandwidth * 30000 * $request->bulan,
                            ],
                            "label" => $request->label_product,
                            "bulan" => $request->bulan
                        ]
                    ];
                } else {
                    $cart = [
                        $id => [
                            "name" => $product->name,
                            "type" => $product->type,
                            "quantity" => 1,
                            "price" => $product->price,
                            "price_after_qty" => 1 * (int)$product->price - 360000,
                            "photo" => $product->photo,
                            "port" => [
                                "name" => $request->port,
                            ],
                            "IP" => [
                                "name" => $request->IP,
                                "price_after_qty" => $request->SATA * 900000,
                            ],
                            "bandwidth" => [
                                "qty" => $request->bandwidth,
                                "price_after_qty" => (int)$request->bandwidth * 30000 * $request->bulan,
                            ],
                            "label" => $request->label_product,
                            "bulan" => $request->bulan
                        ]
                    ];
                }
            } else {
                $cart = [
                    $id => [
                        "name" => $product->name,
                        "type" => $product->type,
                        "quantity" => 1,
                        "price" => $product->price,
                        "price_after_qty" => 1 * (int)$product->price,
                        "photo" => $product->photo,
                        "ram" => [
                            "name" => $request->ram,
                        ],
                        "SATA" => [
                            "qty" => $request->SATA,
                            "price_after_qty" => $request->SATA * 900000,
                        ],
                        "NVME" => [
                            "qty" => $request->NVME,
                            "price_after_qty" => $request->NVME * 1000000,
                        ],
                        "port" => [
                            "name" => $request->port,
                        ],
                        "IP" => [
                            "name" => $request->IP,
                            "price_after_qty" => $request->SATA * 900000,
                        ],
                        "bandwidth" => [
                            "qty" => $request->bandwidth,
                            "price_after_qty" => (int)$request->bandwidth * 30000 * $request->bulan,
                        ],
                        "datacenter" => $request->datacenter,
                        "OS" => $request->oS,
                        "bulan" => $request->bulan

                    ]
                ];
            }


            session()->put('cart', $cart);

            return redirect()->route('order.cart')->with('success', 'added to cart successfully!');
            //  return redirect()->back()->with('success', 'added to cart successfully!');
        }

        // if cart not empty then check if this product exist then increment quantity
        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;

            session()->put('cart', $cart); // this code put product of choose in cart

            return redirect()->route('order.cart')->with('success', 'Product added to cart successfully!');
            //  return redirect()->back()->with('success', 'Product added to cart successfully!');

        }
        // if cart is empty then this the first product


        // if item not exist in cart then add to cart with quantity = 1
        if ($product['type'] == 'colocation') {
            if ($request->bulan == 12) {
                $cart[$id] = [
                    "name" => $product->name,
                    "type" => $product->type,
                    "quantity" => 1,
                    "price" => $product->price,
                    "price_after_qty" => (int)$request->bulan * (int)$product->price - 360000,
                    "photo" => $product->photo,
                    "port" => [
                        "name" => $request->port,
                    ],
                    "IP" => [
                        "name" => $request->IP,
                        "price_after_qty" => $request->SATA * 900000,
                    ],
                    "bandwidth" => [
                        "qty" => $request->bandwidth,
                        "price_after_qty" => (int)$request->bandwidth * 30000 * $request->bulan,
                    ],
                    "label" => $request->label_product,
                    "bulan" => $request->bulan

                ];
            } else {
                $cart[$id] = [
                    "name" => $product->name,
                    "type" => $product->type,
                    "quantity" => 1,
                    "price" => $product->price,
                    "price_after_qty" => (int)$request->bulan * (int)$product->price,
                    "port" => [
                        "name" => $request->port,
                    ],
                    "IP" => [
                        "name" => $request->IP,
                        "price_after_qty" => $request->SATA * 900000,
                    ],
                    "bandwidth" => [
                        "qty" => $request->bandwidth,
                        "price_after_qty" => (int)$request->bandwidth * 30000 * $request->bulan,
                    ],
                    "label" => $request->label_product,
                    "bulan" => $request->bulan

                ];
            }
            $totalPrice += $cart[$id]['price_after_qty'];
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "type" => $product->type,
                "quantity" => 1,
                "price" => $product->price,
                "price_after_qty" => 1 * (int)$product->price,
                "photo" => $product->photo,
                "ram" => [
                    "name" => $request->ram,
                ],
                "SATA" => [
                    "qty" => $request->SATA,
                    "price_after_qty" => $request->SATA * 900000,
                ],
                "NVME" => [
                    "qty" => $request->NVME,
                    "price_after_qty" => $request->NVME * 1000000,
                ],
                "port" => [
                    "name" => $request->port,
                ],
                "IP" => [
                    "name" => $request->IP,
                    "price_after_qty" => $request->SATA * 900000,
                ],
                "bandwidth" => [
                    "qty" => $request->bandwidth,
                    "price_after_qty" => (int)$request->bandwidth * 30000 * $request->bulan,
                ],
                "datacenter" => $request->datacenter,
                "OS" => $request->oS,
                "bulan" => $request->bulan,

            ];
            $totalPrice += $cart[$id]['price_after_qty'];
        }


        session()->put('cart', $cart); // this code put product of choose in cart

        return redirect()->route('order.cart')->with('success', 'Product added to cart successfully!');
    }
    // update product of choose in cart
    public function updateCart(Request $request)
    {
        if ($request->id and $request->quantity) {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);

            session()->flash('success', 'Cart updated successfully');
        }
    }

    // delete or remove product of choose in cart
    public function removeCart(Request $request)
    {
        if ($request->id) {

            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            session()->flash('success', 'Product removed successfully');
        }
    }

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
}
