<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN   = 'admin';
    case ATASAN  = 'atasan';
    case CREW    = 'crew';
    case CLIENT  = 'client';

    public function label(): string
    {
        return match($this) {
            self::ADMIN  => 'Admin',
            self::ATASAN => 'Atasan',
            self::CREW   => 'Crew',
            self::CLIENT => 'Client',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ADMIN  => 'blue',
            self::ATASAN => 'purple',
            self::CREW   => 'green',
            self::CLIENT => 'orange',
        };
    }
}