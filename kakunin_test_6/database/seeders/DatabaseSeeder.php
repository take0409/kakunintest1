<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 追記する行
        $this->call([
            ProductSeeder::class, // <-- ProductSeederを呼び出す
        ]);

        // \App\Models\User::factory(10)->create(); 
    }
}