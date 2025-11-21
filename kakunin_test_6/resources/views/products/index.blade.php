<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <style>
        /* 見やすいデザインのためのCSS */
        body { font-family: sans-serif; margin: 40px; background-color: #f4f7f6; }
        h1 { color: #2ecc71; text-align: center; margin-bottom: 30px; } 
        .container { max-width: 900px; margin: 0 auto; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
            border-radius: 10px; 
            overflow: hidden; 
            background-color: white;
        }
        th, td { 
            border: 1px solid #e0e0e0; 
            padding: 15px; 
            text-align: left; 
        }
        th { 
            background-color: #e8f8f5; 
            font-weight: bold; 
            color: #2c3e50; 
            text-transform: uppercase;
        }
        tr:nth-child(even) { background-color: #f9fdfc; }
        tr:hover { background-color: #e5f5f0; cursor: default; }
        .price { text-align: right; font-weight: 600; color: #e67e22; }
    </style>
</head>
<body>
    <div class="container">
        <h1>商品一覧</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>説明</th>
                </tr>
            </thead>
            <tbody>
                {{-- コントローラーから渡された $products をループで処理 --}}
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        {{-- 価格を日本円の形式で表示 --}}
                        <td class="price">¥{{ number_format($product->price) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- 商品データがない場合のメッセージ --}}
        @if($products->isEmpty())
            <p style="text-align: center; color: #888; margin-top: 30px;">商品データがありません。</p>
        @endif
    </div>
</body>
</html>