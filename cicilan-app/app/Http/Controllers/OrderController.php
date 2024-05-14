<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use App\Models\Product;
use App\Models\Order_status;
use App\Models\User;
use Carbon\Carbon;

use PDF;
use App\Exports\OrderExport;
use Excel;
use Illuminate\Support\Arr;
use RealRashid\SweetAlert\Facades\Alert;


use App\Mail\LunasMail;
use App\Mail\InvoiceUserMail;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;



class OrderController extends Controller
{
    /**
     * Display a listing of tche resource.
     * 
     */

    // -------------------Admin-Area------------------------
    public function adminStore(Request $request)

    {
        // dd($request->userId);
        //


        $request->validate([
            'name_customer' => 'required',
            'products' => 'required',
            'bulan' => 'required',
            'votes' => 'required',
            'data' => 'required',
            'status' => 'required',
            'rack' => 'nullable',
            'access' => 'nullable',
            // 'no_telp' => 'required',
            // 'address' => 'required',
            'company' => 'nullable', // Menjadikan input company sebagai field yang opsional
            'serverLabel' => 'nullable', // Menjadikan input company sebagai field yang opsional
            // 'custom_ram' => 'numeric|max:512', // Menjadikan input company sebagai field yang opsional


        ]);

        // hasilnya berbentuk : "itemnya" => "jumlah yang sama"
        // menentutak quantity (qty)

        $products = array_count_values($request->products);

        // penampung detail array berbentuk array 2 assoc dari data data yang dipilih
        $dataProducts = [];
        foreach ($products as $key => $value) {
            $product = Product::where('id', $key)->first();

            if ($product['type'] == 'colocation') {
                $arrayAssoc = [
                    "id" => $key,
                    "label" => $request->filled('serverLabel') ? $request->serverLabel : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                    "name_product" => $product['name'],
                    "type" => $product['type'],
                    "price" => $product['price'],
                    "qty" => $value,
                    //(int) memastikan dan mengubah tipe data menjadi integer
                    "price_after_qty" => (int)$request->bulan * (int)$product['price'],

                    // "price_after_qty" => (int)$value * (int)$product['price'],
                ];
            } else {
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

            array_push($dataProducts, $arrayAssoc);
        }

        $datacenter = [];
        $datanew = 0;
        // dd($arrayAssoc['id']);
        if ($arrayAssoc['type'] == "dedicated") {
            $datanew = $request->datacenter;
        } elseif ($arrayAssoc['type'] == "colocation") {

            if ($arrayAssoc['id'] == "10" || $arrayAssoc['id'] == "11") {
                $datanew = "Jakarta";
            } else {
                $datanew = "Bogor";
            }
        }

        $datasum = [
            "datacenter" => $datanew,
            "rack" => $request->rack,
        ];
        array_push($datacenter, $datasum);
        // var total price awalnya 0
        $totalPrice = 0;
        $votes = 1;
        // loop data dari array penamoung yg sudah di format
        foreach ($dataProducts as $formatArray) {
            // dia bakal menambahkan  totalPrice sebelumnya ditambah data harga dari price_after_qty
            $totalPrice += (int)$formatArray['price_after_qty'];
        }
        if ($request->bulan == 12) {
            // Tambahkan 300.000 ke total harga jika bulan adalah 12
            $totalPrice += 300000;
        }

        $defaultRam = 128;
        if ($request->ram == "custom") {
            $qty = 1;
            $ramPrice = 0;
            $ramAssoc = [
                "name_product" => "Additional RAM (" . $request->custom_ram . " (GB) )",
                "type" => "ram",
                "price" => $ramPrice,
                "qty" => $qty,
                "price_after_qty" => (int)$qty * (int)$ramPrice,
            ];
            array_push($dataProducts, $ramAssoc);
        } elseif ($request->ram == 0 && $arrayAssoc['type'] == 'dedicated') {
            $ramId = '0';
            $qty = 1;
            $ramAssoc = [
                "id" => $ramId,
                "name_product" => $defaultRam . ' GB ',
                "type" => 'ram',
                "price" => 0,
                "qty" => $qty,
                "price_after_qty" => (int)$qty * (int)0,
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
        if ($request->bandwidth > 0 && $arrayAssoc['type'] == "colocation") {
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
        if ($arrayAssoc['type'] == "colocation") {
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
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } elseif ($request->bulan == 3) {
                        $IPAssoc = [
                            "id" => $IP1Id,
                            "name_product" => $IP1['name'],
                            "type" => $IP1['type'],
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } elseif ($request->bulan == 6) {
                        $IPAssoc = [
                            "id" => $IP1Id,
                            "name_product" => $IP1['name'],
                            "type" => $IP1['type'],
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } else {
                        $IPAssoc = [
                            "id" => $IP1Id,
                            "name_product" => $IP1['name'],
                            "type" => $IP1['type'],
                            "price" => $ipPrice,
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
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } elseif ($request->bulan == 3) {
                        $IPAssoc = [
                            "id" => $IP2Id,
                            "name_product" => $IP2['name'],
                            "type" => $IP2['type'],
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } elseif ($request->bulan == 6) {
                        $IPAssoc = [
                            "id" => $IP2Id,
                            "name_product" => $IP2['name'],
                            "type" => $IP2['type'],
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } else {
                        $IPAssoc = [
                            "id" => $IP2Id,
                            "name_product" => $IP2['name'],
                            "type" => $IP2['type'],
                            "price" => $ipPrice,
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
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } elseif ($request->bulan == 3) {
                        $IPAssoc = [
                            "id" => $IP3Id,
                            "name_product" => $IP3['name'],
                            "type" => $IP3['type'],
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } elseif ($request->bulan == 6) {
                        $IPAssoc = [
                            "id" => $IP3Id,
                            "name_product" => $IP3['name'],
                            "type" => $IP3['type'],
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    } else {
                        $IPAssoc = [
                            "id" => $IP3Id,
                            "name_product" => $IP3['name'],
                            "type" => $IP3['type'],
                            "price" => $ipPrice,
                            "qty" => $qty,
                            "price_after_qty" => (int)$qty * $ipPrice,
                        ];
                        array_push($dataProducts, $IPAssoc);
                    }
                }
                $totalPrice += $IPAssoc['price_after_qty'];
            }
        }


        if ($request->port == 1 && $arrayAssoc['type'] == "colocation") {
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
        } elseif ($request->port == 10 && $arrayAssoc['type'] == "colocation") {
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


        // dd($votes);

        $additionalCost = 500000;
        $selectedColocationProductId = $request->input('products');
        $productColocation = Product::find($selectedColocationProductId);
        // dd($datacenter);
        $voteSet = 1;

        $lunasTotal = 0;
        $bulanLunas = (int)$request->bulan - 1;
        if ($request->payment == 'lunas') {
            $voteSet = $request->bulan;

            $mailData = [
                'title' => 'Pemberitahuan Invoice',
                'body' => $request->name_customer,
            ];

            // $userMail = User::where('id',$user)->first();

            // dd($userMail->email);

            Mail::to('muhammadazqi098@gmail.com')->send(new LunasMail($mailData));
            // Mail::to($userMail->email)->send(new InvoiceUserMail($mailData));


            $lunasTotal = $arrayAssoc['price_after_qty'] * $bulanLunas;
        } else {
            $lunasTotal = 0;
            $voteSet = 1;
        }
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
                    'datacenter' => $datacenter,
                    // 'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null

                    // user id menyimpan data id dari orang yang login user penanggung jawab
                    'user_id' => $request->userId,
                ]);
                Order_status::create([
                    'order_id' => $prosesTambahData->id,
                    'data' => $request->data,
                    'status' => $request->status,
                    'access' => $request->filled('access') ? $request->access : null,
                    'payment' => (int)$voteSet,
                ]);
            } else {
                $prosesTambahData = Order::create([
                    'name_customer' => $request->name_customer,
                    'products' => $dataProducts,
                    'total_price' => $totalPrice + $lunasTotal,
                    'votes' => $voteSet,
                    'bulan' => $request->bulan,
                    'datacenter' => $datacenter,
                    // 'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null

                    // user id menyimpan data id dari orang yang login user penanggung jawab
                    'user_id' => $request->userId,
                ]);
                Order_status::create([
                    'order_id' => $prosesTambahData->id,
                    'data' => $request->data,
                    'status' => $request->status,
                    'access' => $request->filled('access') ? $request->access : null,
                    'payment' => (int)$voteSet,
                ]);
            }

            $primary = $prosesTambahData->id;
            $order = Order::find($primary);



            if ($order) {


                $existingProduct1 = isset($order->products[0]) ? $order->products[0] : null;
                $existingProduct2 = isset($order->products[1]) ? $order->products[1] : null;
                $existingProduct3 = isset($order->products[2]) ? $order->products[2] : null;
                $existingProduct4 = isset($order->products[3]) ? $order->products[3] : null;

                $price = data_get($order['products'], '1.price', 0);


                $inventory = [];
                if ($existingProduct1['type'] == 'dedicated') {

                    foreach ([$request->ramServer] as $key) {
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
                } else {
                    $totalin = 0;
                }

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

                // $inventory = [$request->ram,$request->disk,$request->disk];
                $existingProductObject2 = $this->convertArrayToStdClass($existingProduct2);


                // dd($inventory);
                $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                $newProduct->id = $primary;
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
                if ($existingProduct1['type'] == 'dedicated') {
                    $newProduct->startDate = $order['created_at']->addDays(30 * $request->bulan);
                }

                // dd($order['created_at']->addDays(30 * $request->bulan));
                if ($request->payment == "lunas" || $existingProduct1['type'] == 'colocation') {
                    $newProduct->endDate = $order['created_at']->addDays(30 * $request->bulan);
                }



                $orderProducts = $order['products'];


                if ($existingProduct1['type'] == 'dedicated') {
                    // $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4, $newProduct];
                    // $newProduct->qty = 1;
                    array_push($orderProducts, $newProduct);
                } elseif ($totalin == 0) {
                    array_push($orderProducts, $newProduct);
                    // $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4, $newProduct];
                } else {
                    array_push($orderProducts, $newProduct);
                    // $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4, $newProduct, $ramProduct];
                }

                $order->products = $orderProducts;

                $user_id = $order['user_id'];
                $entryGet = User::where('id', $user_id)->first();
                $dataEntry = $entryGet['entryData'];
                $dataEnd = [
                    "datacenter" => $request->datacenter,
                    "serverLabel" => $request->serverLabel,
                ];
                $dataEntry[0][2] = $dataEnd;

                $entryGet['entryData'] = $dataEntry;

           
                $entryGet->save();
               

                $order->save();
                // dd($orderProducts,$newProduct);
            }
            return redirect()->route('user.data', $prosesTambahData->id)->with('success', 'Berhasil Menambahakan Order');
        }


        // redirect ke halaman login
    }


    public function adminBayar($id)
    {
        //
        // $products = Product::all();
        $paymentData =  Order_status::where('order_id', $id)->first();

        if ($paymentData['payment'] < 1) {

            $payment = $paymentData['payment'] + 1;
        } else {
            $payment = $paymentData['payment'] - 1;
        }
        // $tambahPayment =  Order_status::where('order_id', $id)->update([
        //     'payment' => $payment + 1,
        // ]);
        // dd($payment);
        $paymentData['payment'] = $payment;

        $paymentData->save();


        $orderId = Order::where('id', $id)->first();
        // dd($orderId['products']);
        $order = Order::find($id);


        // $totalPrice = $order->total_price;
        $totalPrice = $this->calculateTotalPrice($order);
        $existingTotal = $orderId['total_price'];
        // $singleTotal = $order['products'][0]['price_after_qty'];

        $sewaPrice = $order['products'][0]['price_after_qty'];
        $typeProduct = $order['products'][0]['type'];
        // dd($totalEnd,$totalPrice,$existingTotal,$sewaPrice);


        //   $data = array_diff($data, ["5", "6"]);
        if ($paymentData['payment'] < 0) {

            $orderId['votes'] = $payment * -1;
            $totalEnd = $existingTotal + $sewaPrice;
        } elseif ($typeProduct == 'dedicated') {

            $orderId['votes'] = $payment;

            if ($orderId['bulan'] == 12) {

                $totalEnd = $existingTotal + $sewaPrice + 300000;
            } else {

                $totalEnd = $existingTotal + $sewaPrice;
            }

            // pildun
        } else {

            $orderId['votes'] = $payment;
            $totalEnd = $existingTotal + $sewaPrice;
            // sewaPrice disini adalah menu Pembelian setelah melakuakn pembayaran sekali dari ram ddr dan lainnya 
        }

        $orderId['total_price'] = $totalEnd;

        $endDate = $orderId['updated_at']->addDays(30 );
      
        // $price = data_get($order['products'], '1.price', 0);
        $existingProduct5 = last($orderId->products);

        $existingProductObject5 = $this->convertArrayToStdClass($existingProduct5);

        $existingProduct5['startDate'] = $endDate;
        // $existingProductObject5->startDate = $endDate;

        // dd($existingProductObject1->startDate);
        // $orderId['products'] = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4, $existingProductObject5];
        $productHas = $orderId['products'];
        array_push($productHas, $existingProduct5);
        // mentega
        array_splice($productHas, 5);

        $orderId['products'] = $productHas;
        // $existingProductObject1->save();

        $orderId->save();


        return redirect()->back()->with('success', 'Berhasil Mengkonfirmasi Order!');
    }




    public function createDedicated($id)
    {
        //
        // $products = Product::all();
        $productEntry = User::where('id', $id)->first();
        $data1 = [];

        // dd($data1[4]);
        $lunasChecked = data_get($data1, '4', false);
        // dd($lunasChecked);
        $products = Product::orderBy('name', 'ASC')->paginate(100);
        $bulan = Order::all();
        $userData = User::where('id', $id)->first();


        return view('order.admin.dedicated', compact('lunasChecked', 'products', 'bulan', 'userData', 'productEntry'));
    }

    public function createColocation($id)
    {
        //
        // $products = Product::all();

        // $lunasChecked = data_get($data1,'4',false);
        $productEntry = User::where('id', $id)->first();
        $data1 = [];

        // dd($data1[4]);
        $lunasChecked = data_get($data1, '4', false);
        // dd($lunasChecked);
        $products = Product::orderBy('type', 'ASC')->paginate(100);
        $bulan = Order::all();
        $userData = User::where('id', $id)->first();



        return view('order.admin.colocation', compact('products', 'bulan', 'userData', 'productEntry', 'lunasChecked'));
    }



    // -------------------Admin-Area------------------------
    //


    public function pengirimanAdmin($id)
    {

        $payment = Order_status::where('order_id', $id)->first();


        $paymentAdd = $payment['payment'];
        if ($paymentAdd < 1) {
            $PaymentPlus = $payment['payment'] - 1;
        } else {
            $PaymentPlus = $payment['payment'] + 1;
        }

        $payment['payment'] = $PaymentPlus;

        $payment->save();

        if ($paymentAdd < 1) {

            $paymentSewa = $payment['payment'] + 2;
            $paymentMinus = $paymentSewa * -1;
        } else {
            $paymentMinus = $payment['payment'] - 2;
        }

        $order = Order::find($id);

        if ($paymentMinus == $order['votes']) {
            return redirect()->back()->with('success', 'Berhasil Melakukan Pembayaran');
        } else {


            $mailData = [
                'title' => 'Pemberitahuan Invoice',
                'body' => $order->name_customer,
            ];

            $user = $order->user_id;
            // $userMail = User::where('id',$user)->first();

            // dd($userMail->email);

            Mail::to('muhammadazqi098@gmail.com')->send(new InvoiceMail($mailData));
            // Mail::to($userMail->email)->send(new InvoiceUserMail($mailData));

            // dd('Email send successfully.');

            return redirect()->back()->with('success', 'berhasil mengirirm Email');
        }
    }


    public function data()
    {
        $order_statuses = Order_status::OrderBy('id', 'ASC')->simplePaginate(5);
        
        $perPage = request('perPage', 10); // Default to 10 items per page
        $orders = Order::simplePaginate($perPage);
        $selectedProduct1 = Product::find(13);
        $selectedProduct2 = Product::find(14);
        $selectedProduct3 = Product::find(15);
        return view('order.admin.index', compact('order_statuses', 'orders'));
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
   
    // public function show(Order $order)
    // {
    //     //
    // }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
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
    public function updateAdmin(Request $request, $id)
    {
        // validasi
        $request->validate([
            'name_customer' => 'required',
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

                    if ($selectedDedicatedProductId == 1) {
                        $baseCost = 350000;
                    } elseif ($selectedDedicatedProductId == 2) {

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


                    // Periksa apakah produk sudah ada
                    if ($existingProduct) {
                        $existingProductObject = $this->convertArrayToStdClass($existingProduct);
                        // $existingBulanObject = $this->convertArrayToStdClass($existingBulan);
                        // Update data produk yang sudah ada
                        $existingProduct['price'] = $existingProduct;
                    }

                    // Buat objek produk baru
                    if ($selectedDedicatedProductId == 1) {
                        $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                        $newProduct->id = 1;
                        $newProduct->name_product = "(FREEZE) Dedicated Bogor";
                        $newProduct->price = 350000;
                        $newProduct->type = "FREEZE";
                        $newProduct->qty = 1;
                        $newProduct->price_after_qty = $totalCost;
                    } elseif ($selectedDedicatedProductId == 2) {
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
                   
                    // Produk Perpindahan ----------------------------
                    $dataProducts = [];
                    $newProduct = new Product(); // Gantilah 'Product' dengan nama model atau entitas yang sesuai
                    $newProduct->id = $productColocation->id;
                    $newProduct->name_product = $productColocation->name;
                    $newProduct->price = $productColocation->price;
                    $newProduct->type = $productColocation->type;
                    $newProduct->qty = 1;
                    $newProduct->price_after_qty = $totalCost;

                    if ($existingProduct['type'] == 'colocation') {

                        if ($existingProduct['id'] == $newProduct['id']) {
                            // Perbarui harga produk pertama (produk colocation) sesuai dengan bulan yang dipilih
                            $totalPrice = 0;
                            $existingProduct = $order->products[0];
                            if ($selectedMonths == 12) {

                                $existingProduct['price_after_qty'] = ($existingProduct['price'] * $selectedMonths) - 360000;
                            } else {
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

                            // Hitung total harga dari semua produk
                            $totalPrice = $existingProduct['price_after_qty'] +     $existingProduct1['price_after_qty'] + $existingProduct2['price_after_qty'] + $existingProduct3['price_after_qty'];
                            // dd(count($order->products));
                            $order->total_price = $totalPrice + $existingTotal;
                            // Perbarui total harga pesanan

                            $order->bulan = $selectedMonths; // Update jumlah bulan pada pesanan

                            $order->products = [$existingProductObject1, $existingProductObject2, $existingProductObject3, $existingProductObject4];

                            $order->save();

                            // beda alammm:>

                        } else {
                            // perak2
                            $totalPrice = 0;


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
                         

                            $order->total_price = $totalPrice + $existingTotal;
                            // Perbarui total harga pesanan

                            $order->bulan = $selectedMonths; // Update jumlah bulan pada 

                            $order->save();
                        }
                    } else {

                        $userGet = User::find($order['user_id']);
                        $userEntry = $userGet['entryData'][0];
                        $total_price = 0;
                        $dataProducts = [];




                        $existingProduct = $newProduct;
                        if ($selectedMonths == 12) {
                            $newProduct['price_after_qty'] = ($newProduct['price'] * $selectedMonths) - 360000;
                        } else {
                            $newProduct['price_after_qty'] = $newProduct['price'] * $selectedMonths;
                        }

                        $newProductObject1 = $newProduct;

                        array_push($dataProducts, $newProductObject1);

                        $firstId = $newProductObject1['id'];
                        if ($firstId == "10" || $firstId == "11") {
                            $datacenter = [
                                "datacenter" => "Jakarta",
                            ];
                        } else {
                            $datacenter = [
                                "datacenter" => "Bogor",
                            ];
                        }

                        // Setup Start In here
                        if ($request->datacenter == 'Jakarta') {
                            $totalPrice += 750000;
                        }
                        if ($request->bandwidth > 0) {
                            $bandwidthId = '21';
                            $qty = $request->bandwidth;
                            $bandwidth = Product::where('id', $bandwidthId)->first();
                            if ($selectedMonths == 1) {

                                $bandwidthAssoc = [
                                    "id" => $bandwidthId,
                                    "name_product" => $bandwidth['name'],
                                    "type" => $bandwidth['type'],
                                    "price" => $bandwidth['price'],
                                    "qty" => $qty,
                                    "price_after_qty" => (int)$qty * (int)$bandwidth['price'],
                                ];
                                array_push($dataProducts, $bandwidthAssoc);
                            } elseif ($selectedMonths == 3) {
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
                            } elseif ($selectedMonths == 6) {
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

                                $ipPrice = ($IP1['price'] * $selectedMonths);


                                if ($selectedMonths == 1) {

                                    $IPAssoc = [
                                        "id" => $IP1Id,
                                        "name_product" => $IP1['name'],
                                        "type" => $IP1['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } elseif ($selectedMonths == 3) {
                                    $IPAssoc = [
                                        "id" => $IP1Id,
                                        "name_product" => $IP1['name'],
                                        "type" => $IP1['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } elseif ($selectedMonths == 6) {
                                    $IPAssoc = [
                                        "id" => $IP1Id,
                                        "name_product" => $IP1['name'],
                                        "type" => $IP1['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } else {
                                    $IPAssoc = [
                                        "id" => $IP1Id,
                                        "name_product" => $IP1['name'],
                                        "type" => $IP1['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                }
                            } elseif ($request->IP == 27) {

                                $ipPrice = ($IP2['price'] * $selectedMonths);

                                if ($selectedMonths == 1) {

                                    $IPAssoc = [
                                        "id" => $IP2Id,
                                        "name_product" => $IP2['name'],
                                        "type" => $IP2['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } elseif ($selectedMonths == 3) {
                                    $IPAssoc = [
                                        "id" => $IP2Id,
                                        "name_product" => $IP2['name'],
                                        "type" => $IP2['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } elseif ($selectedMonths == 6) {
                                    $IPAssoc = [
                                        "id" => $IP2Id,
                                        "name_product" => $IP2['name'],
                                        "type" => $IP2['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } else {
                                    $IPAssoc = [
                                        "id" => $IP2Id,
                                        "name_product" => $IP2['name'],
                                        "type" => $IP2['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                }
                            } elseif ($request->IP == 24) {
                                $ipPrice = ($IP3['price'] * $selectedMonths);

                                if ($selectedMonths == 1) {

                                    $IPAssoc = [
                                        "id" => $IP3Id,
                                        "name_product" => $IP3['name'],
                                        "type" => $IP3['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } elseif ($selectedMonths == 3) {
                                    $IPAssoc = [
                                        "id" => $IP3Id,
                                        "name_product" => $IP3['name'],
                                        "type" => $IP3['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } elseif ($selectedMonths == 6) {
                                    $IPAssoc = [
                                        "id" => $IP3Id,
                                        "name_product" => $IP3['name'],
                                        "type" => $IP3['type'],
                                        "price" => $ipPrice,
                                        "qty" => $qty,
                                        "price_after_qty" => (int)$qty * $ipPrice,
                                    ];
                                    array_push($dataProducts, $IPAssoc);
                                } else {
                                    $IPAssoc = [
                                        "id" => $IP3Id,
                                        "name_product" => $IP3['name'],
                                        "type" => $IP3['type'],
                                        "price" => $ipPrice,
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
                        // Satup End in here

                        $label = [
                            "label" => $request->label_product,
                        ];
                        $bulanNew = [
                            "bulan" => $selectedMonths,
                        ];

                        $newProductEnd = [$dataProducts, $bulanNew, $datacenter, $label];
                        $ended = [$userEntry, $newProductEnd];

                        // if($request->has('IP')){
                        //     // $existingProduct2['name_product'] = 

                        //     dd($newProductObject1['id'],$ended,$userGet->entryData,$order['user_id'],$newProduct);

                        // }


                        // $userGet-
                        //emas

                        $userGet->entryData = [$userEntry, $newProductEnd]; // Update jumlah bulan pada pesanan
                        $userGet->save(); // Update jumlah bulan pada pesanan
                        // dd($existingTotal);

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
                        // 'bulan' => "$status2->data",
                    ]);
                    // perak
                    $statusPayment = Order_status::where('order_id', $id)->first();
                    //    $payment =  $statusPayment['payment'];
                    //    perak
                    if ($statusPayment['payment'] < 1) {
                        $statusPayment->increment('payment', 1, [
                            'updated_at' => $order->updated_at
                        ]);
                    } else {

                        $statusPayment->decrement('payment', 1, [
                            'updated_at' => $order->updated_at
                        ]);
                    }
                }
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
                'votes' => 1,
                'total_price' => $totalPrice,

            ]);
            // $order->increment('votes', 1, [
            //     'updated_at' => $order->updated_at
            // ]);
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
            $existingProduct1 = $order->products[4] ?? null;
            $existingTotal = $order->total_price;

            // if($order->p)
            $productType = data_get($order->products, '1.type', 0);
            $price = data_get($order->products, '1.price', 0);
            // if ($productType == "ram") {

            //     if ($existingProduct) {
            //         $existingProductObject = $this->convertArrayToStdClass($existingProduct);
            //         $existingProductObject1 = $this->convertArrayToStdClass($existingProduct1);
            //     }

            //     $order->products = [$existingProductObject,$existingProductObject1];

            //     $order->save();
            // }

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
                        $Ha = $existingProduct['price'] + 300000;
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                        $totalEnd = $Ha + $existingTotal;
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                    } else {
                        $Ha = $existingProduct['price'];
                        // ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
                        $totalEnd = $Ha + $existingTotal;

                    }
                }

                $order->update([
                    'total_price' => $totalEnd,

                ]);
            }
            $statusPayment = Order_status::where('order_id', $id)->first();
            //    $payment =  $statusPayment['payment'];
            //    perak
            if ($statusPayment['payment'] < 1) {
                $statusPayment->increment('payment', 1, [
                    'updated_at' => $order->updated_at
                ]);
            } else {

                $statusPayment->decrement('payment', 1, [
                    'updated_at' => $order->updated_at
                ]);
            }
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

        $accessMail = $status2->access;
       

            return redirect()->back()->with('success', 'Berhasil Mengajukan Pemesanan!');
        
    }




    // batas{Konoha!}

    

    




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

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Order $order)
    {
        //
    }

    public function lengthed(Request $request, $id)
    {

        $order = Order::find($id);
        $productsId = $order['products'][0]['id'];
        $products = Product::find($productsId);
        $status = Order_status::where('order_id', $id)->first();

        $productGet = $order['products'];

        if ($request->bulan == 12) {

            $productGet[0]['price_after_qty'] = ($products['price'] * $request->bulan) - 2500000;
        } else {
            $productGet[0]['price_after_qty'] = $products['price'] * $request->bulan;
        }


        // $order['products'] = $productGet;

        $order['bulan'] = $request->bulan;
        $order['votes'] = $request->bulan;

        $status['payment'] = $request->bulan;
        // $order['payment'] = $request->bulan;

        $existingTotal = $order['total_price'];
        $totalPrice = $productGet[0]['price_after_qty'];

        // $ram = data_get($productGet[1],"type.ram",false);
        // $ssd = data_get($productGet[1],"type.ssd",false);
        // if ($productGet[1]['type'] == "ram" || $order[1]['type'] == "ssd") {

        // } else {
        //     $totalPrice = $this->calculateTotalPrice($order);
        // }

        $totalEnd = $existingTotal + $totalPrice;
        $countProduct = count($order['products']) - 1;

        // $totalEnd = $existingTotal + $totalPrice + 500000;
        // sebeumnya ditambahakn 500rb untuk biaya setup
        $last = $productGet[$countProduct];  
        $startDate = data_get($last,"startDate",false);
        $endDate = data_get($last,"endDate",false);
     
        if($startDate !== false){
            $productGet[$countProduct]["startDate"] = $order['updated_at']->addDays(30 * $request->bulan);
        }else{
            $productGet[$countProduct]["endDate"] = $order['updated_at']->addDays(30 * $request->bulan);
            
        }
        $order['total_price'] = $totalEnd;
        // dd($productGet,$totalEnd);
        // dd($last);

        $order['products'] = $productGet;

        // dd($productGet,$products['price'],$totalPrice,$totalEnd,$products['price']);

        $order->save();

        return redirect()->route('status.colocation')->with('success', 'berhasil melakukan Perpanjangan Produk !');
    }
}
