<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Season extends Model
{
    use HasFactory;

    /**
     * 一括割り当て可能な属性（カラム）を定義します。
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Product モデルとの多対多リレーションを定義します。
     * Season は複数の Product に属し、Product は複数の Season に属します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        // 中間テーブル名は慣習的に 'product_season' となります。
        return $this->belongsToMany(Product::class);
    }
}