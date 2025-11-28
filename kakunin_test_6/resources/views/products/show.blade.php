<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細: {{ $product->name }}</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* 画像をコンテナに合わせ、角を丸くするスタイル */
        .product-image {
            width: 100%;
            height: 300px; /* 高さを固定 */
            object-fit: cover; /* コンテナに合わせて画像をトリミング */
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        /* 画像がない場合のプレースホルダーのスタイル */
        .product-image-placeholder {
            width: 100%;
            height: 300px;
            background-color: #f8f9fa;
            border: 2px dashed #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #6c757d;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">{{ $product->name }} の詳細</h1>
        
        {{-- 成功メッセージの表示 --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    {{-- 左カラム: 画像と価格情報 --}}
                    <div class="col-md-5">
                        {{-- 画像表示エリアの修正 --}}
                        @if ($product->image_name)
                            <img src="{{ asset('images/' . $product->image_name) }}" alt="{{ $product->name }}" class="product-image mb-3">
                        @else
                            <div class="product-image-placeholder mb-3">
                                <i class="bi bi-image" style="font-size: 2rem;"></i> 画像なし
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                            <h2 class="h4 mb-0 text-success">価格</h2>
                            <span class="fs-3 fw-bold text-success">{{ number_format($product->price) }} 円</span>
                        </div>
                    </div>

                    {{-- 右カラム: 詳細情報 --}}
                    <div class="col-md-7">
                        <h2 class="card-title text-primary fw-bold">{{ $product->name }}</h2>
                        <hr>

                        <div class="mb-4">
                            <h5 class="text-muted">商品詳細</h5>
                            <p class="lead">{{ $product->description }}</p>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-muted">関連する季節</h5>
                            <div>
                                @if ($product->seasons->isEmpty())
                                    <span class="badge bg-secondary p-2">季節指定なし</span>
                                @else
                                    {{-- 関連する季節をループして表示 --}}
                                    @foreach ($product->seasons as $season)
                                        <span class="badge bg-primary me-2 p-2">{{ $season->name }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>商品ID:</strong>
                                <span>{{ $product->id }}</span>
                            </li>
                            {{-- 在庫数の表示を削除しました
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>在庫数:</strong>
                                <span>{{ $product->stock }}</span>
                            </li>
                            --}}
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>登録日時:</strong>
                                <span>{{ $product->created_at->format('Y/m/d H:i') }}</span>
                            </li>
                        </ul>
                        
                        {{-- 管理ボタン --}}
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            {{-- 商品一覧に戻るボタン --}}
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> 一覧に戻る
                            </a>
                            
                            <div class="d-flex gap-2">
                                {{-- 編集ボタン --}}
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> 編集
                                </a>

                                {{-- 削除フォーム --}}
                                {{-- 確認ダイアログはCanvasでは動作しないため、カスタムモーダルを使用することを推奨しますが、元のコードのまま維持します。 --}}
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('本当にこの商品「{{ $product->name }}」を削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> 削除
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>