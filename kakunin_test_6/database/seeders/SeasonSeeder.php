<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Season; // Seasonモデルを使用するために追記

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ★★★ 修正箇所: データが存在しない場合のみ実行するガードを追加 ★★★
        if (Season::count() > 0) {
            echo "INFO: seasonsテーブルにデータが存在するため、シーディングをスキップします。\n";
            return;
        }
        
        // データベースに挿入する季節データ
        $seasons = [
            ['name' => '春', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '夏', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '秋', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '冬', 'created_at' => now(), 'updated_at' => now()],
        ];

        // データを一括挿入
        DB::table('seasons')->insert($seasons);

        echo "SUCCESS: seasonsテーブルへのシーディングが完了しました。\n";
    }
}