<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーションを実行 (中間テーブルの作成)
     */
    public function up(): void
    {
        // productsとseasonsを結びつける中間テーブルを作成します。
        // 外部キー制約は、タイムスタンプが後のファイル (100000) で行います。
        Schema::create('product_season', function (Blueprint $table) {
            // product_id と season_id のカラムを定義 (外部キー制約なし)
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('season_id');

            // 複合主キーを設定
            $table->primary(['product_id', 'season_id']);
        });
    }

    /**
     * マイグレーションをロールバック (中間テーブルの削除)
     */
    public function down(): void
    {
        Schema::dropIfExists('product_season');
    }
};