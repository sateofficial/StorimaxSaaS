<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Foto',        'description' => 'Tim fotografer'],
            ['name' => 'Video',       'description' => 'Tim videografer & sinematografer'],
            ['name' => 'Editing',     'description' => 'Tim editor foto & video'],
            ['name' => 'Copywriting', 'description' => 'Tim penulis konten & caption'],
            ['name' => 'Desain',      'description' => 'Tim desain grafis'],
            ['name' => 'Manajemen',   'description' => 'Tim manajer & koordinator project'],
        ];

        foreach ($departments as $dept) {
            Department::create([
                'name'        => $dept['name'],
                'slug'        => Str::slug($dept['name']),
                'description' => $dept['description'],
            ]);
        }
    }
}