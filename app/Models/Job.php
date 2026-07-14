<?php

namespace App\Models;

use App\Enums\JobStatus;
use App\Enums\JobPriority;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'created_by',
        'title',
        'description',
        'status',
        'priority',
        'deadline',
        'started_at',
        'completed_at',
        'notes',
        'gdrive_link',
    ];

    protected function casts(): array
    {
        return [
            'status'       => JobStatus::class,
            'priority'     => JobPriority::class,
            'deadline'     => 'date',
            'started_at'   => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // ── team() removed — fitur tim/PIC dihapus

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(JobLog::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(JobAttachment::class);
    }

    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline->isPast()
            && $this->status !== JobStatus::DONE;
    }
}