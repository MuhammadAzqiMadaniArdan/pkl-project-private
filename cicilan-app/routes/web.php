<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('IsGuest')->group(function () {
    // ketika akses link pertama kali yg dimunculin halaman login
    Route::get('/', function () {
        return view('main');
    })->name('main');

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::prefix('/register')->name('register.')->group(function () {
        Route::get('/', function () {
            return view('register');
        })->name('index');
        Route::post('/create', [UserController::class, 'accountCreate'])->name('account-create');
    });


    Route::prefix('/loginAdmin')->name('loginAdmin.')->group(function () {
        Route::get('/', function () {
            return view('loginAdmin');
        })->name('index');
    });

    // menangani proses submit login
    Route::post('/loginUser', [UserController::class, 'authLogin'])->name('auth-login');
    Route::post('/loginAdmin', [UserController::class, 'authLoginAdmin'])->name('auth-login-Admin');
    Route::post('/register', [UserController::class, 'authregister'])->name('auth-register');
});

Route::middleware('IsLogin')->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('auth-logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    });


    Route::get('/send-mail', [MailController::class, 'index']);

    Route::fallback(function () {
        return view('errors.404');
    });
    
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::middleware('IsAdmin')->group(function () {
        Route::prefix('/admin/order')->name('admin.order.')->group(function () {
            Route::get('/', [OrderController::class, 'data'])->name('data');
            Route::get('/downloadExcel', [OrderController::class, 'downloadExcel'])->name('downloadExcel');
            Route::get('/createDedicated/{id}', [OrderController::class, 'createDedicated'])->name('createDedicated');
            Route::get('/createColocation/{id}', [OrderController::class, 'createColocation'])->name('createColocation');
            Route::get('/entryData/{id}', [OrderStatusController::class, 'entryData'])->name('entryData');
            Route::post('/store', [OrderController::class, 'adminStore'])->name('adminStore');
            Route::patch('/bayar/{id}', [OrderController::class, 'adminBayar'])->name('adminBayar');
            Route::patch('/update/{id}', [OrderController::class, 'adminUpdate'])->name('adminUpdate');
            // Route::patch('/update/{id}', [OrderController::class, 'update'])->name('update');

            Route::get('/pengirimanAdmin/{id}', [OrderController::class, 'pengirimanAdmin'])->name('pengirimanAdmin');
            Route::patch('/lengthed/{id}', [OrderController::class, 'lengthed'])->name('lengthed');

        });
        Route::prefix('/product')->name('product.')->group(function () {
            Route::get('/data', [ProductController::class, 'index'])->name('data');
            Route::get('/search', [ProductController::class, 'search'])->name('search');
            Route::get('/searchStock', [ProductController::class, 'searchStock'])->name('searchStock');

            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/store', [ProductController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('delete');
            Route::get('/data/stock', [ProductController::class, 'stockData'])->name('data.stock');
            Route::get('/{id}', [ProductController::class, 'show'])->name('show');
            Route::patch('/stock/update/{id}', [ProductController::class, 'updateStock'])->name('stock.update');
        });
        Route::prefix('/detail_server')->name('detail_server.')->group(function () {
            
            Route::get('/data', [OrderStatusController::class, 'indexServer'])->name('data');
            Route::get('/data/{id}', [OrderStatusController::class, 'singleServer'])->name('single');
            Route::get('/search', [OrderStatusController::class, 'search'])->name('search');
            Route::get('/create/{id}', [OrderStatusController::class, 'createServer'])->name('create');
            Route::post('/store/{id}', [OrderStatusController::class, 'storeServer'])->name('store');
            Route::get('/edit/{id}', [OrderStatusController::class, 'editServer'])->name('edit');
            Route::patch('/update/{id}', [OrderStatusController::class, 'updateServer'])->name('update');
            Route::delete('/delete/{id}', [OrderStatusController::class, 'destroyServer'])->name('delete');
            Route::get('/data/stock', [OrderStatusController::class, 'stockData'])->name('data.stock');
            Route::get('/{id}', [OrderStatusController::class, 'show'])->name('show');
            Route::patch('/stock/update/{id}', [OrderStatusController::class, 'updateStock'])->name('stock.update');
        });
        Route::prefix('/internal')->name('internal.')->group(function () {
            Route::get('/Bogor', [OrderStatusController::class, 'indexBogor'])->name('Bogor');

            Route::get('/Jakarta', [OrderStatusController::class, 'indexJakarta'])->name('Jakarta');
            Route::get('/search', [OrderStatusController::class, 'searchData'])->name('search');
            Route::get('/racksearch', [OrderStatusController::class, 'rackSearch'])->name('rackSearch');
            Route::get('/search2', [OrderStatusController::class, 'searchData2'])->name('search2');
            Route::get('/ajax-autocomplete-search', [OrderStatusController::class, 'selectSearch'])->name('searchAjax');
            Route::get('/create/{id}', [OrderStatusController::class, 'createInternal'])->name('create');
            Route::post('/store/{id}', [OrderStatusController::class, 'storeInternal'])->name('store');
            Route::get('/edit/{id}', [OrderStatusController::class, 'editInternal'])->name('edit');
            Route::patch('/update/{id}', [OrderStatusController::class, 'updateInternal'])->name('update');
            Route::delete('/delete/{id}', [OrderStatusController::class, 'destroyInternal'])->name('delete');
            Route::get('/data/stock', [OrderStatusController::class, 'stockData'])->name('data.stock');
            Route::get('/{id}', [OrderStatusController::class, 'show'])->name('show');
            Route::patch('/stock/update/{id}', [OrderStatusController::class, 'updateStock'])->name('stock.update');
        });
        Route::prefix('/user')->name('user.')->group(function () {
            Route::get('/data', [UserController::class, 'index'])->name('data');
            Route::get('/user_data', [UserController::class, 'user_data'])->name('user_data');
            Route::get('/searchAdmin', [UserController::class, 'searchAdmin'])->name('searchAdmin');
            Route::get('/search', [UserController::class, 'search'])->name('search');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
            Route::get('/data/stock', [UserController::class, 'stockData'])->name('data.stock');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
        });

        Route::prefix('/status')->name('status.')->group(function () {
            Route::get('/', [OrderStatusController::class, 'index'])->name('index');
            Route::get('/create', [OrderStatusController::class, 'create'])->name('create');
            Route::get('/search', [UserController::class, 'search'])->name('search');   
            Route::get('/status', [OrderStatusController::class, 'status'])->name('status');
            Route::get('/dedicated', [ProductController::class, 'dedicatedNew'])->name('dedicated');
            Route::get('/sewaData', [OrderStatusController::class, 'sewaIndex'])->name('sewaIndex');
            Route::get('/sewa/{id}', [OrderStatusController::class, 'sewa'])->name('sewa');
            Route::get('/colocation', [ProductController::class, 'colocationNew'])->name('colocation');
            Route::post('/store', [OrderStatusController::class, 'store'])->name('store');
            Route::get('/single/{id}', [OrderStatusController::class, 'single'])->name('single');
            Route::delete('/deleteSingle/{id}', [OrderStatusController::class, 'deleteSingle'])->name('deleteSingle');
            Route::patch('/sewaUpdate/{id}', [OrderStatusController::class, 'sewaUpdate'])->name('sewaUpdate');
            Route::get('/sewaSearch', [OrderStatusController::class, 'sewaSearch'])->name('sewaSearch');
            
            // Route::get('/edit/{id}', [OrderStatusController::class, 'edit'])->name('edit');

            Route::get('/new_status/{id}', [OrderStatusController::class, 'new_status'])->name('new_status');
            Route::get('/show/{id}', [OrderStatusController::class, 'show'])->name('show');
            Route::patch('/status/{id}', [OrderStatusController::class, 'update'])->name('update');
            
            Route::get('/custom/{id}', [OrderStatusController::class, 'custom'])->name('custom');
            Route::get('/struk/{id}', [OrderStatusController::class, 'strukPembelian'])->name('struk');
            // Route::get('/download-pdf/{id}', [OrderStatusController::class, 'downloadPDF'])->name('download-pdf');
            Route::get('/search', [OrderStatusController::class, 'search'])->name('search');
            Route::post('add-remove-multiple-input-fields', [OrderStatusController::class, 'storeMulitple']);
            Route::patch('/lunas/{id}', [OrderStatusController::class, 'lunasUpdate'])->name('lunasUpdate');
        });
    });

    Route::middleware('IsUser')->group(function () {

        Route::prefix('/userAkun')->name('userAkun.')->group(function () {
            Route::get('/edit/{id}', [UserController::class, 'editAkun'])->name('editAkun');
            Route::patch('/update/{id}', [UserController::class, 'updateAkun'])->name('updateAkun');
        });

        Route::prefix('/order')->name('order.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/create', [OrderController::class, 'create'])->name('create');
            Route::get('/dedicated', [OrderController::class, 'dedicated'])->name('dedicated');
            Route::get('/colocation', [OrderController::class, 'colocation'])->name('colocation');
            Route::post('/store', [OrderController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
            Route::get('/bayar/{id}', [OrderController::class, 'bayar'])->name('bayar');
            Route::get('/length/{id}', [OrderController::class, 'length'])->name('length');
            Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
            Route::patch('/update/{id}', [OrderController::class, 'update'])->name('update');
            Route::get('/pengiriman/{id}', [OrderController::class, 'pengiriman'])->name('pengiriman');
            Route::get('/lunas/{id}', [OrderController::class, 'lunas'])->name('lunas');
            Route::patch('/choose-collocation/{id}', [OrderController::class, 'chooseCollocation'])->name('choose-collocation');
            Route::get('/struk/{id}', [OrderController::class, 'strukPembelian'])->name('struk');
            Route::get('/download-pdf/{id}', [OrderController::class, 'downloadPDF'])->name('download-pdf');
            Route::get('/search', [OrderController::class, 'search'])->name('search');
            Route::get('cart', [ProductController::class, 'cart'])->name('cart');
            Route::get('add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('addToCart');
            Route::patch('update-cart', [ProductController::class, 'updateCart'])->name('updateCart');
            Route::delete('remove-from-cart', [ProductController::class, 'removeCart'])->name('removeCart');
            Route::get('/sewa/{id}', [OrderStatusController::class, 'sewaUser'])->name('sewa');
            Route::patch('/sewaEntry/{id}', [OrderStatusController::class, 'sewaEntry'])->name('sewaEntry');
        });

    });
});
