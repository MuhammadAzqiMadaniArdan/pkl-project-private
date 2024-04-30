<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Order_status;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dashboard()
    {
        $users = User::where('role', 'user')->count();
        $admin = User::where('role', 'admin')->count();
        // $order = Order::All();
        $order = Order::All();
        $dedicated = Product::where('type', 'dedicated')->count();
        $dedicated1 = Product::where('type', 'dedicated')->simplePaginate(100);
        $colocation = Product::where('type', 'colocation')->count();
        $product = Product::orderBy('id')->count();
        $status2 = Order_status::orderBy('id')->count();
        $authTest = Auth::user()->id;
        $orderGet = Order::where('user_id', $authTest)->get();

        $colloOrder = [];
        $dedicOrder = [];

        for ($i = 0; $i < count($orderGet); $i++) {

            $orderType = $orderGet[$i]['products'][0]['type'];
            if ($orderType == 'colocation') {
                $data1 = $orderGet[$i];
                array_push($colloOrder, $data1);
            } else {
                $data2 = $orderGet[$i];
                array_push($dedicOrder, $data2);
            }
        }
        // id = 19
        // dd(count($dedicOrder),$authTest,count($orderGet));
        $dedicTotal = count($dedicOrder);
        $colloTotal = count($colloOrder);
        $dedic1 = [];
        $dedicMurah = [];
        $dedicatedDer = Order::simplePaginate(100);
        // foreach ($dedicatedDer as $key ) {
        //     foreach ($key['products'] as $product) {
        //         // dd($dedicatedDer[0]['products'],$product);
        //         # code...
        //         if($product['type'] == 'dedicated' ){
        //             $dedic2 = $key;
        //             array_push($dedic1,$dedic2 );
        //         }elseif($product['type'] == 'colocation' ) {
        //             # code...
        //             $dedic3 = $key;
        //             array_push($dedicMurah,$dedic3 );
        //         }

        //     }
        // }

        // dd($order,$dedicated1,$dedic1,$dedicMurah);
        return view('dashboard', compact('dedicatedDer', 'users', 'admin', 'order', 'product', 'status2', 'dedicated', 'colocation', 'dedicTotal', 'colloTotal'))->with('success', 'login berhasil!');
    }
    //  karena function diakses setelah login maka diatmabahkan request
    public function authLogin(Request $request)
    {
        $request->validate([
            // email dns digunakan untuk mengecek user apakah memeiliki alamt google,yahoo dll yang bersifat dns
            'email' => 'required',
            // 'email' => 'required|email:dns',
            'password' => 'required',

        ]);
        // simpan data dari dalam email dan password ke dalam variabel untuk memudahkan panggilan 
        $user = $request->only(['email', 'password']);
        // mengecek kecocokkan email dan password kemudian menyimopannya d dalam class beranama auth(memberi didentitas data riwayat login ke project)
        if (Auth::attempt($user)) {
            return redirect('/dashboard')->with('success', 'Login Berhasil!');
            // perbedaan redirecxt dan route 
        } else {
            return redirect()->back()->with('failed', 'Login gagal! silakan coba lagi');
        }
    }

    public function authLoginAdmin(Request $request)
    {
        $request->validate([
            // email dns digunakan untuk mengecek user apakah memeiliki alamt google,yahoo dll yang bersifat dns
            'email' => 'required|email',
            'password' => 'required',

        ]);
        // simpan data dari dalam email dan password ke dalam variabel untuk memudahkan panggilan 
        $user = $request->only(['email', 'password']);

        $userRole = User::where('email', $request->email)->first();


        // mengecek kecocokkan email dan password kemudian menyimopannya d dalam class beranama auth(memberi didentitas data riwayat login ke project)
        if ($userRole['role'] == "admin" && Auth::attempt($user)) {
            return redirect('/dashboard')->with('success', 'login Admin berhasil!');
            // perbedaan redirect dan route 
        } else {
            return redirect()->back()->with('error', 'Login gagal! Anda Bukan Admin');
        }
    }

    public function authRegister(Request $request)
    {
        $request->validate([
            // email dns digunakan untuk mengecek user apakah memeiliki alamt google,yahoo dll yang bersifat dns
            'email' => 'required|email',
            'password' => 'required',

        ]);
        // simpan data dari dalam email dan password ke dalam variabel untuk memudahkan panggilan 
        $user = $request->only(['email', 'password']);
        // mengecek kecocokkan email dan password kemudian menyimopannya d dalam class beranama auth(memberi didentitas data riwayat login ke project)
        if (Auth::attempt($user)) {
            return redirect('/dashboard');
            // perbedaan redirecxt dan route 
        } else {
            return redirect()->back()->with('failed', 'Register gagal! silakan coba lagi');
        }
    }

    public function logout()
    {
        // menghapus atau menghilangkan data session login 
        Auth::logout();
        return redirect()->route('login');
    }
    public function index()
    {
        //proses ambil data
        $users = User::where('role', 'user')->orderBy('name', 'ASC')->simplePaginate(5);
        // $users = $usersRole::orderBy('name','ASC')->simplePaginate(5);
        $admin = User::where('role', 'admin')->simplePaginate(5);

        // mannggil html yang ada di folder resources/views/user.index.blade.php
        //compact : mengirim data ke blade 
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('user.index', compact('users', 'admin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function accountCreate(Request $request)
    {
        //

        // validasi
        // 'name_input' => 'validasi1/validasi2'
        // dd($request->firstname);
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'nullable',
            'email' => 'required|email|unique:users',
            'notelp' => 'required',
            'company' => 'nullable',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        // arrRecipient


        // Ambil data dari checkbox yang terpilih

        // Proses untuk menyimpan data penerima ke dalam array $dataRecipients
        $dataAddress = [];

        // Cari penerima di antara guru-guru yang ada
        // $guru = $guruaddress->where('id', $recipientId)->first();

        // Jika guru ditemukan, tambahkan ke dalam array $dataAddress
        $dataAddress[] = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ];


        $request['address'] = $dataAddress;
        if ($request->lastname == null) {
        } else {

            $name = $request->firstname . ' ' . $request->lastname;
        }

        if ($request->password == $request->confirm_password) {
            User::create([
                'name' => $name,
                'email' => $request->email,
                'password' => hash::make($request->password),
                'role' => $request->role,
                'notelp' => $request->notelp,
                'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                'address' => $request->address,
            ]);

            // abis simpen, arahin ke halaman mana
            return redirect()->route('user.data')->with('success', 'berhasil membuat Akun!');
        } else {
            return redirect()->back()->with('error', 'Password dan confirm Password Harus Sama');
        }
    }

    public function store(Request $request)
    {
        //

        // validasi
        // 'name_input' => 'validasi1/validasi2'

        // validasi
        // 'name_input' => 'validasi1/validasi2'

        $request->validate([
            'firstname' => 'required',
            'lastname' => 'nullable',
            'email' => 'required|email|unique:users',
            'notelp' => 'required',
            'company' => 'nullable',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'password' => 'required',
            'role' => 'required',
            'confirm_password' => 'required',
        ]);

        // arrRecipient


        // Ambil data dari checkbox yang terpilih

        // Proses untuk menyimpan data penerima ke dalam array $dataRecipients
        $dataAddress = [];

        // Cari penerima di antara guru-guru yang ada
        // $guru = $guruaddress->where('id', $recipientId)->first();

        // Jika guru ditemukan, tambahkan ke dalam array $dataAddress
        $dataAddress[] = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ];


        $request['address'] = $dataAddress;

        if ($request->lastname == null) {
            $name = $request->firstname;
        } else {

            $name = $request->firstname . ' ' . $request->lastname;
        }

        // dd($name,$request->password ,$request->confirm_password);

        if ($request->password == $request->confirm_password) {
            User::create([
                'name' => $name,
                'email' => $request->email,
                'password' => hash::make($request->password),
                'role' => $request->role,
                'notelp' => $request->notelp,
                'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null
                'address' => $request->address,
            ]);

            // abis simpen, arahin ke halaman mana
            return redirect()->route('user.data')->with('success', 'berhasil membuat Akun!');
        } else {
            return redirect()->back()->with('failed', 'Password dan confirm Password Harus Sama');
        }

        // abis simpen, arahin ke halaman mana
        // return redirect()->back()->with('success', 'berhasil menambahkan data produk!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $user = User::find($id);
        // mengembalikan bentuk json dikirim data yang diambil dari response status code 200
        // response status code api :
        // 200 -> success/ok
        // 400 an -> errror kode/validasi input
        // 419 ->error token csrf
        // 500 an -> error server hosting
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        // mengambil data yang belum dimunculkan
        // find: mencari berdasarkan column
        // bisa jkuga : where ('id',$id)->first()
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    public function editAkun($id)
    {
        //
        // mengambil data yang belum dimunculkan
        // find: mencari berdasarkan column
        // bisa jkuga : where ('id',$id)->first()
        $user = User::find($id);

        return view('order.user.user', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        // validasi
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'notelp' => 'required',
            'company' => 'nullable',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        $pw = password_hash($request->password, PASSWORD_DEFAULT);

        // cari berdasarkan id terus update
        $dataAddress = [];

        // Cari penerima di antara guru-guru yang ada
        // $guru = $guruaddress->where('id', $recipientId)->first();

        // Jika guru ditemukan, tambahkan ke dalam array $dataAddress
        $dataAddress[] = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ];


        $request['address'] = $dataAddress;

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'notelp' => $request->notelp,
            'address' => $request->address,
            'password' => $pw,
            'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null
        ]);
        // redirect ke html user data
        // route digunakan untuk memindahkan suatu ke page yang lain jika ingin menambahkan notif ke tempat lain bisa di ganti ke product.tambah atau product.edit
        // return redirect()->back()->with('success', 'Berhasil mengubah data produk!');
        return redirect()->route('user.data')->with('success', 'Berhasil mengubah data user!');
    }

    public function updateAkun(Request $request, $id)
    {
        //
        // validasi
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'notelp' => 'required',
            'company' => 'nullable',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'password' => '',
        ]);
        $pw = password_hash($request->password, PASSWORD_DEFAULT);

        // cari berdasarkan id terus update
        $dataAddress = [];

        // Cari penerima di antara guru-guru yang ada
        // $guru = $guruaddress->where('id', $recipientId)->first();

        // Jika guru ditemukan, tambahkan ke dalam array $dataAddress
        $dataAddress[] = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ];


        $request['address'] = $dataAddress;

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'notelp' => $request->notelp,
            'address' => $request->address,
            'company' => $request->filled('company') ? $request->company : null, // Menyimpan data perusahaan jika diisi, jika tidak, maka null
        ]);
        // redirect ke html user data
        // route digunakan untuk memindahkan suatu ke page yang lain jika ingin menambahkan notif ke tempat lain bisa di ganti ke product.tambah atau product.edit
        // return redirect()->back()->with('success', 'Berhasil mengubah data produk!');
        return redirect()->back()->with('success', 'Berhasil mengubah data user!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        User::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }

    public function searchAdmin(Request $request)
    {

        $user = User::OrderBy('name', 'Asc')->paginate(5);
        $adminHas = [];
        if ($request->has('q')) {
            $search = $request->q;
            $adminHas = User::select('id', 'name')
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }
        dd($adminHas);
        return response()->json($adminHas);
    }

    public function search(Request $request)
    {
        $search = $request->input('searchUser');


        $users = User::where('role', 'user')->where('name', 'like', "%$search%")->simplePaginate(5);
        $admin = User::where('role', 'admin')->simplePaginate(5);


        // ('name', 'like', "%$search%")->get();

        // $product = Product::where('created_at',$order)->first();

        // format assoc dimasukkan ke array penampung sebelumnya


        return view('user.index', compact('users', 'admin'));
    }
}
