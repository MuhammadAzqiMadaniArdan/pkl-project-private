<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
// import agar hash dapat digunakan
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // menambahkan data ke table di db tanpa melalui input form (biasanya untuk data data default /bawaan)
        User::create([
            "name" => "Administrator",
            "email" => "admin@gmail.com",
            // Hash : enksripsi agar password tersimpan berisi teks acak agar tidak bisa diprediksi/ dibaca orang lain
            // hash -> bcrypt
            "password" => Hash::make("adminIKD"),
            "role" => "admin",
        ]);
        
    }
}
