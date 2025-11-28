<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Productモデルをインポート
use App\Models\Season; // Seasonモデルをインポート
use Illuminate\Support\Facades\DB; // トランザクションのためにDBファサードをインポート
use Illuminate\Support\Facades\Storage; // 画像保存のためにStorageファサードをインポート

class ProductController extends Controller
{
    /**
     * 商品一覧を表示する。（検索、価格、季節によるフィルタリングに対応）
     */
    public function index(Request $request)
    {
        // フィルタリングパラメータを取得
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $seasonId = $request->input('season_id');
        
        // データベースからデータを取得するためのクエリビルダを開始
        $query = Product::with('seasons');

        // 1. 商品名検索
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // 2. 価格帯フィルタ
        if (isset($minPrice) && is_numeric($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }
        if (isset($maxPrice) && is_numeric($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        // 3. 季節フィルタ (多対多のリレーションを利用)
        if ($seasonId) {
            // whereHasを使って、指定されたseasonIdを持つリレーションが存在する商品のみに絞り込む
            $query->whereHas('seasons', function ($q) use ($seasonId) {
                $q->where('seasons.id', $seasonId);
            });
        }
        
        // ページネーションとリレーションをロードして商品データを取得
        // ページネーションのリンクに全てのフィルタリングクエリを含めるため、append()を使用します。
        $products = $query->paginate(10)->appends($request->query());
        
        // フィルタリングのために全ての季節データを取得
        $seasons = Season::all();

        // 商品一覧ビューにデータを渡して表示
        return view('products.index', [
            'products' => $products,
            'seasons' => $seasons, // 季節データをビューに渡す
        ]);
    }

    /**
     * 新規商品登録フォームを表示する。
     */
    public function create()
    {
        // すべての季節データを取得し、ビューに渡す
        $seasons = Season::all();
        return view('products.create', compact('seasons'));
    }

    /**
     * 新規商品をデータベースに保存する。
     */
    public function store(Request $request)
    {
        // 1. バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 最大2MB
            'seasons' => 'nullable|array',
            'seasons.*' => 'exists:seasons,id', // 選択された季節IDがseasonsテーブルに存在するかチェック
        ]);

        // トランザクション開始：商品作成とリレーション保存をまとめて処理
        try {
            DB::beginTransaction();

            // 2. 画像の処理
            $imageName = null;
            if ($request->hasFile('image')) {
                // public/images ディレクトリに画像を保存し、ファイル名を $imageName に格納
                // ファイル名衝突を防ぐため、一意な名前を生成
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                // public/images/ ディレクトリへの保存
                $request->file('image')->move(public_path('images'), $imageName);
            }

            // 3. 商品データの保存
            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'description' => $validated['description'] ?? null,
                'image_name' => $imageName,
            ]);

            // 4. 季節リレーションの保存
            if (!empty($validated['seasons'])) {
                // attach() メソッドで中間テーブルにデータを保存
                $product->seasons()->attach($validated['seasons']);
            }

            DB::commit();

            // 5. 成功リダイレクト
            return redirect()->route('products.index')->with('success', '新しい商品「' . $product->name . '」を登録しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            // エラーログを記録
            \Log::error('商品登録エラー: ' . $e->getMessage());
            
            // ユーザーにエラーを通知
            return back()->withInput()->withErrors(['db_error' => '商品の登録中にエラーが発生しました。時間をおいて再度お試しください。']);
        }
    }

    /**
     * 指定された商品の詳細を表示する。
     */
    public function show(Product $product)
    {
        // 詳細ビューを返す
        return view('products.show', compact('product'));
    }

    /**
     * 指定された商品の編集フォームを表示する。
     */
    public function edit(Product $product)
    {
        // 編集対象の商品、およびすべての季節データを取得
        $seasons = Season::all();
        
        // 編集ビューに商品データと季節データを渡す
        return view('products.edit', compact('product', 'seasons'));
    }

    /**
     * 指定された商品をデータベースで更新する。
     */
    public function update(Request $request, Product $product)
    {
        // 1. バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            // 更新時は画像がなくてもOK。新しい画像がある場合のみチェックする
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'seasons' => 'nullable|array',
            'seasons.*' => 'exists:seasons,id',
        ]);

        // トランザクション開始
        try {
            DB::beginTransaction();

            // 2. 画像の更新処理
            $imageName = $product->image_name; // 既存の画像ファイル名を保持
            
            if ($request->hasFile('image')) {
                // a. 古い画像があれば削除
                if ($product->image_name) {
                    $oldImagePath = public_path('images/' . $product->image_name);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // b. 新しい画像を保存
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->file('image')->move(public_path('images'), $imageName);
            }

            // 3. 商品データの更新
            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'description' => $validated['description'] ?? null,
                'image_name' => $imageName,
            ]);

            // 4. 季節リレーションの更新
            // sync() メソッドは、渡されたIDのみを残し、中間テーブルを更新・削除します。
            $product->seasons()->sync($validated['seasons'] ?? []);

            DB::commit();

            // 5. 成功リダイレクト
            return redirect()->route('products.index')->with('success', '商品「' . $product->name . '」を更新しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            // エラーログを記録
            \Log::error('商品更新エラー: ' . $e->getMessage());
            
            // ユーザーにエラーを通知
            return back()->withInput()->withErrors(['db_error' => '商品の更新中にエラーが発生しました。時間をおいて再度お試しください。']);
        }
    }

    /**
     * 指定された商品を削除する。
     */
    public function destroy(Product $product)
    {
        // 削除処理の前に画像があれば削除
        if ($product->image_name) {
            $imagePath = public_path('images/' . $product->image_name);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $product->delete();

        // 成功メッセージとともに一覧へリダイレクト
        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }
}