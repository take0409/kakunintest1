<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Product モデルをインポート
use App\Models\Season; // Season モデルをインポート

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 季節データを事前にロードして、名前でIDを引けるようにキャッシュします。
        // 例: $seasonMap['春'] => 1, $seasonMap['夏'] => 2, ...
        $seasonMap = Season::pluck('id', 'name')->toArray();

        // PDFの内容に基づいた商品データとリレーション情報を定義
        $productsData = [
            [
                'name' => 'キウイ',
                'price' => 800,
                'description' => 'キウイは甘みと酸味のバランスが絶妙なフルーツです。ビタミンCなどの栄養素も豊富のため、美肌効果や疲労回復効果も期待できます。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['秋', '冬'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'kiwi.jpg', // 画像名は仮に設定
            ],
            [
                'name' => 'ストロベリー',
                'price' => 1200,
                'description' => '大人から子供まで大人気のストロベリー。当店では鮮度抜群の完熟いちごを使用しています。ビタミンCはもちろん食物繊維も豊富なため、腸内環境の改善も期待できます。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['春'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'strawberry.jpg',
            ],
            [
                'name' => 'オレンジ',
                'price' => 850,
                'description' => '当店では酸味と甘みのバランスが抜群のネーブルオレンジを使用しています。酸味は控えめで、甘さと濃厚な果汁が魅力の商品です。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['冬'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'orange.jpg',
            ],
            [
                'name' => 'スイカ',
                'price' => 700,
                'description' => '甘くてシャリシャリ食感が魅力のスイカ。全体の90%が水分のため、暑い日の水分補給や熱中症予防、カロリーが気になる方にもおすすめの商品です。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['夏'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'watermelon.jpg',
            ],
            [
                'name' => 'ピーチ',
                'price' => 1000,
                'description' => '豊潤な香りととろけるような甘さが魅力のピーチ。美味しさはもちろん見た目の可愛さも抜群の商品です。ビタミンEが豊富なため、生活習慣病の予防にもおすすめです。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['夏'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'peach.jpg',
            ],
            [
                'name' => 'シャインマスカット',
                'price' => 1400,
                'description' => '爽やかな香りと上品な甘みが特長的なシャインマスカットは大人から子どもまで大人気のフルーツです。疲れた脳や体のエネルギー補給にも最適の商品です。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['夏', '秋'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'muscat.jpg',
            ],
            [
                'name' => 'パイナップル',
                'price' => 800,
                'description' => '甘酸っぱさとトロピカルな香りが特徴のパイナップル。当店では甘さと酸味のバランスが絶妙な国産のパイナップルを使用しています。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['春', '夏'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'pineapple.jpg',
            ],
            [
                'name' => 'ブドウ',
                'price' => 1100,
                'description' => 'ブドウの中でも人気の高い国産の「巨峰」を使用しています。高い糖度と適度な酸味が魅力で、鮮やかなパープルで見た目も可愛い商品です。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['夏', '秋'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'grape.jpg',
            ],
            [
                'name' => 'バナナ',
                'price' => 600,
                'description' => '低カロリーでありながら栄養満点のため、ダイエット中の方にもおすすめの商品です。1杯でバナナの濃厚な甘みを存分に堪能できます。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['夏'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'banana.jpg',
            ],
            [
                'name' => 'メロン',
                'price' => 900,
                'description' => '香りがよくジューシーで品のある甘さが人気のメロンスムージー。カリウムが多く含まれているためむくみ解消効果も抜群です。もぎたてフルーツのスムージーをお召し上がりください!',
                'seasons' => ['春', '夏'],
                'stock' => 0, // 在庫数を0に変更
                'image_name' => 'melon.jpg',
            ],
        ];

        // データをループして products テーブルに保存し、リレーションを確立します。
        foreach ($productsData as $data) {
            // 1. 商品を products テーブルに作成・保存
            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                // 'stock' はデータベースには存在するが、値は0で固定
                'stock' => $data['stock'],
                'image_name' => $data['image_name'],
            ]);

            // 2. 関連する季節のIDを取得
            // $seasonMapから季節名に対応するIDを抽出し、配列として取得します。
            $seasonIdsToAttach = array_map(function ($seasonName) use ($seasonMap) {
                return $seasonMap[$seasonName] ?? null; // IDを取得。見つからなければnull
            }, $data['seasons']);

            // nullをフィルタリングして、有効なIDのみをattachに渡す
            $seasonIdsToAttach = array_filter($seasonIdsToAttach);

            // 3. 多対多のリレーションを保存
            // product_season 中間テーブルにレコードを追加します。
            if (!empty($seasonIdsToAttach)) {
                $product->seasons()->attach($seasonIdsToAttach);
            }
        }

        $this->command->info('SUCCESS: productsテーブルへのシーディングとリレーションの保存が完了しました。');
    }
}