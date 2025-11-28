<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// マイグレーションファイル名から生成されるクラス名は AddImageNameToProductsTable です。
// このクラス名がファイル内に正しく定義されている必要があります。
return new class extends Migration
{
    /**
     * マイグレーションを実行 (カラムの追加)
     */
    public function up(): void
    {
        // products テーブルに image_name カラムを追加します。
        // image_nameは文字列型で、一時的にNULLを許可します。
        // stock カラムの後ろに追加する設定です。
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_name', 255)->nullable()->after('stock');
        });
    }

    /**
     * マイグレーションをロールバック (カラムの削除)
     */
    public function down(): void
    {
        // products テーブルから image_name カラムを削除します。
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_name');
        });
    }
};