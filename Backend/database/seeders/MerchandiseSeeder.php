<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchandise;

class MerchandiseSeeder extends Seeder
{
    public function run(): void
    {
        $merchandises = [
            // 100 poin
            ['nama_merchandise' => 'Ballpoin', 'jumlah' => 100, 'gambar' => 'images/merchandise/ballpoint.png', 'nilai_point' => 100],
            ['nama_merchandise' => 'Stiker', 'jumlah' => 100, 'gambar' => 'images/merchandise/sticker.png', 'nilai_point' => 100],

            // 250 poin
            ['nama_merchandise' => 'Mug', 'jumlah' => 100, 'gambar' => 'images/merchandise/mug.jpg', 'nilai_point' => 250],
            ['nama_merchandise' => 'Topi', 'jumlah' => 100, 'gambar' => 'images/merchandise/topi.jpg', 'nilai_point' => 250],

            // 500 poin
            ['nama_merchandise' => 'Tumblr', 'jumlah' => 100, 'gambar' => 'images/merchandise/tumbler.jpeg', 'nilai_point' => 500],
            ['nama_merchandise' => 'T-shirt', 'jumlah' => 100, 'gambar' => 'images/merchandise/t-shirt.jpeg', 'nilai_point' => 500],
            ['nama_merchandise' => 'Jam Dinding', 'jumlah' => 100, 'gambar' => 'images/merchandise/jam_dinding.jpeg', 'nilai_point' => 500],

            // 1000 poin
            ['nama_merchandise' => 'Tas Travel', 'jumlah' => 100, 'gambar' => 'images/merchandise/tas_travel.jpg', 'nilai_point' => 1000],
            ['nama_merchandise' => 'Payung', 'jumlah' => 100, 'gambar' => 'images/merchandise/payung.jpeg', 'nilai_point' => 1000],
        ];

        foreach ($merchandises as $item) {
            Merchandise::create($item);
        }
    }
}
