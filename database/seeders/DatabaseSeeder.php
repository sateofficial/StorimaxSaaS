<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,  // harus pertama, users butuh department
            UserSeeder::class,        // harus sebelum ClientSeeder
            ClientSeeder::class,
        ]);
    }
}