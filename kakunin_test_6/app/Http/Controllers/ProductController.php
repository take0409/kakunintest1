<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season; // Seasonモデルのインポートを追加
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 商品一覧を表示する (R: Read)
     */
    public function index()
    {
        // with('seasons') を追加し、関連する季節データも一緒に取得する
        $products = Product::with('seasons')->orderBy('id', 'desc')->get();
        
        return view('products.index', compact('products'));
    }

    /**
     * 商品登録フォームを表示する (C: Create)
     */
    public function create()
    {
        // 季節の全データを取得し、ビューに渡す
        $seasons = Season::all();
        return view('products.create', compact('seasons'));
    }

    /**
     * 送信された商品データをデータベースに保存する (C: Store)
     */
    public function store(Request $request)
    {
        // 1. バリデーションルール
        $request->validate([
            'name' => 'required|max:50',
            'description' => 'nullable|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'seasons' => 'required|array|min:1', // 季節IDの配列で、最低1つは選択必須
            'seasons.*' => 'exists:seasons,id', // 配列内の各要素がseasonsテーブルに存在すること
        ]);

        // 2. データの保存
        // 商品本体を登録
        $product = Product::create($request->all());
        
        // 3. 多対多のリレーションを保存
        // 中間テーブル(product_season)に、選択された季節IDを登録
        $product->seasons()->attach($request->input('seasons'));

        // 4. 一覧ページへリダイレクト
        return redirect()->route('products.index')
                         ->with('success', '商品を登録しました！');
    }
    
    /**
     * 商品編集フォームを表示する (U: Edit)
     * LaravelはURLセグメントからProductモデルのインスタンスを自動で取得します (Route Model Binding)
     */
    public function edit(Product $product)
    {
        // 季節の全データと、現在の商品に紐づいている季節IDを取得
        $seasons = Season::all();
        // 現在の商品に紐づいている季節IDの配列を取得 (例: [1, 3])
        // pluck('id')でIDのみを抽出し、toArray()で配列に変換
        $productSeasonIds = $product->seasons->pluck('id')->toArray();
        
        // 商品データ、全季節データ、紐づいている季節IDをビューに渡す
        return view('products.edit', compact('product', 'seasons', 'productSeasonIds'));
    }

    /**
     * 送信された商品データをデータベースに更新する (U: Update)
     */
    public function update(Request $request, Product $product)
    {
        // 1. バリデーションルール
        $request->validate([
            'name' => 'required|max:50',
            'description' => 'nullable|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'seasons' => 'required|array|min:1', 
            'seasons.*' => 'exists:seasons,id', 
        ]);

        // 2. データの更新
        $product->update($request->all());
        
        // 3. 多対多のリレーションを更新 (syncメソッド)
        // syncは、中間テーブルの既存の関連を削除し、新しい関連のみを保存します
        $product->seasons()->sync($request->input('seasons'));

        // 4. 一覧ページへリダイレクト
        return redirect()->route('products.index')
                         ->with('success', $product->name . 'の情報を更新しました！');
    }

    /**
     * 商品を削除する (D: Destroy)
     */
    public function destroy(Product $product)
    {
        $productName = $product->name;
        
        // 商品を削除。リレーション（中間テーブル）のデータも自動で削除されます
        // (マイグレーションで onDelete('cascade') を設定しているため)
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', $productName . 'を削除しました。');
    }
}