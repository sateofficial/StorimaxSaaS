<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $manajemen = Department::where('slug', 'manajemen')->first();
        $foto      = Department::where('slug', 'foto')->first();
        $video     = Department::where('slug', 'video')->first();
        $editing   = Department::where('slug', 'editing')->first();

        // ── Akun Admin ────────────────────────────────────
        User::create([
            'department_id' => $manajemen?->id,
            'name'          => 'Admin Storimax',
            'email'         => 'admin@storimax.id',
            'password'      => Hash::make('password'),
            'role'          => UserRole::ADMIN,
            'phone'         => '081200000001',
            'is_active'     => true,
        ]);

        // ── Akun Atasan ───────────────────────────────────
        User::create([
            'department_id' => $manajemen?->id,
            'name'          => 'Direktur Storimax',
            'email'         => 'atasan@storimax.id',
            'password'      => Hash::make('password'),
            'role'          => UserRole::ATASAN,
            'phone'         => '081200000002',
            'is_active'     => true,
        ]);

        // ── Akun Crew Demo ────────────────────────────────
        User::create([
            'department_id' => $foto?->id,
            'name'          => 'Budi Fotografer',
            'email'         => 'budi@storimax.id',
            'password'      => Hash::make('password'),
            'role'          => UserRole::CREW,
            'phone'         => '081200000003',
            'is_active'     => true,
        ]);

        User::create([
            'department_id' => $video?->id,
            'name'          => 'Sari Videografer',
            'email'         => 'sari@storimax.id',
            'password'      => Hash::make('password'),
            'role'          => UserRole::CREW,
            'phone'         => '081200000004',
            'is_active'     => true,
        ]);

        User::create([
            'department_id' => $editing?->id,
            'name'          => 'Andi Editor',
            'email'         => 'andi@storimax.id',
            'password'      => Hash::make('password'),
            'role'          => UserRole::CREW,
            'phone'         => '081200000005',
            'is_active'     => true,
        ]);
    }
}