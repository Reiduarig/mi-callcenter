<?php
namespace App\Domains\Agents\Actions;
use App\Domains\Agents\Models\Agent;
use App\Models\User;


class CreateAgent
{
    public function execute(User $user, array $data)
    {
        // Logic to create a new Agent
        return Agent::create([
            'user_id' => $user->id,
            'employee_code' => $data['employee_code'] ?? strtoupper(uniqid('AGT-')),
            'status' => $data['status'] ?? 'available',
            'skill_group' => $data['skill_group'] ?? null,
            'hired_at' => $data['hired_at'] ?? now(),
        ]);

    }
}