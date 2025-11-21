<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * 一括割り当て可能な属性（カラム）を定義します。
     * ユーザーからの入力で更新を許可するカラムを指定します。
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];

    /**
     * 属性の型キャストを定義します。
     * データベースから取得した price と stock をPHP側で確実に整数型 (integer) として扱うように設定します。
     */
    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
    ];
}