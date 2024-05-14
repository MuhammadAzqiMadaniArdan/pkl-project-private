<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LunasMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {   
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reminderMonths = $this->order['updated_at']->addMonths(1)->setHour(3)->setMinute(0);
                $nowWithoutSeconds = now()->format('Y-m-d H:i');

                if (
                    $reminderMonths->startOfMonth()->setHour(3)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds // Awal bulan
                    )
                    {
                        $sisaWaktu = "Sebulan";
                        
                    }elseif( $reminderMonths->startOfMonth()->addDays(15)->setHour(3)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds){
                        
                        $sisaWaktu = "Setengah Bulan";
                    }
                    elseif($reminderMonths->endOfMonth()->subDays(7)->setHour(3)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds){
                        
                        $sisaWaktu = "Seminggu";
                    }
                    elseif( $reminderMonths->endOfMonth()->subDays(3)->setHour(3)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds){
                        $sisaWaktu = "Tiga Hari";
                        
                    }elseif ( $reminderMonths->endOfMonth()->subDays(2)->setHour(3)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds) {
                        # code...
                        $sisaWaktu = "Dua Hari";
                    }elseif ($reminderMonths->endOfMonth()->subDays(1)->setHour(3)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds) {
                        $sisaWaktu = "Sehari";
                        # code...
                    }elseif ($reminderMonths->endOfMonth()->setHour(3)->setMinute(0)->format('Y-m-d H:i') == $nowWithoutSeconds) {
                        # code...
                        $sisaWaktu = "Do Date";
                    }elseif($reminderMonths->format('m') < now()->format('m') ) {
                        $sisaWaktu = "terlewati";
                    }else{
                        $sisaWaktu = "Sebulan Baru (User Membayar Kemarin) ";

                    }
                    $akhirBulan = $reminderMonths->endOfMonth()->format('Y-m-d H:i');
                    $product = $this->order['products'][0]['name_product'];
                      // 7 hari sebelum akhir bulan
                    // 3 hari sebelum akhir bulan
                     // 2 hari sebelum akhir bulan
                      // Satu hari sebelum akhir bulan
                     // Akhir bulan
        return $this->view('emails.lunas')
            ->subject('Pemberitahuan Pembayaran Lunas')
            ->with([
                'name_customer' => $this->order->name_customer,
                'sisaWaktu' => $sisaWaktu,
                'name_product' =>$product,
                'akhirBulan' => $akhirBulan,
                'idUser' => $this->order->user_id,
            ]);
    }
}
