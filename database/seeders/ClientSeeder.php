<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'user' => [
                    'name'      => 'Rizky Pratama',
                    'email'     => 'rizky@gmail.com',
                    'password'  => Hash::make('password'),
                    'role'      => UserRole::CLIENT,
                    'is_active' => true,
                ],
                'client' => [
                    'company_name' => 'PT Maju Bersama',
                    'contact_name' => 'Rizky Pratama',
                    'phone'        => '081300000001',
                    'instagram'    => '@rizky.pratama',
                    'address'      => 'Jl. Sudirman No. 10, Jakarta',
                ],
            ],
            [
                'user' => [
                    'name'      => 'Dewi Sartika',
                    'email'     => 'dewi@gmail.com',
                    'password'  => Hash::make('password'),
                    'role'      => UserRole::CLIENT,
                    'is_active' => true,
                ],
                'client' => [
                    'company_name' => null,
                    'contact_name' => 'Dewi Sartika',
                    'phone'        => '081300000002',
                    'instagram'    => '@dewisartika_official',
                    'address'      => 'Jl. Gatot Subroto No. 25, Bandung',
                ],
            ],
        ];

        foreach ($clients as $data) {
            $user = User::create($data['user']);
            Client::create(array_merge($data['client'], ['user_id' => $user->id]));
        }
    }
}