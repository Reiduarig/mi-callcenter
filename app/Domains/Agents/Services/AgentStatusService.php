<?php 
namespace App\Domains\Agents\Services;

use App\Domains\Agents\Models\Agent;
use App\Domains\Agents\Models\AgentStatusHistory;

class AgentStatusService
{
    public function changeStatus(Agent $agent, string $newStatus): Agent
    {
        // Registrar historial del estado anterior
        if ($agent->status) {
            AgentStatusHistory::create([
                'agent_id' => $agent->id,
                'status' => $agent->status,
                'started_at' => now(),
                'ended_at' => now(),
            ]);
        }

        // Actualizar estado actual
        $agent->status = $newStatus;
        $agent->save();

        return $agent;
    }

    public function getStatusHistory(Agent $agent)
    {
        return $agent->statusHistories()->orderByDesc('started_at')->get();
    }
}