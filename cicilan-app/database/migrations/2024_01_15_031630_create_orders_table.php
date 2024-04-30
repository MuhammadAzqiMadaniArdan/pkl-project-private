<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id");
            //fk untuk id yang auto increments bertipe data big Integer
            $table->json('products');
            $table->json('datacenter');
            $table->string('name_customer');
            $table->integer('no_telp');
            $table->integer('total_price');
            $table->integer('votes')->nullable();
            $table->enum('bulan', [1,2,3,4,6,12, 24]);
            $table->string('company');
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
