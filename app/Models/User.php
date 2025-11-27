<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Absence;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'supervisor_id',
        'hired_at',
        'annual_vacation_days',
        'used_vacation_days',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hired_at' => 'datetime',
            'is_active' => 'boolean',
            'annual_vacation_days' => 'integer',
            'used_vacation_days' => 'integer',
        ];
    }

    // Relaciones de jerarquía
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    // Relaciones de staff
    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    // Métodos de negocio
    public function availableVacationDays(): int
    {
        return $this->annual_vacation_days - $this->used_vacation_days;
    }

    public function getTeamMembers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->subordinates()->with('subordinates')->get()->flatMap(function ($subordinate) {
            return collect([$subordinate])->merge($subordinate->getTeamMembers());
        });
    }

    /**
     * Obtener posición desde roles del usuario
     */
    public function getPositionAttribute(): string
    {
        return $this->roles?->first()?->name ?? 'Sin rol';
    }
}
