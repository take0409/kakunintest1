<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーションを実行 (外部キーの追加)
     */
    public function up(): void
    {
        Schema::table('product_season', function (Blueprint $table) {
            // 既に存在している products テーブルを参照
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            // 既に存在している seasons テーブルを参照
            $table->foreign('season_id')
                  ->references('id')
                  ->on('seasons')
                  ->onDelete('cascade');
        });
    }

    /**
     * マイグレーションをロールバック (外部キーの削除)
     */
    public function down(): void
    {
        Schema::table('product_season', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['season_id']);
        });
    }
};