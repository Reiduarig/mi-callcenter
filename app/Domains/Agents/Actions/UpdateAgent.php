<?php
namespace App\Domains\Agents\Actions;

use App\Domains\Agents\Models\Agent;

class UpdateAgent
{
    public function execute(Agent $agent, array $data): Agent
    {
        $agent->update([
            'employee_code' => $data['employee_code'] ?? $agent->employee_code,
            'status' => $data['status'] ?? $agent->status,
            'skill_group' => $data['skill_group'] ?? $agent->skill_group,
            'hired_at' => $data['hired_at'] ?? $agent->hired_at,
        ]);

        return $agent;
    }
}