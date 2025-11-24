<?php

namespace App\Domains\Agents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;


class Agent extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'status',
        'skill_group',
        'hired_at',
    ];

    protected $casts = [
        'hired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(AgentStatusHistory::class);
    }
}
