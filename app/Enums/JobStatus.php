<?php

namespace App\Enums;

enum JobStatus: string
{
    case TODO       = 'todo';
    case INPROGRESS = 'inprogress';
    case REVIEW     = 'review';
    case DONE       = 'done';

    public function label(): string
    {
        return match($this) {
            self::TODO       => 'To Do',
            self::INPROGRESS => 'In Progress',
            self::REVIEW     => 'Review',
            self::DONE       => 'Done',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::TODO       => 'gray',
            self::INPROGRESS => 'blue',
            self::REVIEW     => 'yellow',
            self::DONE       => 'green',
        };
    }
}