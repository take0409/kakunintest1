<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // DBファサードを使用するために追記

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // productsテーブルにデータを挿入
        DB::table('products')->insert([
            [
                'name' => 'Tシャツ（白）',
                'description' => 'ベーシックな白いTシャツです。',
                'price' => 2500,
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'デニムパンツ',
                'description' => 'オールシーズン使える定番のデニムです。',
                'price' => 7800,
                'stock' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'スニーカー（限定色）',
                'description' => '人気の高い限定色のスニーカーです。',
                'price' => 12000,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}