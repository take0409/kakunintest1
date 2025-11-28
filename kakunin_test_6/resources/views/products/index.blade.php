<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .product-image {
            width: 100%;
            height: 180px; /* カードの上部高さを固定 */
            object-fit: cover;
            border-top-left-radius: calc(0.5rem - 1px);
            border-top-right-radius: calc(0.5rem - 1px);
        }
        .sidebar {
            background-color: #ffffff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .search-area-title {
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #ffc107; /* 黄色のライン */
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .btn-yellow {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #333;
            font-weight: bold;
        }
        .btn-yellow:hover {
            background-color: #e0a800;
            border-color: #e0a800;
            color: #333;
        }
        .pagination-area {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-secondary fw-light">商品一覧</h1>
            {{-- 新規登録ボタン --}}
            <a href="{{ route('products.create') }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> 商品を登録
            </a>
        </div>
        
        {{-- 成功メッセージの表示 --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- 左サイドバー (検索・フィルタエリア) --}}
            <div class="col-lg-3">
                <div class="sidebar">
                    <h5 class="search-area-title">検索エリア</h5>
                    
                    {{-- 1. 商品名検索フォーム --}}
                    {{-- 既存のフィルタリングパラメータを保持するためにGETを使用し、非表示フィールドを追加 --}}
                    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        <input type="hidden" name="season_id" value="{{ request('season_id') }}">

                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="商品名検索" value="{{ request('search') }}">
                            <button class="btn btn-yellow" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <h5 class="search-area-title">2. 価格帯で表示</h5>
                    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                        {{-- 他のフィルタリングパラメータを保持 --}}
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="season_id" value="{{ request('season_id') }}">

                        <div class="mb-2">
                            <label for="min_price" class="form-label visually-hidden">最小価格</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="min_price" id="min_price" class="form-control" placeholder="最小価格" value="{{ request('min_price') }}">
                                <span class="input-group-text">円 ～</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="max_price" class="form-label visually-hidden">最大価格</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="max_price" id="max_price" class="form-control" placeholder="最大価格" value="{{ request('max_price') }}">
                                <span class="input-group-text">円</span>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-yellow w-100" type="submit">価格帯で表示</button>
                    </form>
                    
                    <h5 class="search-area-title mt-4">3. 季節で絞り込み</h5>
                    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                        {{-- 他のフィルタリングパラメータを保持 --}}
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                        <div class="mb-3">
                            <select name="season_id" class="form-select form-select-sm">
                                <option value="">全ての季節</option>
                                {{-- $seasons変数はControllerから渡されています --}}
                                @foreach ($seasons as $season)
                                    {{-- 現在のseason_idがリクエストと一致する場合にselectedを付与 --}}
                                    <option value="{{ $season->id }}" {{ (string)$season->id === request('season_id') ? 'selected' : '' }}>
                                        {{ $season->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-sm btn-outline-secondary w-100" type="submit">絞り込む</button>
                        
                        {{-- フィルタクリアボタン --}}
                        {{-- 何かしらのフィルタが適用されている場合に表示 --}}
                        @if (request()->has('search') || request()->has('min_price') || request()->has('max_price') || request()->has('season_id'))
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger w-100 mt-2">
                                <i class="bi bi-x-circle me-1"></i> フィルタをクリア
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- 右カラム (商品グリッド) --}}
            <div class="col-lg-9">
                @if ($products->isEmpty())
                    <div class="alert alert-info text-center">
                        該当する商品データが見つかりませんでした。
                    </div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach ($products as $product)
                            <div class="col">
                                <div class="card h-100 product-card shadow-sm border-0 rounded-3">
                                    <a href="{{ route('products.show', $product) }}">
                                        @if ($product->image_name)
                                            {{-- 画像がある場合は表示 --}}
                                            <img src="{{ asset('images/' . $product->image_name) }}" alt="{{ $product->name }}" class="product-image">
                                        @else
                                            {{-- 画像がない場合はプレースホルダー --}}
                                            <div class="bg-light d-flex align-items-center justify-content-center product-image" style="font-size: 1.5rem; color: #ccc;">
                                                <i class="bi bi-image"></i> 画像なし
                                            </div>
                                        @endif
                                    </a>
                                    <div class="card-body text-center">
                                        {{-- 商品名 --}}
                                        <h5 class="card-title fw-bold text-dark mb-1">
                                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                                        </h5>
                                        {{-- 価格 --}}
                                        <p class="card-text text-danger fs-5 fw-bold mb-3">
                                            ¥{{ number_format($product->price) }}
                                        </p>
                                        {{-- 季節タグを表示 --}}
                                        @if ($product->seasons->isNotEmpty())
                                            <div class="mb-2">
                                                @foreach ($product->seasons as $season)
                                                    <span class="badge rounded-pill text-bg-secondary">{{ $season->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-white border-0 text-center pb-3 pt-0">
                                        {{-- 編集・削除ボタン --}}
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- ページネーションリンク --}}
                    <div class="pagination-area">
                        {{ $products->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>