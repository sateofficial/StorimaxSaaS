<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case DRAFT   = 'draft';
    case SENT    = 'sent';
    case DP_PAID = 'dp_paid';
    case PAID    = 'paid';
    case OVERDUE = 'overdue';

    public function label(): string
    {
        return match($this) {
            self::DRAFT   => 'Draft',
            self::SENT    => 'Terkirim',
            self::DP_PAID => 'DP Dibayar',
            self::PAID    => 'Lunas',
            self::OVERDUE => 'Jatuh Tempo',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT   => 'gray',
            self::SENT    => 'blue',
            self::DP_PAID => 'yellow',
            self::PAID    => 'green',
            self::OVERDUE => 'red',
        };
    }
}