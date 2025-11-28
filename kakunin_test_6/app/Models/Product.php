<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // BelongsToManyをインポート
use App\Models\Season; // 【重要】Seasonモデルを明示的にインポート

class Product extends Model
{
    use HasFactory;

    /**
     * 一括割り当て可能な属性（カラム）を定義します。
     */
    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'image_name', // 画像ファイル名もfillableに追加
    ];

    /**
     * Season モデルとの多対多リレーションを定義します。
     * Product は複数の Season に属し、Season は複数の Product に属します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function seasons(): BelongsToMany
    {
        // 中間テーブル名は慣習的に 'product_season' となります。
        // Season::class を指定
        return $this->belongsToMany(Season::class);
    }
}