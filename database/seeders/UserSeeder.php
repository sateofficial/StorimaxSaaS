<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Admin Storimax',
                'email'     => 'admin@storimax.id',
                'role'      => UserRole::ADMIN,
                'phone'     => '081200000001',
            ],
            [
                'name'      => 'Direktur Storimax',
                'email'     => 'atasan@storimax.id',
                'role'      => UserRole::ATASAN,
                'phone'     => '081200000002',
            ],
            [
                'name'      => 'Budi Fotografer',
                'email'     => 'budi@storimax.id',
                'role'      => UserRole::CREW,
                'phone'     => '081200000003',
            ],
            [
                'name'      => 'Sari Videografer',
                'email'     => 'sari@storimax.id',
                'role'      => UserRole::CREW,
                'phone'     => '081200000004',
            ],
            [
                'name'      => 'Andi Editor',
                'email'     => 'andi@storimax.id',
                'role'      => UserRole::CREW,
                'phone'     => '081200000005',
            ],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'  => Hash::make('password'),
                    'is_active' => true,
                ])
            );
        }
    }
}
