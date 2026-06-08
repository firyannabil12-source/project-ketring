<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Menu::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $menus = [
            [
                'name' => 'Paket Nasi Kotak A',
                'category' => 'NASI KOTAK',
                'price' => 25000,
                'stock' => 150,
                'description' => 'Nasi Putih, Ayam Goreng, Sambal Goreng Ati, Sayur, Kerupuk.',
                'image' => 'images/nasi_kotak.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tumpeng Mini Mewah',
                'category' => 'TUMPENG',
                'price' => 45000,
                'stock' => 20,
                'description' => 'Tumpeng Kuning dengan 7 macam lauk pauk lengkap dan hiasan cantik.',
                'image' => 'images/tumpeng.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Snack Box A',
                'category' => 'SNACK BOX',
                'price' => 15000,
                'stock' => 100,
                'description' => 'Lemper, Sosis Solo, Kue Sus, Air Mineral.',
                'image' => 'https://images.unsplash.com/photo-1599481238640-4c1288750d7a?auto=format&fit=crop&q=80&w=800',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Nasi Kotak B',
                'category' => 'NASI KOTAK',
                'price' => 30000,
                'stock' => 120,
                'description' => 'Nasi Putih, Ayam Bakar, Tempe Orek, Lalapan, Sambal, Kerupuk.',
                'image' => 'images/nasi_kotak.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Prasmanan Spesial',
                'category' => 'PRASMANAN',
                'price' => 85000,
                'stock' => 50,
                'description' => 'Paket prasmanan lengkap untuk 50 pax: 5 menu utama, 3 lauk, sup, dessert.',
                'image' => 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&q=80&w=800',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Snack Box B',
                'category' => 'SNACK BOX',
                'price' => 20000,
                'stock' => 80,
                'description' => 'Risoles Mayo, Kue Lapis, Nagasari, Putu Ayu, Air Mineral.',
                'image' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&q=80&w=800',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Menu::insert($menus);
    }
}
