<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 以下のSeederを呼び出すように修正します
        $this->call([
            SeasonSeeder::class, // 季節データを先に挿入
            ProductSeeder::class, // 商品データを挿入
        ]);
    }
}