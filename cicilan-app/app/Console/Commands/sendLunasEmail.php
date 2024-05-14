<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\LunasMail;

class sendLunasEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-lunas-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // }
    public function handle()
    {
        // Mendapatkan nilai dari cache dengan kunci 'orders_sent_email'. Jika nilai tidak ada dalam cache, maka akan mengembalikan objek koleksi kosong (collect()).
        // Variabel `$sentOrders` pada kode di atas digunakan untuk menyimpan daftar pesanan yang sudah dikirimkan email. Hal ini berguna agar email tidak dikirimkan lebih dari satu kali untuk setiap pesanan. Saat proses pengiriman email dilakukan, ID pesanan yang sudah dikirimkan akan ditambahkan ke dalam `$sentOrders`.
        // Pada setiap iterasi dalam loop `foreach ($orders as $order)`, kode akan memeriksa apakah ID pesanan sudah ada di dalam `$sentOrders`. Jika belum ada, maka email akan dikirimkan dan ID pesanan akan ditambahkan ke dalam `$sentOrders`. Jika sudah ada, maka email tidak akan dikirimkan lagi untuk pesanan tersebut.
        // Dengan menggunakan `$sentOrders`, Anda dapat memastikan bahwa setiap pesanan hanya akan menerima satu email, meskipun proses pengiriman email dijalankan berkali-kali (seperti pada kondisi jadwal yang telah ditentukan).
        $sentOrders = Cache::get('orders_sent_email', collect());

        // Cache::forget('orders_sent_email');
        // Cache::forget('orders_to_email');

        // Mendapatkan nilai dari cache dengan kunci 'orders_to_email'. Jika nilai tidak ada dalam cache, maka akan mengembalikan objek koleksi kosong (collect()).
        $orderIds = Cache::get('orders_to_email', collect());
        // $orderIds = collect();       

            
        // Mengambil data pada tabel order yang mendefinisikan votes menghasilkan 12 atau 24
        $orders = Order::where(function ($query) {
            $query->where('votes', 11)->where('bulan', '12');
        })->orWhere(function ($query) {
            $query->where('votes', 23)->where('bulan', '24');
        })->where('products->0->type', 'dedicated')
            ->whereNotIn('id', $sentOrders->toArray())
            ->orWhere(function ($query) {
                $query->where('votes', 23)->where('bulan', '24');
            })
            ->get();

        
            
            $adminEmail = 'muhammadazqi098@gmail.com';
            foreach ($orders as $order) {   

                Mail::to($adminEmail)->send(new LunasMail($order));
            }
        // Melakukan looping pada data di atas dan melakukan kondisi sistem pengiriman
        foreach ($orders as $order) {
            // Cek apakah order ID sudah ada dalam $orderIds
            if (!$orderIds->contains($order->id)) {
                // $orderIds->push($order->id);

                // if (!$orderIds->contains($order->id)) {
                // Jika belum ada, tambahkan order ID ke $orderIds

                // Kirim email ke admin
                // Mail::to($adminEmail)->send(new LunasMail($order));

                // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                $cacheKey = 'order_email_sent_count_' . $order->id;
                // Mail::to($adminEmail)->send(new LunasMail($order));
                // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                // $emailSentCount = Cache::get($cacheKey, 0);
                // Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
                $this->info("Mail Sent With First Condition");

                $reminderOneDay = $order['updated_at']->addDays(1)->setHour(0)->setMinute(0);
                $nowWithoutSeconds = now()->format('Y-m-d H:i');

                // Baris berikut menyesuaikan waktu pengiriman email pada awal bulan, pertengahan bulan, 7 hari sebelum akhir bulan, 3 hari sebelum akhir bulan, 2 hari sebelum akhir bulan, satu hari sebelum akhir bulan, dan akhir bulan.
                if (
                    $reminderOneDay->format('Y-m-d H:i') == $nowWithoutSeconds
                ) {
                    $orderIds->push($order->id);

                    Mail::to($adminEmail)->send(new LunasMail($order));
                    // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                    $emailSentCount = Cache::get($cacheKey, 0);
                    Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
                    $this->info("Mail Sent Success");
                    $this->info("==================");
                } else {
                    $this->info("Mail Sent Failed");
                    $this->info("==================");
                }
                // elseif(count($orderIds) > 1){

                //     Cache::forget('orders_to_email',$orderIds);
                // }
            } else {

                // Kirim email ke admin
                // Mail::to($adminEmail)->send(new LunasMail($order));

                // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                $cacheKey = 'order_email_sent_count_' . $order->id;
                // $emailSentCount = Cache::get($cacheKey, 0);
                // Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());

                // Baris berikut menyesuaikan waktu pengiriman email pada awal bulan, pertengahan bulan, 7 hari sebelum akhir bulan, 3 hari sebelum akhir bulan, 2 hari sebelum akhir bulan, satu hari sebelum akhir bulan, dan akhir bulan.

                $this->info("Mail Sent With Second Condition");

                // Mail::to($adminEmail)->send(new LunasMail($order));

                $reminderMonths = $order['updated_at']->addMonths(1)->setHour(0)->setMinute(0);
                $reminderUpdate = $order['updated_at']->addMonths(2)->setHour(0)->setMinute(0);
                $nowWithoutSeconds = now()->format('Y-m-d H:i');

                if (
                    $reminderMonths->startOfMonth()->setHour(0)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds || // Awal bulan
                    $reminderMonths->startOfMonth()->addDays(15)->setHour(0)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds || // Pertengahan bulan
                    $reminderMonths->endOfMonth()->subDays(7)->setHour(0)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds || // 7 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(3)->setHour(0)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds || // 3 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(2)->setHour(0)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds || // 2 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(1)->setHour(0)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds || // Satu hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->setHour(0)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds // Akhir bulan
                ) {
                    $orderIds->push($order->id);

                    $this->info("Pengiriman Terjadwal");
                    Mail::to($adminEmail)->send(new LunasMail($order));
                    // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                    $emailSentCount = Cache::get($cacheKey, 0);
                    Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
                    $this->info("Mail Sent Success");
                    $this->info("==================");
                } elseif ($reminderMonths->startOfMonth()->format('m') < now()->format('m') && $order['products'][0]['type'] == 'dedicated') {

                    # code
                    $orderIds = collect();
                    // Cache::forget('');
                    //    $emailSentCount = Cache::get($cacheKey,0);
                    $orderIds->push($order->id);
                    //    Cache::put($cacheKey,$emailSentCount + 1,now()->endOfMonth());
                    Mail::to($adminEmail)->send(new LunasMail($order));

                    $this->info("Pengiriman Setelah Jadwal");
                    $this->info("Mail Sent Success (Products until Dedicated)");
                    $this->info("==================");
                } else {
                    $this->info("Mail Sent Failed");
                    $this->info("==================");
                    # code...
                }
            }
        }
        // Cache::forget('orders_sent_email');



        $nowWithoutSeconds = now()->format('Y-m-d H:i');
        // $this->info("Ini Adalah sentOrders : " . $sentOrders);
        $this->info("Order Pembayaran ke-11 : " . $orderIds . " Jumlah : " . count($orderIds));
        $this->info("Jumlah Pengiriman : " . count($orderIds));
        $this->info(
            "Waktu Sekarang (Tanpa Detik) : " .  $nowWithoutSeconds
        );
        $this->info("====================================================");
        //

        foreach ($orders as $order) {
            $reminderMonths = $order['updated_at']->addMonths(1)->setHour(0)->setMinute(0);
            $afterOneDay = $order['updated_at']->addDays(1);
            $startMonth = $order['updated_at']->addMonths(1)->format('Y-m');
            $this->info("Client : " . $order['name_customer']);
            $this->info("Tanggal start Bulan " . $startMonth);
            $this->info("Tanggal Pembayaran : " . $order['updated_at']->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman Sehari Setelah Pembayaran : " . $afterOneDay->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman Awal Bulan : " . $reminderMonths->startOfMonth()->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman Pertengahan : " . $reminderMonths->startOfMonth()->addDays(15)->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman Seminggu Sebelum Lunas : " . $reminderMonths->endOfMonth()->subDays(7)->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman 3 hari Sebelum Lunas : " . $reminderMonths->endOfMonth()->subDays(3)->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman 2 hari Sebelum Lunas : " . $reminderMonths->endOfMonth()->subDays(2)->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman 1 hari Sebelum Lunas : " . $reminderMonths->endOfMonth()->subDays(1)->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tanggal Pengiriman Akhir Bulan :" . $reminderMonths->endOfMonth()->setHour(0)->setMinute(0)->format('Y-m-d H:i'));
            $this->info("Tipe Produk Info : " . $order['products'][0]['type']);
            $this->info("Bulan Reminder : " . $reminderMonths->startOfMonth()->format('m') . " Bulan Sekarang : " . now()->format('m'));
            $this->info("====================================================");
        }

        // $this->info(now()->startOfMonth()->between(now(), now()->startOfMonth()->between(now(), now()->startOfMonth()->addSecond(20))));

        // Simpan $orderIds ke cache dengan kunci 'orders_to_email'
        Cache::forever('orders_to_email', $orderIds);
        // Simpan $sentOrders ke cache dengan kunci 'orders_sent_email'
        Cache::forever('orders_sent_email', $sentOrders);
    }






}

    // Pada kode tersebut, contains digunakan untuk memastikan bahwa email hanya dikirimkan sekali untuk setiap $order->id. Jika $order->id sudah ada dalam $orderIdX, maka email tidak akan dikirimkan lagi untuk $order->id tersebut.