<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 基本ルート: '/' にアクセスした場合、商品一覧ページにリダイレクトします。
Route::get('/', function () {
    return redirect()->route('products.index');
});

// **********************************************
// 商品関連のルートをまとめて定義します (CRUD)
// **********************************************

// 1. 商品一覧 (Read all) - GET /products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 2. 新規作成フォーム (Create form) - GET /products/create
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// 3. 商品詳細 (Read one) - GET /products/{product}
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// 4. データ保存 (Create data) - POST /products
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// 5. 編集フォーム (Update form) - GET /products/{product}/edit
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

// 6. データ更新 (Update data) - PUT/PATCH /products/{product}
// PUT/PATCHのどちらも更新に使われますが、ここではPUTで統一します。
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

// 7. データ削除 (Delete data) - DELETE /products/{product}
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');