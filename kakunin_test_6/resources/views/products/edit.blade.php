<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フルーツ商品編集</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        h1 { color: #e67e22; text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea { resize: vertical; height: 100px; }
        .btn-submit {
            background-color: #2ecc71; /* 更新は緑色 */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            display: block;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-submit:hover { background-color: #27ae60; }
        .btn-back {
            display: inline-block;
            margin-top: 10px;
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }
        .error { color: #e74c3c; font-size: 14px; margin-top: 5px; }
        .checkbox-group { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 8px; }
        .checkbox-item { display: flex; align-items: center; }
        .checkbox-item input { width: auto; margin-right: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $product->name }} の情報を編集</h1>

        {{-- バリデーションエラー表示 --}}
        @if ($errors->any())
            <div style="background-color: #fdd; border: 1px solid #e74c3c; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <p style="color: #e74c3c; font-weight: bold;">入力内容にエラーがあります。</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 更新処理には PUT メソッドと @method('PUT') が必要 --}}
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf 
            @method('PUT')

            <div class="form-group">
                <label for="name">フルーツ商品名 <span style="color: #e74c3c;">(必須)</span></label>
                {{-- old() 関数でエラー時の入力を保持、なければ既存データを使用 --}}
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required placeholder="例: あまおう、シャインマスカット">
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            {{-- 季節選択フィールド (多対多リレーション) --}}
            <div class="form-group">
                <label>季節 <span style="color: #e74c3c;">(必須 - 複数選択可)</span></label>
                <div class="checkbox-group">
                    {{-- $seasons は ProductController の edit メソッドから渡される全季節データ --}}
                    @foreach ($seasons as $season)
                        <div class="checkbox-item">
                            <input 
                                type="checkbox" 
                                id="season_{{ $season->id }}" 
                                name="seasons[]" 
                                value="{{ $season->id }}" 
                                {{-- 選択状態のチェック --}}
                                @php
                                    // old('seasons') に値があればそちらを優先（バリデーションエラー時）
                                    // なければ $productSeasonIds (コントローラーで取得した既存の紐付けID配列) を確認
                                    $checked = in_array($season->id, old('seasons', $productSeasonIds));
                                @endphp
                                {{ $checked ? 'checked' : '' }}
                            >
                            <label for="season_{{ $season->id }}" style="margin-bottom: 0;">{{ $season->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('seasons') <p class="error">{{ $message }}</p> @enderror
            </div>
            
            <div class="form-group">
                <label for="price">価格（円） <span style="color: #e74c3c;">(必須)</span></label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0">
                @error('price') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="stock">在庫数 <span style="color: #e74c3c;">(必須)</span></label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0">
                @error('stock') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="description">商品説明 (任意)</label>
                <textarea id="description" name="description" placeholder="みずみずしい、甘みが強いなど商品の特徴を記述">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-submit">更新を保存する</button>
        </form>

        <a href="{{ route('products.index') }}" class="btn-back">← フルーツ商品一覧に戻る</a>
    </div>
</body>
</html>