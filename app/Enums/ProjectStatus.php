<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case DRAFT    = 'draft';
    case ACTIVE   = 'active';
    case REVIEW   = 'review';
    case DONE     = 'done';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::DRAFT    => 'Draft',
            self::ACTIVE   => 'Active',
            self::REVIEW   => 'Review',
            self::DONE     => 'Done',
            self::ARCHIVED => 'Archived',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT    => 'gray',
            self::ACTIVE   => 'blue',
            self::REVIEW   => 'yellow',
            self::DONE     => 'green',
            self::ARCHIVED => 'red',
        };
    }
}