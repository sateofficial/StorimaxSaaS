<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTeam extends Model
{
    use HasUuids;

    protected $fillable = [
        'project_id',
        'pic_user_id',
        'team_name',
        'description',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}