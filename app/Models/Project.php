<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Enums\JobPriority;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'client_id',
        'created_by',
        'name',
        'code',
        'description',
        'category',
        'status',
        'priority',
        'deadline',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'   => ProjectStatus::class,
            'priority' => JobPriority::class,
            'deadline' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (self $project) {
            // Cascade soft-delete: jobs, invoices, portfolios
            foreach ($project->jobs as $job) {
                $job->attachments()->delete(); // JobAttachment ikut terhapus
                $job->logs()->delete();        // JobLog ikut terhapus
                $job->delete();                // Soft-delete job
            }
            foreach ($project->invoices as $invoice) {
                $invoice->items()->delete(); // InvoiceItem ikut terhapus
                $invoice->delete();           // Soft-delete invoice
            }
            $project->portfolios()->delete();
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline->isPast()
            && $this->status !== ProjectStatus::DONE;
    }
}