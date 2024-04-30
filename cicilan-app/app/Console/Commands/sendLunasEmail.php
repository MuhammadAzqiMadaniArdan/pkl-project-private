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
    //     public function handle()
    // {
    //     // Baris ini mengambil nilai dari cache dengan kunci 'orders_sent_email'. Jika nilai tidak ada dalam cache, maka akan mengembalikan objek koleksi kosong (collect()). Jadi, variabel $sentOrders akan berisi nilai dari cache tersebut jika sudah ada, atau akan berisi objek koleksi kosong jika belum ada.
    //     // Menyimpan atau mengambil cache dengan nama orders_sent_mail dan mengumpulkannya atau menghitung
    //     $orderIdX = Cache::rememberForever('orders_to_email',function(){
    //         return collect();
    //     });


    //     $sentOrders = Cache::get('orders_sent_email', collect());

    //     // Mengambil data pada tabel order yang mendefinisikan votes menghasilkan 12 atau 24
    //     $orders = Order::where(function ($query) {
    //         $query->where('votes', 12)->where('bulan', '12');
    //     })->orWhere(function ($query) {
    //         $query->where('votes', 24)->where('bulan', '24');
    //     })->where('products->0->type', 'dedicated')
    //       ->whereNotIn('id', $sentOrders->toArray())
    //       ->whereMonth('created_at', now()->month)
    //       ->get();

    //       $this->info('Isi Tabel Order: ' . $orders);

    //     // Melakukan looping pada data di atas dan melakukan kondisi sistem pengiriman

    //     $adminEmail = 'muhammadazqi098@gmail.com';
    //     foreach ($orders as $order) {
    //         if (!$orderIdX->contains($order->id)) {

    //         $orderIdX->push($order->id);

    //         $cacheKey = 'order_email_sent_count_' . $order->id;
    //         $emailSentCount = Cache::get($cacheKey, 0);
    //         Mail::to($adminEmail)->send(new LunasMail($order));

    //         if ($emailSentCount == 0) {
    //             Mail::to($adminEmail)->send(new LunasMail($order));
    //             $sentOrders->push($order->id);
    //             Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
    //         } elseif (now()->startOfMonth()->equalTo(now()) || //awal bulan pengecekan dengan awal bulan updated_at
    //         now()->startOfMonth()->addDays(15)->equalTo(now()) || //pertengahan  ulan pengecekan dengan updated_at 
    //         now()->endOfMonth()->subDays(7)->equalTo(now()) || //seminggu sebelum akhir bulan melakukan pengecekan dengan tanggal yang sedang berlangsung
    //         now()->endOfMonth()->subDays(3)->equalTo(now()) || //tiga hari sebelum akhir bulan akan dikirimkan serta dibandingkan dengan tanggal yang sedang berlangsung
    //         now()->endOfMonth()->subDays(2)->equalTo(now()) ||
    //         now()->endOfMonth()->subDays(1)->equalTo(now()) ||
    //         now()->endofMonth()->equalTo(now()) // melakukan pengiriman pada tanggal akhir bulan
    //         ) {
    //             Mail::to($adminEmail)->send(new LunasMail($order));
    //  }

    //     // Baris tersebut menggunakan metode Cache::forever untuk menyimpan nilai $sentOrders ke dalam cache dengan kunci 'orders_sent_email'. Metode Cache::forever digunakan untuk menyimpan nilai ke dalam cache tanpa waktu kadaluarsa, yang berarti nilai tersebut akan tetap ada di cache selamanya kecuali dihapus secara eksplisit. Dalam konteks ini, nilai $sentOrders digunakan untuk melacak pesanan yang telah dikirimkan emailnya, sehingga tetap perlu disimpan di cache tanpa kadaluarsa.

    //     Cache::forever('orders_to_email', $orderIdX);
    //     Cache::forever('orders_sent_email', $sentOrders);
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

        // Mengambil data pada tabel order yang mendefinisikan votes menghasilkan 12 atau 24
        $orders = Order::where(function ($query) {
            $query->where('votes', 11)->where('bulan', '12');
        })->orWhere(function ($query) {
            $query->where('votes', 23)->where('bulan', '24');
        })->where('products->0->type', 'dedicated')
            ->whereNotIn('id', $sentOrders->toArray())
            ->whereMonth('created_at', now()->month)
            ->orWhere(function ($query) {
                $query->where('votes', 23)->where('bulan', '24');
            })
            ->get();

        // Melakukan looping pada data di atas dan melakukan kondisi sistem pengiriman
        $adminEmail = 'muhammadazqi098@gmail.com';
        foreach ($orders as $order) {
            // Cek apakah order ID sudah ada dalam $orderIds
            if (!$orderIds->contains($order->id)) {
                // if (!$orderIds->contains($order->id)) {
                // Jika belum ada, tambahkan order ID ke $orderIds
                $orderIds->push($order->id);

                // Kirim email ke admin
                // Mail::to($adminEmail)->send(new LunasMail($order));

                // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                $cacheKey = 'order_email_sent_count_' . $order->id;
                Mail::to($adminEmail)->send(new LunasMail($order));
                // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                $emailSentCount = Cache::get($cacheKey, 0);
                Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
                $this->info("Mail Sent With First Condition");

                
                $reminderMonths = $order['updated_at']->addDays(30);
                $nowWithoutSeconds = now()->startOfMinute()->format('Y-m-d H:i');
                // $emailSentCount = Cache::get($cacheKey, 0);
                // Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
                $reminderMonths = $order['updated_at']->addDays(30);
                // Baris berikut menyesuaikan waktu pengiriman email pada awal bulan, pertengahan bulan, 7 hari sebelum akhir bulan, 3 hari sebelum akhir bulan, 2 hari sebelum akhir bulan, satu hari sebelum akhir bulan, dan akhir bulan.
                if (
                    $reminderMonths->startOfMonth()->format('Y-m-d H:i') == $nowWithoutSeconds || // Awal bulan
                    $reminderMonths->startOfMonth()->addDays(15)->format('Y-m-d H:i') == $nowWithoutSeconds || // Pertengahan bulan
                    $reminderMonths->endOfMonth()->subDays(7)->format('Y-m-d H:i') == $nowWithoutSeconds || // 7 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(3)->format('Y-m-d H:i') == $nowWithoutSeconds || // 3 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(2)->format('Y-m-d H:i') == $nowWithoutSeconds || // 2 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(1)->format('Y-m-d H:i') == $nowWithoutSeconds || // Satu hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->format('Y-m-d H:i') == $nowWithoutSeconds // Akhir bulan
                ) {
                    Mail::to($adminEmail)->send(new LunasMail($order));
                    // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                    $emailSentCount = Cache::get($cacheKey, 0);
                    Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
                    $this->info("Mail Sent With First Condition");
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


                // Mail::to($adminEmail)->send(new LunasMail($order));

                $reminderMonths = $order['updated_at']->addDays(30);
                $nowWithoutSeconds = now()->startOfMinute()->setHour(3)->setMinute(0)->format('Y-m-d H:i');

                $this->info("reminder Date" . $reminderMonths);

                $this->info("reminder Date" . $reminderMonths);
                $this->info("First Reminder Date" . $reminderMonths->startOfMonth());

                if (
                    $reminderMonths->startOfMonth()->setHour()->format('Y-m-d H:i') == $nowWithoutSeconds || // Awal bulan
                    $reminderMonths->startOfMonth()->addDays(15)->format('Y-m-d H:i') == $nowWithoutSeconds || // Pertengahan bulan
                    $reminderMonths->endOfMonth()->subDays(7)->format('Y-m-d H:i') == $nowWithoutSeconds || // 7 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(3)->format('Y-m-d H:i') == $nowWithoutSeconds || // 3 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(2)->format('Y-m-d H:i') == $nowWithoutSeconds || // 2 hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->subDays(1)->format('Y-m-d H:i') == $nowWithoutSeconds || // Satu hari sebelum akhir bulan
                    $reminderMonths->endOfMonth()->format('Y-m-d H:i') == $nowWithoutSeconds // Akhir bulan
                ) {
                    $orderIds->push($order->id);

                    $this->info("Pengiriman Terjadwal");
                    Mail::to($adminEmail)->send(new LunasMail($order));
                    // Simpan jumlah pengiriman email ke cache dengan kunci 'order_email_sent_count_[order_id]'
                    $emailSentCount = Cache::get($cacheKey, 0);
                    Cache::put($cacheKey, $emailSentCount + 1, now()->endOfMonth());
                    $this->info("Mail Sent With Second Condition");
                }
            }
        }
        // Cache::forget('orders_sent_email');



        $nowWithoutSeconds = now()->startOfMinute()->setHour(3)->setMinute(0)->format('Y-m-d H:i');
        $this->info("Ini Adalah sentOrders" . $sentOrders);
        $this->info("ini adalah startOfMonth" . now()->startOfMonth()->equalTo(now()));
        $this->info("ini adalah BUkti" . $orderIds . count($orderIds));
        $this->info(
            "now tanpa detik" .  $nowWithoutSeconds
        );

        //

        foreach ($orders as $order) {
            $reminderMonths = $order['updated_at']->addDays(30);

            $this->info("reminder Datek" . $reminderMonths);
            $this->info("First Reminder Datek" . $reminderMonths->startOfMonth()->format('Y-m-d H:i'));

            $startMonth = $order['updated_at']->addMonths(1);
            $this->info("Tanggal start Bulan" . $startMonth);
        }
        $this->info("Tanggal Pengiriman " . now()->startOfMonth() . // Awal bulan
            now()->startOfMonth()->addDays(15) . // Pertengahan bulan
            now()->endOfMonth()->subDays(7) . // 7 hari sebelum akhir bulan
            now()->endOfMonth()->subDays(3) . // 3 hari sebelum akhir bulan
            now()->endOfMonth()->subDays(2) . // 2 hari sebelum akhir bulan
            now()->endOfMonth()->subDays(1) . //    khir bulan
            now()->endOfMonth());

        $this->info(now()->startOfMonth()->between(now(), now()->startOfMonth()->between(now(), now()->startOfMonth()->addSecond(20))));

        // Simpan $orderIds ke cache dengan kunci 'orders_to_email'
        Cache::forever('orders_to_email', $orderIds);
        // Simpan $sentOrders ke cache dengan kunci 'orders_sent_email'
        Cache::forever('orders_sent_email', $sentOrders);
    }





    //     public function handle()
    // {
    //     $orderIds = Cache::rememberForever('orders_to_email', function () {
    //         return collect();
    //     });

    //     $orders = Order::where('votes', 11)
    //                    ->get();

    //     $adminEmail = 'muhammadazqi098@gmail.com';
    //     foreach ($orders as $order) {
    //         if (!$orderIds->contains($order->id)) {
    //             $email = $order->user->email; // Mengambil email dari relasi user

    //             // Kirim email hanya jika email user adalah muhammadazqi098@gmail.com
    //             if ($email === 'muhammadazqi098@gmail.com') {
    //                 Mail::to($email)->send(new LunasMail($order));

    //                 $orderIds->push($order->id);

    //                 // Tambahkan pesan log setelah mengirim email
    //                 $this->info('Email sent to: ' . $email);
    //             }
    //         }
    //     }

    //     // Kirim email ke admin
    //     Mail::to($adminEmail)->send(new LunasMail($order));

    //     $this->info('Email sent to admin: ' . $adminEmail);

    //     Cache::forever('orders_to_email', $orderIds);

    //     // Tambahkan pesan log setelah loop
    //     $this->info('Email sending process completed.');
    // }



}

    // Pada kode tersebut, contains digunakan untuk memastikan bahwa email hanya dikirimkan sekali untuk setiap $order->id. Jika $order->id sudah ada dalam $orderIdX, maka email tidak akan dikirimkan lagi untuk $order->id tersebut.