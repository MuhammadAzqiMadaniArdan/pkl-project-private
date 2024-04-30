<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'products',
        'name_customer',
        'no_telp',
        'total_price',
        'votes',
        'bulan',
        'company',
        'address',
        'datacenter',
        'entryData',
    ];

    // penegasan tipe data dari migration (hasil property ini ketika diambil atau diinsert/update dibuat dalam bentuk tipe data apa)
    protected $casts = [
        'products' => 'array',
        'datacenter' => 'array',
        'bulan' => 'integer',
        // 'bulan' => 'array',

    ];

    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    //     // ... (atribut tanggal lainnya jika ada)
    // ];

    // protected $timestamps = true;
    public function user()
    {
        // menghubungkan ke primary key nya
        // dalam kurung merupakan nama model tempat penyimpanan dari pk nya si fk yang ada di model ini
        return $this->belongsTo(User::class);
    }
    public function status()
    {
        // membuat relasi ke table lain dengan tipe one to many
        // dalam kurung merupakan nama model yang akan disambungkan (tempat fk)
        return $this->hasMany(Order_status::class);
    }

}
