<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, SoftDeletes;

    protected $fillable = [
        'department_id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
            'is_active'         => 'boolean',
        ];
    }

    // ── Relasi ────────────────────────────────────────────
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'assigned_to');
    }

    public function jobLogs(): HasMany
    {
        return $this->hasMany(JobLog::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function teamMemberships(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class);
    }

    public function picTeams(): HasMany
    {
        return $this->hasMany(ProjectTeam::class, 'pic_user_id');
    }

    // ── Helper role ───────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isAtasan(): bool
    {
        return $this->role === UserRole::ATASAN;
    }

    public function isCrew(): bool
    {
        return $this->role === UserRole::CREW;
    }

    public function isClient(): bool
    {
        return $this->role === UserRole::CLIENT;
    }

    public function isAdminOrAtasan(): bool
    {
        return in_array($this->role, [UserRole::ADMIN, UserRole::ATASAN]);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}