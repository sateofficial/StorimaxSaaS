<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_name',
        'phone',
        'instagram',
        'address',
        'notes',
    ];

    protected static function booted(): void
    {
        static::deleting(function (self $client) {
            // Cascade soft-delete: projects (→ jobs → invoices → portfolios)
            foreach ($client->projects as $project) {
                $project->delete(); // Project cascade akan handle jobs, invoices, portfolios
            }
            // Hapus user login terkait
            if ($client->user) {
                $client->user->delete();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}