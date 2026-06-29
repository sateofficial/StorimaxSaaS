<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'project_id',
        'client_id',
        'created_by',
        'invoice_number',
        'invoice_date',
        'session_date',
        'due_date',
        'subtotal',
        'pph_rate',
        'pph_amount',
        'total',
        'dp_amount',
        'dp_paid',
        'remaining',
        'status',
        'bank_name',
        'bank_account',
        'bank_holder',
        'payment_notes',
        'internal_notes',
        'sent_at',
        'dp_paid_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => InvoiceStatus::class,
            'invoice_date' => 'date',
            'session_date' => 'date',
            'due_date'     => 'date',
            'sent_at'      => 'datetime',
            'dp_paid_at'   => 'datetime',
            'paid_at'      => 'datetime',
            'subtotal'     => 'decimal:2',
            'pph_rate'     => 'decimal:2',
            'pph_amount'   => 'decimal:2',
            'total'        => 'decimal:2',
            'dp_amount'    => 'decimal:2',
            'dp_paid'      => 'decimal:2',
            'remaining'    => 'decimal:2',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast()
            && !in_array($this->status, [InvoiceStatus::PAID]);
    }
}