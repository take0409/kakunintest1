<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品編集: {{ $product->name }}</title>
    <style>
        /* 基本スタイル */
        body { font-family: sans-serif; margin: 40px; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 0 auto; padding: 30px; background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        h1 { color: #f39c12; text-align: center; margin-bottom: 30px; }
        
        /* フォーム要素 */
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea { resize: vertical; min-height: 100px; }
        
        /* 画像表示エリア */
        .current-image { margin-top: 10px; margin-bottom: 15px; text-align: center; }
        .current-image img { max-width: 100%; height: auto; border-radius: 5px; border: 1px solid #ddd; }
        .image-placeholder { display: block; text-align: center; color: #777; padding: 20px; border: 1px dashed #ccc; border-radius: 5px; }

        /* ボタン */
        .btn-submit {
            background-color: #f39c12;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
            width: 100%;
        }
        .btn-submit:hover { background-color: #e67e22; }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-back:hover { text-decoration: underline; }

        /* エラーメッセージ */
        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }
        
        select[multiple] {
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>商品編集: {{ $product->name }}</h1>

        {{-- フォームアクション: products.updateルート（PUTメソッド） --}}
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- PUTメソッドで更新処理を行うことをLaravelに伝える --}}
            @method('PUT')

            {{-- 1. 商品名 --}}
            <div class="form-group">
                <label for="name">商品名 <span style="color: #e74c3c;">*</span></label>
                {{-- old()があればその値、なければ$product->nameを初期値として設定 --}}
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- 2. 価格 --}}
            <div class="form-group">
                <label for="price">価格 (円) <span style="color: #e74c3c;">*</span></label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0">
                @error('price')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- 3. 在庫 --}}
            <div class="form-group">
                <label for="stock">在庫数 <span style="color: #e74c3c;">*</span></label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0">
                @error('stock')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- 4. 説明 --}}
            <div class="form-group">
                <label for="description">商品説明</label>
                {{-- old()があればその値、なければ$product->descriptionを初期値として設定 --}}
                <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- 5. 現在の画像表示と新しい画像のアップロード --}}
            <div class="form-group">
                <label for="image">商品画像 (新しい画像を選択)</label>
                
                <div class="current-image">
                    @if ($product->image_name)
                        {{-- 現在の画像ファイル名があれば表示 --}}
                        <img src="{{ asset('images/' . $product->image_name) }}" alt="{{ $product->name }}の画像" style="max-height: 200px;">
                        <small style="display: block; color: #555;">現在の画像: {{ $product->image_name }}</small>
                    @else
                        <span class="image-placeholder">画像が設定されていません</span>
                    @endif
                </div>

                <input type="file" id="image" name="image" accept="image/jpeg, image/png">
                <small style="display: block; color: #777;">新しい画像を選択した場合、現在の画像は上書きされます。</small>
                @error('image')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- 6. 旬の季節（複数選択） --}}
            <div class="form-group">
                <label for="seasons">旬の季節 (複数選択可)</label>
                <select id="seasons" name="seasons[]" multiple>
                    @foreach ($seasons as $season)
                        @php
                            // 既存の商品に紐付いている季節IDの配列を取得（初回ロード時）
                            $productSeasonIds = $product->seasons->pluck('id')->toArray();
                            // old()データが優先、なければ既存のIDを使用
                            $selectedSeasons = old('seasons') !== null ? old('seasons') : $productSeasonIds;
                        @endphp
                        
                        <option value="{{ $season->id }}" 
                            {{-- IDが選択済み配列に含まれていればselectedを付与 --}}
                            {{ in_array($season->id, $selectedSeasons) ? 'selected' : '' }}>
                            {{ $season->name }}
                        </option>
                    @endforeach
                </select>
                @error('seasons')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">更新する</button>
        </form>

        <a href="{{ route('products.index') }}" class="btn-back">商品一覧へ戻る</a>
    </div>
</body>
</html>