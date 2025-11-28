<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        h1 { color: #2ecc71; text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea { resize: vertical; height: 100px; }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .checkbox-group label {
            display: flex;
            align-items: center;
            font-weight: normal;
            margin-bottom: 0;
            cursor: pointer;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }
        .btn-submit {
            background-color: #2ecc71;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>商品登録</h1>

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

        <form action="{{ route('products.store') }}" method="POST">
            @csrf {{-- CSRF対策トークン --}}

            <div class="form-group">
                <label for="name">商品名 <span style="color: #e74c3c;">(必須)</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="price">価格 <span style="color: #e74c3c;">(必須)</span></label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0">
                @error('price') <p class="error">{{ $message }}</p> @enderror
            </div>
            
            {{-- 在庫数の入力フィールドは削除されています --}}

            <div class="form-group">
                <label for="description">商品説明 (任意)</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>旬の季節 (任意)</label>
                <div class="checkbox-group">
                    {{-- $seasons は ProductController@create から渡される全季節データ --}}
                    @foreach ($seasons as $season)
                        <label>
                            <input 
                                type="checkbox" 
                                name="seasons[]" 
                                value="{{ $season->id }}" 
                                {{-- バリデーションエラーで戻ってきた際に、チェック状態を復元 --}}
                                {{ in_array($season->id, old('seasons', [])) ? 'checked' : '' }}
                            >
                            {{ $season->name }}
                        </label>
                    @endforeach
                </div>
                @error('seasons') <p class="error">{{ $message }}</p> @enderror
                @error('seasons.*') <p class="error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-submit">登録する</button>
        </form>

        <a href="{{ route('products.index') }}" class="btn-back">← 商品一覧に戻る</a>
    </div>
</body>
</html>