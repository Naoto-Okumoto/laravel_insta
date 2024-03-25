<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    // 中身を編集した！
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            AdminSeeder::class
        ]);
    }
}
