<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_status extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'data',
        'status',
        'access',
        'attachment',
        'payment',
    ];

    protected $casts = [
        'attachment' => 'array',
        // 'bulan' => 'array',

    ];
    // penegasan tipe data dari migration (hasil property ini ketika diambil atau diinsert/update dibuat dalam bentuk tipe data apa)
    // protected $casts = [
    //     'products' => 'array',
    // ];

    public function order()
    {
        // menghubungkan ke primary key nya
        // dalam kurung merupakan nama model tempat penyimpanan dari pk nya si fk yang ada di model ini
        return $this->belongsTo(Order::class);
    }
    
}

